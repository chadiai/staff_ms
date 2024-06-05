<div>
    <div id="start-schedule">
        <div class="absolute lg:top-2 right-2 top-20 hidden md:block">
            <x-button class="h-10">
                Start the tour
            </x-button>
        </div>
        <div class="absolute right-4 mt-1 top-20 md:hidden ">
            <x-heroicon-m-question-mark-circle class="w-10 text-regal-blue hover:text-regal-blue-dark cursor-pointer"/>

        </div>
    </div>
    <div class="fixed top-8 left-1/2 md:ml-32 -translate-x-1/2 z-50 animate-pulse"
         wire:loading>
        <x-tmk.preloader class="bg-blue-400/60 text-white border border-blue-600 shadow-2xl p-4">
            {{ $loading }}
        </x-tmk.preloader>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-tmk.section class="flex min-w-min">
        <div id="calendar" class="w-full"></div>
        <div class="flex flex-col ml-1 mt-14 pt-3 h-1/3 hidden md:block" id="colorpicker">
            <div class="flex" data-tippy-content="Change the color for the tasks">
                <x-input id="task-color" type="color" name="color"
                         wire:model.defer="color.task"
                         class="mt-1 block rounded w-10 h-10"/>
                <x-label for="task-color" class="ml-1 mt-3">Task</x-label>
            </div>
            <div class="flex" data-tippy-content="Change the color for the appointments">
                <x-input id="appointment-color" type="color" name="color"
                         wire:model.defer="color.appointment"
                         class="mt-1 block rounded w-10 h-10"/>
                <x-label for="task-color" class="ml-1 mt-3">Appointment</x-label>
            </div>
            <div class="flex" data-tippy-content="Change the color for the meal plans">
                <x-input id="meal-color" type="color" name="color"
                         wire:model.defer="color.meal"
                         class="mt-1 block rounded w-10 h-10"/>
                <x-label for="meal-color" class="ml-1 mt-3">Meal Plan</x-label>
            </div>
            <x-button class="mt-3" wire:click="updateColor()">Apply colors</x-button>
        </div>
    </x-tmk.section>
    <x-dialog-modal id="eventModal"
                    wire:model="showModal">
        <x-slot name="title">
            @if($showSection)
                @if($isEvent)
                    <h1 class="text-3xl">{{$sectionEvent->name}}</h1>
                @else
                    <h1 class="text-3xl">{{$sectionEvent->meal_type->name}}</h1>
                @endif
            @endif
        </x-slot>
        <x-slot name="content">
            @if($showSection)
                @if($isEvent)
                    <h2 class="font-bold text-xl block mt-2 w-full">Description:</h2>
                    <div class="mt-1 p-2 text-xl">{{$sectionEvent->description}}</div>
                    @if($sectionEvent->is_task)
                        <h2 class="font-bold text-xl block mt-2 w-full">Task Members:</h2>
                    @else
                        <h2 class="font-bold text-xl block mt-2 w-full">Appointment Members:</h2>
                    @endif
                    @if(empty($taskMembers))
                    @else
                        <x-tmk.list title="Task Members">
                            @foreach ($taskMembers as $member)
                                <li class="mt-1 text-xl">{{$member['first_name']}} {{$member['last_name']}}</li>
                            @endforeach
                        </x-tmk.list>
                    @endif
                    @isset($sectionEvent->location)
                        <h2 class="font-bold text-xl block mt-2 w-full">Location:</h2>
                        <div class="mt-1 p-2 text-xl">{{$sectionEvent->location}}</div>
                    @endisset
                @else
                    <h2 class="font-bold text-xl block mt-2 w-full">Meal:</h2>
                    <div class="mt-1 text-xl">{{$sectionEvent->meal->name}}</div>
                    @if(empty($mealMembers))
                        <h2 class="font-bold text-xl block mt-2 w-full">No members are subscribed to this meal</h2>
                    @else
                        <h2 class="font-bold text-xl block mt-2 w-full">Subscribed members:</h2>
                        <div>

                            <div>
                                @foreach ($mealMembers as $member)
                                    <div>
                                        <div
                                            class="flex mt-3 justify-between w-full p-3 font-medium text-left text-gray-500 border border-gray-200 rounded-md focus:ring-4 focus:ring-gray-200">
                                            <span class="mt-3">{{$member->first_name}} {{$member->last_name}}</span>
                                            @if(auth()->user()->isAdminOrSuperAdmin() or auth()->user()->isCook() or auth()->user()->isGrandson())
                                                <div
                                                    class="flex justify-between w-3/4 p-3 font-medium text-left text-gray-500  focus:ring-4 focus:ring-gray-200">
                                                    <div class="w-full border border-0 border-r-2"><span
                                                            class="font-bold">Dislikes:</span><br>
                                                        @if($member['dislike'])
                                                            {{$member['dislike']}}
                                                        @else
                                                            None
                                                        @endif
                                                    </div>
                                                    <div class="w-full ml-3"><span
                                                            class="font-bold">Allergies:</span><br>
                                                        @if($member['allergy'])
                                                            {{ $member['allergy'] }}
                                                        @else
                                                            None
                                                        @endif
                                                    </div>
                                                </div>

                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button class="ml-2" wire:click="resetEvent()" @click="show = false">Back</x-secondary-button>
            @if(!$isEvent)
                @if($subscribed)
                    <x-button wire:click="unsubscribeMeal({{$sectionEvent}})" id="subscribe" @click="show = false"
                              class="text-white bg-red-700 hover:bg-red-800 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 ml-2">
                        Unsubscribe
                    </x-button>
                @else
                    <x-button class="ml-2" wire:click="subscribeMeal({{$sectionEvent}})" id="subscribe"
                              @click="show = false">Subscribe
                    </x-button>
                @endif
            @endif
        </x-slot>
    </x-dialog-modal>

</div>
