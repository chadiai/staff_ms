@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 text-sm focus:border-regal-blue focus:ring focus:ring-regal-blue focus:ring-opacity-50 rounded-md shadow-sm']) !!}>{{$slot}}</textarea>
