{{-- Kotak cek status pendaftaran di beranda (tanpa login) --}}
<section id="cek-status" class="py-16 bg-gray-50" aria-labelledby="cek-status-judul">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 id="cek-status-judul" class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Cek Status Pendaftaran</h2>
            <p class="text-gray-600">Lihat perkembangan pendaftaran Anda tanpa perlu login, cukup dengan nomor pendaftaran atau NISN.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8">
            @include('livewire.public.partials.cek-status-form')
            @include('livewire.public.partials.cek-status-hasil')
        </div>
        <p class="mt-4 text-center text-sm text-gray-500">
            Butuh detail lengkap? <a href="{{ route('cek.pendaftaran') }}" class="font-medium text-emerald-700 hover:text-emerald-800 underline">Buka halaman cek pendaftaran</a>
        </p>
    </div>
</section>
