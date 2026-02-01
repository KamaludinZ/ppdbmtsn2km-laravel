{{-- SIMPLE SOLUTION: Ganti tombol "Daftar Sekarang" dengan kode ini --}}
{{-- File: resources/views/livewire/auth/register.blade.php --}}
{{-- Cari section "elseif($currentStep == 3)" sekitar line 795 --}}

@elseif($currentStep == 3)
    <button
        type="button"
        onclick="if(confirm('Yakin ingin mendaftar? Data yang sudah diisi akan disimpan dan email konfirmasi akan dikirim.')) { @this.call('register') }"
        id="btnDaftarSekarang"
        class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Daftar Sekarang
    </button>
@endif

{{-- HAPUS semua kode modal confirmation yang lama --}}
{{-- HAPUS juga semua kode JavaScript handleConfirmSubmit(), showConfirmationModal(), dll --}}
