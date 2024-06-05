<div>
    {{--    bootstrap user support tour    --}}
    <div id="start-submit-invoice">
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
        {{--        preloader   --}}
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
        <div class="lg:flex lg:flex-row">
            <div>
                <div class="md:ml-6" id="title">
                    <x-label for="title" value="Title" class="mt-4"/>
                    <x-input id="title" type="text"
                             wire:model.defer="newInvoice.title"
                             class="mt-1 block w-full cursor-pointer" autofocus/>
                </div>
                <div class="md:ml-6" id="pay">
                    <x-label for="dueDate" value="Due date to pay" class="mt-4"/>
                    <x-input id="dueDate" type="date"
                             wire:model.defer="newInvoice.dueDate"
                             class="mt-1 block w-full"/>
                </div>
                <div class="md:ml-6" id="file">
                    <div class="">
                        <div class="form-group file-input">
                            <x-label for="url{{$iteration}}" value="Upload file" class="mt-4"/>

                            <x-input type="file" id="url{{$iteration}}" class="sm:w-full mt-1"
                                     wire:model.defer="newInvoice.url">
                            </x-input>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:flex md:flex-row lg:flex-col">
                <div class="lg:mr-6 md:ml-6 lg:ml-12" id="amount">
                    <x-label for="amount" value="Amount" class="mt-4"/>
                    <x-input id="amount" type="number"
                             wire:model.defer="newInvoice.amount"
                             class="mt-1 block md:w-7/12 w-full lg:w-full"/>
                </div>
                <div class="lg:mr-6 lg:ml-12 " id="number">
                    <x-label for="number" value="Invoice number (optional)" class="mt-4"/>
                    <x-input id="number" type="text"
                             wire:model.defer="newInvoice.number"
                             class="mt-1 block w-full"/>
                </div>
                <div class="xl:hidden lg:inline hidden">
                    <div class="lg:mr-6 md:ml-6 lg:ml-12">
                        <div id="categorymd">
                            <x-label for="superCategory" value="Category" class="mt-4"/>
                            <x-tmk.form.select id="superCategory" type="text" class="block mt-1 w-full"
                                               wire:model="superCategoryId">
                                <option value="">Select a category</option>
                                @foreach($superCategories as $superCategory)
                                    <option value="{{$superCategory->id}}">{{$superCategory->name}}</option>
                                @endforeach
                            </x-tmk.form.select>
                        </div>
                        @if(count($categories) > 0 )

                            <x-label for="subCategory" value="Sub category (optional)" class="mt-4"/>
                            <x-tmk.form.select id="subCategory" type="text" wire:model="subCategoryId"
                                               class="block mt-1 w-full">
                                <option value="">Select a sub category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </x-tmk.form.select>
                        @endif
                    </div>
                </div>
            </div>
            <div class="lg:hidden xl:inline inline">
                <div class="md:flex md:flex-row">
                    <div class="md:mr-12 md:ml-6 lg:mr-6">
                        <div id="category">
                            <x-label for="superCategory" value="Category" class="mt-4"/>
                            <x-tmk.form.select id="superCategory" type="text" class="block mt-1 w-full"
                                               wire:model="superCategoryId">
                                <option value="">Select a category</option>
                                @foreach($superCategories as $superCategory)
                                    <option value="{{$superCategory->id}}">{{$superCategory->name}}</option>
                                @endforeach
                            </x-tmk.form.select>
                        </div>
                        @if(count($categories) > 0 )
                            <x-label for="subCategory" value="Sub category (optional)" class="mt-4"/>
                            <x-tmk.form.select id="subCategory" type="text" wire:model="subCategoryId"
                                               class="block mt-1 w-full">
                                <option value="">Select a sub category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </x-tmk.form.select>
                        @endif
                    </div>
                </div>
            </div>

        </div>
        <div class=" mt-8 md:ml-6">
            <x-button id="save"
                      wire:click="createInvoice()"
                      wire:loading.attr="disabled"
                      class="h-10">Submit invoice
            </x-button>
            <x-secondary-button wire:click="resetInvoice()" class="ml-2 h-10">Cancel</x-secondary-button>
        </div>

    </x-tmk.section>

</div>

