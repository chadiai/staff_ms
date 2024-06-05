<nav class="container mx-auto p-4 flex justify-between">
    {{-- left navigation--}}
    <div class="flex items-center space-x-2">
        {{-- Logo --}}
        <a href="{{ route('home') }}">
            <x-tmk.logo class="w-8 h-8"/>
        </a>
        <a class="hidden sm:block font-medium text-lg" href="{{ route('home') }}">
            Staff Management System
        </a>
    </div>

    {{-- right navigation --}}

    <div class="relative flex items-center space-x-2">
        @guest
            <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                Login
            </x-nav-link>
            {{--Not visible--}}
            {{-- <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                Register
            </x-nav-link> --}}
        @endguest
        {{-- dropdown navigation--}}
        @auth
            <x-dropdown align="right" width="48">
                {{-- avatar --}}
                <x-slot name="trigger">
                    <img class="rounded-full h-8 w-8 cursor-pointer"
                         src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}"
                         alt="{{ auth()->user()->name }}">
                </x-slot>
                <x-slot name="content">
                    {{-- all users --}}
                    <div class="block px-4 py-2 text-xs text-gray-400">{{ auth()->user()->first_name }}</div>
                    <x-dropdown-link href="{{ route('user.schedule') }}">View Schedule</x-dropdown-link>
                    <x-dropdown-link href="{{ route('profile.show') }}">Update Profile</x-dropdown-link>
                    <div class="border-t border-gray-100"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">
                            Logout
                        </button>
                    </form>
                    @if(auth()->user()->authorization_type_id === 1)

                        <div class="border-t border-gray-100"></div>
                        {{-- admins only --}}
                        <div class="block px-4 py-2 text-xs text-gray-400">Admin</div>
                        <x-dropdown-link href="{{ route('admin.users') }}">Manage Users</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.appointments') }}">Manage Appointments</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.invoices') }}">Manage Invoices</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.files') }}">Manage Files</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.categories') }}">Manage Categories</x-dropdown-link>
                    @endif

                    @if(auth()->user()->staff_role_id === 4 || auth()->user()->authorization_type_id === 1)
                        <div class="border-t border-gray-100"></div>
                        {{-- cooks only --}}
                        <div class="block px-4 py-2 text-xs text-gray-400">Cook</div>
                        <x-dropdown-link href="{{ route('cook.meals') }}">Manage Meals</x-dropdown-link>
                        <x-dropdown-link href="{{ route('cook.schedule-meals') }}">Schedule a Meal</x-dropdown-link>
                    @endif

                    @if(auth()->user()->staff_role_id || auth()->user()->authorization_type_id === 1)
                        <div class="border-t border-gray-100"></div>
                        {{-- staff only --}}
                        <div class="block px-4 py-2 text-xs text-gray-400">Staff</div>
                        <x-dropdown-link href="{{ route('staff.tasks') }}">Manage Tasks</x-dropdown-link>
                        <x-dropdown-link href="{{ route('staff.submit-invoice') }}">Submit Invoice</x-dropdown-link>
                        <x-dropdown-link href="{{ route('staff.absence') }}">Declare Absence</x-dropdown-link>

                    @endif


                </x-slot>
            </x-dropdown>
        @endauth
    </div>


</nav>
