@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp

@aware(['reverse', 'type'])

@props([
    'reverse' => false,
    'srOnly' => false,
])

<label
    {{ $attributes->class(['flex items-center text-base text-primary font-black', 'sr-only' => $srOnly]) }}>
    <span>{{ $slot }}</span>
</label>
