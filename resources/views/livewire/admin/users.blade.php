<div>
    <x-tmk.section>
        <div id="start-users">
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
            <div class="w-full   flex flex-col lg:flex-row lg:space-x-2 lg:mb-2" id="userSearch">
                <div class="w-full mb-4 col-lg-3 lg:mb-0">
                    <div x-data="{ name: @entangle('name') }" class="relative" id="filter">
                        <x-label for="name" value="Search"/>
                        <x-input id="name" type="text" x-model.debounce.500ms="name"
                                 class="block mt-1 w-full my-2 relative" placeholder="Search by name or role" autofocus/>
                        <div x-show="name" @click="name = '';" class="w-5 absolute right-3 cursor-pointer top-10">
                            <x-phosphor-x-duotone/>
                        </div>
                    </div>
                </div>

                <div class="w-full mb-4 col-lg-3 lg:mb-0" id="activeFilter">
                    <x-label for="active" value="Filter by active"/>
                    <x-tmk.form.select id="active" class="block mt-1 p-2 w-full"
                                       wire:model="active">
                        <option value="true">Active users</option>
                        <option value="false">Inactive users</option>
                        <option value="">All users</option>
                    </x-tmk.form.select>
                </div>

                <div class="w-full mb-4 col-lg-3 lg:mb-0" id="numberOfUsers">
                    <x-label for="perPage" value="Users per page"/>
                    <x-tmk.form.select id="perPage"
                                       wire:model="perPage"
                                       class="block mt-1 p-2 w-full">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </x-tmk.form.select>

                </div>

            </div>

            <div class="w-3/4 mb-4 col-lg-3 lg:mb-0 items-end">
                <div class="flex-col lg:flex-row">
                    <x-button wire:click="setNewUser()" class="text-white rounded mb-4 mr-1 h-10" id="createUser">
                        Create User
                    </x-button>
                    <x-button wire:click="$set('showStaffRolesModal', true)" class="text-white h-10 rounded"
                              id="manageStaffRoles">Manage
                        Staff Roles
                    </x-button>
                </div>
            </div>
        </div>
    </x-tmk.section>

    <x-tmk.section id="userCards">

        {{-- master section: cards with paginationlinks --}}
        <div class="mt-2 mb-6">{{ $users->links() }}</div>
        <div class="grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-3 gap-8">

            {{-- No users found --}}
            {{--@if($users->isEmpty())
                <div></div>
                <div class="text-center font-bold italic text-blue-300">No users found</div>
                <div></div>
            @endif--}}
            @forelse ($users as $user)
                <div
                    wire:key="user_{{ $user->id }}"
                    class="@if($user->active) bg-white @else bg-pink-100 @endif() flex  border border-gray-300 shadow-md rounded-lg overflow-hidden w-full">
                    <img
                        class="border-r border-gray-300 contain"
                        src="{{ $user->profile_photo_url }}"
                        alt="">
                    <div class="flex-1 flex flex-col">
                        <div class="flex-1 p-4 cursor-pointer"
                             data-tippy-content="Click for more info"
                             wire:click="setUserInformationModal({{ $user }})">

                            <p class="text-sm md:text-base font-bold ">{{ $user->first_name }} {{$user->last_name}}</p>
                            @if($user->staff_role_id != NULL)
                                <p class="text-sm md:text-base pb-2"><span
                                        class="font-medium underline">Role:</span> {{$user->staff_role->name}}</p>
                            @else
                                <p class="text-sm md:text-base pb-2"><span
                                        class="font-medium underline">Role:</span> Not staff</p>

                            @endif
                            <p class="pb-2 text-base hidden md:block"><span
                                    class="font-medium underline">Phone:</span> {{$user->phoneFormat}}</p>
                        </div>
                        <div class="flex justify-between border-t border-gray-300 bg-gray-100 ">

                            <div></div>
                            <div class="flex [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">

                                <x-phosphor-pencil-line-duotone
                                    data-tippy-content="Edit this user"
                                    wire:click="setNewUser({{ $user->id }})"
                                    class="w-10 text-gray-300 hover:text-green-600"/>
                                @if(!$user->active)
                                    <x-phosphor-trash-duotone
                                        x-data=""
                                        @click="$dispatch('swal:confirm', {
                    title: 'Delete user?',
                    cancelButtonText: 'NO!',
                    confirmButtonText: 'YES DELETE THIS USER',
                    next: {
                        event: 'delete-user',
                        params: {
                            id: {{ $user->id }}
                                            }
                                        }
                                    });"
                                        data-tippy-content="Delete this user"
                                        class="w-10 text-gray-300 hover:text-red-600"/>

                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center col-span-full">
                    <p class="font-bold italic text-blue-300">No users found</p>
                </div>
            @endforelse
        </div>
        <div class="mt-6 mb-2">{{ $users->links() }}</div>


        {{-- user modal --}}
        <x-dialog-modal id="userModal"
                        wire:model="showUserModal">
            <x-slot name="title">
                <h2 class="text-2xl">{{ is_null($newUser['id']) ? 'New user' : 'Edit user' }}</h2>
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
                        <x-label for="authorization_type_id" value="Authorization type" class="mt-4"/>

                        <x-tmk.form.select wire:model.defer="newUser.authorization_type_id" id="authorization_type_id"
                                           class="block mt-1 w-full" autofocus>
                            <option class="text-center" value="">Select an authorization type</option>

                            @if(auth()->user()->isSuperAdmin())
                                @foreach($authorizations as $authorization)
                                    <option class="text-center "
                                            value="{{ $authorization->id }}">{{ $authorization->name }} </option>
                                @endforeach
                            @else
                                @foreach($authorizations as $authorization)
                                    @if($authorization->id != 5)
                                        <option class="text-center "
                                                value="{{ $authorization->id }}">{{ $authorization->name }} </option>
                                    @endif
                                @endforeach
                            @endif


                        </x-tmk.form.select>

                        <x-label for="staff_role_id" value="Staff role (optional)" class="mt-4"/>
                        <x-tmk.form.select wire:model.defer="newUser.staff_role_id" id="staff_role_id"
                                           class="block mt-1 w-full">
                            <option class="text-center" value="">Select a staff role</option>
                            @foreach($staffRoles as $staffRole)
                                <option class="text-center" value="{{ $staffRole->id }}">{{ $staffRole->name }}</option>
                            @endforeach
                        </x-tmk.form.select>

                        <x-label for="first_name" value="First name" class="mt-4"/>
                        <x-input id="first_name" type="text"
                                 wire:model.defer="newUser.first_name"
                                 class="mt-1 block w-full input-group-lg"/>
                        <x-label for="last_name" value="Last name" class="mt-4"/>
                        <x-input id="last_name" type="text"
                                 wire:model.defer="newUser.last_name"
                                 class="mt-1 block w-full input-group-lg"/>

                        <x-label for="password" value="Password (optional)" class="mt-4"/>
                        <x-input id="password" type="text"
                                 wire:model.defer="newUser.password"
                                 class="mt-1 block w-full input-group-lg"/>

                        <x-label for="date_of_birth" value="Birth date (optional)" class="mt-4"/>
                        <x-input id="date_of_birth" type="date"
                                 wire:model.defer="newUser.date_of_birth"
                                 class="mt-1 block w-full"/>
                        <x-label for="email" value="E-mail" class="mt-4"/>
                        <x-input id="email" type="text"
                                 wire:model.defer="newUser.email"
                                 class="mt-1 block w-full"/>

                        <x-label for="allergy" value="Allergies (optional)" class="mt-4"/>
                        <x-input id="allergy" type="text"
                                 wire:model.defer="newUser.allergy"
                                 class="mt-1 block w-full"/>

                        <x-label for="telephone" value="Phone number" class="mt-4"/>
                        <x-input id="telephone" type="text"
                                 wire:model.defer="newUser.telephone"
                                 class="mt-1 block w-full"/>
                        <x-label for="bank_account_number" value="IBAN (optional)" class="mt-4"/>
                        <x-input id="bank_account_number" type="text"
                                 wire:model.defer="newUser.bank_account_number"
                                 class="mt-1 block w-full"/>


                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">

                @if(!is_null($newUser['id']))
                    <x-tmk.form.switch id="activate"
                                       wire:model="newUser.active"
                                       class="text-white absolute left-5 shadow-lg rounded-full w-28"
                                       color-off="bg-green-800" color-on="bg-red-800"
                                       text-off="Activate" text-on="Deactivate"/>
                @endif

                <x-secondary-button @click="show = false">Cancel</x-secondary-button>
                @if(is_null($newUser['id']))

                    <x-button
                        wire:click="createUser()"
                        wire:loading.attr="disabled"
                        class="ml-2">Confirm new user
                    </x-button>
                @else
                    <x-button
                        color="success"
                        wire:click="updateUser({{ $newUser['id'] }})"
                        wire:loading.attr="disabled"
                        class="ml-2">Update user
                    </x-button>
                @endif
            </x-slot>
        </x-dialog-modal>

        {{-- staff role modal --}}
        <x-dialog-modal id="staffRolesModal"
                        wire:model="showStaffRolesModal">
            <x-slot name="title">
                <h2>Staff roles</h2>
            </x-slot>
            <x-slot name="content">

                <div>
                    <x-tmk.section
                        x-data="{ open: false }"
                        class="p-0 mb-4 flex flex-col gap-2">
                        <div class="p-4 flex justify-between items-start gap-4">
                            <div class="flex items-center relative w-full [&>*]:h-10">
                                <x-input id="newStaffRole" type="text" placeholder="New staff role"
                                         wire:model.defer="newStaffRole"
                                         wire:keydown.enter="createStaffRole()"
                                         wire:keydown.tab="createStaffRole()"

                                         class="w-full shadow-md placeholder-gray-300"/>

                                <x-button class="text-white  py-2 px-4 ml-2 rounded lg:mb-0 lg:mr-10"
                                          wire:click="createStaffRole">
                                    Create Role
                                </x-button>
                                <x-phosphor-arrows-clockwise
                                    wire:loading
                                    wire:target="createStaffRole"
                                    class="w-5 h-5 text-gray-500 absolute top-3 right-2 animate-spin"/>

                            </div>
                        </div>
                        <x-input-error for="newStaffRole" class="m-4 mt-4 w-full"/>

                    </x-tmk.section>

                    <x-tmk.section>
                        <table class="text-center w-full border border-gray-300">

                            <thead>
                            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 cursor-pointer">

                                <th>
                                    Staff Role
                                </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($staffRoles as $staffRole)

                                <tr
                                    wire:key="staffRole_{{ $staffRole->id }}"
                                    class="border-t border-gray-300 [&>td]:p-2">

                                    @if($editStaffRole['id'] !== $staffRole->id)

                                        <td
                                            class="text-left text-lg cursor-pointer"
                                            wire:click="editExistingStaffRole({{ $staffRole->id }})">

                                            {{$staffRole->name}}
                                        </td>
                                    @else
                                        <td>
                                            <div class="flex flex-col text-left relative">
                                                <x-input id="edit_{{ $staffRole->id }}" type="text"
                                                         x-data=""
                                                         x-init="$el.focus()"
                                                         wire:model.defer="editStaffRole.name"
                                                         class="w-48"/>
                                                <x-phosphor-arrows-clockwise
                                                    wire:loading
                                                    wire:target="updateStaffRole"
                                                    class="w-5 h-5 text-gray-500 absolute top-3 right-2 animate-spin"/>
                                                <x-input-error for="editStaffRole.name" class="mt-2"/>

                                            </div>
                                        </td>
                                    @endif

                                    <td x-data="">

                                        <div
                                            class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                            @if($editStaffRole['id'] !== $staffRole->id)

                                                <x-phosphor-pencil-line-duotone
                                                    wire:click="editExistingStaffRole({{ $staffRole->id }})"
                                                    data-tippy-content="Edit this staff role"
                                                    class="w-12 text-gray-300 hover:text-green-600"/>
                                                <x-phosphor-trash-duotone
                                                    @click="$dispatch('swal:confirm', {
                    title: 'Delete staff role?',
                    cancelButtonText: 'NO!',
                    confirmButtonText: 'YES DELETE THIS ROLE',
                    next: {
                        event: 'delete-staffRole',
                        params: {
                            id: {{ $staffRole->id }}
                                            }
                                        }
                                    });" class="w-12 text-gray-300 hover:text-red-600"
                                                    data-tippy-content="Delete this staff role"/>

                                            @else
                                                <div
                                                    class="flex flex-row [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                                    <x-phosphor-check-duotone
                                                        wire:click="updateStaffRole({{ $staffRole->id }})"
                                                        data-tippy-content="Save changes"
                                                        class="w-12 text-gray-300 hover:text-green-600"/>
                                                    <x-phosphor-x-duotone
                                                        wire:click="resetEditStaffRole()"
                                                        data-tippy-content="Cancel changes"
                                                        class="w-12 text-gray-300 hover:text-red-600"/>

                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </x-tmk.section>
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button @click="show = false">Close</x-secondary-button>
            </x-slot>
        </x-dialog-modal>

        {{-- user information modal --}}
        <x-dialog-modal id="userInformationModal"
                        wire:model="showUserInformationModal">

            <x-slot name="title">
                <h2>Information</h2>
            </x-slot>
            <x-slot name="content">
                <x-label value="Name:" class="font-weight-bold"></x-label>
                <div>
                    {{$newUser['first_name']}} {{$newUser['last_name']}}
                </div>

                <x-label value="Authorization type:" class="font-weight-bold mt-2"></x-label>
                <div>
                    {{$authorizations->where('id', $newUser['authorization_type_id'])->first()->name ?? 'No authorization type' }}
                </div>

                <x-label value="Staff role:" class="font-weight-bold mt-2"></x-label>
                <div>
                    {{$staffRoles->where('id', $newUser['staff_role_id'])->first()->name ?? 'No staff role assigned'}}
                </div>

                <x-label value="Date of birth:" class="font-weight-bold mt-2"></x-label>
                <div>
                    {{$newUser['date_of_birth'] ?? 'No date of birth provided'}}
                </div>

                <x-label value="E-mail:" class="font-weight-bold mt-2"></x-label>
                <div>
                    {{$newUser['email']}}
                </div>

                <x-label value="Phone number:" class="font-weight-bold mt-2"></x-label>
                <div>
                    {{$newUser['phoneFormat']}}
                </div>

                <x-label value="Allergies:" class="font-weight-bold mt-2"></x-label>
                <div>
                    {{$newUser['allergy'] ?? 'No allergies provided'}}
                </div>

                <x-label value="IBAN" class="font-weight-bold mt-2"></x-label>
                <div>
                    {{$newUser['bank_account_number'] ?? 'No bank account number provided'}}
                </div>

            </x-slot>
            <x-slot name="footer">
                <x-secondary-button @click="show = false">Close</x-secondary-button>
            </x-slot>
        </x-dialog-modal>


    </x-tmk.section>
</div>
