<div>
    <div id="start-absence-schedule">
        <div class="absolute lg:top-2 right-2 top-20 hidden md:block">
            <x-button class="h-10">
                Start the tour
            </x-button>
        </div>
        <div class="absolute right-4 mt-1 top-20 md:hidden ">
            <x-heroicon-m-question-mark-circle class="w-10 text-regal-blue hover:text-regal-blue-dark cursor-pointer"/>

        </div>
    </div>
    <div>
        <div class="fixed top-8 left-1/2 md:ml-32 -translate-x-1/2 z-50 animate-pulse"
             wire:loading>
            <x-tmk.preloader class="bg-blue-400/60 text-white border border-blue-600 shadow-2xl p-4">
                {{ $loading }}
            </x-tmk.preloader>
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <x-tmk.section id="calendar" class="min-w-min">
        </x-tmk.section>
    </div>
    <x-dialog-modal id="eventModal"
                    wire:model="showEventModal">
        <x-slot name="title">
            @if($showSection)
                <h2>Absence of {{$sectionEvent->user->first_name}} {{$sectionEvent->user->last_name}}</h2>
            @endif
        </x-slot>
        <x-slot name="content">
            @if($showSection)
                <div class="flex bg-white rounded-lg overflow-hidden w-full">
                    <img class="object-fill w-1/4"
                         src="{{ $sectionEvent->user->profile_photo_url }}"
                         alt="{{$sectionEvent->user->first_name}} profile picture"
                    >

                    <div class="flex-1 flex flex-col">
                        <div class="flex-1 p-4">
                            <p class="text-lg font-bold">{{ $sectionEvent->user->first_name }} {{$sectionEvent->user->last_name}}</p>
                            <p class="pb-2"><span
                                    class="font-medium underline">Reason of absence:</span> {{$sectionEvent->reason_of_absence}}
                            </p>
                            <p class="pb-2"><span
                                    class="font-medium underline">Role:</span> {{$sectionEvent->user->staff_role->name}}
                            </p>
                            <p class="pb-2"><span
                                    class="font-medium underline">Phone:</span> {{$sectionEvent->user->phoneFormat}}</p>
                            <p class="pb-2 font-medium">{{ $sectionEvent->user->first_name }} is going to be absent from
                                <span
                                    class="font-bold">{{ date('d/m/Y H:i', strtotime($sectionEvent->start_date_time)) }}</span>
                                to <span
                                    class="font-bold">{{ date('d/m/Y H:i', strtotime($sectionEvent->end_date_time)) }}</span>.
                            </p>

                        </div>
                    </div>
                </div>
            @endif
        </x-slot>
        <x-slot name="footer" hidden>
            @if($showSection)
                @if(auth()->user()->id == $sectionEvent->user->id || auth()->user()->isAdminOrSuperAdmin())

                    <div class="flex gap-1 [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition"
                         style="float: right;">
                        <x-secondary-button class="ml-2" @click="show = false">Back</x-secondary-button>
                        <x-phosphor-pencil-line-duotone
                            wire:click="setNewSectionEvent({{ $sectionEvent->id }})"
                            data-tippy-content="Edit this absence declaration"
                            class="w-12 text-gray-300 hover:text-green-600 "/>
                        <x-phosphor-trash-duotone
                            x-data=""
                            @click="$dispatch('swal:confirm', {
                                        title: 'Delete this absence?',
                                        cancelButtonText: 'NO!',
                                        confirmButtonText: 'YES DELETE THIS ABSENCE',
                                        next: {
                                            event: 'delete-absence',
                                            params: {
                                                id: {{ $sectionEvent->id }}
                                            }
                                        }
                                    });"
                            data-tippy-content="Delete this absence declaration"
                            class="w-12 text-gray-300 hover:text-red-600 mr-2"/>
                    </div>
                @endif
            @endif
        </x-slot>
    </x-dialog-modal>


    <x-dialog-modal id="sectionEventModal" wire:model="showModal">
        <x-slot:title>
            <h2>Edit Scheduled Absence</h2>
        </x-slot:title>
        <x-slot name="content">
            <div class="fixed top-8 left-1/2 md:ml-32 -translate-x-1/2 z-50 animate-pulse"
                 wire:loading>
                <x-tmk.preloader class="bg-blue-400/60 text-white border border-blue-600 shadow-2xl p-4">
                    {{ $loading }}
                </x-tmk.preloader>
            </div>
            @if ($errors->any())
                <x-tmk.alert type="danger">
                    <x-tmk.list>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </x-tmk.list>
                </x-tmk.alert>
            @endif
            <div>
                <div class="w-full">
                    <div class="md:mr-3 md:ml-3">
                        <x-label for="startDateTime" value="From:" class="mt-4"/>
                        <x-input id="startDateTime" type="datetime-local"
                                 wire:model.defer="newSectionEvent.start_date_time"
                                 class="mt-1 w-full"/>
                        {{--                        <x-input id="startDateTime" type="text"--}}
                        {{--                                 wire:model.defer="newSectionEvent.start_date_time"--}}
                        {{--                                 class="mt-1 w-full"/>--}}
                    </div>
                    <div class="md:mr-3 md:ml-3">
                        <x-label for="endDateTime" value="Until:" class="mt-4"/>
                        <x-input id="endDateTime" type="datetime-local"
                                 wire:model.defer="newSectionEvent.end_date_time"
                                 class="mt-1 w-full"/>
                    </div>
                </div>
                <div class="w-full">
                    <div class="md:mr-3 md:ml-3">
                        {{--                        <?php dump($this->newSectionEvent); ?>--}}
                        <x-label for="reasonOfAbsence" value="Reason of absence:" class="mt-4"/>
                        <x-tmk.form.textarea id="reasonOfAbsence" wire:model.defer="newSectionEvent.reason_of_absence"
                                             class="p-2 mt-1 w-full h-32">{{ $this->newSectionEvent['reason_of_absence'] }}</x-tmk.form.textarea>
                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class=" gap-2 mt-2 ml-5" x-data="">
                <x-secondary-button wire:click="resetSectionEvent()">Cancel</x-secondary-button>
                <x-button
                    wire:click="updateSectionEvent({{ $newSectionEvent['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Save absence
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
