<?php

namespace App\Support;

class Nominal
{
    /**
     * Ubah nilai nominal rupiah menjadi angka.
     *
     * Data biaya lama bisa berisi string kosong atau teks berformat seperti
     * "Rp 150.000" / "150.000,-". array_sum()/number_format() melempar error
     * untuk nilai seperti itu di PHP 8.3+, jadi semua nominal dinormalisasi di sini.
     */
    public static function toNumber($value): float
    {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        if (!is_string($value)) {
            return 0.0;
        }

        $value = trim($value);

        // Angka biasa dari input type="number", mis. "150000" atau "150000.50".
        // Format ribuan Indonesia ("150.000") juga lolos is_numeric, jadi dikecualikan.
        if (is_numeric($value) && !preg_match('/^\d{1,3}(\.\d{3})+$/', $value)) {
            return (float) $value;
        }

        // Format teks: buang bagian desimal ",00" / ",-", lalu ambil digitnya saja
        $value = preg_replace('/,(\d{1,2}|-)?\s*$/', '', $value);
        $digits = preg_replace('/\D/', '', $value);

        return $digits === '' ? 0.0 : (float) $digits;
    }

    /**
     * Jumlahkan nominal biaya dalam bentuk apa pun yang tersimpan di database:
     * - satu item: {"name": "SPP", "amount": "150000"}
     * - daftar item: [{"name": "...", "amount": ...}, ...]
     * - daftar angka: [150000, "50.000"] atau angka/teks tunggal
     * Kolom "name" tidak ikut dijumlahkan (penyebab error array_sum() di production).
     */
    public static function sum($items, string $key = 'amount'): float
    {
        if (is_string($items)) {
            $decoded = json_decode($items, true);
            $items = json_last_error() === JSON_ERROR_NONE ? $decoded : $items;
        }

        if ($items === null || $items === '') {
            return 0.0;
        }

        if (!is_array($items)) {
            return self::toNumber($items);
        }

        // Satu item biaya {name, amount}
        if (array_key_exists($key, $items)) {
            return self::toNumber($items[$key]);
        }

        $total = 0.0;
        foreach ($items as $item) {
            $total += is_array($item) ? self::sum($item, $key) : self::toNumber($item);
        }

        return $total;
    }

    /**
     * Ambil hanya nilai yang benar-benar angka (mis. nilai rapor) sebagai float.
     * Mendukung koma desimal ("85,5"); nilai kosong/teks lain diabaikan,
     * sehingga aman dipakai dengan array_sum().
     */
    public static function numericValues(array $values): array
    {
        $result = [];
        foreach ($values as $value) {
            if (is_string($value)) {
                $value = str_replace(',', '.', trim($value));
            }
            if (is_numeric($value)) {
                $result[] = (float) $value;
            }
        }

        return $result;
    }
}
