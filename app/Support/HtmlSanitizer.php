<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Pembersih HTML sederhana (allowlist) untuk konten rich-text dari admin/panitia
 * (pengumuman, profil, informasi, FAQ, dll) sebelum ditampilkan dengan {!! !!}.
 *
 * Tag berbahaya (script, style, form, dll) dibuang beserta isinya, tag lain yang
 * tidak dikenal hanya dibuka (isinya tetap tampil), atribut event (onclick, dll)
 * dan URL javascript: selalu dihapus.
 */
class HtmlSanitizer
{
    private const TAG = [
        'a', 'abbr', 'b', 'blockquote', 'br', 'caption', 'code', 'del', 'div', 'em', 'figcaption',
        'figure', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'i', 'iframe', 'img', 'ins', 'li', 'mark',
        'ol', 'p', 'pre', 's', 'small', 'span', 'strike', 'strong', 'sub', 'sup', 'table', 'tbody',
        'td', 'tfoot', 'th', 'thead', 'tr', 'u', 'ul',
    ];

    /** Dibuang beserta seluruh isinya. */
    private const HAPUS = [
        'script', 'style', 'object', 'embed', 'applet', 'form', 'input', 'button', 'textarea', 'select',
        'option', 'meta', 'link', 'base', 'svg', 'math', 'template', 'noscript', 'frame', 'frameset', 'title',
    ];

    private const ATRIBUT_UMUM = ['class', 'style', 'title', 'align', 'dir', 'lang'];

    private const ATRIBUT = [
        'a' => ['href', 'target', 'rel'],
        'img' => ['src', 'alt', 'width', 'height', 'loading'],
        'iframe' => ['src', 'width', 'height', 'allow', 'allowfullscreen', 'frameborder', 'loading', 'referrerpolicy'],
        'td' => ['colspan', 'rowspan'],
        'th' => ['colspan', 'rowspan', 'scope'],
        'ol' => ['start', 'type'],
    ];

    /** Iframe hanya boleh dari layanan ini (video & peta). */
    private const IFRAME_HOST = '/^https:\/\/(www\.)?(youtube\.com|youtube-nocookie\.com|player\.vimeo\.com|google\.com\/maps|maps\.google\.com|drive\.google\.com|docs\.google\.com)\//i';

    public static function clean(?string $html): string
    {
        $html = (string) $html;
        if (trim($html) === '') {
            return '';
        }

        $dom = new DOMDocument();
        $prev = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8"?><div id="__root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $root = $dom->getElementById('__root');
        if (!$root) {
            return e(strip_tags($html));
        }

        self::bersihkanAnak($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return $out;
    }

    private static function bersihkanAnak(DOMNode $node): void
    {
        // Salin dulu karena daftar anak berubah saat node dihapus/dibuka
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_COMMENT_NODE || $child->nodeType === XML_PI_NODE) {
                $node->removeChild($child);
                continue;
            }
            if (!$child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::HAPUS, true)
                || ($tag === 'iframe' && !preg_match(self::IFRAME_HOST, (string) $child->getAttribute('src')))) {
                $node->removeChild($child);
                continue;
            }

            self::bersihkanAnak($child);

            if (!in_array($tag, self::TAG, true)) {
                // Tag tidak dikenal: buka, isinya tetap dipertahankan
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            self::bersihkanAtribut($child, $tag);
        }
    }

    private static function bersihkanAtribut(DOMElement $el, string $tag): void
    {
        $boleh = array_merge(self::ATRIBUT_UMUM, self::ATRIBUT[$tag] ?? []);

        foreach (iterator_to_array($el->attributes) as $attr) {
            $nama = strtolower($attr->name);
            $nilai = trim($attr->value);

            $hapus = !in_array($nama, $boleh, true)
                || (in_array($nama, ['href', 'src'], true) && !self::urlAman($nilai, $tag === 'img' && $nama === 'src'))
                || ($nama === 'style' && preg_match('/expression|javascript:|behavior|url\s*\(|@import/i', $nilai));

            if ($hapus) {
                $el->removeAttribute($attr->name);
            }
        }

        if ($tag === 'a' && strtolower($el->getAttribute('target')) === '_blank') {
            $el->setAttribute('rel', 'noopener noreferrer');
        }
    }

    private static function urlAman(string $url, bool $bolehDataGambar = false): bool
    {
        // Hilangkan karakter kontrol/spasi yang bisa dipakai menyamarkan "javascript:"
        $url = preg_replace('/[\x00-\x20\x7F]+/', '', html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        if ($url === '' || !preg_match('/^[a-z][a-z0-9+.\-]*:/i', $url)) {
            return true; // URL relatif (/halaman, #anchor, gambar.png)
        }

        if ($bolehDataGambar && preg_match('/^data:image\/(png|jpe?g|gif|webp);base64,/i', $url)) {
            return true;
        }

        return (bool) preg_match('/^(https?|mailto|tel):/i', $url);
    }
}
