@php use App\Models\User; @endphp
<div>
    {{-- Button to start the tour of the page --}}
    <div id="start-invoice">
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

    {{-- Filter section for the invoices page --}}
    <x-tmk.section class="mb-4 flex flex-col gap-2">
        {{-- Show preloader while fetching data in the background --}}
        <div class="fixed top-8 left-1/2 md:ml-32 -translate-x-1/2 z-50 animate-pulse"
             wire:loading>
            <x-tmk.preloader class="bg-blue-400/60 text-white border border-blue-600 shadow-2xl p-4">
                {{ $loading }}
            </x-tmk.preloader>
        </div>
        {{-- The filters start here --}}
        <div class="grid grid-cols-10 gap-4" id="filter">
            {{-- Search bar for invoices by entering the title of the invoice or the invoice number --}}
            <div class="col-span-10 lg:col-span-4" id="namefilter">
                <x-label for="title" value="Search"/>
                <div
                    x-data="{ title: @entangle('title') }"
                    class="relative">
                    <x-input id="title" type="text"
                             x-model.debounce.500ms="title"
                             class="block mt-1 w-full"
                             placeholder="Filter by invoice number or title" autofocus/>
                    <div
                        x-show="title"
                        @click="title = '';"
                        class="w-5 absolute right-4 top-3 cursor-pointer">
                        <x-phosphor-x-duotone/>
                    </div>
                </div>
            </div>

            {{-- Dropdown to filter based on category --}}
            <div class="col-span-10 md:col-span-5 lg:col-span-3 xl:col-span-2" id="supfilter">
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
            <div class="col-span-10 md:col-span-5 lg:col-span-3 xl:col-span-2" id="subfilter">
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

            {{-- Dropdown to filter invoices based on their payment status --}}
            <div class="col-span-10 md:col-span-5 lg:col-span-4 xl:col-span-2" id="statusfilter">
                <x-label for="paid" value="Payment status"/>
                <x-tmk.form.select id="paid" class="block mt-1 p-2 w-full"
                                   wire:model="paid">
                    <option value="">Paid & unpaid invoices</option>
                    <option value="true">Paid only</option>
                    <option value="false">Unpaid only</option>
                </x-tmk.form.select>
            </div>

            {{-- Toggle switch to filter invoices based on their archive status --}}
            <div class="col-span-10 md:col-span-5 lg:col-span-3 xl:col-span-2" id="archiving">
                <x-label for="showArchived">
                    @if ($isArchived)
                        Archived invoices
                    @else
                        Unarchived invoices
                    @endif
                </x-label>
                <div class="mt-2">
                    <x-tmk.form.archivesToggle id="showArchived" wire:model="isArchived"
                                               class="mt-1 p-2"></x-tmk.form.archivesToggle>
                </div>
            </div>

            {{-- Dropdown to choose the criteria to order the invoices: title, submission date, due date, or amount --}}
            <div class="col-span-5 lg:col-span-3 xl:col-span-2" id="orderfilter-1">
                <x-label for="orderOption" value="Order by"/>
                <x-tmk.form.select id="orderOption" class="block mt-1 p-2 w-full"
                                   wire:model="orderBy">
                    <option value="title" wire:click="resort('title')">Title</option>
                    <option value="submission_date" wire:click="resort('submission_date')">Submission date</option>
                    <option value="due_date" wire:click="resort('due_date')">Due date</option>
                    <option value="amount" wire:click="resort('amount')">Amount</option>
                </x-tmk.form.select>
            </div>

            {{-- Dropdown to choose order direction: ascending or descending --}}
            <div class="col-span-5 lg:col-span-4 xl:col-span-2" id="orderfilter-2">
                <x-label for="orderDirection" value="Order direction"/>
                <x-tmk.form.select id="orderDirection" class="block mt-1 p-2 w-full"
                                   wire:model="orderAsc">
                    <option value="1" wire:click="setOrderDirection(1)">Ascending</option>
                    <option value="0" wire:click="setOrderDirection(0)">Descending</option>
                </x-tmk.form.select>
            </div>

            {{-- Dropdown to choose number of invoices per page (pagination) --}}
            <div class="col-span-5 lg:col-span-3 xl:col-span-2" id="perpagefilter">
                <x-label for="perPage" value="Invoices per page"/>
                <x-tmk.form.select id="perPage"
                                   wire:model="perPage"
                                   class="block mt-1 p-2 w-full">
                    @foreach([5,10,15,20] as $invoicesPerPage)
                        <option value="{{$invoicesPerPage}}">{{$invoicesPerPage}}</option>
                    @endforeach
                </x-tmk.form.select>
            </div>

            {{-- Button to submit a new invoice. It will redirect the user to the submit new invoice page --}}
            <div class="flex flex-col mt-7 col-span-5 md:col-span-4 lg:col-span-3 xl:col-span-2 "
                 id="submitNewInvoice">
                <a href="{{ route('staff.submit-invoice') }}">
                    <x-button class="h-10">
                        Submit new invoice
                    </x-button>
                </a>
            </div>
        </div>
        {{-- End of filter section --}}
    </x-tmk.section>


    {{-- Start of listed invoices section. The first div is for wrapping both big and small screen views in order to be used later in the tour --}}
    <div id="invoicesection">
        {{-- Section for invoices on big screens: table view --}}
        <x-tmk.section class="hidden xl:block">
            {{-- Pagination links --}}
            <div class="mt-2 mb-6">{{ $invoices->links() }}</div>
            {{-- Start of table --}}
            <table class="text-left w-full border border-gray-300">
                <colgroup>
                    <col class="w-1/8">
                    <col class="w-1/8">
                    <col class="w-1/8">
                    <col class="w-1/8">
                    <col class="w-1/8">
                    <col class="w-1/8">
                    <col class="w-1/8">
                    <col class="w-1/8">
                </colgroup>
                {{-- Table header --}}
                <thead class="h-12">
                <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 " style="text-align: left">
                    <th>Invoice title</th>
                    <th>Category</th>
                    <th>Sub category</th>
                    <th>Due date</th>
                    <th>Amount</th>
                    <th>Invoice number</th>
                    <th>Payment status</th>
                    <th></th>
                </tr>
                </thead>
                {{-- Table body --}}
                <tbody>
                {{-- Forelse is used so we can show later a message "no invoices found" when empty --}}
                @forelse($invoices as $invoice)
                    <tr class="border-t border-gray-300 [&>td]:p-2"
                        wire:key="invoice_{{ $invoice->id }}">
                        {{-- When clicking on the name of the invoice, a pop up appears with extra information about the invoice --}}
                        <td class=" cursor-pointer font-bold underline"
                            wire:click="setDescription({{ $invoice->id }})">
                        <span data-tippy-content="Click for more info">
                            {{$invoice->title}}
                        </span>
                        </td>
                        {{-- Super category display with both the name and color tag --}}
                        <td>
                            @if($invoice->category->super_category_id != null)
                                <div class="flex gap-1 items-center justify-between">
                                    {{ $invoice->category->super_category->name }}
                                    <svg width="50" height="50" style="margin: 0;">
                                        <circle cx="25" cy="25" r="15"
                                                fill="#{{ $invoice->category->super_category->color_hex_code}}"/>
                                    </svg>
                                </div>
                            @elseif($invoice->category->id != null)
                                <div class="flex gap-1 items-center justify-between">
                                    {{ $invoice->category->name }}
                                    <svg width="50" height="50" style="margin: 0;">
                                        <circle cx="25" cy="25" r="15"
                                                fill="#{{ $invoice->category->color_hex_code }}"/>
                                    </svg>
                                </div>
                            @elseif($invoice->category->id == null)
                                <div>
                                    N/A
                                </div>
                            @endif
                        </td>
                        {{-- Sub category display with both the name and color tag --}}
                        <td>
                            @if($invoice->category->super_category_id != null)
                                <div class="flex gap-1 items-center justify-between">
                                    {{ $invoice->category->name }}
                                    <svg width="50" height="50" style="margin: 0;">
                                        <circle cx="25" cy="25" r="15"
                                                fill="#{{ $invoice->category->color_hex_code }}"/>
                                    </svg>
                                </div>
                            @elseif($invoice->category->super_category_id == null)
                                <div>
                                    N/A
                                </div>
                            @endif
                        </td>
                        <td>{{ date('d/m/Y', strtotime($invoice->due_date)) }}</td>
                        <td>£ {{ $invoice->amount }}</td>
                        <td>
                            @if($invoice->number)
                                {{ $invoice->number  }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="{{ $invoice->is_paid ? 'bg-green-200' : 'bg-red-200' }} text-center">{{ $invoice->is_paid ? 'Paid' : 'Unpaid' }}</td>
                        {{-- The type of buttons depends on the state of the shown invoices: archived/un-archived --}}
                        {{-- If the invocie has a value in the deleted_at column, it is archived. Only permanent delete and unarchive butttons are shown --}}
                        {{-- If the invoice doesn't have a value in the deleted_at column, meaning it is null, it is unarchived. Edit, archive, and download buttons are shown --}}
                        @if($invoice->deleted_at)
                            <td>
                                <div
                                    class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                    {{-- Permanent delete button. This implements hard delete. If the invoice is unpaid, the user is warned --}}
                                    <button
                                        x-data=""
                                        @click="$dispatch('swal:confirm', {
                                        title: 'Permanently Delete {{ $invoice->title }}?',
                                        icon: '{{ ($invoice->is_paid ? '' : 'warning') }}',
                                        background: '{{ ($invoice->is_paid ? '' : 'error') }}',
                                        cancelButtonText: 'NO!',
                                        confirmButtonText: 'YES DELETE THIS INVOICE',
                                        confirmButtonColor: '#374152' ,
                                        html: '{{ ($invoice->is_paid ? '' : '<b>ATTENTION</b>: you are going to permanently delete an <b> unpaid</b> invoice') }}',
                                        color: '{{ ($invoice->is_paid ? '' : 'red') }}',
                                        next: {
                                            event: 'hard-delete-invoice',
                                            params: {
                                                id: {{ $invoice->id }}
                                            }
                                        }
                                    });"
                                        class="w-10 text-gray-300 hover:text-red-600">
                                        <x-phosphor-trash-duotone
                                            data-tippy-content="Delete this invoice"
                                            class="w-10 text-gray-300 hover:text-red-600"/>
                                    </button>
                                    {{-- Unarchive button. This is technically the undo button. --}}
                                    <button wire:click="restoreInvoice({{$invoice->id}})"
                                            class="w-10 text-gray-300 hover:text-blue-600">
                                        <x-phosphor-arrow-bend-double-up-left
                                            data-tippy-content="Restore this invoice"
                                            class="w-10 text-gray-300 hover:text-blue-600"/>
                                    </button>
                                </div>
                            </td>
                        @else
                            <td>
                                <div
                                    class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                    {{-- Edit button. When clciking it, the setNewInvoice method from the livewire component is triggered --}}
                                    {{-- The triggered method sets the values of the newInvoice variable to the values of the chosen invoice so they can be edited --}}
                                    <button wire:click="setNewInvoice({{ $invoice->id }})"
                                            class="w-10 text-gray-300 hover:text-green-600">
                                        <x-phosphor-pencil-line-duotone
                                            data-tippy-content="Edit this invoice"
                                            class="w-10 text-gray-300 hover:text-green-600"/>
                                    </button>
                                    {{-- Archive button. This implements soft delete. If the invoice is unpaid, the user is warned --}}
                                    <button
                                        x-data=""
                                        @click="$dispatch('swal:confirm', {
                                        title: 'Archive {{ $invoice->title }}?',
                                        icon: '{{ ($invoice->is_paid ? '' : 'warning') }}',
                                        background: '{{ ($invoice->is_paid ? '' : 'error') }}',
                                        cancelButtonText: 'NO!',
                                        confirmButtonText: 'YES ARCHIVE THIS INVOICE',
                                        confirmButtonColor: '#374152' ,
                                        html: '{{ ($invoice->is_paid ? '' : '<b>ATTENTION</b>: you are going to archive an <b> unpaid</b> invoice') }}',
                                        color: '{{ ($invoice->is_paid ? '' : 'red') }}',
                                        next: {
                                            event: 'soft-delete-invoice',
                                            params: {
                                                id: {{ $invoice->id }}
                                            }
                                        }
                                    });"
                                        class="w-10 text-gray-300 hover:text-red-600">
                                        <x-phosphor-archive-duotone
                                            data-tippy-content="Archive this invoice"
                                            class="w-10 text-gray-300 hover:text-red-600"/>
                                    </button>
                                    {{-- Download button --}}
                                    <button wire:click="downloadInvoice({{$invoice->id}})"
                                            class="w-10 text-gray-300 hover:text-blue-600">
                                        <x-phosphor-download
                                            data-tippy-content="Download this invoice"
                                            class="w-10 text-gray-300 hover:text-blue-600"/>
                                    </button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    {{-- In case no invoices match what the user requested in the filters, or if there's just no invoices, this message is displayed. --}}
                    <tr>
                        <td colspan="8" class="border-t border-gray-300 p-4 text-center">
                            <div class="font-bold italic text-blue-300">No invoices found</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{-- End of table --}}
            {{-- Pagination with links at the bottom --}}
            <div class="mt-6 mb-2">{{ $invoices->links() }}</div>
        </x-tmk.section>

        {{-- When the user clicks on the title of the invoice, this modal is shown --}}
        {{-- Modal showing extra detail about the invoice --}}
        <x-dialog-modal id="descriptionModal"
                        wire:model="showDescriptionModal">
            <x-slot name="title">
                <h2 class="text-2xl">{{$shownInvoice['title']}}</h2>
            </x-slot>
            <x-slot name="content">
                <h2 class="font-medium text-xl block mt-2 w-full">Submit by:</h2>
                <div
                    class="mt-1 ml-4 p-2 text-lg">{{ $shownInvoice['submitting_user_first_name'] . ' ' . $shownInvoice['submitting_user_last_name'] }}</div>
                <h2 class="font-medium text-xl block mt-2 w-full">Submission date:</h2>
                <div
                    class="mt-1 ml-4 p-2 text-lg">{{ date('d/m/Y', strtotime($shownInvoice['submission_date'])) }}</div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button @click="show = false">Close</x-secondary-button>
            </x-slot>
        </x-dialog-modal>


        {{-- View for small and medium screen --}}
        {{-- The invoices are shown as cards instead of rows in a table --}}
        <div class="xl:hidden max-w-screen">
            {{-- Pagination using links --}}
            <div class="my-4">{{ $invoices->links() }}</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                {{-- Forelse is used so we can show later a message "no invoices found" when empty --}}
                @forelse($invoices as $invoice)
                    <div class="bg-white p-4 rounded-lg shadow max-w-full" wire:key="invoiceSm_{{ $invoice->id }}">
                        <div class="flex space-x-2 text-md justify-between">
                            {{-- Invoice title --}}
                            <div class="text-lg">{{ $invoice->title }}</div>
                            {{-- Invoice payment status --}}
                            <div
                                class="{{ $invoice->is_paid ? 'bg-green-200 p-2 rounded shadow-green-900' : 'bg-red-200 p-2 rounded shadow-red-900' }}">{{ $invoice->is_paid ? 'Paid' : 'Unpaid' }}</div>
                        </div>

                        {{-- Invoice amount --}}
                        <div><span style="font-weight: 600; ">Amount:</span> <b>{{ $invoice->amount }}</b></div>

                        {{-- Invoice category tags --}}
                        <div>
                        <span class="flex items-center gap-1">
                            <span style="font-weight: 600; ">Tags:</span>
                            {{-- Super category tag --}}
                            @if($invoice->category->super_category_id != null)
                                <span class="flex gap-1 items-center mr-3 ml-3">
                                    {{ $invoice->category->super_category->name }}
                                    <svg width="50" height="50" style="margin: 0;">
                                        <circle cx="25" cy="25" r="15"
                                                fill="#{{ $invoice->category->super_category->color_hex_code }}"/>
                                    </svg>
                                </span>
                            @elseif($invoice->category->id != null)
                                <span class="flex gap-1 items-center mr-3 ml-3">
                                    {{ $invoice->category->name }}
                                    <svg width="50" height="50" style="margin: 0;">
                                        <circle cx="25" cy="25" r="15"
                                                fill="#{{ $invoice->category->color_hex_code }}"/>
                                    </svg>
                                </span>
                            @endif
                            {{-- Sub category tag --}}
                            @if($invoice->category->super_category_id != null)
                                <span class="flex gap-1 items-center mr-3 ml-3">
                                    {{ $invoice->category->name }}
                                    <svg width="50" height="50" style="margin: 0;">
                                        <circle cx="25" cy="25" r="15"
                                                fill="#{{ $invoice->category->color_hex_code }}"/>
                                    </svg>
                                </span>
                            @endif
                            {{-- If there is no category show None --}}
                            @if($invoice->category_id == null)
                                <span class="italic">None</span>
                            @endif
                            </span>
                        </div>

                        {{-- Invoice number --}}
                        <div>
                            <span style="font-weight: 600;">Invoice Number:</span>
                            @if($invoice->number == null)
                                <span class="italic">None</span>
                            @else
                                <b>{{ $invoice->number }}</b>
                            @endif
                        </div>
                        <br>
                        {{-- Invoice submission date and submitting user --}}
                        <div>
                            <span style="font-weight: 600;">Submit on:</span>
                            <b>{{ date('d/m/Y', strtotime($invoice->submission_date)) }}</b> <span
                                style="font-weight: 600;">by</span> <span
                                class="italic">{{ $invoice->user->first_name }} {{ $invoice->user->last_name }}</span>
                        </div>
                        {{-- Due date --}}
                        <div>
                            <span style="font-weight: 600;">Due on:</span>
                            <b>{{ date('d/m/Y', strtotime($invoice->due_date)) }}</b>
                        </div>

                        {{-- The type of buttons depends on the state of the shown invoices: archived/un-archived --}}
                        {{-- If the invocie has a value in the deleted_at column, it is archived. Only permanent delete and unarchive butttons are shown --}}
                        {{-- If the invoice doesn't have a value in the deleted_at column, meaning it is null, it is unarchived. Edit, archive, and download buttons are shown --}}
                        @if($invoice->deleted_at)
                            <div class="flex gap-1 [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition"
                                 style="float: right;">
                                {{-- Permanent delete button. This implements hard delete. If the invoice is unpaid, the user is warned --}}
                                <button
                                    x-data=""
                                    @click="$dispatch('swal:confirm', {
                                        title: 'Permanently Dlete {{ $invoice->title }}?',
                                        icon: '{{ ($invoice->is_paid ? '' : 'warning') }}',
                                        background: '{{ ($invoice->is_paid ? '' : 'error') }}',
                                        cancelButtonText: 'NO!',
                                        confirmButtonText: 'YES DELETE THIS INVOICE',
                                        confirmButtonColor: '#374152' ,
                                        html: '{{ ($invoice->is_paid ? '' : '<b>ATTENTION</b>: you are going to permanently delete an <b> unpaid</b> invoice') }}',
                                        color: '{{ ($invoice->is_paid ? '' : 'red') }}',
                                        next: {
                                            event: 'hard-delete-invoice',
                                            params: {
                                                id: {{ $invoice->id }}
                                            }
                                        }
                                    });"
                                    class="w-10 text-gray-300 hover:text-red-600">
                                    <x-phosphor-trash-duotone
                                        data-tippy-content="Delete this invoice"
                                        class="w-10 text-gray-300 hover:text-red-600"/>
                                </button>
                                {{-- Unarchive button. This is technically the undo button. --}}
                                <button x-data="" wire:click="restoreInvoice({{$invoice->id}})"
                                        class="w-10 text-gray-300 hover:text-blue-600">
                                    <x-phosphor-arrow-bend-double-up-left
                                        data-tippy-content="Restore this invoice"
                                        class="w-10 text-gray-300 hover:text-blue-600"/>
                                </button>
                            </div>
                        @else
                            <div class="flex gap-1 [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition"
                                 style="float: right;">
                                {{-- Edit button --}}
                                <button wire:click="setNewInvoice({{ $invoice->id }})"
                                        class="w-10 text-gray-300 hover:text-green-600">
                                    <x-phosphor-pencil-line-duotone
                                        data-tippy-content="Edit this invoice"
                                        class="w-10 text-gray-300 hover:text-green-600"/>
                                </button>
                                {{-- Archive button. This implements soft delete. If the invoice is unpaid, the user is warned --}}
                                <button
                                    x-data=""
                                    @click="$dispatch('swal:confirm', {
                                        title: 'Archive {{ $invoice->title }}?',
                                        icon: '{{ ($invoice->is_paid ? '' : 'warning') }}',
                                        background: '{{ ($invoice->is_paid ? '' : 'error') }}',
                                        cancelButtonText: 'NO!',
                                        confirmButtonText: 'YES ARCHIVE THIS INVOICE',
                                        confirmButtonColor: '#374152' ,
                                        html: '{{ ($invoice->is_paid ? '' : '<b>ATTENTION</b>: you are going to archive an <b> unpaid</b> invoice') }}',
                                        color: '{{ ($invoice->is_paid ? '' : 'red') }}',
                                        next: {
                                            event: 'soft-delete-invoice',
                                            params: {
                                                id: {{ $invoice->id }}
                                            }
                                        }
                                    });"
                                    class="w-10 text-gray-300 hover:text-red-600">
                                    <x-phosphor-archive-duotone
                                        data-tippy-content="Archive this invoice"
                                        class="w-10 text-gray-300 hover:text-red-600"/>
                                </button>
                                {{-- Download button --}}
                                <button wire:click="downloadInvoice({{$invoice->id}})"
                                        class="w-10 text-gray-300 hover:text-blue-600">
                                    <x-phosphor-download
                                        data-tippy-content="Download this invoice"
                                        class="w-10 text-gray-300 hover:text-blue-600"/>
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    {{-- In case no invoices match what the user requested in the filters, or if there's just no invoices, this message is displayed. --}}
                    <x-tmk.section class="w-full">
                        <div class="p-4 text-center">
                            <p class="font-bold italic text-blue-300">No invoices found</p>
                        </div>
                    </x-tmk.section>
                @endforelse
            </div>
            {{-- Pagination links --}}
            <div class="my-4">{{ $invoices->links() }}</div>
        </div>
        {{-- End of listed invoices section. --}}
    </div>

    {{-- Edit invoice pop up modal --}}
    <x-dialog-modal id="invoiceModal"
                    wire:model="showModal">
        {{-- Modal title --}}
        <x-slot name="title">
            <h2>Edit invoice</h2>
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
            {{-- Fields to fill in for a new invoice --}}
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-2">

                    {{-- Invoice title --}}
                    <x-label for="title" value="Title" class="mt-4"/>
                    <x-input type="text" id="title" wire:model.defer="newInvoice.title"
                             class="mt-1 block w-full" autofocus/>

                    {{-- Invoice number --}}
                    <x-label for="number" value="Number (optional)" class="mt-4"/>
                    <x-tmk.form.textarea type="text" id="number" wire:model.defer="newInvoice.number"
                                         class="mt-1 block w-full"/>

                    {{-- Choose super category, the sub category dropdown only shows when the super category has sub categories --}}
                    <x-label for="superCategory" value="Category" class="mt-4"/>
                    <x-tmk.form.select id="superCategory" type="text" class="block mt-1 w-full"
                                       wire:model.defer="newInvoice.sup_category_id"
                                       wire:change="resetSubCategory()">
                        <option value="">Select a category</option>
                        @foreach($superCategories as $superCategory)
                            <option value="{{$superCategory->id}}">{{$superCategory->name}}</option>
                        @endforeach
                    </x-tmk.form.select>

                    {{-- Getting only the sub categories of the chosen super category --}}
                    @php
                        $newCategories = App\Models\Category::where('super_category_id', $this->newInvoice['sup_category_id'])->get();
                    @endphp
                    {{-- Don't show the second dropdown if a super category is not chosen, or if the chosen super category doesn't have sub categories --}}
                    <div class="@if(count(\App\Models\Category::where('id',$this->newInvoice['sup_category_id'])->get()) < 1 )
                        hidden
                   @elseif(count($newCategories) < 1) hidden
                     @endif">

                        {{-- Dropdown for sub categories --}}
                        <x-label for="subCategory" value="Sub category (Optional)" class="mt-4"/>
                        <x-tmk.form.select id="subCategory" type="text"
                                           wire:model.defer="newInvoice.sub_category_id"
                                           class="block mt-1 w-full">
                            <option value="-1">Select a sub category</option>
                            @foreach($newCategories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </x-tmk.form.select>
                    </div>

                    {{-- Invoice due date --}}
                    <x-label for="dueDate" value="Due date to pay" class="mt-4"/>
                    <x-input id="dueDate" type="date"
                             wire:model.defer="newInvoice.due_date"
                             class="mt-1 block w-full"/>

                    {{-- Invoice payment status --}}
                    <x-label for="isPaid" value="Payment status" class="mt-4"/>
                    <x-tmk.form.switch id="isPaid"
                                       wire:model="newInvoice.is_paid"
                                       class="text-white shadow-lg rounded-full w-28"
                                       color-off="bg-red-800" color-on="bg-green-800"
                                       text-off="Unpaid" text-on="Paid"/>
                </div>
            </div>
        </x-slot>

        {{-- Modal footer --}}
        <x-slot name="footer">
            {{-- Cancel button that closes the modal --}}
            <x-secondary-button @click="show = false">Cancel</x-secondary-button>
            {{-- Update button that triggers the updateInvocie method in the livewire component. This updates the values of the invoice --}}
            <x-button
                color="success"
                wire:click="updateInvoice({{ $newInvoice['id'] }})"
                wire:loading.attr="disabled"
                class="ml-2">Update invoice
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
