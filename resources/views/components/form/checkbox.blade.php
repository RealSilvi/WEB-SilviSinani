@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp

@aware(['error', 'name', 'reverse'])

@props([
    'type' => 'checkbox',
    'name' => '',
    'error' => isset($name) && $name !== null ? $errors->first($name) : null,
    'reverse' => false,
    'checked' => null,
])

<input type="{{ $type }}" name="{{$name}}"
    @checked($checked)
    {{ $attributes->class([
        'block w-4 h-4 bg-transparent border focus:ring-0 focus:outline-0 focus:outline-offset-0 focus:outline-none',
        'text-primary border-primary/40 focus:outline-primary' => !$reverse,
        'text-primary border-primary/50 checked:border-primary/50 focus:outline-primary/50' => $reverse,
    ]) }}>
