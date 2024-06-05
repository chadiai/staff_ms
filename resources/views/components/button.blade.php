<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#5fb2d3] border border-transparent rounded-md font-semibold text-xs lg:font-bold lg:text-sm-center text-white uppercase tracking-widest hover:bg-regal-blue-dark focus:bg-regal-blue-dark active:bg-regal-blue-dark focus:outline-none focus:ring-2 focus:ring-regal-blue-dark focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
