@props(['field', 'sortField' => null, 'sortDirection' => 'asc'])

@php
    $isSorted = $sortField === $field;
    $classes = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition select-none';

    if ($isSorted) {
        $classes .= ' bg-gray-50 text-emerald-600';
    }
@endphp

<th {{ $attributes->merge(['class' => $classes]) }} wire:click="sortBy('{{ $field }}')">
    <div class="flex items-center space-x-1">
        <span>{{ $slot }}</span>

        @if($isSorted)
            @if($sortDirection === 'asc')
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
            @else
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            @endif
        @else
            <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
        @endif
    </div>
</th>
