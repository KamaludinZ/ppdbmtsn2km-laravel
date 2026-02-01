#!/bin/bash
# Fix All Panitia Component Role Access
# Add panitia_seragam and remove wrong 'panitia' role

echo "================================================"
echo "Fix Panitia Role Access - All Components"
echo "================================================"
echo ""

cd "$(dirname "$0")"

FILES=(
    "app/Livewire/Panitia/DataAsalSekolah.php"
    "app/Livewire/Panitia/DetailPendaftar.php"
    "app/Livewire/Panitia/Jadwal.php"
    "app/Livewire/Panitia/Jalur.php"
    "app/Livewire/Panitia/Kelulusan.php"
    "app/Livewire/Panitia/Laporan.php"
    "app/Livewire/Panitia/Pendaftar.php"
    "app/Livewire/Panitia/Pengumuman.php"
    "app/Livewire/Panitia/DaftarUlang.php"
    "app/Livewire/Panitia/TesPsikologi.php"
    "app/Livewire/Panitia/EmisInput.php"
)

FIXED=0
SKIPPED=0

for file in "${FILES[@]}"; do
    if [ ! -f "$file" ]; then
        echo "⏭️  Skip: $file (not found)"
        ((SKIPPED++))
        continue
    fi

    echo "🔧 Processing: $file"

    # Pattern 1: Replace wrong array (with 'panitia')
    sed -i "s/\['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia'\]/['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam']/g" "$file"

    # Pattern 2: Replace array without panitia_seragam
    sed -i "s/\['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi'\]/['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam']/g" "$file"

    # Pattern 3: Replace any remaining with only 2 roles
    sed -i "s/\['panitia_pendaftaran', 'panitia_daftarulang', 'panitia'\]/['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam']/g" "$file"

    echo "   ✅ Fixed"
    ((FIXED++))
done

echo ""
echo "================================================"
echo "Summary"
echo "================================================"
echo "Fixed: $FIXED files"
echo "Skipped: $SKIPPED files"
echo "================================================"
echo ""
echo "Next: Upload to production and clear cache"
