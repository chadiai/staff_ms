<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-bold text-sm text-gray-700 uppercase tracking-widest shadow-xs hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-regal-blue focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
