@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp

@aware(['error', 'name', 'reverse'])

@props([
    'max'=> now()->toDateString(),
    'min'=> now()->subYear(100)->toDateString(),
    'value'=> now()->toDateString(),
    'name' => '',
    'error' => isset($name) && $name !== null ? $errors->first($name) : null,
    'reverse' => false,
])

<input
    type="text" onfocus="(this.type = 'date')"
    name="{{ $name }}"
    min="{{$min}}"
    max="{{$max}}"
    {{ $attributes->class([
        'w-full bg-transparent px-0 py-2 text-sm font-normal border-0 border-b focus:ring-0 focus:outline-0 focus:outline-offset-0',
        'text-gray-50 placeholder-white/70 border-white/50 focus:border-white focus:outline-white' => $reverse,
        'text-primary placeholder-primary border-primary focus:border-primary' => !$reverse,
    ]) }}>
