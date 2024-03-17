@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp

@props([])

<span {{ $attributes->merge(['class' => 'block text-error text-sm mt-1']) }}>
    {{ $slot }}
</span>
