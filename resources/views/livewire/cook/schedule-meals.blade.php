<div>
    {{--    bootstrap user support tour    --}}
    <div id="start-meal">
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
        {{-- show preloader while fetching data in the background --}}
        <div class="fixed top-8 left-1/2 md:ml-32 -translate-x-1/2 z-50 animate-pulse"
             wire:loading>
            <x-tmk.preloader class="bg-blue-400/60 text-white border border-blue-600 shadow-2xl p-4">
                {{ $loading }}
            </x-tmk.preloader>
        </div>
        {{-- filtering on name or date, type, how many items per page and the direction to sort on the date--}}
        <div class="grid grid-cols-10 gap-4" id="filter">
            <div class="col-span-10 md:col-span-5 lg:col-span-3" id="namefilter">
                <x-label for="name" value="Search"/>
                <div
                    x-data="{ name: @entangle('name') }"
                    class="relative">
                    <x-input id="name" type="text"
                             x-model.debounce.500ms="name"
                             class="block mt-1 w-full "
                             placeholder="Filter by meal or date" autofocus/>
                    <div
                        x-show="name"
                        @click="name = '';"
                        class="w-5 absolute right-4 top-3 cursor-pointer">
                        <x-phosphor-x-duotone/>
                    </div>
                </div>
            </div>
            <div class="col-span-10 md:col-span-5 lg:col-span-3" id="typefilter">
                <x-label for="mealType" value="Meal type"/>
                <x-tmk.form.select id="mealType"
                                   wire:model="mealType"
                                   class="block mt-1 p-2 w-full">
                    <option value="%">All meal types</option>
                    @foreach($allTypes as $t)
                        <option value="{{ $t->id }}">
                            {{ $t->name }}
                        </option>
                    @endforeach
                </x-tmk.form.select>
            </div>
            <div class="col-span-5 md:col-span-5 lg:col-span-3" id="perpagefilter">
                <div class="hidden md:inline">
                    <x-label for="perPage" value="Meal plans per page"/>
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
            <div class="col-span-5 md:col-span-5 lg:col-span-3" id="bydate">
                <x-label for="orderDirection" value="Order by date"/>
                <x-tmk.form.select id="orderDirection" class="block mt-1 p-2 w-full"
                                   wire:model="orderDesc">
                    <option value="1">Descending</option>
                    <option value="0">Ascending</option>
                </x-tmk.form.select>
            </div>
            <div class="col-span-5 md:col-span-5 lg:col-span-4 lg:mt-7 mt-4">
                <x-button wire:click="setNewMealSchedule()" class="h-10" id="newbutton">
                    Schedule a meal
                </x-button>
            </div>
        </div>
    </x-tmk.section>
    <x-tmk.section class="mt-4 mb-4 hidden lg:block" id="eventsection">
        {{--        pagination--}}
        <div class="mb-6 mt-2">{{ $mealPlans->links() }}</div>
        <table class="text-left w-full border border-gray-300">
            <thead class="h-12">
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 ">
                <th>
                    <span class="ml-1">Meal</span>

                </th>
                <th>
                    <span class="ml-1">Date

                    </span>

                </th>
                <th>
                    <p class="ml-1">Start time
                    </p>
                </th>
                <th>
                    <span class="ml-1">End time
                    </span>
                </th>
                <th>
                    <span class="ml-1">Meal type
                    </span>
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($mealPlans as $mealPlan)
                <tr wire:key="mealPlan_{{ $mealPlan->id }}"
                    class="border-t border-gray-300 [&>td]:p-2">
                    <td>{{$mealPlan->meal->name}}</td>
                    <td>{{date('d/m/Y', strtotime($mealPlan->start_date_time))}}</td>
                    <td>{{date('H:i', strtotime($mealPlan->start_date_time))}}</td>
                    <td>{{date('H:i', strtotime($mealPlan->end_date_time))}}</td>
                    <td>{{$mealPlan->meal_type->name}}</td>
                    <td>
                        <div class="flex [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition justify-end">
                            <x-phosphor-pencil-line-duotone
                                x-data=""
                                data-tippy-content="Edit this meal plan"
                                wire:click="setNewMealSchedule({{ $mealPlan->id }})"
                                class="w-10 text-gray-300 hover:text-green-600"/>
                            <x-phosphor-trash-duotone
                                x-data=""
                                @click="$dispatch('swal:confirm', {
                    title: 'Delete meal plan?',
                    cancelButtonText: 'NO!',
                    confirmButtonText: 'YES DELETE THIS MEAL PLAN',
                    next: {
                        event: 'delete-meal-plan',
                        params: {
                            id: {{ $mealPlan->id }}
                                    }
                                }
                            });"
                                data-tippy-content="Delete this meal plan"
                                class="w-10 text-gray-300 hover:text-red-600"/>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border-t border-gray-300 p-4 text-center">
                        <div class="font-bold italic text-blue-300">No meal plans found</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{--      pagination--}}
        <div class="mt-6 mb-2">{{ $mealPlans->links() }}</div>
    </x-tmk.section>

    {{--   small en medium screen--}}
    <div class="lg:hidden" id="eventsectionsm">
        <div class="my-4">{{ $mealPlans->links() }}</div>
        <div class="grid md:grid-cols-2 sm:grid-cols-1 gap-4  max-w-screen">

            @forelse($mealPlans as $mealPlan)
                <div class="bg-white p-4 rounded-lg shadow max-w-full">
                    <div class="flex space-x-2 text-md justify-between">
                        <div class="text-lg">{{ $mealPlan->meal->name }}</div>
                    </div>
                    <br>
                    <div>Date:
                        <span class="ml-12"
                              style="font-weight: 600;"> {{ date('d/m/Y', strtotime($mealPlan->start_date_time)) }}</span>
                    </div>
                    <div>Start time:
                        <span class="ml-2"
                              style="font-weight: 600;">{{ date('H:i', strtotime($mealPlan->start_date_time)) }}</span>
                    </div>
                    <div>End time: <span class="ml-4"
                                         style="font-weight: 600;">{{ date('H:i', strtotime($mealPlan->end_date_time)) }}</span>
                    </div>
                    <div>Meal type: <span class="ml-3"
                                          style="font-weight: 600;">{{ $mealPlan->meal_type->name }}</span>
                    </div>


                    <div class="flex gap-1 [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition" style="float: right;">
                        <x-phosphor-pencil-line-duotone
                            x-data=""
                            data-tippy-content="Edit this meal plan"
                            wire:click="setNewMealSchedule({{ $mealPlan->id }})"
                            class="w-10 text-gray-300 hover:text-green-600"/>
                        <x-phosphor-trash-duotone
                            x-data=""
                            @click="$dispatch('swal:confirm', {
                    title: 'Delete meal plan?',
                    cancelButtonText: 'NO!',
                    confirmButtonText: 'YES DELETE THIS MEAL PLAN',
                    next: {
                        event: 'delete-meal-plan',
                        params: {
                            id: {{ $mealPlan->id }}
                                    }
                                }
                            });"
                            data-tippy-content="Delete this meal plan"
                            class="w-10 text-gray-300 hover:text-red-600"/>
                    </div>
                </div>
            @empty
                <div class="bg-white p-4 rounded-lg shadow max-w-full">
                    <div class="p-4 text-center col-span-full">
                        <p class="font-bold italic text-blue-300">No meal plans found</p>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="my-4">{{ $mealPlans->links() }}</div>
    </div>

    {{--    Modal for adding and editing--}}
    <x-dialog-modal id="mealModal"
                    wire:model="showModal">
        <x-slot name="title">
            <h2>{{ is_null($newMealSchedule['id']) ? 'New meal plan' : 'Edit meal plan' }}</h2>
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
            <div class="md:flex md:flex-row">
                <div class="md:flex-col">
                    <div class="w-full md:ml-5 md:w-72">
                        <x-label for="filter" value="Search for a meal"/>
                        <div
                            x-data="{ name: @entangle('filter') }"
                            class="relative">
                            <x-input id="filter" type="text"
                                     wire:model.debounce.500ms="filter"
                                     class="block mt-1 w-full"
                                     placeholder="Filter by meal name" autofocus/>
                            <div
                                class="w-5 absolute right-4 top-3 cursor-pointer">
                                <x-phosphor-x-duotone
                                    x-show="name"
                                    @click="name = '';"/>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:ml-5 md:w-72">
                        <x-label for="meal_id" value="Meal" class="mt-4"/>
                        <x-tmk.form.select wire:model.defer="newMealSchedule.meal_id" id="meal_id"
                                           class="block mt-1 w-full p-2">
                            <option value="">Select a meal</option>
                            @foreach($filterMeals as $meal)
                                <option value="{{ $meal->id }}">{{ $meal->name }}</option>
                            @endforeach
                        </x-tmk.form.select>
                    </div>
                    <div class="w-full md:ml-5 md:w-72">
                        <x-label for="meal_type_id" value="Meal type" class="mt-5"/>
                        <x-tmk.form.select wire:model.defer="newMealSchedule.meal_type_id" id="meal_type_id"
                                           class="block mt-1 w-full p-2">
                            <option value="">Select a meal type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </x-tmk.form.select>
                    </div>
                </div>
                <div class="md:flex md:flex-col">
                    <div class="md:w-1/2 md:ml-5 md:w-72 mt-4 md:mt-0">
                        <x-label for="startDateTime" value="From" class=""/>
                        <x-input id="startDateTime" type="datetime-local"
                                 wire:model.defer="newMealSchedule.start_date_time"
                                 class="mt-1 block w-full"/>
                    </div>
                    <div class="md:w-1/2 md:ml-5 md:w-72">
                        <x-label for="endDateTime" value="Until" class="mt-4"/>
                        <x-input id="endDateTime" type="datetime-local"
                                 wire:model.defer="newMealSchedule.end_date_time"
                                 class="mt-1 block w-full"/>
                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">

            <div class=" md:gap-2 mt-6 md:ml-6">
                <x-secondary-button @click="show = false">Cancel</x-secondary-button>
                @if(is_null($newMealSchedule['id']))

                    <x-button
                        wire:click="createMealPlan()"
                        wire:loading.attr="disabled"
                        class="md:ml-2 w-1/2 md:w-52 w-48">Confirm meal plan
                    </x-button>
                @else
                    <x-button
                        wire:click="updateMealPlan({{$newMealSchedule['id']}})"
                        wire:loading.attr="disabled"
                        class="md:ml-2 w-1/2 md:w-52 w-48">Update meal plan
                    </x-button>
                @endif
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
