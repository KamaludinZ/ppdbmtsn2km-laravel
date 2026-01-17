<?php
// Script untuk inject tab templates ke detail-pendaftar.blade.php

$detailPendaftarFile = __DIR__ . '/resources/views/livewire/admin/detail-pendaftar.blade.php';
$templateDir = __DIR__ . '/resources/views/tab_templates/';

// Read original file
$content = file_get_contents($detailPendaftarFile);
$lines = explode("\n", $content);

// Find tab content section (starts at line ~141)
$tabContentStart = null;
$tabContentEnd = null;

foreach ($lines as $index => $line) {
    if (stripos($line, '<!-- Tab Content -->') !== false) {
        $tabContentStart = $index;
    }
    if (stripos($line, '<!-- Data Daftar Ulang Tab -->') !== false ||
        stripos($line, '<div x-show="activeTab === \'daftarulang\'"') !== false) {
        $tabContentEnd = $index - 1; // Line before "Data Daftar Ulang"
        break;
    }
}

if ($tabContentStart === null || $tabContentEnd === null) {
    die("Could not find tab content boundaries\n");
}

echo "Tab content found from line " . ($tabContentStart + 1) . " to " . ($tabContentEnd + 1) . "\n";

// Build new tab content
$newTabContent = [
    "                <!-- Tab Content -->",
    "                <div class=\"p-6\">",
    "                    <!-- Tab Data Siswa -->",
    "                    <div x-show=\"activeTab === 'siswa'\" x-transition>",
    "                        @include('tab_templates.tab-data-siswa')",
    "                    </div>",
    "",
    "                    <!-- Tab Data Orang Tua -->",
    "                    <div x-show=\"activeTab === 'orangtua'\" x-transition>",
    "                        @include('tab_templates.tab-data-orangtua')",
    "                    </div>",
    "",
    "                    <!-- Tab Data Alamat -->",
    "                    <div x-show=\"activeTab === 'alamat'\" x-transition>",
    "                        @include('tab_templates.tab-data-alamat')",
    "                    </div>",
    "",
    "                    <!-- Tab Prestasi Siswa -->",
    "                    <div x-show=\"activeTab === 'prestasi'\" x-transition>",
    "                        @include('tab_templates.tab-prestasi')",
    "                    </div>",
    "",
    "                    <!-- Tab Beasiswa & Bantuan -->",
    "                    <div x-show=\"activeTab === 'beasiswa'\" x-transition>",
    "                        @include('tab_templates.tab-beasiswa')",
    "                    </div>",
    "",
    "                    <!-- Tab Pendidikan Lain -->",
    "                    <div x-show=\"activeTab === 'pendidikan'\" x-transition>",
    "                        @include('tab_templates.tab-pendidikan-lain')",
    "                    </div>",
    "",
    "                    <!-- Tab Verifikasi Dokumen -->",
    "                    <div x-show=\"activeTab === 'dokumen'\" x-transition>",
    "                        <!-- Keep existing content for dokumen tab -->"
];

// Extract dokumen and daftarulang tabs (keep them)
$dokumentabStartIndex = null;
$daftarulangTabEndIndex = null;

for ($i = $tabContentEnd; $i < count($lines); $i++) {
    if (stripos($lines[$i], 'x-show="activeTab === \'dokumen\'"') !== false ||
        stripos($lines[$i], "x-show=\"activeTab === 'dokumen'\"") !== false) {
        $dokumentabStartIndex = $i;
        break;
    }
}

// Find end of file (closing divs)
for ($i = count($lines) - 1; $i >= 0; $i--) {
    if (stripos($lines[$i], '</div>') !== false &&
        stripos($lines[$i-1], '@endif') !== false &&
        stripos($lines[$i-2], '@endif') !== false) {
        $daftarulangTabEndIndex = $i;
        break;
    }
}

if ($dokumentabStartIndex === null) {
    echo "Warning: Could not find dokumen tab, will keep all content after tab section\n";
    $dokumentabStartIndex = $tabContentEnd + 1;
}

// Build final content
$finalLines = [];

// Add everything before tab content
for ($i = 0; $i < $tabContentStart; $i++) {
    $finalLines[] = $lines[$i];
}

// Add new tab includes
foreach ($newTabContent as $line) {
    $finalLines[] = $line;
}

// Add remaining dokumen and daftarulang tabs
for ($i = $dokumentabStartIndex; $i < count($lines); $i++) {
    $finalLines[] = $lines[$i];
}

// Write to file
$outputContent = implode("\n", $finalLines);
file_put_contents($detailPendaftarFile, $outputContent);

echo "Successfully injected tab templates!\n";
echo "Total lines: " . count($finalLines) . "\n";
echo "Original file backed up as detail-pendaftar.blade.php.backup3-before-inject\n";
?>
