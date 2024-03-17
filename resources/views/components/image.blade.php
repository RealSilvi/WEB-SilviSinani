@php
    /**
     * @var string $placeholder_src
     * @var string $placeholder_alt
     * @var \Illuminate\View\ComponentAttributeBag $attributes
     */
@endphp

@props([
    'src' => null,
    'alt' => null,
])

<div>
    <img {{ $attributes->class([]) }} src="{{ $src ?? $placeholder_src }}" alt="{{ $alt ?? $placeholder_alt }}">
</div>
