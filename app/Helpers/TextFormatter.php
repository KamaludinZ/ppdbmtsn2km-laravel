<?php

namespace App\Helpers;

class TextFormatter
{
    /**
     * Format text dengan markdown-like syntax untuk pengumuman
     *
     * Supported formatting:
     * - *text* -> <strong>text</strong> (bold)
     * - _text_ -> <em>text</em> (italic)
     * - URL -> <a href="URL">URL</a> (clickable link)
     * - Line breaks preserved
     * - Paragraphs preserved
     *
     * @param string $text
     * @return string
     */
    public static function formatPengumuman($text)
    {
        if (empty($text)) {
            return '';
        }

        // Escape HTML untuk keamanan (prevent XSS)
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        // 1. Convert URLs to clickable links
        // Pattern untuk detect URL (http, https, www)
        $urlPattern = '/\b(https?:\/\/[^\s<]+|www\.[^\s<]+\.[^\s<]+)/i';
        $text = preg_replace_callback($urlPattern, function($matches) {
            $url = $matches[0];
            // Tambahkan http:// jika dimulai dengan www
            $href = (strpos($url, 'http') === 0) ? $url : 'http://' . $url;
            return '<a href="' . $href . '" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 underline">' . $url . '</a>';
        }, $text);

        // 2. Convert *text* to <strong>text</strong> (bold)
        // Pattern: *kata* atau *beberapa kata*
        $text = preg_replace('/\*([^\*\n]+)\*/', '<strong class="font-bold text-gray-900">$1</strong>', $text);

        // 3. Convert _text_ to <em>text</em> (italic)
        // Pattern: _kata_ atau _beberapa kata_
        $text = preg_replace('/_([^_\n]+)_/', '<em class="italic text-gray-700">$1</em>', $text);

        // 4. Preserve line breaks - convert \n to <br>
        // Tapi jangan convert jika ada double line break (paragraph)
        $text = preg_replace('/\n\n/', '<!--PARAGRAPH-->', $text);
        $text = nl2br($text);

        // 5. Convert double line breaks to paragraphs
        $text = str_replace('<!--PARAGRAPH-->', '</p><p class="mb-3">', $text);

        // 6. Wrap dalam paragraph pertama
        if (!str_starts_with($text, '<p')) {
            $text = '<p class="mb-3">' . $text . '</p>';
        } else {
            $text = '<p class="mb-3">' . $text;
        }

        // 7. Close last paragraph jika belum tertutup
        if (!str_ends_with($text, '</p>')) {
            $text = $text . '</p>';
        }

        return $text;
    }

    /**
     * Strip formatting dari text (untuk preview/excerpt)
     *
     * @param string $text
     * @return string
     */
    public static function stripFormatting($text)
    {
        // Remove markdown-like syntax
        $text = preg_replace('/\*([^\*]+)\*/', '$1', $text); // Remove *bold*
        $text = preg_replace('/_([^_]+)_/', '$1', $text);     // Remove _italic_

        // Remove HTML tags
        $text = strip_tags($text);

        return trim($text);
    }

    /**
     * Get plain text dari formatted text (untuk search/indexing)
     *
     * @param string $text
     * @return string
     */
    public static function toPlainText($text)
    {
        return self::stripFormatting($text);
    }
}
