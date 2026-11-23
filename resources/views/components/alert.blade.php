@props(['type' => 'success', 'message' => null])

@php
    $classes = [
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
        'error' => 'bg-rose-50 border-rose-200 text-rose-800',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
    ][$type] ?? 'bg-blue-50 border-blue-200 text-blue-800';

    $icon = [
        'success' => 'fa-circle-check text-emerald-600',
        'error' => 'fa-circle-exclamation text-rose-600',
        'warning' => 'fa-triangle-exclamation text-amber-600',
        'info' => 'fa-circle-info text-blue-600',
    ][$type] ?? 'fa-circle-info text-blue-600';
@endphp

<div class="p-4 rounded-2xl flex items-center gap-3 border {{ $classes }} mb-6 shadow-sm transition">
    <i class="fa-solid {{ $icon }} text-lg"></i>
    <span class="text-xs sm:text-sm font-semibold flex-1">
        {{ $message ?? $slot }}
    </span>
</div>
