@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp

@aware(['error', 'name', 'reverse'])

@props([
    'type' => 'text',
    'name' => '',
    'error' => isset($name) && $name !== null ? $errors->first($name) : null,
    'reverse' => false,
])

<textarea name="{{ $name }}" type="{{ $type }}"
    {{ $attributes->class([
        'w-full px-1 py-5 text-base font-base border-0 border-b focus:ring-0 focus:outline-none focus:outline-offset-0 focus:outline-none',
        'text-primary placeholder-primary/50 border-primary ' => !$reverse,
    ]) }}>{{ !$slot->isEmpty() ? $slot : '' }}</textarea>
