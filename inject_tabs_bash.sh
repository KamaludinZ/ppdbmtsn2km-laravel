#!/bin/bash

# Script to inject tab templates into detail-pendaftar.blade.php

SOURCE_FILE="resources/views/livewire/admin/detail-pendaftar.blade.php"
TEMP_FILE="resources/views/livewire/admin/detail-pendaftar.blade.php.temp"

# Extract header (lines 1-141)
head -n 141 "$SOURCE_FILE" > "$TEMP_FILE"

# Add new tab includes
cat >> "$TEMP_FILE" << 'EOF'
                <div class="p-6">
                    <!-- Tab Data Siswa -->
                    <div x-show="activeTab === 'siswa'" x-transition>
                        @include('tab_templates.tab-data-siswa')
                    </div>

                    <!-- Tab Data Orang Tua -->
                    <div x-show="activeTab === 'orangtua'" x-transition>
                        @include('tab_templates.tab-data-orangtua')
                    </div>

                    <!-- Tab Data Alamat -->
                    <div x-show="activeTab === 'alamat'" x-transition>
                        @include('tab_templates.tab-data-alamat')
                    </div>

                    <!-- Tab Prestasi Siswa -->
                    <div x-show="activeTab === 'prestasi'" x-transition>
                        @include('tab_templates.tab-prestasi')
                    </div>

                    <!-- Tab Beasiswa & Bantuan -->
                    <div x-show="activeTab === 'beasiswa'" x-transition>
                        @include('tab_templates.tab-beasiswa')
                    </div>

                    <!-- Tab Pendidikan Lain -->
                    <div x-show="activeTab === 'pendidikan'" x-transition>
                        @include('tab_templates.tab-pendidikan-lain')
                    </div>

                    <!-- Tab Verifikasi Dokumen -->
EOF

# Add remaining content from line 1474 to end (Verifikasi Dokumen tab content)
tail -n +1474 "$SOURCE_FILE" >> "$TEMP_FILE"

# Replace original file
mv "$TEMP_FILE" "$SOURCE_FILE"

echo "Successfully injected tab templates!"
echo "File updated: $SOURCE_FILE"
