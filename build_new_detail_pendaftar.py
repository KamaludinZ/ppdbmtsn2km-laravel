#!/usr/bin/env python3
"""
Script untuk rebuild file detail-pendaftar.blade.php dengan struktur tab EMIS baru
"""

def read_file_lines(filename, start=None, end=None):
    """Read specific lines from file"""
    with open(filename, 'r', encoding='utf-8') as f:
        lines = f.readlines()
        if start is not None and end is not None:
            return ''.join(lines[start-1:end])
        elif start is not None:
            return ''.join(lines[start-1:])
        else:
            return ''.join(lines)

# Read original file sections
original_file = r'C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\detail-pendaftar.blade.php'

# Section 1: Header + Tab Navigation (already modified, baris 1-143)
header_section = read_file_lines(original_file, 1, 143)

# Section 2: Tab Daftar Ulang (baris 1150-1473, akan dipindahkan setelah tab baru)
daftarulang_section = read_file_lines(original_file, 1150, 1473)

# Section 3: Tab Dokumen + Footer (baris 1474-end)
dokumen_footer_section = read_file_lines(original_file, 1474, None)

# Now build the new file
output_file = r'C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\detail-pendaftar-new.blade.php'

with open(output_file, 'w', encoding='utf-8') as f:
    # Write header
    f.write(header_section)

    # Write new tab content placeholder
    f.write('\n                <!-- Tab Content -->\n')
    f.write('                <div class="p-6">\n')
    f.write('                    <!-- NEW TAB CONTENT WILL BE INSERTED HERE -->\n')
    f.write('                    <!-- Tab content untuk: Data Siswa, Data Orang Tua, Data Alamat, Prestasi, Beasiswa, Pendidikan Lain -->\n')
    f.write('                    <!-- Kemudian diikuti tab Daftar Ulang dan Dokumen -->\n\n')

    # Write tab Daftar Ulang
    f.write(daftarulang_section)
    f.write('\n')

    # Write tab Dokumen + Footer
    f.write(dokumen_footer_section)

print(f"File baru berhasil dibuat: {output_file}")
print("Langkah selanjutnya: Insert konten untuk 6 tab baru (Data Siswa, Orang Tua, Alamat, Prestasi, Beasiswa, Pendidikan Lain)")
