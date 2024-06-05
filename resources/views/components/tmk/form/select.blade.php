@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-regal-blue focus:border-regal-blue focus:ring focus:ring-regal-blue-dark focus:ring-opacity-50 rounded-md shadow-sm']) !!}>
    {{$slot}}
</select>
