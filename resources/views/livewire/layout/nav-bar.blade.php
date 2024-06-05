<nav class="container mx-auto p-4 flex justify-center">
    {{-- left navigation--}}
    <div class="flex justify-center space-x-2">
        {{-- Logo --}}
        <a href="{{ route('home') }}">
            <x-tmk.logo class="w-8 h-8"/>
        </a>
        <a class="hidden sm:block font-medium text-lg mt-3" href="{{ route('home') }}">
            Staff Management System
        </a>
    </div>
</nav>
<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar"
        type="button"
        class="absolute top-0 left-0 p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <span class="text-xl font-medium">Menu</span>
    {{--    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">--}}
    {{--        <path clip-rule="evenodd" fill-rule="evenodd"--}}
    {{--              d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>--}}
    {{--    </svg>--}}
</button>
<aside id="default-sidebar"
       class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 border-e"
       aria-label="Sidebar">
    <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar"
            type="button"
            class="absolute top-0 left-52 w-6 h-6 p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden block dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Close sidebar</span>
        <svg class="absolute top-0 right-4 w-8 h-8" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
             xmlns="http://www.w3.org/2000/svg">
            <path d="M16.5 3.5L3.5 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M16.5 16.5L3.5 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
    </button>
    <div class="h-full px-5 py-4 overflow-y-auto bg-gray-50">
        {{-- all users --}}
        @if (auth()->check())
            
        <div
            class="block px-4 py-1 text-xs text-gray-400">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
        <x-dropdown-link href="{{ route('home') }}">Home</x-dropdown-link>

        <x-dropdown-link href="{{ route('user.schedule') }}">View Schedule</x-dropdown-link>
        <x-dropdown-link href="{{ route('profile.show') }}">Update Profile</x-dropdown-link>
        <x-dropdown-link href="{{ route('user.f-a-qs') }}">Frequently Asked Questions</x-dropdown-link>

            <div class="border-t border-gray-100"></div>

            @if(auth()->user()->isAdminOrSuperAdmin())

                <div class="border-t border-gray-100"></div>
                {{-- admins only --}}
                <div class="block px-4 py-2 text-xs text-gray-400">Admin</div>
                <x-dropdown-link href="{{ route('admin.users') }}">Manage Users</x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.appointments') }}">Manage Appointments</x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.invoices') }}">Manage Invoices</x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.files') }}">Manage Files</x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.categories') }}">Manage Categories</x-dropdown-link>
            @endif

            @if(auth()->user()->isCook() || auth()->user()->isAdminOrSuperAdmin())
                <div class="border-t border-gray-100"></div>
                {{-- cooks only --}}
                <div class="block px-4 py-2 text-xs text-gray-400">Cook</div>
                <x-dropdown-link href="{{ route('cook.meals') }}">Manage Meals</x-dropdown-link>
                <x-dropdown-link href="{{ route('cook.schedule-meals') }}">Manage Meal Plans</x-dropdown-link>
            @endif

            @if(auth()->user()->staff_role_id || auth()->user()->isAdminOrSuperAdmin())
                <div class="border-t border-gray-100"></div>
                {{-- staff only --}}
                <div class="block px-4 py-2 text-xs text-gray-400">Staff</div>
                <x-dropdown-link href="{{ route('staff.tasks') }}">Manage Tasks</x-dropdown-link>
                <x-dropdown-link href="{{ route('staff.submit-invoice') }}" id="submitinvoice">Submit Invoice
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('staff.absence-schedule') }}">Absence Schedule</x-dropdown-link>
                <x-dropdown-link href="{{ route('staff.absence') }}">Declare Absence</x-dropdown-link>

            @endif

            <div class="border-t border-gray-100"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-red-500 block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">
                    Logout
                </button>
            </form>
        @endif
    </div>
</aside>
