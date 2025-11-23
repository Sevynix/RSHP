@props(['type' => 'info', 'message'])

@php
    $icons = [
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle',
    ];
    $icon = $icons[$type] ?? 'fa-info-circle';
@endphp

<div class="alert alert-{{ $type }} alert-dismissible fade show d-flex align-items-center" role="alert">
    <i class="fas {{ $icon }} me-2"></i>
    <div>{{ $message }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
