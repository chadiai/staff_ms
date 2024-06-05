<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>

@props([
    'checked' => false,
    'disabled' => false,
    'value' => 'true',
    'name' => null,
    'id' => null,
    'colorOff' => 'bg-gray-200',
    'colorOn' => 'bg-green-300',
    'textOff' => '✘',
    'textOn' => '✓',
])

@php
    $checked = filter_var($checked, FILTER_VALIDATE_BOOLEAN);
    $disabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
    $name = $name ?? 'checkbox_' . rand();
    $id = $id ?? $name;
    $cursor = $disabled ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'
@endphp

<label class="switch">
    <input type="checkbox" class="hidden peer"
           name="{{ $name }}"
           id="{{ $name }}"
           value="{{ $value }}"
           {{ $checked ? 'checked' : '' }}
           {{ $disabled ? 'disabled' : '' }}
           wire:model="{{ $attributes->get('wire:model') }}">
    <span class="slider round"></span>
</label>


