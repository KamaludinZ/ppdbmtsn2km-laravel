<?php

namespace App\Support;

/**
 * Captcha gambar angka sederhana berbasis GD.
 *
 * Kode hanya disimpan di session (tidak pernah dikirim sebagai teks ke browser),
 * gambar dibuat ulang setiap kali diminta, dan kode hanya bisa dipakai sekali.
 */
class Captcha
{
    /** Session key yang boleh dipakai lewat route gambar captcha. */
    public const KEYS = ['login_captcha', 'admin_login_captcha'];

    public const LENGTH = 6;

    public static function generate(string $key): string
    {
        $code = '';
        for ($i = 0; $i < self::LENGTH; $i++) {
            $code .= (string) random_int(0, 9);
        }

        session([$key => $code]);

        return $code;
    }

    /**
     * Cocokkan input dengan kode di session. Kode langsung dihapus (sekali pakai),
     * jadi setelah gagal pengguna harus memakai gambar baru.
     */
    public static function verify(string $key, $input): bool
    {
        $expected = session()->pull($key);
        $input = preg_replace('/\s+/', '', (string) $input);

        return is_string($expected) && $expected !== '' && hash_equals($expected, $input);
    }

    /**
     * URL gambar captcha; parameter "v" memaksa browser memuat gambar baru.
     */
    public static function url(string $key, $version = null): string
    {
        return route('captcha.image', ['key' => $key, 'v' => $version ?? substr(md5(uniqid('', true)), 0, 8)]);
    }

    /**
     * Buat kode baru lalu gambar sebagai PNG.
     */
    public static function png(string $key): string
    {
        $code = self::generate($key);

        $width = 200;
        $height = 60;
        $img = imagecreatetruecolor($width, $height);

        $bg = imagecolorallocate($img, random_int(236, 248), random_int(240, 250), random_int(236, 248));
        imagefilledrectangle($img, 0, 0, $width, $height, $bg);

        // Garis dan titik pengacau di belakang angka
        for ($i = 0; $i < 6; $i++) {
            $c = imagecolorallocate($img, random_int(150, 200), random_int(150, 200), random_int(150, 200));
            imagesetthickness($img, random_int(1, 2));
            imageline($img, random_int(0, $width), random_int(0, $height), random_int(0, $width), random_int(0, $height), $c);
        }
        for ($i = 0; $i < 350; $i++) {
            $c = imagecolorallocate($img, random_int(120, 210), random_int(120, 210), random_int(120, 210));
            imagesetpixel($img, random_int(0, $width - 1), random_int(0, $height - 1), $c);
        }

        // Setiap digit digambar kecil, diperbesar, lalu diputar acak
        $slot = (int) floor(($width - 20) / self::LENGTH);
        foreach (str_split($code) as $i => $digit) {
            $glyph = imagecreatetruecolor(12, 16);
            $transparent = imagecolorallocatealpha($glyph, 0, 0, 0, 127);
            imagealphablending($glyph, false);
            imagefill($glyph, 0, 0, $transparent);
            imagesavealpha($glyph, true);
            $color = imagecolorallocate($glyph, random_int(10, 70), random_int(30, 90), random_int(60, 120));
            imagestring($glyph, 5, 2, 0, $digit, $color);

            $scaled = imagecreatetruecolor(27, 36);
            imagealphablending($scaled, false);
            imagefill($scaled, 0, 0, imagecolorallocatealpha($scaled, 0, 0, 0, 127));
            imagesavealpha($scaled, true);
            imagecopyresampled($scaled, $glyph, 0, 0, 0, 0, 27, 36, 12, 16);

            $rotated = imagerotate($scaled, random_int(-22, 22), imagecolorallocatealpha($scaled, 0, 0, 0, 127));
            imagesavealpha($rotated, true);

            $x = 10 + $i * $slot + random_int(-2, 3);
            $y = (int) (($height - imagesy($rotated)) / 2) + random_int(-5, 5);
            imagealphablending($img, true);
            imagecopy($img, $rotated, $x, $y, 0, 0, imagesx($rotated), imagesy($rotated));

            imagedestroy($glyph);
            imagedestroy($scaled);
            imagedestroy($rotated);
        }

        // Satu garis melintang di atas angka
        $c = imagecolorallocate($img, random_int(60, 120), random_int(60, 120), random_int(90, 150));
        imagesetthickness($img, 2);
        imageline($img, 0, random_int(18, 42), $width, random_int(18, 42), $c);

        ob_start();
        imagepng($img);
        imagedestroy($img);

        return (string) ob_get_clean();
    }
}
