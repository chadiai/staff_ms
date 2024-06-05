@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-regal-blue focus:ring-regal-blue rounded-md shadow-sm']) !!}>
