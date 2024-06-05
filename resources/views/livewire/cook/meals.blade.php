<div>
    <div id="start-meals">
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

        <x-tmk.section>
            <div class="grid grid-cols-1 lg:grid-cols-2 lg:space-x-4">

                <div>
                    <x-label for="name" value="Search"/>
                    <div
                        x-data="{ name: @entangle('name') }"
                        class="relative"
                        id="searchMeal">
                        <x-input id="name" type="text"
                                 x-model.debounce.500ms="name"
                                 class="block mt-1 w-full md:w-3/4 lg:w-full"
                                 placeholder="Filter by meal name"/>
                        <div
                            x-show="name"
                            @click="name = '';"
                            class="w-5 absolute right-4 cursor-pointer top-3">
                            <x-phosphor-x-duotone/>
                        </div>
                    </div>
                </div>

                <div>
                    <x-label class="mt-4 lg:mt-0" for="newMeal" value="Create new meal"/>

                    <div class="flex items-center" id="createMeal">
                        <x-input id="newMeal" type="text" placeholder="New meal name"
                                 @keydown.enter="$el.setAttribute('disabled', true); $el.value = '';"
                                 @keydown.tab="$el.setAttribute('disabled', true); $el.value = '';"
                                 @keydown.esc="$el.setAttribute('disabled', true); $el.value = '';"
                                 wire:model.defer="newMeal"
                                 wire:keydown.enter="createMeal()"
                                 wire:keydown.tab="createMeal()"
                                 wire:keydown.escape="resetNewMeal()"
                                 class=" block mt-1">
                        </x-input>
                        <x-button class="text-white py-2 px-4 rounded ml-3 h-10"
                                  wire:click="createMeal">
                            Create Meal
                        </x-button>
                    </div>
                </div>
            </div>
            <x-input-error for="newMeal" class="m-4 w-full"/>
        </x-tmk.section>

        <div class="my-4">{{ $meals->links() }}</div>

        <x-tmk.section>

            <table class="text-center w-full border border-gray-300" id="meals">
                <colgroup>
                    <col class="w-full">
                    <col class="w-36">
                </colgroup>
                <thead class="bg-gray-100 h-12">
                <tr class="text-gray-700 [&>th]:p-2 cursor-pointer">
                    <th wire:click="resort('name')" class="text-left">
                        <span data-tippy-content="Order by meal name">Meal</span>
                        <x-heroicon-s-chevron-up
                            class="w-5 text-slate-400
                 {{$orderAsc ?: 'rotate-180'}}
                            {{$orderBy === 'name' ? 'inline-block' : 'hidden'}}
                                "/>
                    </th>
                    <th class="text-center">
                        <span data-tippy-contrent="Edit or delete a meal"></span>

                    </th>
                </tr>
                </thead>
                <tbody>
                {{-- No users found --}}

                @forelse($meals as $meal)
                    <tr
                        wire:key="meal_{{ $meal->id }}"
                        class="border-t border-gray-300 [&>td]:p-2">
                        @if($editMeal['id'] !== $meal->id)

                            <td class="text-left cursor-pointer">{{ $meal->name }}</td>
                        @else
                            <td>
                                <div class="flex flex-col text-left">
                                    <x-input id="edit_{{ $meal->id }}" type="text"
                                             x-data=""
                                             x-init="$el.focus()"
                                             wire:model.defer="editMeal.name"
                                             class="w-48"/>

                                    <x-input-error for="editMeal.name" class="mt-2"/>
                                </div>
                            </td>
                        @endif
                        <td
                            x-data="">
                            @if($editMeal['id'] !== $meal->id)
                                <div
                                    class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                    <div class="flex flex-row">
                                        <x-phosphor-pencil-line-duotone
                                            wire:click="editExistingMeal({{ $meal->id }})"
                                            data-tippy-content="Edit this meal"
                                            class="w-10 text-gray-300 hover:text-green-600"/>
                                        <x-phosphor-trash-duotone
                                            @click="$dispatch('swal:confirm', {
                    title: 'Delete meal?',
                    cancelButtonText: 'NO!',
                    confirmButtonText: 'YES DELETE THIS MEAL',
                    next: {
                        event: 'delete-meal',
                        params: {
                            id: {{ $meal->id }}
                                        }
                                    }
                                });"
                                            data-tippy-content="Delete this meal"
                                            class="w-10 text-gray-300 hover:text-red-600"/>
                                    </div>
                                    @else
                                        <div class="flex flex-row">
                                            <x-phosphor-check-duotone
                                                @click="$el.setAttribute('disabled', true);"
                                                wire:click="updateMeal({{ $meal->id }})"
                                                data-tippy-content="Save changes"
                                                class="w-10 text-gray-300 hover:text-green-600 cursor-pointer"/>
                                            <x-phosphor-x-duotone
                                                wire:click="resetEditMeal()"
                                                data-tippy-content="Cancel changes"
                                                class="w-10 text-gray-300 hover:text-red-600 cursor-pointer"/>
                                        </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border-t border-gray-300 p-4 text-center">
                            <div class="font-bold italic text-blue-300">No meals found</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="my-4">{{ $meals->links() }}</div>

        </x-tmk.section>
    </div>

</div>
