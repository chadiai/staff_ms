<div>
    <x-tmk.section>
        <div id="start-faq">
            <div class="absolute lg:top-2 right-2 top-20 hidden md:block">
                <x-button class="h-10">
                    Start the tour
                </x-button>
            </div>
            <div class="absolute right-4 mt-1 top-20 md:hidden ">
                <x-heroicon-m-question-mark-circle
                    class="w-10 text-regal-blue hover:text-regal-blue-dark cursor-pointer"/>

            </div>
        </div>
        {{-- show preloader while fetching data in the background --}}
        <div class="fixed top-8 left-1/2 md:ml-32 -translate-x-1/2 z-50 animate-pulse"
             wire:loading>
            <x-tmk.preloader class="bg-blue-400/60 text-white border border-blue-600 shadow-2xl p-4">
                {{ $loading }}
            </x-tmk.preloader>
        </div>
        {{-- filter section --}}


        <div class="flex flex-col items-start">
            <div class="w-full   flex flex-col lg:flex-row lg:space-x-2 lg:mb-2" id="questionSearch">
                <div class="w-full  mb-4 col-lg-3 lg:mb-0">
                    <div x-data="{ name: @entangle('name') }" class="relative">
                        <x-label for="name" value="Search"/>
                        <x-input id="name" type="text" x-model.debounce.500ms="name"
                                 class="block mt-1 w-full my-2 relative" placeholder="Search question"/>
                        <div x-show="name" @click="name = '';" class="w-5 absolute right-3 cursor-pointer top-10">
                            <x-phosphor-x-duotone/>
                        </div>
                    </div>
                </div>


            </div>
            @if(auth()->user()->isAdminOrSuperAdmin())

                <x-button wire:click="setNewQuestion()" class="text-white px-4 rounded mb-4 lg:mr-10"
                          id="createQuestion">Add Question
                </x-button>
            @endif
        </div>
    </x-tmk.section>

    <x-tmk.section>

        <h2 class="text-lg font-bold">Frequently asked questions</h2>
        {{-- master section: cards  --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-3 gap-8 mt-8" id="questionCards">

            {{-- No questions found --}}
            {{--        @if($questions->isEmpty())--}}
            {{--            <div></div>--}}
            {{--            <div class="text-center font-bold italic text-blue-300">No questions found.</div>--}}
            {{--            <div></div>--}}
            {{--        @endif--}}
            @forelse ($questions as $question)
                @if(!$question->authorization_type_id == null)
                    @if($question->authorization_type_id == auth()->user()->authorization_type_id || auth()->user()->isAdminOrSuperAdmin())
                        <div
                            wire:key="question_{{ $question->id }}"
                            class="bg-white flex  border border-gray-300 shadow-md rounded-lg overflow-hidden">
                            <div class="flex-1 flex flex-col">
                                <div class="flex-1 p-4"
                                >
                                    <p class="text-lg font-bold">{{ $question->question }}</p>
                                    <p class="pb-2 text-md-end"> {{$question->answer}}</p>
                                </div>
                                @if(auth()->user()->isAdminOrSuperAdmin())
                                    <div class="flex justify-between border-t border-gray-300 bg-gray-100 ">

                                        <div></div>

                                        <div class="flex [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">

                                            <x-phosphor-pencil-line-duotone
                                                data-tippy-content="Edit this question"
                                                wire:click="setNewQuestion({{ $question->id }})"
                                                class="w-10 text-gray-300 hover:text-green-600"/>

                                            <x-phosphor-trash-duotone
                                                x-data=""
                                                @click="$dispatch('swal:confirm', {
                    title: 'Delete question?',
                    cancelButtonText: 'NO!',
                    confirmButtonText: 'YES DELETE THIS QUESTION',
                    next: {
                        event: 'delete-question',
                        params: {
                            id: {{ $question->id }}
                                                }
                                            }
                                        });"
                                                data-tippy-content="Delete this question"
                                                class="w-10 text-gray-300 hover:text-red-600"/>


                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif
                @else
                    <div
                        wire:key="question_{{ $question->id }}"
                        class="bg-white flex  border border-gray-300 shadow-md rounded-lg overflow-hidden">
                        <div class="flex-1 flex flex-col">
                            <div class="flex-1 p-4"
                            >
                                <p class="text-lg font-bold">{{ $question->question }}</p>
                                <p class="pb-2 text-md-end"> {{$question->answer}}</p>
                            </div>
                            @if(auth()->user()->isAdminOrSuperAdmin())
                                <div class="flex justify-between border-t border-gray-300 bg-gray-100 ">

                                    <div></div>

                                    <div class="flex [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">

                                        <x-phosphor-pencil-line-duotone
                                            data-tippy-content="Edit this question"
                                            wire:click="setNewQuestion({{ $question->id }})"
                                            class="w-10 text-gray-300 hover:text-green-600"/>

                                        <x-phosphor-trash-duotone
                                            x-data=""
                                            @click="$dispatch('swal:confirm', {
                    title: 'Delete question?',
                    cancelButtonText: 'NO!',
                    confirmButtonText: 'YES DELETE THIS QUESTION',
                    next: {
                        event: 'delete-question',
                        params: {
                            id: {{ $question->id }}
                                            }
                                        }
                                    });"
                                            data-tippy-content="Delete this question"
                                            class="w-10 text-gray-300 hover:text-red-600"/>


                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                @endif
            @empty
                <div class="p-4 text-center col-span-full">
                    <p class="font-bold italic text-blue-300">No questions found</p>
                </div>
            @endforelse
        </div>


        {{-- question modal --}}
        <x-dialog-modal id="questionModal"
                        wire:model="showQuestionModal">
            <x-slot name="title">
                <h2 class="text-2xl">{{ is_null($newQuestion['id']) ? 'New question' : 'Edit question' }}</h2>
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
                        <x-label for="authorization_type_id" value="Who is this question for?" class="mt-4"/>

                        <x-tmk.form.select wire:model.defer="newQuestion.authorization_type_id"
                                           id="authorization_type_id"
                                           class="block mt-1 w-full" autofocus>
                            <option class="text-center" value="{{null}}">All users</option>

                            @foreach($authorizations as $authorization)
                                <option class="text-center "
                                        value="{{ $authorization->id }}">{{ $authorization->name }} </option>
                            @endforeach


                        </x-tmk.form.select>

                        <x-label for="question" value="Question" class="mt-4"/>
                        <x-tmk.form.textarea id="question" type="text"
                                  wire:model.defer="newQuestion.question"
                                  class="mt-1 block w-full input-group-lg border-regal-blue">
                    </x-tmk.form.textarea>
                        <x-label for="answer" value="Answer" class="mt-4"/>
                        <x-tmk.form.textarea id="answer" type="text"
                                  wire:model.defer="newQuestion.answer"
                                  class="mt-1 block w-full input-group-text border-regal-blue"></x-tmk.form.textarea>

                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button @click="show = false">Cancel</x-secondary-button>
                @if(is_null($newQuestion['id']))

                    <x-button
                        wire:click="createQuestion()"
                        wire:loading.attr="disabled"
                        class="ml-2">Confirm new question
                    </x-button>
                @else
                    <x-button
                        color="success"
                        wire:click="updateQuestion({{ $newQuestion['id'] }})"
                        wire:loading.attr="disabled"
                        class="ml-2">Update question
                    </x-button>
                @endif
            </x-slot>
        </x-dialog-modal>


    </x-tmk.section>

</div>
