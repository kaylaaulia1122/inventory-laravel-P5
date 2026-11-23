@props(['status' => 'safe'])

@php
    if ($status === 'empty') {
        $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
        $icon = 'fa-solid fa-circle-xmark';
        $text = 'Stok Habis (0)';
    } elseif ($status === 'low') {
        $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
        $icon = 'fa-solid fa-triangle-exclamation';
        $text = 'Stok Menipis';
    } else {
        $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        $icon = 'fa-solid fa-circle-check';
        $text = 'Stok Aman';
    }
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badgeClass }}">
    <i class="{{ $icon }}"></i>
    {{ $text }}
</span>
