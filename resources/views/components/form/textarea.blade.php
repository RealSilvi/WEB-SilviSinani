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
        'w-full px-0 py-2 text-base font-base border-0 border-b focus:border-primary focus:ring-0 focus:outline-none focus:outline-offset-0 focus:outline-none',
        'text-primary placeholder-primary border-primary focus:border-primary' => !$reverse,
    ]) }}>{{ !$slot->isEmpty() ? $slot : '' }}</textarea>
