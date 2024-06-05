<x-staff-management-layout>

    <x-slot name="description">Home Page</x-slot>
    <x-slot name="title">Home Page</x-slot>

    <p>Staff Management System</p>

    @push('script')
        <script>
            console.log('The JavaScript works! 🙂')
        </script>
    @endpush

</x-staff-management-layout>
