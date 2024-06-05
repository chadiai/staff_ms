<div
    {{$attributes->merge(['class'=>"my-4 rounded-md !flex items-center bg-regal-blue gap-4 text-white border border-blue-600 shadow-2xl p-4"])}}}>
    <x-phosphor-spinner-gap-bold class="animate-spin w-6 h-6"/>
    <span class="flex-1">
        @if($slot->isEmpty())
            <p>Processing data...</p>
        @else
            {{$slot}}
        @endif
    </span>
</div>
