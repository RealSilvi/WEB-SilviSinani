@props([
    'name' => null,
    'error' => isset($name) && $name !== null ? $errors->first($name) : null,
    'reverse' => false,
    'type' => null,
])

@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    $horizontal = $type === 'checkbox' || $type === 'radio';
@endphp

<div {{$attributes->class([
    'flex gap-2',
    'items-start' => $horizontal,
    'flex-col' => !$horizontal,
])}}>
    {{ $slot }}

    @if ($error)
        <x-form.error-message>{{ $error }}</x-form.error-message>
    @endif
</div>
