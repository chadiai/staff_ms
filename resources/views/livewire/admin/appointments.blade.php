<div>
    <div id="start-event">
        <div class="absolute lg:top-2 right-2 top-20 hidden md:block">
            <x-button class="h-10">
                Start the tour
            </x-button>
        </div>
        <div class="absolute right-4 mt-1 top-20 md:hidden ">
            <x-heroicon-m-question-mark-circle class="w-10 text-regal-blue hover:text-regal-blue-dark cursor-pointer"/>

        </div>
    </div>
    <x-tmk.section>
        <div class="fixed top-8 left-1/2 md:ml-32 -translate-x-1/2 z-50 animate-pulse"
             wire:loading>
            <x-tmk.preloader class="bg-blue-400/60 text-white border border-blue-600 shadow-2xl p-4">
                {{ $loading }}
            </x-tmk.preloader>
        </div>
        {{-- filtering on name or date, type, how many items per page and on what to sort and the direction--}}
        <div class="grid grid-cols-10 gap-4" id="filter">
            <div class="col-span-10 md:col-span-5 lg:col-span-3" id="namefilter">
                <x-label for="name" value="Search"/>
                <div
                    x-data="{ name: @entangle('filter') }"
                    class="relative">
                    <x-input id="name" type="text"
                             x-model.debounce.500ms="name"
                             class="block mt-1 w-full"
                             x-data=""
                             x-init="$el.focus()"
                             placeholder="Filter by name"/>
                    <div
                        x-show="name"
                        @click="name = '';"
                        class="w-5 absolute right-4 top-3 cursor-pointer">
                        <x-phosphor-x-duotone/>
                    </div>
                </div>
            </div>
            <div class="col-span-10 md:col-span-5 lg:col-span-3" id="supfilter">
                <x-label for="superCategory" value="Category"/>
                <x-tmk.form.select id="superCategory" type="text" class="block mt-1 w-full"
                                   wire:model="superCategoryId" wire:change="resetSubCategory()">
                    <option value="0">All categories</option>
                    @foreach($superCategories as $superCategory)
                        <option value="{{$superCategory->id}}">{{$superCategory->name}}</option>
                    @endforeach
                </x-tmk.form.select>
            </div>

            <div class="col-span-10 md:col-span-5 lg:col-span-4" id="subfilter">
                <x-label for="subCategory" value="Sub category"/>
                <x-tmk.form.select id="subCategory" type="text" wire:model.lazy="subCategoryId"
                                   class="block mt-1 w-full">
                    <option value="0">All sub categories</option>
                    @foreach($allCategories as $c)
                        @if($c->super_category_id != null)
                            <option value="{{ $c->id }}">
                                {{ $c->name }}
                            </option>
                        @endif
                    @endforeach
                </x-tmk.form.select>
            </div>
            <div class="col-span-5 md:col-span-5 lg:col-span-3" id="perpagefilter">
                <div class="hidden md:inline">
                    <x-label for="perPage" value="Appointments per page"/>
                </div>
                <div class="md:hidden">
                    <x-label for="perPage" value="Per page"/>
                </div>
                <x-tmk.form.select id="perPage"
                                   wire:model="perPage"
                                   class="block mt-1 p-2 w-full">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </x-tmk.form.select>

            </div>
            <div class="col-span-5 md:col-span-5 lg:col-span-2" id="orderfilter-1">
                <x-label for="orderOption" value="Order by"/>
                <x-tmk.form.select id="orderOption" class="block mt-1 p-2 w-full"
                                   wire:model="orderBy">
                    <option value="start_date_time" wire:click="resort('date')">Date</option>
                    <option value="name" wire:click="resort('name')">Name</option>
                </x-tmk.form.select>
            </div>

            <div class="col-span-5 md:col-span-5 lg:col-span-2" id="orderfilter-2">
                <x-label for="orderDirection" value="Order direction"/>
                <x-tmk.form.select id="orderDirection" class="block mt-1 p-2 w-full"
                                   wire:model="orderAsc">
                    <option value="1">Ascending</option>
                    <option value="0">Descending</option>
                </x-tmk.form.select>
            </div>
            <div class="col-span-5 md:col-span-5 lg:col-span-3 mt-7">
                <x-button wire:click="setNewAppointment()" class="h-10" id="newbutton">
                    new appointment
                </x-button>
            </div>
        </div>
    </x-tmk.section>
    <x-tmk.section class="mb-4 hidden xl:block" id="eventsection">
        <div class="mt-2 mb-6">{{ $appointments->links() }}</div>

        <table class="text-left w-full border border-gray-300">
            <colgroup>
                <col class="w-4/12">
                <col class="w-1/24">
                <col class="w-1/32">
                <col class="w-1/32">
                <col class="w-2/12">
                <col class="w-2/12">
                <col class="w-1/24">
            </colgroup>
            <thead class="h-12">
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 ">
                <th>
                    <span class="ml-2">Name</span>
                </th>
                <th>
                    <span class="ml-2">Date</span>
                </th>
                <th>
                    <span class="ml-2">Start <p class="ml-2">time</p></span>
                </th>
                <th>
                    <span class="ml-2">End <p class="ml-2">time</p></span>


                </th>
                <th>
                    <span class="ml-2">Category</span>

                </th>
                <th>
                    <span class="ml-2">Sub category</span>

                </th>
                <th>

                </th>
            </tr>
            </thead>
            <tbody>
            @forelse($appointments as $appointment)
                <tr class="border-t border-gray-300 [&>td]:p-2">
                    <td class="text-left font-bold cursor-pointer underline"
                        wire:click="setDescription({{ $appointment->id }})"
                    ><span data-tippy-content="Click for more info">{{$appointment->name}}</span>
                    </td>
                    <td>{{date('d/m/Y', strtotime($appointment->start_date_time))}}</td>
                    <td>{{date('H:i', strtotime($appointment->start_date_time))}}</td>
                    <td>{{date('H:i', strtotime($appointment->end_date_time))}}</td>
                    <td>
                        @if($appointment->category->super_category_id != null)
                            <div class="flex gap-1 items-center justify-between">
                                {{ $appointment->category->super_category ? $appointment->category->super_category->name : '-' }}
                                <svg width="50" height="50" style="margin: 0;">
                                    <circle cx="25" cy="25" r="15"
                                            fill="#{{ $appointment->category->super_category->color_hex_code }}"/>
                                </svg>
                            </div>
                        @elseif($appointment->category->id != null)
                            <div class="flex gap-1 items-center justify-between">
                                {{ $appointment->category->name }}
                                <svg width="50" height="50" style="margin: 0;">
                                    <circle cx="25" cy="25" r="15"
                                            fill="#{{ $appointment->category->color_hex_code }}"/>
                                </svg>
                            </div>
                        @endif
                        @if($appointment->category_id == null)
                            <span>N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($appointment->category->super_category_id != null)
                            <div class="flex gap-1 items-center justify-between">
                                {{ $appointment->category->name }}
                                <svg width="50" height="50" style="margin: 0;">
                                    <circle cx="25" cy="25" r="15"
                                            fill="#{{ $appointment->category->color_hex_code }}"/>
                                </svg>
                            </div>
                        @else
                            <span>N/A</span>
                        @endif
                    </td>

                    <td>
                        <div class="flex [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition justify-end">
                            <x-phosphor-pencil-line-duotone
                                x-data=""
                                data-tippy-content="Edit this appointment"
                                wire:click="setNewAppointment({{ $appointment->id }})"
                                class="w-10 text-gray-300 hover:text-green-600"/>
                            <x-phosphor-trash-duotone
                                x-data=""
                                data-tippy-content="Delete this appointment"
                                @click="$dispatch('swal:confirm', {
    html: 'Delete {{ $appointment->name }}?',
    cancelButtonText: 'NO!',
    confirmButtonText: 'YES DELETE THIS APPOINTMENT',
    next: {
        event: 'delete-appointment',
        params: {
            id: {{ $appointment->id }}
        }
    }
})"
                                class="w-10 text-gray-300 hover:text-red-600"/>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border-t border-gray-300 p-4 text-center">
                        <div class="font-bold italic text-blue-300">No appointments found</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-6 mb-2">{{ $appointments->links() }}</div>

    </x-tmk.section>


    {{--        For small and medium screen--}}
    <div class="grid grid-cols-1 gap-4 xl:hidden max-w-screen" id="eventsectionsm">
        <div class="my-4">{{ $appointments->links() }}</div>
        <div class="grid lg:grid-cols-2 md:grid-cols-1 gap-4">
            @forelse($appointments as $appointment)
                <div class="bg-white p-4 rounded-lg shadow max-w-full">
                    <div class="flex space-x-2 text-md justify-between">
                        <div class="text-lg">{{ $appointment->name }}</div>
                    </div>
                    <div class="flex space-x-2 text-md justify-between mt-4 mb-3">
                        <div class="text-md">Location:
                            <span class="ml-4"
                                  style="font-weight: 600;">{{ $appointment->location }}</span></div>
                    </div>

                    <div>Date:
                        <span class="ml-12"
                              style="font-weight: 600;"> {{ date('d/m/Y', strtotime($appointment->start_date_time)) }}</span>
                    </div>
                    <div>Start time:
                        <span class="ml-2"
                              style="font-weight: 600;">{{ date('H:i', strtotime($appointment->start_date_time)) }}</span>
                    </div>
                    <div>End time: <span class="ml-4"
                                         style="font-weight: 600;">{{ date('H:i', strtotime($appointment->end_date_time)) }}</span>
                    </div>
                    <span class="flex items-center gap-1">Tags:
                            @if($appointment->category->super_category_id != null)
                            <span class="flex gap-1 items-center mr-3 ml-3">
                                                        {{ $appointment->category->super_category ? $appointment->category->super_category->name : '-' }}
                                                        <svg width="50" height="50" style="margin: 0;">
                                                            <circle cx="25" cy="25" r="15"
                                                                    fill="#{{ $appointment->category->super_category->color_hex_code }}"/>
                                                        </svg>
                                                    </span>
                        @elseif($appointment->category->id != null)
                            <span class="flex gap-1 items-center mr-3 ml-3">
                                                        {{ $appointment->category->name }}
                                                        <svg width="50" height="50" style="margin: 0;">
                                                            <circle cx="25" cy="25" r="15"
                                                                    fill="#{{ $appointment->category->color_hex_code }}"/>
                                                        </svg>
                                                    </span>
                        @endif
                        @if($appointment->category->super_category_id != null)
                            <span class="flex gap-1 items-center mr-3 ml-3">
                                                        {{ $appointment->category->name }}
                                                        <svg width="50" height="50" style="margin: 0;">
                                                            <circle cx="25" cy="25" r="15"
                                                                    fill="#{{ $appointment->category->color_hex_code }}"/>
                                                        </svg>
                                                    </span>
                        @endif
                        @if($appointment->category_id == null)
                            <span class="italic">None</span>
                        @endif
                            </span>
                    <br>
                    <div>

                        <div class="flex [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition justify-end">
                            <x-phosphor-pencil-line-duotone
                                x-data=""
                                data-tippy-content="Edit this appointment"
                                wire:click="setNewAppointment({{ $appointment->id }})"
                                class="w-10 text-gray-300 hover:text-green-600"/>
                            <x-phosphor-trash-duotone
                                x-data=""
                                data-tippy-content="Delete this appointment"
                                @click="$dispatch('swal:confirm', {
    html: 'Delete {{ $appointment->name }}?',
    cancelButtonText: 'NO!',
    confirmButtonText: 'YES DELETE THIS APPOINTMENT',
    next: {
        event: 'delete-appointment',
        params: {
            id: {{ $appointment->id }}
        }
    }
})"
                                class="w-10 text-gray-300 hover:text-red-600"/>
                        </div>
                    </div>
                </div>
            @empty
                <x-tmk.section class="w-full">
                    <div class="p-4 text-center">
                        <p class="font-bold italic text-blue-300">No appointments found</p>
                    </div>
                </x-tmk.section>
            @endforelse
        </div>
        <div class="my-4">{{ $appointments->links() }}</div>
    </div>


    <x-dialog-modal id="descriptionModal"
                    wire:model="showDescriptionModal">

        <x-slot name="title">
            @if($currentAppointment)
                <h2 class="text-2xl">{{$currentAppointment->name}}</h2>
            @endif
        </x-slot>
        <x-slot name="content">

            @if($currentAppointment)
                <h3 class="text-xl font-medium block mt-2 w-full">Description:</h3>
                <div class="mt-1 ml-4 p-2 text-lg">{{$currentAppointment->description}}</div>
                <h3 class="text-xl block font-medium mt-2 w-full">Appointment Members:</h3>
                <x-tmk.list class="ml-4" title="Appointment Members">
                    @foreach ($appointmentMembers as $member)
                        <li class="mt-1 ml-4 text-lg">{{$member->first_name}} {{$member->last_name}}</li>
                    @endforeach
                </x-tmk.list>
                @isset($currentAppointment->location)
                    <h3 class=" text-xl font-medium block mt-2 w-full">Location:</h3>
                    <div class="mt-1 ml-4 p-2 text-lg">{{$currentAppointment->location}}</div>
                @endisset
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="resetDescription()">Close</x-secondary-button>

        </x-slot>
    </x-dialog-modal>
    <x-dialog-modal id="appointmentModal"
                    wire:model="showModal">
        <x-slot name="title">
            <h2>{{ is_null($newAppointment['id']) ? 'New appointment' : 'Edit appointment' }}</h2>
        </x-slot>
        <x-slot name="content">
            @if ($errors->any())
                <x-tmk.alert type="danger">
                    <x-tmk.list>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </x-tmk.list>
                </x-tmk.alert>
            @endif
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-2">
                    <x-label for="name" value="Name" class="mt-4"/>
                    <x-input type="text" id="name" wire:model.defer="newAppointment.name"
                             class="mt-1 block w-full" autofocus/>
                    <x-label for="description" value="Description" class="mt-4"/>
                    <x-tmk.form.textarea type="text" id="description"
                                         wire:model.defer="newAppointment.description"
                                         class="mt-1 block w-full"/>
                    <x-label for="superCategory" value="Category" class="mt-4"/>
                    <x-tmk.form.select id="superCategory" type="text" class="block mt-1 w-full"
                                       wire:model.defer="newAppointment.sup_category_id"
                                       wire:change="resetSubCategory()">
                        <option value="">Select a category</option>
                        @foreach($superCategories as $superCategory)
                            <option value="{{$superCategory->id}}">{{$superCategory->name}}</option>
                        @endforeach
                    </x-tmk.form.select>
                    @php
                        $newCategories = App\Models\Category::where('super_category_id', $this->newAppointment['sup_category_id'])->get();
                    @endphp
                    <div class="@if(count(\App\Models\Category::where('id',$this->newAppointment['sup_category_id'])->get()) < 1 )
                        hidden
                   @elseif(count($newCategories) < 1) hidden
                     @endif">
                        <x-label for="subCategory" value="Sub category (optional)" class="mt-4"/>
                        <x-tmk.form.select id="subCategory" type="text"
                                           wire:model.defer="newAppointment.sub_category_id"
                                           class="block mt-1 w-full">
                            <option value="-1">Select a sub category</option>
                            @foreach($newCategories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </x-tmk.form.select>
                    </div>
                    <x-label for="description" value="Add other members" class="mt-4"/>
                    <x-tmk.form.select id="members" type="text" class="block mt-1 w-full" multiple="multiple"
                                       wire:model="members">
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                        @endforeach
                    </x-tmk.form.select>
                    <x-label for="location" value="Location (optional)" class="mt-4"/>
                    <x-input type="text" id="location" wire:model.defer="newAppointment.location"
                             class="mt-1 block w-full"/>
                    <x-label for="startDateTime" value="From" class="mt-4"/>
                    <x-input id="startDateTime" type="datetime-local"
                             wire:model.defer="newAppointment.start_date_time"
                             class="mt-1 block w-full"/>
                    <x-label for="endDateTime" value="Until" class="mt-6"/>
                    <x-input id="endDateTime" type="datetime-local"
                             wire:model.defer="newAppointment.end_date_time"
                             class="mt-1 block w-full"/>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Cancel</x-secondary-button>
            @if(is_null($newAppointment['id']))
                <x-button
                    wire:click="createAppointment()"
                    wire:loading.attr="disabled"
                    class="ml-2">Save new appointment
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateAppointment({{ $newAppointment['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Update appointment
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>

