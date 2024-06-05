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
    <div class="fixed top-8 left-1/2 md:ml-32 -translate-x-1/2 z-50 animate-pulse"
         wire:loading>
        <x-tmk.preloader class="bg-blue-400/60 text-white border border-blue-600 shadow-2xl p-4">
            {{ $loading }}
        </x-tmk.preloader>
    </div>
    <x-tmk.section>
        {{-- filter section: on task name, start date, end date, category and sec category--}}
        <div class="grid grid-cols-10 gap-4">
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
                    <option value="0">All sub category</option>
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
                <x-label for="perPage" value="Tasks per page"/>
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

            <div class="col-span-5 md:col-span-5 lg:col-span-3" id="orderfilter-2">
                <x-label for="orderDirection" value="Order direction"/>
                <x-tmk.form.select id="orderDirection" class="block mt-1 p-2 w-full"
                                   wire:model="orderAsc">
                    <option value="1">Ascending</option>
                    <option value="0">Descending</option>
                </x-tmk.form.select>
            </div>
            <div class="col-span-5 md:col-span-5 lg:col-span-2 mt-7">
                <x-button wire:click="setNewTask()" class="h-10" id="newbutton">
                    new task
                </x-button>
            </div>
        </div>
    </x-tmk.section>

    <x-tmk.section class="mb-4 hidden xl:block" id="eventsection">
        <div class="mb-6 mt-2">{{ $tasks->links() }}</div>
        <table class="text-left w-full border border-gray-300">
            <thead>
            <tr class="bg-gray-100 text-gray-700 cursor-pointer [&>th]:p-2">
                <th>
                    Name
                    {{--                    <x-heroicon-s-chevron-up class="w-5 p-5 text-slate-400 inline-block"/>--}}
                </th>
                <th>Date</th>
                <th>Start time</th>
                <th>End time</th>
                <th>Category</th>
                <th>Sub category</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($tasks as $task)
                <tr class="border-t border-gray-300 [&>td]:p-2">
                    <td class=" cursor-pointer font-bold underline"
                        wire:click="setDescription({{ $task->id }})">
                        <span data-tippy-content="Click for more info">
                            {{$task->name}}
                        </span></td>
                    <td>{{date('d/m/Y', strtotime($task->start_date_time))}}</td>
                    <td>{{date('H:i', strtotime($task->start_date_time))}}</td>
                    <td>{{date('H:i', strtotime($task->end_date_time))}}</td>
                    <td>
                        @if($task->category->super_category_id != null)
                            <div class="flex gap-1 items-center justify-between">
                                {{ $task->category->super_category ? $task->category->super_category->name : '-' }}
                                <svg width="50" height="50" style="margin: 0;">
                                    <circle cx="25" cy="25" r="15"
                                            fill="#{{ $task->category->super_category->color_hex_code }}"/>
                                </svg>
                            </div>
                        @elseif($task->category->id != null)
                            <div class="flex gap-1 items-center justify-between">
                                {{ $task->category->name }}
                                <svg width="50" height="50" style="margin: 0;">
                                    <circle cx="25" cy="25" r="15"
                                            fill="#{{ $task->category->color_hex_code }}"/>
                                </svg>
                            </div>
                        @endif
                        @if($task->category_id == null)
                            <span>N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($task->category->super_category_id != null)
                            <div class="flex gap-1 items-center justify-between">
                                {{ $task->category->name }}
                                <svg width="50" height="50" style="margin: 0;">
                                    <circle cx="25" cy="25" r="15"
                                            fill="#{{ $task->category->color_hex_code }}"/>
                                </svg>
                            </div>
                        @else
                            <span>N/A</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition justify-end">
                            <x-phosphor-magic-wand-duotone
                                x-data=""
                                data-tippy-content="Edit all recurring tasks"
                                wire:click="setAll({{ $task->id }})"
                                class="w-10 text-gray-300 hover:text-green-600"/>
                            @if(count($task->event_members) > 1)
                                <x-phosphor-user-circle-minus-duotone
                                    x-data=""
                                    data-tippy-content="Remove yourself from this task"
                                    @click="confirm('Are you sure you want to remove yourself from this task?') ? $wire.removeSelfTask({{ $task->id }}) : ''"
                                    class="w-10 text-gray-300 hover:text-red-600"/>
                            @endif
                            <x-phosphor-pencil-line-duotone
                                x-data=""
                                data-tippy-content="Edit this task"
                                wire:click="setNewTask({{ $task->id }})"
                                class="w-10 text-gray-300 hover:text-green-600"/>
                            <x-phosphor-trash-duotone
                                x-data=""
                                data-tippy-content="Delete this task"
                                @click="$dispatch('swal:confirm', {
                                html: 'Delete &ldquo;{{ $task->name }}&rdquo;?',
                                cancelButtonText: 'NO!',
                                confirmButtonText: 'YES DELETE THIS TASK',
                                next: {
                                    event: 'delete-task',
                                    params: {
                                        id: {{ $task->id }}
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
                        <div class="font-bold italic text-blue-300">No tasks found</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="my-4">{{ $tasks->links() }}</div>
    </x-tmk.section>

    {{--        For small and medium screen--}}
    <div class="xl:hidden" id="eventsectionsm">
        <div class="my-4">{{ $tasks->links() }}</div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 xl:hidden max-w-screen">
            @forelse($tasks as $task)
                <div class="bg-white p-4 rounded-lg shadow max-w-full">
                    <div class="flex space-x-2 text-md justify-between">
                        <div class="text-lg">{{ $task->name }}</div>
                    </div>
                    <div class="flex space-x-2 text-md justify-between mt-4 mb-3">
                        <div class="text-md">Location:
                            <span class="ml-4"
                                  style="font-weight: 600;">{{ $task->location }}</span></div>
                    </div>

                    <div>Date:
                        <span class="ml-12"
                              style="font-weight: 600;"> {{ date('d/m/Y', strtotime($task->start_date_time)) }}</span>
                    </div>
                    <div>Start time:
                        <span class="ml-2"
                              style="font-weight: 600;">{{ date('H:i', strtotime($task->start_date_time)) }}</span>
                    </div>
                    <div>End time: <span class="ml-4"
                                         style="font-weight: 600;">{{ date('H:i', strtotime($task->end_date_time)) }}</span>
                    </div>
                    <span class="flex items-center gap-1">Tags:
                            @if($task->category->super_category_id != null)
                            <span class="flex gap-1 items-center mr-3 ml-3">
                                                        {{ $task->category->super_category ? $task->category->super_category->name : '-' }}
                                                        <svg width="50" height="50" style="margin: 0;">
                                                            <circle cx="25" cy="25" r="15"
                                                                    fill="#{{ $task->category->super_category->color_hex_code }}"/>
                                                        </svg>
                                                    </span>
                        @elseif($task->category->id != null)
                            <span class="flex gap-1 items-center mr-3 ml-3">
                                                        {{ $task->category->name }}
                                                        <svg width="50" height="50" style="margin: 0;">
                                                            <circle cx="25" cy="25" r="15"
                                                                    fill="#{{ $task->category->color_hex_code }}"/>
                                                        </svg>
                                                    </span>
                        @endif
                        @if($task->category->super_category_id != null)
                            <span class="flex gap-1 items-center mr-3 ml-3">
                                                        {{ $task->category->name }}
                                                        <svg width="50" height="50" style="margin: 0;">
                                                            <circle cx="25" cy="25" r="15"
                                                                    fill="#{{ $task->category->color_hex_code }}"/>
                                                        </svg>
                                                    </span>
                        @endif
                        @if($task->category_id == null)
                            <span class="italic">None</span>
                        @endif
                            </span>
                    <br>
                    <div>

                        <div class="flex [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition justify-end">
                            <x-phosphor-magic-wand-duotone
                                x-data=""
                                data-tippy-content="Edit all recurring tasks"
                                wire:click="setAll({{ $task->id }})"
                                class="w-10 text-gray-300 hover:text-green-600"/>
                            @if(count($task->event_members) > 1)
                                <x-phosphor-user-circle-minus-duotone
                                    x-data=""
                                    data-tippy-content="Remove yourself from this task"
                                    @click="confirm('Are you sure you want to remove yourself from this task?') ? $wire.removeSelfTask({{ $task->id }}) : ''"
                                    class="w-10 text-gray-300 hover:text-red-600"/>
                            @endif
                            <x-phosphor-pencil-line-duotone
                                x-data=""
                                data-tippy-content="Edit this task"
                                wire:click="setNewTask({{ $task->id }})"
                                class="w-10 text-gray-300 hover:text-green-600"/>
                            <x-phosphor-trash-duotone
                                x-data=""
                                data-tippy-content="Delete this task"
                                @click="$dispatch('swal:confirm', {
                                html: 'Delete &ldquo;{{ $task->name }}&rdquo;?',
                                cancelButtonText: 'NO!',
                                confirmButtonText: 'YES DELETE THIS TASK',
                                next: {
                                    event: 'delete-task',
                                    params: {
                                        id: {{ $task->id }}
                                    }
                                }
                            })"
                                class="w-10 text-gray-300 hover:text-red-600"/>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white p-4 rounded-lg shadow max-w-full">
                    <div class="p-4 text-center col-span-full">
                        <p class="font-bold italic text-blue-300">No tasks found</p>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="my-4">{{ $tasks->links() }}</div>
    </div>
    <x-dialog-modal id="descriptionModal"
                    wire:model="showDescriptionModal">

        <x-slot name="title">
            @if($currentTask)
                <h2 class="text-2xl">{{$currentTask->name}}</h2>
            @endif
        </x-slot>
        <x-slot name="content">

            @if($currentTask)
                <h3 class="text-xl font-medium block mt-2 w-full">Description</h3>
                <div class="mt-1 ml-4 p-2 text-lg">{{$currentTask->description}}</div>
                <h3 class="text-xl block font-medium mt-2 w-full">Task Members</h3>
                <x-tmk.list class="ml-4" title="Task Members">
                    @foreach ($taskMembers as $member)
                        <li class="mt-1 ml-4 text-lg">{{$member->first_name}} {{$member->last_name}}</li>
                    @endforeach
                </x-tmk.list>
                @isset($currentTask->location)
                    <h3 class=" text-xl font-medium block mt-2 w-full">Location</h3>
                    <div class="mt-1 ml-4 p-2 text-lg">{{$currentTask->location}}</div>
                @endisset
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="resetDescription()">Close</x-secondary-button>

        </x-slot>
    </x-dialog-modal>
    <x-dialog-modal id="taskModal"
                    wire:model="showModal">
        <x-slot name="title">
            @if(is_null($newTask['id']))
                <h2>New task</h2>
            @elseif($newTask['event_creation_request_id'])
                <h2>Edit all recurring tasks</h2>
            @else
                <h2>Edit task</h2>
            @endif
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
                    <x-input type="text" id="name" wire:model.defer="newTask.name"
                             class="mt-1 block w-full"/>
                    <x-label for="description" value="Description" class="mt-4"/>
                    <x-tmk.form.textarea type="text" id="description" wire:model.defer="newTask.description"
                                         class="mt-1 block w-full"/>
                    <x-label for="superCategory" value="Category" class="mt-4"/>
                    <x-tmk.form.select id="superCategory" type="text" class="block mt-1 w-full"
                                       wire:model.defer="newTask.sup_category_id" wire:change="resetSubCategory()">
                        <option value="">Select a category</option>
                        @foreach($superCategories as $superCategory)
                            <option value="{{$superCategory->id}}">{{$superCategory->name}}</option>
                        @endforeach
                    </x-tmk.form.select>
                    @php
                        $newCategories = App\Models\Category::where('super_category_id', $this->newTask['sup_category_id'])->get();
                    @endphp
                    <div class="@if(count(\App\Models\Category::where('id',$this->newTask['sup_category_id'])->get()) < 1 )
                        hidden
                    @elseif(count($newCategories) < 1) hidden
                    @endif">
                        <x-label for="subCategory" value="Sub category (optional)" class="mt-4"/>
                        <x-tmk.form.select id="subCategory" type="text" wire:model.defer="newTask.sub_category_id"
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
                    @if(is_null($newTask['event_creation_request_id']))
                        <x-label for="location" value="Location (optional)" class="mt-4"/>
                        <x-input type="text" id="location" wire:model.defer="newTask.location"
                                 class="mt-1 block w-full"/>
                        <x-label for="startDateTime" value="From" class="mt-4"/>
                        <x-input id="startDateTime" type="datetime-local"
                                 wire:model.defer="newTask.start_date_time"
                                 class="mt-1 block w-full"/>
                        <x-label for="endDateTime" value="Until" class="mt-6"/>
                        <x-input id="endDateTime" type="datetime-local"
                                 wire:model.defer="newTask.end_date_time"
                                 class="mt-1 block w-full"/>
                    @endif
                    @if(is_null($newTask['id']))
                        <x-label for="recurring" value="Recurring (optional)" class="mt-4"/>
                        <x-checkbox type="text" id="recurring" class="mt-1 block"
                                    wire:model="showRecurring"></x-checkbox>
                        @if($showRecurring)
                            <x-label for="numberOccurrences" value="Number of occurrences" class="mt-4"/>
                            <x-input type="number" id="numberOccurrences" class="block mt-1 w-full" min="2"
                                     wire:model.defer="newEventRequest.number_of_occurrences">
                            </x-input>
                            <x-label for="occurrence" value="Occurrence" class="mt-4"/>
                            <x-tmk.form.select id="occurrence" type="text" class="block mt-1 w-full"
                                               wire:model.defer="newEventRequest.recurrence_id">
                                <option>Select the recurrence type</option>
                                @foreach($recurring as $recurrence)
                                    <option value="{{$recurrence->id}}">{{$recurrence->type}}</option>
                                @endforeach
                            </x-tmk.form.select>
                        @endif
                    @endif
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Cancel</x-secondary-button>
            @if(is_null($newTask['id']))
                <x-button
                    wire:click="createTask()"
                    wire:loading.attr="disabled"
                    class="ml-2">Save new task
                </x-button>
            @elseif($newTask['event_creation_request_id'])
                <x-button
                    x-data=""
                    @click="$dispatch('swal:confirm', {
                    title: 'Edit all recurring tasks?',
                    icon: 'warning',
                    background: 'error',
                    cancelButtonText: 'NO!',
                    confirmButtonText: 'YES EDIT ALL TASKS',
                    html: '<b>ATTENTION</b>: you are going to edit  <b> ALL </b> recurring tasks',
                    color: 'red',
                    next: {
                        event: 'update-all-tasks',
                        params: {
                        id: {{ $newTask['event_creation_request_id'] }}
                        }
                    }
                    });"
                    class="ml-2"> Update all recurring tasks
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateTask({{ $newTask['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Update task
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
