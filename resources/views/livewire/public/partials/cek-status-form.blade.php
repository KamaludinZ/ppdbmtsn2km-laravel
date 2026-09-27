{{-- Form cek status (dipakai halaman /cek-pendaftaran dan beranda) --}}
<form wire:submit="search" class="space-y-3" novalidate>
    <label for="cekStatus-{{ $this->getId() }}" class="block text-sm font-medium text-gray-700">
        Nomor Pendaftaran / NISN
    </label>
    <div class="flex flex-col sm:flex-row gap-3">
        <input
            type="text"
            id="cekStatus-{{ $this->getId() }}"
            wire:model="searchQuery"
            maxlength="40"
            autocomplete="off"
            autocapitalize="characters"
            spellcheck="false"
            @class([
                'block w-full min-w-0 flex-1 px-4 py-3 border rounded-lg text-gray-900 focus:outline-none focus:ring-2',
                'border-red-400 focus:ring-red-500 focus:border-red-500' => $errors->has('searchQuery'),
                'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500' => !$errors->has('searchQuery'),
            ])
            placeholder="Contoh: SPMBM-2025-001234 atau 0012345678"
            aria-describedby="cekStatusHint-{{ $this->getId() }}"
        >
        <div class="flex gap-2">
            <button type="submit" wire:loading.attr="disabled" wire:target="search"
                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-emerald-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition disabled:opacity-60 disabled:cursor-wait">
                <svg wire:loading.remove wire:target="search" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <svg wire:loading wire:target="search" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                <span wire:loading.remove wire:target="search">Cek Status</span>
                <span wire:loading wire:target="search">Mencari…</span>
            </button>
            @if($resultId || $errorMessage)
                <button type="button" wire:click="resetSearch"
                    class="px-4 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 bg-white hover:bg-gray-50 transition">
                    Reset
                </button>
            @endif
        </div>
    </div>
    <p id="cekStatusHint-{{ $this->getId() }}" class="text-xs text-gray-500">Tanpa login. Data yang tampil hanya ringkasan status.</p>
    @error('searchQuery')
        <p class="text-sm text-red-600" role="alert">{{ $message }}</p>
    @enderror
</form>

@if($errorMessage)
    <div class="mt-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4" role="alert">
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <p class="text-sm">{{ $errorMessage }}</p>
    </div>
@endif
