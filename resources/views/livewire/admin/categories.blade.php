<div>
    <div id="start-categories">
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
    <x-tmk.section class="m-0 p-0 mb-4 gap-2">
        <div class="grid grid-cols-12 gap-4" id="filter">
            {{-- search by category name and description--}}
            <div class="col-span-12 md:col-span-8" id="namedescriptionfilter">
                <x-label for="name" value="Search"/>
                <div
                    x-data="{ name: @entangle('search') }"
                    class="relative"
                    id="search">
                    <x-input id="search" type="text"
                             x-model.debounce.500ms="name"
                             class="block mt-1 w-full"
                             placeholder="Filter by category or description" autofocus/>
                    <div
                        x-show="name"
                        @click="name = '';"
                        class="w-5 absolute right-4 cursor-pointer top-3">
                        <x-phosphor-x-duotone/>
                    </div>
                </div>
            </div>

            <div class="flex flex-col justify-end col-span-6 md:col-span-3">
                <x-button id="newbutton" class="h-10 w-40" wire:click="setNewCategory()">new
                    category
                </x-button>

            </div>

            {{-- filter on category name and description--}}
            <div class="col-span-6 md:col-span-4" id="orderfilter-1">
                <x-label for="orderOption" value="Order by"/>
                <x-tmk.form.select id="orderOption" class="block mt-1 p-2 w-full"
                                   wire:model="orderBy">
                    <option value="name" wire:click="resort()">Name</option>
                    <option value="description" wire:click="resort()">Description</option>
                </x-tmk.form.select>
            </div>

            {{-- change order direction--}}
            <div class="col-span-6 md:col-span-4" id="orderfilter-2">
                <x-label for="orderDirection" value="Order direction"/>
                <x-tmk.form.select id="orderDirection" class="block mt-1 p-2 w-full"
                                   wire:model="orderAsc">
                    <option value="1" wire:click="setOrderDirection()">Ascending</option>
                    <option value="0" wire:click="setOrderDirection()">Descending</option>
                </x-tmk.form.select>
            </div>
            {{-- change amount of categories per page--}}
            <div class="col-span-6 md:col-span-4" id="perpagefilter">
                <x-label for="perPage" value="Categories per page"/>
                <x-tmk.form.select id="perPage"
                                   wire:model="perPage"
                                   class="block mt-1 p-2 w-full">
                    @foreach([5,10,15,20] as $invoicesPerPage)
                        <option value="{{$invoicesPerPage}}">{{$invoicesPerPage}}</option>
                    @endforeach
                </x-tmk.form.select>
            </div>
        </div>
    </x-tmk.section>

    {{--Display only on md+ screens--}}
    <x-tmk.section class="hidden md:block" id="categoriessectionmd">
        <div class="mt-2 mb-6">{{ $categories->links() }}</div>

        <table class="text-left w-full border border-gray-300">
            <colgroup>
                <col class="w-40">
                <col class="w-20">
                <col class="w-48">
                <col class="w-16">
            </colgroup>
            <thead>
            <tr class="text-left bg-gray-100 text-gray-700 [&>th]:p-2">
                <th class="">Category name</th>
                <th class="text-center">Color</th>
                <th class="">Description</th>
                <th class="">
                </th>
            </tr>
            </thead>
            <tbody>
            <div>
                {{--Display all the super categories with the name, color and description--}}
                @forelse($categories as $category)
                    <tr
                        wire:key="category_{{ $category->id }}"
                        class="border-t border-gray-300">
                        <td class="pl-4"><b>{{ $category->name }}</b></td>
                        <td>
                            <div style="display: flex; justify-content: center;">
                                <svg width="50" height="50" style="margin: auto;">
                                    <circle cx="25" cy="25" r="25" fill="#{{ $category->color_hex_code }}"/>
                                </svg>
                            </div>
                        </td>
                        <td class="pl-4">{{ $category->description }}</td>
                        <td
                            x-data="">
                            <div class="flex gap-1 justify-end [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                {{--Button to edit a category, shows the modal --}}
                                <button
                                    wire:click="setNewCategory({{ $category->id }})"
                                    class="w-10 text-gray-300 hover:text-green-600">
                                    <x-phosphor-pencil-line-duotone
                                        data-tippy-content="Edit this category"
                                        class="w-10 text-gray-300 hover:text-green-600"/>
                                </button>
                                {{--Button to delete a category, shows confirmation pop-up --}}
                                <x-phosphor-trash-duotone
                                    @click="$dispatch('swal:confirm', {
                    title: 'Delete category?',
                    cancelButtonText: 'NO!',
                    confirmButtonText: 'YES, DELETE THIS CATEGORY',
                    next: {
                        event: 'delete-category',
                        params: {
                            id: {{ $category->id }}
                                    }
                                }
                            });"
                                    data-tippy-content="Delete this category"
                                    class="w-10 text-gray-300 hover:text-red-600"/>
                            </div>
                        </td>
                    </tr>
                    {{--Display all the sub categories underneath the linked super category with the name, color and description--}}
                    @foreach($subcat as $c)
                        @php
                            $subcategoriesCount = $subcat->where('super_category_id', $category->id)->count();
                        @endphp
                        @if(($c->super_category_id == $category->id) && $subcategoriesCount > 0)
                            <tr
                                wire:key="category_{{ $c->id }}"
                                class="border-t border-gray-300">
                                <td class="pl-10">{{ $c->name }}</td>
                                <td>
                                    <div style="display: flex; justify-content: center;">
                                        <svg width="50" height="50" style="margin: auto;">
                                            <circle cx="25" cy="25" r="25" fill="#{{ $c->color_hex_code }}"/>
                                        </svg>
                                    </div>
                                </td>
                                <td class="pl-4">{{ $c->description }}</td>
                                <td
                                    x-data="">
                                    <div
                                        class="flex gap-1 justify-end [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                        <button
                                            wire:click="setNewCategory({{ $c->id }})"
                                            class="delete-button w-10 text-gray-300 hover:text-green-600">
                                            <x-phosphor-pencil-line-duotone
                                                data-tippy-content="Edit this category"
                                                class="w-10 text-gray-300 hover:text-green-600"/>
                                        </button>
                                        <x-phosphor-trash-duotone
                                            @click="$dispatch('swal:confirm', {
                                                    title: 'Delete category?',
                                                    cancelButtonText: 'NO!',
                                                    confirmButtonText: 'YES, DELETE THIS CATEGORY',
                                                    next: {
                                                        event: 'delete-category',
                                                        params: {
                                                            id: {{ $c->id }}
                                                                    }
                                                                }
                                                            });"
                                            data-tippy-content="Delete this category"
                                            class="w-10 text-gray-300 hover:text-red-600"/>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" class="border-t border-gray-300 p-4 text-center">
                            <div class="font-bold italic text-blue-300">No categories found</div>
                        </td>
                    </tr>
                @endforelse
            </div>
            </tbody>
        </table>
        <div class="mt-6 mb-2">{{ $categories->links() }}</div>
    </x-tmk.section>

    {{--Only display for small screens--}}
    <x-tmk.section class="md:hidden" id="categoriessectionsm">
        <div class="grid grid-cols-1 gap-4 max-w-screen accordion ">
            <div class="my-4">{{ $categories->links() }}</div>
            {{--Display all super categories with their name, description and color--}}
            @forelse($categories as $category)
                <div class="visible block md:invisible md:hidden accordion-item border border-regal-blue rounded-md">
                    <div data-accordion-id="{{ $category->id }}"
                         class="accordion-header flex items-center justify-between px-4 py-3  cursor-pointer">
                        <h2 class="text-lg font-bold text-gray-700">{{ $category->name }}</h2>
                        {{--<div class="rounded-md overflow-hidden m-2 h-10 ml-auto">--}}
                        <div
                            class=" overflow-hidden m-2 h-10 ml-auto [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            {{--Button to edit a category, shows a modal--}}
                            <button
                                wire:click="setNewCategory({{ $category->id }})"
                                class="w-10 text-gray-300 hover:text-green-600">
                                <x-phosphor-pencil-line-duotone
                                    data-tippy-content="Edit this category"
                                    class="w-10 text-gray-300 hover:text-green-600"/>
                            </button>
                        </div>
                        <div x-data=""
                             class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            {{--Button to delete a category, shows confirmation pop-up --}}
                            <button
                                @click="$dispatch('swal:confirm', {
                    title: 'Delete category?',
                    cancelButtonText: 'NO!',
                    confirmButtonText: 'YES, DELETE THIS CATEGORY',
                    next: {
                        event: 'delete-category',
                        params: {
                            id: {{ $category->id }}
                                    }
                                }
                            });">
                                <x-phosphor-trash-duotone
                                    data-tippy-content="Delete this category"
                                    class="w-10 text-gray-300 hover:text-red-600"/>
                            </button>
                        </div>
                        <svg class="w-6 h-6 transform ml-2" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6 6-6"></path>
                        </svg>
                    </div>
                    <div style="display: none;" class="accordion-content px-4 py-3">
                        <div class="py-1">
                            <h5 class="font-bold text-gray-700">Description</h5>
                            <p class="ml-5">{{ $category->description != null ? $category->description : "/" }}</p>
                        </div>
                        <div class="py-1">
                            <h5 class="font-bold text-gray-700">Color</h5>
                            <svg class="ml-5" width="50" height="50">
                                <circle cx="25" cy="25" r="25" fill="#{{ $category->color_hex_code }}"/>
                            </svg>
                        </div>
                        <div class="py-1">
                            @php
                                $subcategoriesCount = $subcat->where('super_category_id', $category->id)->count();
                            @endphp
                            @if($subcategoriesCount > 0)
                                <h5 class="font-bold text-gray-700">Categories</h5>
                                {{--Display all sub categories beneath the linked super category with their name--}}
                                @foreach($subcat as $c)
                                    @if(($c->super_category_id == $category->id) && $subcategoriesCount > 0)
                                        {{--Code to display each sub category--}}
                                        <div class="flex items-center justify-between">
                                            <p class="ml-5">{{ $c->name }}</p>

                                            <div class="flex justify-end">
                                                <button
                                                    wire:click="setNewCategory({{ $c->id }})"
                                                    class="w-10 text-gray-300 hover:text-green-600">
                                                    <x-phosphor-pencil-line-duotone
                                                        data-tippy-content="Edit this category"
                                                        class="w-10 text-gray-300 hover:text-green-600"/>
                                                </button>

                                                <button
                                                    @click="$dispatch('swal:confirm', {
                    title: 'Delete category?',
                    cancelButtonText: 'NO!',
                    confirmButtonText: 'YES, DELETE THIS CATEGORY',
                    next: {
                        event: 'delete-category',
                        params: {
                            id: {{ $c->id }}
                                    }
                                }
                            });">
                                                    <x-phosphor-trash-duotone
                                                        data-tippy-content="Delete this category"
                                                        class="w-10 text-gray-300 hover:text-red-600"/>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <h2 class="visible block md:invisible md:hidden border-t border-regal-blue p-4 text-center">
                    <div class="font-bold italic text-blue-300">No categories found</div>
                </h2>
            @endforelse
            <div class="my-4">{{ $categories->links() }}</div>
        </div>
    </x-tmk.section>

    {{--Modal to add or edit a category--}}
    <x-dialog-modal id="categorydModal"
                    wire:model="showModal">
        <x-slot name="title">
            <h2>{{ is_null($newCategory['id']) ? 'Add new category' : 'Edit category' }}</h2>
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
            {{--fill in or change attributes of a category--}}
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-2">
                    <x-label for="name" value="Name" class="mt-4"/>
                    <x-input id="name" type="text" step="0.01"
                             wire:model.defer="newCategory.name"
                             class="mt-1 block w-full" autofocus/>
                    <x-label for="description" value="Description (optional)" class="mt-4"/>
                    <x-input id="description" type="text"
                             wire:model.defer="newCategory.description"
                             class="mt-1 block w-full"/>
                    <x-label for="color" value="Color" class="mt-4"/>
                    <x-input id="color" type="color" name="color" :value="$newCategory['color_hex_code']"
                             wire:model.defer="newCategory.color_hex_code" class="mt-1 block w-24 h-24"/>
                    <x-label for="super_category_id" value="Belongs to (optional)" class="mt-4"/>
                    <x-tmk.form.select wire:model.defer="newCategory.super_category_id" id="super_category_id_id"
                                       class="block mt-1 w-full h-10">
                        <option value="">Select a category</option>
                        {{--Show all subcategories as an option in the dropdown menu--}}
                        @foreach($supercategories as $supercategory)
                            <option value="{{ $supercategory->id }}">{{ $supercategory->name }}</option>
                        @endforeach
                    </x-tmk.form.select>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Cancel</x-secondary-button>
            @if(is_null($newCategory['id']))
                <x-button
                    wire:click="createCategory()"
                    wire:loading.attr="disabled"
                    class="ml-2">Save new category
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateCategory({{ $newCategory['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Update category
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>


