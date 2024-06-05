<div>
    {{--    bootstrap user support tour    --}}
    <div id="start-absence">
        <div class="absolute lg:top-2 right-2 top-20 hidden md:block">
            <x-button class="h-10">
                Start the tour
            </x-button>
        </div>
        <div class="absolute right-4 mt-1 top-20 md:hidden ">
            <x-heroicon-m-question-mark-circle class="w-10 text-regal-blue hover:text-regal-blue-dark cursor-pointer"/>

        </div>
    </div>
    <x-tmk.section class="mb-4">
        {{-- show preloader while fetching data in the background --}}
        <div class="fixed top-8 left-1/2 md:ml-32 -translate-x-1/2 z-50 animate-pulse"
             wire:loading>
            <x-tmk.preloader class="bg-regal-blue text-white border border-blue-600 shadow-2xl p-4">
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
        <div class="md:flex md:flex-row">
            <div>
                <div class="md:mr-3 md:ml-3" id="from">
                    <x-label for="startDateTime" value="From" class="mt-4"/>
                    <x-input id="startDateTime" type="datetime-local"
                             wire:model.defer="newAbsence.startDateTime"
                             class="mt-1 w-full md:w-11/12 lg:w-64 xl:w-96" autofocus/>
                </div>
                <div class="md:mr-3 md:ml-3" id="until">
                    <x-label for="endDateTime" value="Until" class="mt-4"/>
                    <x-input id="endDateTime" type="datetime-local"
                             wire:model.defer="newAbsence.endDateTime"
                             class="mt-1 w-full md:w-11/12 lg:w-64 xl:w-96"/>
                </div>
            </div>
            <div>
                <div class="md:mr-3 md:ml-3 xl:ml-16" id="reason">
                    <x-label for="reasonOfAbsence" value="Reason of absence" class="mt-4"/>
                    <x-tmk.form.textarea id="reasonOfAbsence" wire:model.defer="newAbsence.reasonOfAbsence"
                                         class="p-2 mt-1 w-full md:w-11/12 lg:w-72 xl:w-96 h-32"></x-tmk.form.textarea>
                </div>
            </div>
        </div>
        @if(auth()->user()->isAdmin())
            <div class="md:mr-3 md:ml-3 mb-9">
                <x-label for="staffMember" value="Staff member that will be absent" class="mt-4"/>
                <x-tmk.form.select id="staffMember" type="text" class=" mt-1 w-full md:w-3/5 lg:w-2/2 xl:w-96"
                                   wire:model="staffMember">
                    <option value="">Select a staff member</option>
                    @foreach($staffMembers as $staffMember)
                        <option
                            value="{{$staffMember->id}}">{{$staffMember->first_name}} {{$staffMember->last_name}}</option>
                    @endforeach
                </x-tmk.form.select>
            </div>
        @endif


        <div class=" gap-2 mt-2 md:ml-3">
            <x-button id="save"
                      wire:click="createAbsence()"
                      wire:loading.attr="disabled"
                      class="h-10">Save absence
            </x-button>
            <x-secondary-button wire:click="resetAbsence()" class="h-10 ml-2 ">Cancel</x-secondary-button>

        </div>

    </x-tmk.section>

</div>
