<div>
    {{-- Button to start the tour of the page --}}
    <div id="start-file">
        {{-- On big screens --}}
        <div class="absolute lg:top-2 right-2 top-20 hidden md:block">
            <x-button class="h-10">
                Start the tour
            </x-button>
        </div>
        {{-- On small screens --}}
        <div class="absolute right-4 mt-1 top-20 md:hidden ">
            <x-heroicon-m-question-mark-circle class="w-10 text-regal-blue hover:text-regal-blue-dark cursor-pointer"/>
        </div>
    </div>

    {{-- Filter section for the files page --}}
    <x-tmk.section class="mb-4 flex flex-col gap-2">
        {{-- Show preloader while fetching data in the background --}}
        <div class="fixed top-8 left-1/2 md:ml-32 -translate-x-1/2 z-50 animate-pulse"
             wire:loading>
            <x-tmk.preloader class="bg-blue-400/60 text-white border border-blue-600 shadow-2xl p-4">
                {{ $loading }}
            </x-tmk.preloader>
        </div>
        {{-- The filters start here --}}
        <div class="grid grid-cols-10 gap-4" id="filterSection">
            {{-- Search bar for files by entering the name of the file --}}
            <div class="col-span-10 lg:col-span-4" id="nameFilter">
                <x-label for="name" value="Search"/>
                <div
                    x-data="{ name: @entangle('name') }"
                    class="relative">
                    <x-input id="name" type="text"
                             x-model.debounce.500ms="name"
                             class="block mt-1 w-full"
                             placeholder="Filter by file name" autofocus/>
                    <div
                        x-show="name"
                        @click="name = '';"
                        class="w-5 absolute right-4 top-3 cursor-pointer">
                        <x-phosphor-x-duotone/>
                    </div>
                </div>
            </div>

            {{-- Dropdown to filter based on category --}}
            <div class="col-span-5 lg:col-span-3" id="categoryFilter">
                <x-label for="superCategory" value="Category"/>
                <x-tmk.form.select id="superCategory" class="block mt-1 p-2 w-full"
                                   wire:model="superCategory"
                                   wire:change="resetSubCategory()">
                    <option value="">All categories</option>
                    @foreach($allCategories as $c)
                        @if($c->super_category_id == null)
                            <option value="{{ $c->id }}">
                                {{ $c->name }}
                            </option>
                        @endif
                    @endforeach
                </x-tmk.form.select>
            </div>

            {{-- Dropdown to filter on subcategory --}}
            <div class="col-span-5 lg:col-span-3" id="subCategoryFilter">
                <x-label for="subCategory" value="Sub category"/>
                <x-tmk.form.select id="subCategory" class="block mt-1 p-2 w-full"
                                   wire:model="subCategory">
                    <option value="">All sub categories</option>
                    @foreach($allCategories as $c)
                        @if($c->super_category_id != null)
                            <option value="{{ $c->id }}">
                                {{ $c->name }}
                            </option>
                        @endif
                    @endforeach
                </x-tmk.form.select>
            </div>

            {{-- Toggle switch to filter files based on their archive status --}}
            <div class="col-span-5 lg:col-span-2" id="archiving">
                <x-label for="showArchived">
                    @if ($isArchived)
                        Archived files
                    @else
                        Unarchived files
                    @endif
                </x-label>
                <div class="mt-3">
                    <x-tmk.form.archivesToggle id="showArchived" wire:model="isArchived"
                                               class="mt-2"></x-tmk.form.archivesToggle>
                </div>
            </div>

            {{-- Dropdown to choose the criteria to order the files: name and upload date --}}
            <div class="col-span-5 lg:col-span-2" id="orderBy">
                <x-label for="orderOption" value="Order by"/>
                <x-tmk.form.select id="orderOption" class="block mt-1 p-2 w-full"
                                   wire:model="orderBy">
                    <option value="name" wire:click="resort('name')">File name</option>
                    <option value="upload_date" wire:click="resort('upload_date')">Upload date</option>
                </x-tmk.form.select>
            </div>

            {{-- Dropdown to choose order direction: ascending or descending --}}
            <div class="col-span-5 lg:col-span-2" id="orderDirection">
                <x-label for="orderDirection" value="Order direction"/>
                <x-tmk.form.select id="orderDirection" class="block mt-1 p-2 w-full"
                                   wire:model="orderAsc">
                    <option value="1" wire:click="setOrderDirection(1)">Ascending</option>
                    <option value="0" wire:click="setOrderDirection(0)">Descending</option>
                </x-tmk.form.select>
            </div>

            {{-- Dropdown to choose number of files per page (pagination) --}}
            <div class="col-span-5 lg:col-span-2" id="perPageFilter">
                <x-label for="perPage" value="Files per page"/>
                <x-tmk.form.select id="perPage"
                                   wire:model="perPage"
                                   class="block mt-1 p-2 w-full">
                    @foreach([5,10,15,20] as $filesPerPage)
                        <option value="{{$filesPerPage}}">{{$filesPerPage}}</option>
                    @endforeach
                </x-tmk.form.select>
            </div>

            {{-- Button to upload a new file. It will open a pop-up where the user can fill in the details of the new file and upload it --}}
            <div class="flex flex-col justify-end col-span-5 md:col-span-3 lg:col-span-2" id="uploadNewFile">
                <x-button
                    class="flex justify-center mb-2 h-10" wire:click="setNewFile()">Upload new file
                </x-button>
            </div>
        </div>
        {{-- End of filter section --}}
    </x-tmk.section>


    {{-- Start of listed files section. The first div is for wrapping both big and small screen views in order to be used later in the tour --}}
    <div id="filesSection">
        {{-- Section for files on big screens: table view --}}
        <x-tmk.section class="hidden xl:block">
            {{-- Pagination links --}}
            <div class="mt-2 mb-6">{{ $files->links() }}</div>
            {{-- Start of table --}}
            <table class="text-left w-full border border-gray-300">
                <colgroup>
                    <col class="w-1/4">
                    <col class="w-1/8">
                    <col class="w-1/8">
                    <col class="w-1/8">
                    <col class="w-2/8">
                    <col class="w-1/8">
                </colgroup>
                {{-- Table header --}}
                <thead>
                <tr class="bg-gray-100 text-gray-700 [&>th]:p-2" style="text-align: left">
                    <th>File name</th>
                    <th>Category</th>
                    <th>Sub category</th>
                    <th>Uploaded by</th>
                    <th>Uploaded on</th>
                    <th></th>
                </tr>
                </thead>
                {{-- Table body --}}
                <tbody>
                {{-- Forelse is used so we can show later a message "no files found" when empty --}}
                @forelse($files as $file)
                    <tr class="border-t border-gray-300 [&>td]:p-2"
                        wire:key="file_{{ $file->id }}">
                        {{-- File name with a lock icon if confidential --}}
                        <td style="text-align: left">{{ $file->name }}
                            @if($file->is_confidential)
                                <x-phosphor-lock
                                    data-tippy-content="This file is confidential"
                                    class="w-5 inline"/>
                            @endif
                        </td>
                        {{-- Super category display with both the name and color tag --}}
                        <td>
                            @if($file->category->super_category_id != null)
                                <div class="flex gap-1 items-center justify-between">
                                    {{ $file->category->super_category->name }}
                                    <svg width="50" height="50" style="margin: 0 4rem 0 0;">
                                        <circle cx="25" cy="25" r="15"
                                                fill="#{{ $file->category->super_category->color_hex_code}}"/>
                                    </svg>
                                </div>
                            @elseif($file->category->id != null)
                                <div class="flex gap-1 items-center justify-between">
                                    {{ $file->category->name }}
                                    <svg width="50" height="50" style="margin: 0 4rem 0 0;">
                                        <circle cx="25" cy="25" r="15" fill="#{{ $file->category->color_hex_code }}"/>
                                    </svg>
                                </div>
                            @elseif($file->category->id == null)
                                <div>
                                    N/A
                                </div>
                            @endif
                        </td>
                        {{-- Sub category display with both the name and color tag --}}
                        <td>
                            @if($file->category->super_category_id != null)
                                <div class="flex gap-1 items-center justify-between">
                                    {{ $file->category->name }}
                                    <svg width="50" height="50" style="margin: 0 4rem 0 0;">
                                        <circle cx="25" cy="25" r="15" fill="#{{ $file->category->color_hex_code }}"/>
                                    </svg>
                                </div>
                            @elseif($file->category->super_category_id == null)
                                <div>
                                    N/A
                                </div>
                            @endif
                        </td>
                        <td>{{ $file->user->first_name }} {{$file->user->last_name}}</td>
                        <td>{{ date('d/m/Y', strtotime($file->upload_date)) }}</td>
                        {{-- The type of buttons depends on the state of the shown files: archived/un-archived --}}
                        {{-- If the files has a value in the deleted_at column, it is archived. Only permanent delete and unarchive butttons are shown --}}
                        {{-- If the file doesn't have a value in the deleted_at column, meaning it is null, it is unarchived. Edit, archive, and download buttons are shown --}}
                        @if($file->deleted_at)
                            <td>
                                <div
                                    class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                    {{-- Permanent delete button. This implements hard delete. If the file is confidential, the user is warned --}}
                                    <button
                                        x-data=""
                                        @click="$dispatch('swal:confirm', {
                                        title: 'Permanently Delete {{ $file->name }}?',
                                        icon: '{{ ($file->is_confidential ? 'warning' : '') }}',
                                        background: '{{ ($file->is_confidential ? 'error' : '') }}',
                                        cancelButtonText: 'NO!',
                                        confirmButtonText: 'YES DELETE THIS FILE',
                                        confirmButtonColor: '#374152' ,
                                        html: '{{ ($file->is_confidential ? '<b>ATTENTION</b>: you are going to permanently delete an <b>important</b> file that is tagged as a <b>confidential</b> file' : '') }}',
                                        color: '{{ ($file->is_confidential ? 'red' : '') }}',
                                        next: {
                                            event: 'hard-delete-file',
                                            params: {
                                                id: {{ $file->id }}
                                            }
                                        }
                                    });"
                                        class="w-10 text-gray-300 hover:text-red-600">
                                        <x-phosphor-trash-duotone
                                            data-tippy-content="Delete this file"
                                            class="w-10 text-gray-300 hover:text-red-600"/>
                                    </button>
                                    {{-- Unarchive button. This is technically the undo button. --}}
                                    <button wire:click="restoreFile({{$file->id}})"
                                            class="w-10 text-gray-300 hover:text-blue-600">
                                        <x-phosphor-arrow-bend-double-up-left
                                            data-tippy-content="Restore this file"
                                            class="w-10 text-gray-300 hover:text-blue-600"/>
                                    </button>
                                </div>
                            </td>
                        @else
                            <td>
                                <div
                                    class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                    {{-- Edit button. When clciking it, the setNewFile method from the livewire component is triggered --}}
                                    {{-- The triggered method sets the values of the newFile variable to the values of the chosen file so they can be edited --}}
                                    <button wire:click="setNewFile({{ $file->id }})"
                                            class="w-10 text-gray-300 hover:text-green-600">
                                        <x-phosphor-pencil-line-duotone
                                            data-tippy-content="Edit this file"
                                            class="w-10 text-gray-300 hover:text-green-600"/>
                                    </button>
                                    {{-- Archive button. This implements soft delete. If the file is confidential, the user is warned --}}
                                    <button
                                        x-data=""
                                        @click="$dispatch('swal:confirm', {
                                        title: 'Archive {{ $file->name }}?',
                                        icon: '{{ ($file->is_confidential ? 'warning' : '') }}',
                                        background: '{{ ($file->is_confidential ? 'error' : '') }}',
                                        cancelButtonText: 'NO!',
                                        confirmButtonText: 'YES ARCHIVE THIS FILE',
                                        html: '{{ ($file->is_confidential ? '<b>ATTENTION</b>: you are going to archive an <b>important</b> file that is tagged as a <b>confidential</b> file' : '') }}',
                                        color: '{{ ($file->is_confidential ? 'red' : '') }}',
                                        next: {
                                            event: 'soft-delete-file',
                                            params: {
                                                id: {{ $file->id }}
                                            }
                                        }
                                    });"
                                        class="w-10 text-gray-300 hover:text-red-600">
                                        <x-phosphor-archive-duotone
                                            data-tippy-content="Archive this file"
                                            class="w-10 text-gray-300 hover:text-red-600"/>
                                    </button>
                                    {{-- Download button --}}
                                    <button wire:click="downloadFile({{$file->id}})"
                                            class="w-10 text-gray-300 hover:text-blue-600">
                                        <x-phosphor-download
                                            data-tippy-content="Download this file"
                                            class="w-10 text-gray-300 hover:text-blue-600"/>
                                    </button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    {{-- In case no files match what the user requested in the filters, or if there's just no files, this message is displayed. --}}
                    <tr>
                        <td colspan="6" class="border-t border-gray-300 p-4 text-center">
                            <div class="font-bold italic text-blue-300">No files found</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{-- End of table --}}
            {{-- Pagination with links at the bottom --}}
            <div class="my-4">{{ $files->links() }}</div>
        </x-tmk.section>

        {{-- View for small and medium screen --}}
        {{-- The files are shown as cards instead of rows in a table --}}
        <div class="xl:hidden max-w-screen">
            {{-- Pagination using links --}}
            <div class="my-4">{{ $files->links() }}</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 ">
                {{-- Forelse is used so we can show later a message "no files found" when empty --}}
                @forelse($files as $file)
                    <div class="bg-white p-4 rounded-lg shadow max-w-full" wire:key="fileSm_{{ $file->id }}">
                        <div class="flex space-x-2 text-md justify-between">
                            {{-- File name with lock icon if confidential --}}
                            <div class="text-lg">{{ $file->name }}
                                @if($file->is_confidential)
                                    <x-phosphor-lock
                                        data-tippy-content="This file is confidential"
                                        class="w-5 inline"/>
                                @endif
                            </div>
                        </div>

                        <br>

                        {{-- File category tags --}}
                        <div>
                        <span class="flex items-center gap-1">
                            <span style="font-weight: 600; ">Tags:</span>
                            {{-- Super category tag --}}
                            @if($file->category->super_category_id != null)
                                <span class="flex gap-1 items-center mr-3 ml-3">
                                    {{ $file->category->super_category ? $file->category->super_category->name : '-' }}
                                    <svg width="50" height="50" style="margin: 0;">
                                        <circle cx="25" cy="25" r="15"
                                                fill="#{{ $file->category->super_category->color_hex_code }}"/>
                                    </svg>
                                </span>
                            @elseif($file->category->id != null)
                                <span class="flex gap-1 items-center mr-3 ml-3">
                                    {{ $file->category->name }}
                                    <svg width="50" height="50" style="margin: 0;">
                                        <circle cx="25" cy="25" r="15"
                                                fill="#{{ $file->category->color_hex_code }}"/>
                                    </svg>
                                </span>
                            @endif
                            {{-- Sub category tag --}}
                            @if($file->category->super_category_id != null)
                                <span class="flex gap-1 items-center mr-3 ml-3">
                                    {{ $file->category->name }}
                                    <svg width="50" height="50" style="margin: 0;">
                                        <circle cx="25" cy="25" r="15"
                                                fill="#{{ $file->category->color_hex_code }}"/>
                                    </svg>
                                </span>
                            @endif
                            {{-- If there is no category show None --}}
                            @if($file->category_id == null)
                                <span class="italic">None</span>
                            @endif
                            </span>
                        </div>

                        <br>

                        {{-- Upload date --}}
                        <div><span style="font-weight: 600;">Uploaded on:</span>
                            <b>{{ date('d/m/Y', strtotime($file->upload_date)) }}</b> <span
                                style="font-weight: 600;">by</span> <span
                                class="italic">{{ $file->user->first_name }} {{ $file->user->last_name }}</span>
                        </div>

                        <br>

                        {{-- The type of buttons depends on the state of the shown files: archived/un-archived --}}
                        {{-- If the file has a value in the deleted_at column, it is archived. Only permanent delete and unarchive butttons are shown --}}
                        {{-- If the file doesn't have a value in the deleted_at column, meaning it is null, it is unarchived. Edit, archive, and download buttons are shown --}}
                        @if($file->deleted_at)
                            <div class="flex gap-1 [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition"
                                 style="float: right;">
                                {{-- Permanent delete button. This implements hard delete. If the file is confidential, the user is warned --}}
                                <button
                                    x-data=""
                                    @click="$dispatch('swal:confirm', {
                                        title: 'Permanently Delete {{ $file->name }}?',
                                        icon: '{{ ($file->is_confidential ? 'warning' : '') }}',
                                        background: '{{ ($file->is_confidential ? 'error' : '') }}',
                                        cancelButtonText: 'NO!',
                                        confirmButtonText: 'YES DELETE THIS FILE',
                                        confirmButtonColor: '#374152' ,
                                        html: '{{ ($file->is_confidential ? '<b>ATTENTION</b>: you are going to permanently delete an <b>important</b> file that is tagged as a <b>confidential</b> file' : '') }}',
                                        color: '{{ ($file->is_confidential ? 'red' : '') }}',
                                        next: {
                                            event: 'hard-delete-file',
                                            params: {
                                                id: {{ $file->id }}
                                            }
                                        }
                                    });"
                                    class="w-10 text-gray-300 hover:text-red-600">
                                    <x-phosphor-trash-duotone
                                        data-tippy-content="Delete this file"
                                        class="w-10 text-gray-300 hover:text-red-600"/>
                                </button>
                                {{-- Unarchive button. This is technically the undo button. --}}
                                <button wire:click="restoreFile({{$file->id}})"
                                        class="w-10 text-gray-300 hover:text-blue-600">
                                    <x-phosphor-arrow-bend-double-up-left
                                        data-tippy-content="Restore this file"
                                        class="w-10 text-gray-300 hover:text-blue-600"/>
                                </button>
                            </div>
                        @else
                            <div class="flex gap-1 [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition"
                                 style="float: right;">
                                {{-- Edit button --}}
                                <button wire:click="setNewFile({{ $file->id }})"
                                        class="w-10 text-gray-300 hover:text-green-600">
                                    <x-phosphor-pencil-line-duotone
                                        data-tippy-content="Edit this file"
                                        class="w-10 text-gray-300 hover:text-green-600"/>
                                </button>
                                {{-- Archive button. This implements soft delete. If the file is confidential, the user is warned --}}
                                <button
                                    x-data=""
                                    @click="$dispatch('swal:confirm', {
                                        title: 'Archive {{ $file->name }}?',
                                        icon: '{{ ($file->is_confidential ? 'warning' : '') }}',
                                        background: '{{ ($file->is_confidential ? 'error' : '') }}',
                                        cancelButtonText: 'NO!',
                                        confirmButtonText: 'YES ARCHIVE THIS FILE',
                                        html: '{{ ($file->is_confidential ? '<b>ATTENTION</b>: you are going to archive an <b>important</b> file that is tagged as a <b>confidential</b> file' : '') }}',
                                        color: '{{ ($file->is_confidential ? 'red' : '') }}',
                                        next: {
                                            event: 'soft-delete-file',
                                            params: {
                                                id: {{ $file->id }}
                                            }
                                        }
                                    });"
                                    class="w-10 text-gray-300 hover:text-red-600">
                                    <x-phosphor-archive-duotone
                                        data-tippy-content="Archive this file"
                                        class="w-10 text-gray-300 hover:text-red-600"/>
                                </button>
                                {{-- Download button --}}
                                <button wire:click="downloadFile({{$file->id}})"
                                        class="w-10 text-gray-300 hover:text-blue-600">
                                    <x-phosphor-download
                                        data-tippy-content="Download this file"
                                        class="w-10 text-gray-300 hover:text-blue-600"/>
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    {{-- In case no files match what the user requested in the filters, or if there's just no files, this message is displayed. --}}
                    <x-tmk.section class="w-full">
                        <div class="p-4 text-center">
                            <p class="font-bold italic text-blue-300">No files found</p>
                        </div>
                    </x-tmk.section>
                @endforelse
            </div>
            {{-- Pagination links --}}
            <div class="mt-6 mb-2">{{ $files->links() }}</div>
        </div>
        {{-- End of listed files section. --}}
    </div>

    {{-- Edit file pop up modal --}}
    <x-dialog-modal id="fileModal"
                    wire:model="showModal">
        {{-- Modal title based on whether it is for creating a new file or editing an existing --}}
        <x-slot name="title">
            <h2>{{ is_null($newFile['id']) ? 'New file' : 'Edit file' }}</h2>
        </x-slot>

        {{-- Modal content, so the fields to be filled out --}}
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
            {{-- Fields to fill in for a new file --}}
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-2">

                    {{-- File name --}}
                    <x-label for="fileName" value="File name" class="mt-4"/>
                    <x-input type="text" id="fileName" wire:model.defer="newFile.name"
                             class="mt-1 block w-full" autofocus/>

                    {{-- Choose super category, the sub category dropdown only shows when the super category has sub categories --}}
                    <div class="md:mr-12 md:w-72 lg:w-72 lg:mr-6">
                        <x-label for="superCategory" value="Category" class="mt-4"/>
                        <x-tmk.form.select id="superCategory" type="text" class="block mt-1 w-full"
                                           wire:model.defer="newFile.sup_category_id"
                                           wire:change="resetSubCategory()">
                            <option value="">Select a category</option>
                            @foreach($superCategories as $superCategory)
                                <option value="{{$superCategory->id}}">{{$superCategory->name}}</option>
                            @endforeach
                        </x-tmk.form.select>

                        {{-- Getting only the sub categories of the chosen super category --}}
                        @php
                            $newCategories = App\Models\Category::where('super_category_id', $this->newFile['sup_category_id'])->get();
                        @endphp
                        {{-- Don't show the second dropdown if a super category is not chosen, or if the chosen super category doesn't have sub categories --}}
                        <div class="@if(count(\App\Models\Category::where('id',$this->newFile['sup_category_id'])->get()) < 1 )
                        hidden
                   @elseif(count($newCategories) < 1) hidden
                     @endif">

                            {{-- Dropdown for sub categories --}}
                            <x-label for="subCategory" value="Sub category (Optional)" class="mt-4"/>
                            <x-tmk.form.select id="subCategory" type="text"
                                               wire:model.defer="newFile.sub_category_id"
                                               class="block mt-1 w-full">
                                <option value="-1">Select a sub category</option>
                                @foreach($newCategories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </x-tmk.form.select>
                        </div>
                    </div>

                    {{-- This modal serves as both one for uploading new files and editing existing ones. Therefore, the way the "Choose file" button is shown is different. --}}
                    {{-- If it is a new file, then we show the "Choose file" button as it is --}}
                    @if(is_null($newFile['id']))
                        <div>
                            <div class="form-group">
                                <x-label for="url{{$iteration}}" value="Upload file" class="mt-4"/>
                                <x-input type="file" id="url{{$iteration}}" class="form-control-file mt-1"
                                         wire:model.defer="newFile.url"/>
                            </div>
                        </div>
                    @else
                        {{-- If we're editing n existing file, the user has the option to edit the information about the file with or without changing the original file --}}
                        <div class="mt-4">
                            {{-- Clicking this button shows the choose file button so the user can change the original one --}}
                            <x-button wire:click="toggleFileSection" class="btn btn-primary">Change the uploaded file
                            </x-button>
                            @if($showFileSection)
                                <div>
                                    <div class="form-group">
                                        <x-label for="url{{$iteration}}" value="Upload file" class="mt-4"/>
                                        <x-input type="file" id="url{{$iteration}}" class="form-control-file mt-1"
                                                 wire:model.defer="newFile.url"/>
                                    </div>
                                </div>
                                {{-- If the user changes their mind, they can click here to keep the original file and not change it --}}
                                <x-secondary-button wire:click="cancelNewFile" class="mt-3">Keep original file
                                </x-secondary-button>
                            @endif
                        </div>
                    @endif

                    {{-- Only super admins aka the grandparents and any other assigned super admin, can view confidential files --}}
                    {{-- Thus they're the only ones that can edit the confidentiality status of it as well. They do this using this toggle switch --}}
                    @if(auth()->user()->isSuperAdmin())
                        <div>
                            <x-label for="isConfidential" value="Confidentiality status:" class="mt-4"/>
                            <span class="text-sm">This is a toggle switch, click on it to change between confidential and not confidential</span>
                            <br>
                            <x-tmk.form.switch id="isConfidential"
                                               wire:model="newFile.is_confidential"
                                               class="text-white shadow-lg w-28"
                                               color-off="bg-red-800" color-on="bg-green-800"
                                               text-off="Not confidential" text-on="Confidential"/>
                        </div>
                    @endif
                </div>
            </div>
        </x-slot>

        {{-- Modal footer --}}
        <x-slot name="footer">
            {{-- The shown buttons here depend on the type of modal open --}}
            {{-- If the modal is for a new file, a cancel button and save new file button are displayed --}}
            @if(is_null($newFile['id']))
                <x-secondary-button @click="show = false">Cancel</x-secondary-button>
                <x-button
                    wire:click="createFile()"
                    wire:loading.attr="enabled"
                    class="ml-2">Save new file
                </x-button>
            @elseif($showFileSection)
                {{-- If the modal is for editing an existing file and the user changed the original file as well, the following buttons are shown --}}
                {{-- Cancel button that closes both the modal and the change existing file section so it doesn't show when clicking on editing a different file at start --}}
                <div x-data="{ showModal: @entangle('showModal'), showSection: @entangle('showFileSection') }">
                    <x-secondary-button @click="showModal = false; showSection = false">Cancel</x-secondary-button>
                </div>
                {{-- Update button that triggers the updateWithNewFile method in the livewire component. This updates the values of the file and replaces the old file --}}
                <x-button
                    color="success"
                    wire:click="updateWithNewFile({{ $newFile['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Update file
                </x-button>
            @else
                {{-- If the modal is for editing an existing file and the user has not changed the original file, the following buttons are shown --}}
                {{-- Cancel button that closes the modal --}}
                <x-secondary-button @click="show = false">Cancel</x-secondary-button>
                {{-- Update button that triggers the updateFile method in the livewire component. This updates the values of the file--}}
                <x-button
                    color="success"
                    wire:click="updateFile({{ $newFile['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Update file
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
