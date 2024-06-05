{{--Not using it anymore--}}
@guest
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        {{--    <x-application-logo class="block h-12 w-auto" />--}}

        <h1 class="mt-4 text-2xl font-medium text-gray-900">
            Welcome to the staff administration application.
        </h1>

        <p class="mt-6 text-gray-600 leading-relaxed">
            This application was created to keep an overview of when staff is working and which recurring appointments
            or tasks there are on the different estates. You are also able to keep track of paperwork and invoices in
            one place. People can plan meals and declare their absence in the future.
        </p>
    </div>
@endguest
{{-- tasks for users--}}
@auth
    @php
        $tasks = auth()->user()->eventMembersWithinThreeDays(true)->get();
        $tasksCount = $tasks->count();
        $groupedTasks = $tasks->groupBy(function ($task) {
            return \Carbon\Carbon::parse($task->event->start_date_time)->format('l jS \o\f F');
        })->sortBy(function ($tasks, $date) {
            return \Carbon\Carbon::createFromFormat('l jS \o\f F', $date)->startOfDay();
        });

        $appointments = auth()->user()->eventMembersWithinThreeDays(false)->get();
        $appointmentsCount = $appointments->count();
        $groupedAppointments = $appointments->groupBy(function ($appointment) {
            return \Carbon\Carbon::parse($appointment->event->start_date_time)->format('l jS \o\f F');
        })->sortBy(function ($appointments, $date) {
            return \Carbon\Carbon::createFromFormat('l jS \o\f F', $date)->startOfDay();
        });

    @endphp

    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        {{--Tasks--}}
        <div class="p-2">
            <h1 class="text-xl font-medium text-gray-900">
                @if ($tasksCount > 0)
                    You have <strong>{{ $tasksCount }}</strong> task{{$tasksCount > 1 ? 's' : '' }} within the next
                    three days:
                @else
                    You have no tasks within the next three days.
                @endif
            </h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-3">
                @foreach($groupedTasks as $date => $group)
                    <div style="min-height: 100px;">
                        <a class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow h-full">
                            <h5 class="mb-2 text-lg font-semibold text-gray-900">
                                @if(\Carbon\Carbon::createFromFormat('l jS \o\f F', $date)->isToday())
                                    Today:
                                @elseif(\Carbon\Carbon::createFromFormat('l jS \o\f F', $date)->isTomorrow())
                                    Tomorrow:
                                @else
                                    {{ $date }}:
                                @endif
                            </h5>

                            <ul class="pb-1 pl-6 list-disc">
                                @foreach($group as $task)
                                    <li>
                                        '{{ $task->event->name}}'@if(!empty($task->event->location))
                                            at {{ $task->event->location }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        {{--Appointments--}}
        <div class="p-2">
            <h1 class="text-xl font-medium text-gray-900">
                @if ($appointmentsCount > 0)
                    You have <strong>{{ $appointmentsCount }}</strong> appointment{{$appointmentsCount > 1 ? 's' : '' }}
                    within the next three days:
                @else
                    You have no appointments within the next three days.
                @endif
            </h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-3">
                @foreach($groupedAppointments as $date => $group)
                    <div style="min-height: 100px;">
                        <a class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow h-full">
                            <h5 class="mb-2 text-lg font-semibold text-gray-900">
                                @if(\Carbon\Carbon::createFromFormat('l jS \o\f F', $date)->isToday())
                                    Today:
                                @elseif(\Carbon\Carbon::createFromFormat('l jS \o\f F', $date)->isTomorrow())
                                    Tomorrow:
                                @else
                                    {{ $date }}:
                                @endif
                            </h5>

                            <ul class="pb-1 pl-6 list-disc">
                                @foreach($group as $appointment)
                                    <li>
                                        '{{ $appointment->event->name}}'@if(!empty($appointment->event->location))
                                            at {{ $appointment->event->location }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
    <hr>

@endauth


<div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">

    @guest
        <div class="">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="ml-1 w-5 h-5 fill-black">
                    <path
                        d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                    <path
                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                </svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-900">
                    Schedule for everyone
                </h2>
            </div>

            <p class="mt-4 text-gray-600 text-md leading-relaxed h-11">
                The schedule provides a clear overview of all the meal plans, tasks and appointments. This way, you can
                immediately get a clear overview of your tasks.
            </p>
        </div>

        <div class="pt-6 md:pt-10 lg:pt-0">
            <div class="flex items-center mt-8 md:mt-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="ml-1 w-5 h-5 fill-black">
                    <path
                        d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z"/>
                    <path
                        d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                </svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-900">
                    Submit paperwork
                </h2>
            </div>

            <p class="mt-4 text-gray-600 text-md leading-relaxed h-11">
                You can submit different invoices and other paperwork.
            </p>
        </div>

        <div class="pt-4 md:pt-2 lg:pt-16 pb-4">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="ml-1 w-5 h-5 fill-black">
                    <path
                        d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21.294 21.294 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21.317 21.317 0 0 0 14 7.655V1.222z"/>
                </svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-900">
                    Declare absence
                </h2>
            </div>

            <p class="mt-4 text-gray-600 text-md leading-relaxed h-7 mb-4">
                The staff can declare their absence in advance.
            </p>
        </div>
    @endguest

    @auth
        {{--view schedule for everyone--}}
        <div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="ml-1 w-5 h-5 fill-black">
                    <path
                        d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                    <path
                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                </svg>
                <h2 class="ml-3 text-xl font-semibold text-gray-900">
                    Schedule for everyone
                </h2>
            </div>

            <p class="mt-4 text-gray-600 text-md leading-relaxed h-11">
                The schedule provides a clear overview of all the meal plans, tasks and appointments. This way, you can
                immediately get a clear overview of your tasks.
            </p>
            <p class="mt-20 lg:mt-24 xl:mt-16 text-sm">
            <div class="group">
                <a href="{{ route('user.schedule') }}"
                   class="inline-flex items-center font-semibold text-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                    Go to the schedule

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                         class="ml-1 w-5 h-5 fill-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                        <path fill-rule="evenodd"
                              d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                              clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
            </p>
        </div>
        {{-- submit paperwork, declare absence for staff--}}
        @if (auth()->user()->authorization_type_id <= 3)
            <div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="ml-1 w-5 h-5 fill-black">
                        <path
                            d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z"/>
                        <path
                            d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                    </svg>
                    <h2 class="ml-3 text-xl font-semibold text-gray-900">
                        Submit paperwork
                    </h2>
                </div>

                <p class="mt-4 text-gray-600 text-md leading-relaxed h-11">
                    Here you can submit different invoices.
                </p>

                <p class="mt-4 md:mt-4 lg:mt-3 text-sm">
                <div class="group">
                    <a href="{{ route('staff.submit-invoice') }}"
                       class="inline-flex items-center font-semibold text-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                        Go to submit invoice

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             class="ml-1 w-5 h-5 fill-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                            <path fill-rule="evenodd"
                                  d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
                </p>
            </div>

            <div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="ml-1 w-5 h-5 fill-black">
                        <path
                            d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21.294 21.294 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21.317 21.317 0 0 0 14 7.655V1.222z"/>
                    </svg>
                    <h2 class="ml-3 text-xl font-semibold text-gray-900">
                        Declare absence
                    </h2>
                </div>

                <p class="mt-4 text-gray-600 text-md leading-relaxed h-7">
                    The staff can declare their absence in advance.
                </p>

                <p class="mt-16 md:mt-12 lg:mt-14 xl:mt-8 text-sm">
                <div class="group">
                    <a href="{{ route('staff.absence') }}"
                       class="inline-flex items-center font-semibold text-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                        Go to absence page

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             class="ml-1 w-5 h-5 fill-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                            <path fill-rule="evenodd"
                                  d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
                </p>
            </div>
        @endif

        {{-- cook can plan a meal--}}
        @if (auth()->user()->authorization_type_id <= 2)
            <div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="ml-1 w-5 h-5 fill-black">
                        <path d="M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        <path
                            d="M13.997 5.17a5 5 0 0 0-8.101-4.09A5 5 0 0 0 1.28 9.342a5 5 0 0 0 8.336 5.109 3.5 3.5 0 0 0 5.201-4.065 3.001 3.001 0 0 0-.822-5.216zm-1-.034a1 1 0 0 0 .668.977 2.001 2.001 0 0 1 .547 3.478 1 1 0 0 0-.341 1.113 2.5 2.5 0 0 1-3.715 2.905 1 1 0 0 0-1.262.152 4 4 0 0 1-6.67-4.087 1 1 0 0 0-.2-1 4 4 0 0 1 3.693-6.61 1 1 0 0 0 .8-.2 4 4 0 0 1 6.48 3.273z"/>
                    </svg>
                    <h2 class="ml-3 text-xl font-semibold text-gray-900">
                        Plan a meal
                    </h2>
                </div>

                <p class="mt-4 text-gray-600 text-md leading-relaxed h-7">
                    Here you can schedule meals that the cook will prepare for you.
                </p>

                <p class="mt-10 xl:mt-8 text-sm">
                <div class="group">
                    <a href="{{ route('cook.schedule-meals') }}"
                       class="inline-flex items-center font-semibold text-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                        Go to plan meals page

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             class="ml-1 w-5 h-5 fill-regal-blue-dark group-hover:text-black group-hover:fill-black transition-fill duration-300">
                            <path fill-rule="evenodd"
                                  d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
                </p>
            </div>
        @endif

        {{-- admins get 2 more links; manage users + view paperwork--}}
        @if (auth()->user()->authorization_type_id == 1)
            <div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="ml-1 w-5 h-5 fill-black">
                        <path
                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    </svg>
                    <h2 class="ml-3 text-xl font-semibold text-gray-900">
                        Manage users
                    </h2>
                </div>

                <p class="mt-4 text-gray-600 text-md leading-relaxed h-7">
                    Here you can change personal information about users.
                </p>

                <p class="mt-10 lg:mt-8 text-sm">
                <div class="group">
                    <a href="{{ route('admin.users') }}"
                       class="inline-flex items-center font-semibold text-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                        Go to manage users page

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             class="ml-1 w-5 h-5 fill-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                            <path fill-rule="evenodd"
                                  d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
                </p>
            </div>

            <div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="ml-1 w-5 h-5 fill-black">
                        <path
                            d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0V3z"/>
                    </svg>
                    <h2 class="ml-3 text-xl font-semibold text-gray-900">
                        View paperwork
                    </h2>
                </div>

                <p class="mt-4 text-gray-600 text-md leading-relaxed h-7">
                    Here you can view all the available paperwork that has been submitted.
                </p>

                <p class="mt-10 text-sm">
                <div class="group">
                    <a href="{{ route('admin.invoices') }}"
                       class="inline-flex items-center font-semibold text-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                        Go to manage invoices page

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             class="ml-1 w-5 h-5 fill-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                            <path fill-rule="evenodd"
                                  d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
                </p>
                <p class="mt-2 text-sm">
                <div class="group">
                    <a href="{{ route('admin.files') }}"
                       class="inline-flex items-center font-semibold text-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                        Go to manage files page
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             class="ml-1 w-5 h-5 fill-regal-blue-dark group-hover:text-black group-hover:fill-black transition-colors duration-300">
                            <path fill-rule="evenodd"
                                  d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
                </p>
            </div>
        @endif
    @endauth
</div>
