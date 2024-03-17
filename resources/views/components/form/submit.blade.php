@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp


@props([
    'reverse' => false,
])

<button
    type="submit"
    {{ $attributes->class([
        'inline-flex p-3 items-center justify-center text-sm xl:text-lg font-black xl:font-normal',
        'bg-primary text-white' => !$reverse,
        'bg-white text-primary' => $reverse,
    ]) }}>
    {{ $slot }}
</button>
