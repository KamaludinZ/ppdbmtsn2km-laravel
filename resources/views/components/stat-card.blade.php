{{--
    Kartu statistik berwarna. Pemakaian: <x-stat-card color="blue" label="Total" :value="612" sub="keterangan" />
    Kelas warna ditulis lengkap (tidak dirangkai) agar pasti ikut ter-build Tailwind.
--}}
@props(['label', 'value' => 0, 'sub' => null, 'color' => 'gray'])
@php
    $gradien = [
        'gray' => 'from-slate-500 to-slate-600',
        'blue' => 'from-blue-500 to-blue-600',
        'indigo' => 'from-indigo-500 to-indigo-600',
        'purple' => 'from-purple-500 to-purple-600',
        'teal' => 'from-teal-500 to-teal-600',
        'emerald' => 'from-emerald-500 to-emerald-600',
        'green' => 'from-green-500 to-green-600',
        'yellow' => 'from-yellow-400 to-yellow-500',
        'amber' => 'from-amber-500 to-amber-600',
        'orange' => 'from-orange-500 to-orange-600',
        'red' => 'from-red-500 to-red-600',
        'rose' => 'from-rose-500 to-rose-600',
    ][$color] ?? 'from-slate-500 to-slate-600';
    // Latar kuning terlalu terang untuk teks putih, jadi teksnya gelap
    $warnaTeks = $color === 'yellow' ? 'text-yellow-950' : 'text-white';
@endphp
<div {{ $attributes->merge(['class' => "min-w-0 rounded-xl bg-gradient-to-br {$gradien} p-4 {$warnaTeks} shadow-sm"]) }}>
    <p class="text-xs font-medium opacity-90">{{ $label }}</p>
    <p class="mt-1 truncate text-2xl font-bold tabular-nums">{{ is_numeric($value) ? number_format($value) : $value }}</p>
    @if($sub)
        <p class="mt-1 text-xs opacity-80">{{ $sub }}</p>
    @endif
    {{ $slot }}
</div>
