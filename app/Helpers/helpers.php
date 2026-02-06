<?php

if (!function_exists('format_pengumuman')) {
    /**
     * Format text pengumuman dengan markdown-like syntax
     *
     * @param string $text
     * @return string
     */
    function format_pengumuman($text)
    {
        return \App\Helpers\TextFormatter::formatPengumuman($text);
    }
}

if (!function_exists('strip_formatting')) {
    /**
     * Strip formatting dari text
     *
     * @param string $text
     * @return string
     */
    function strip_formatting($text)
    {
        return \App\Helpers\TextFormatter::stripFormatting($text);
    }
}
