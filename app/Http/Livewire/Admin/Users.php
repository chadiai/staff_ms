<?php

namespace App\Http\Livewire\Admin;

use App\Models\AuthorizationType;
use App\Models\StaffRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\File;
use App\Models\Invoice;


class Users extends Component
{
    use WithPagination;

    // public properties
    public $perPage = 5;
    public $loading = 'Please wait...';
    public $showUserModal = false;
    public $showStaffRolesModal = false;
    public $showUserInformationModal = false;
    public $name;
    public $active = 'true';
    public $newUser = [
        'id' => null,
        'authorization_type_id' => null,
        'staff_role_id' => null,
        'name' => null,
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'allergy' => null,
        'date_of_birth' => null,
        'telephone' => null,
        'password' => null,
        'active' => null,
        'phoneFormat' => null,
    ];
    public $newStaffRole;
    public $editStaffRole = ['id' => null, 'name' => null];
    public $authorizations;
    public $staffRoles;

    // rename validation attributes to make them more readable
    protected $validationAttributes = [
        'newUser.first_name' => 'first name',
        'newUser.last_name' => 'last name',
        'newUser.authorization_type_id' => 'authorization type',
        'newUser.email' => 'email',
        'newUser.telephone' => 'telephone',
        'newUser.password' => 'password'
    ];

    // listeners to listen for events from view
    protected $listeners = [
        'delete-staffRole' => 'deleteStaffRole',
        'delete-user' => 'deleteUser',
    ];

    // validation rules
    protected function rules()
    {
        return [
            'newUser.authorization_type_id' => 'required',
            'newUser.first_name' => 'required|regex:/^[^<>]+$/',
            'newUser.last_name' => 'required|regex:/^[^<>]+$/',
            'newUser.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->newUser['id']),
            ],
            'newUser.telephone' => 'required|numeric|min:7',
        ];
    }

    // mount function
    public function resetNewStaffRole()
    {
        $this->reset('newStaffRole');
        $this->resetErrorBag();
    }

    // reset $editStaffRole and validation
    public function resetEditStaffRole()
    {
        $this->reset('editStaffRole');
        $this->resetErrorBag();
    }

    // create a new staff role
    public function createStaffRole()
    {

        // create the staff role
        $staffRole = StaffRole::create([
            'name' => trim($this->newStaffRole),
        ]);

        // reset $newStaffRole
        $this->resetNewStaffRole();
        // toast
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The staff role <b><i>{$staffRole->name}</i></b> has been added",
        ]);
        $this->staffRoles = StaffRole::orderBy('name')->get();

    }

    // update an existing staffRole
    public function updateStaffRole(StaffRole $staffRole)
    {
        $oldName = $staffRole->name;
        $staffRole->update([
            'name' => trim($this->editStaffRole['name']),
        ]);
        $this->resetEditStaffRole();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The staff role <b><i>{$oldName}</i></b> has been updated to <b><i>{$staffRole->name}</i></b>",
        ]);
        $this->staffRoles = StaffRole::orderBy('name')->get();

    }

    // edit the value of $editStaffRole (show inlined edit form)
    public function editExistingStaffRole(StaffRole $staffRole)
    {
        $this->editStaffRole = [
            'id' => $staffRole->id,
            'name' => $staffRole->name,
        ];
    }

    // delete a staff role
    public function deleteStaffRole(StaffRole $staffRole)
    {
        $users = User::where('staff_role_id', $staffRole->id)->get();
        if(!$users->isEmpty() ){
            $this->dispatchBrowserEvent('swal:confirm', [
                'icon' => 'error',
                'title' => 'Error',
                'showCancelButton' => false,
                'confirmButtonText' => 'Okay',
                'text' => "The staff role <b><i>{$staffRole->name}</i></b> cannot be deleted because it is assigned to one or more users.",
            ]);
        }

        else{
            $staffRole->delete();
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'success',
                'html' => "The staff role <b><i>{$staffRole->name} </i></b> has been deleted",
            ]);
            $this->staffRoles = StaffRole::orderBy('name')->get();

        }

    }

    // create a new user
    public function createUser()
    {
        $this->validate();
        $user = User::create([
            'authorization_type_id' => $this->newUser['authorization_type_id'],
            'staff_role_id' => $this->newUser['staff_role_id'] ?? null,
            'name' => $this->newUser['first_name'] . $this->newUser['last_name'],
            'first_name' => $this->newUser['first_name'],
            'last_name' => $this->newUser['last_name'],
            'email' => $this->newUser['email'],
            'allergy' => $this->newUser['allergy'],
            'bank_account_number' => $this->newUser['bank_account_number'] ?? null,
            'date_of_birth' => $this->newUser['date_of_birth'] ?? null,

            'telephone' => $this->newUser['telephone'],
            'password' => Hash::make($this->newUser['password'] ?? "Andersons") ,
        ]);

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The user <b><i>{$user->first_name} {$user->last_name}</i></b>  has been created",
        ]);

        $this->showUserModal = false;
    }
    public function updated($propertyName, $propertyValue)
    {
        $this->resetPage();
    }

    // edit an existing user
    public function updateUser(User $user)
    {
        $this->validate();
        $data = [
            'first_name' => $this->newUser['first_name'],
            'last_name' => $this->newUser['last_name'],
            'bank_account_number' => $this->newUser['bank_account_number'],
            'email' => $this->newUser['email'],
            'allergy' => $this->newUser['allergy'],
            'telephone' => $this->newUser['telephone'],
            'date_of_birth' => $this->newUser['date_of_birth'],
            'staff_role_id' => $this->newUser['staff_role_id'],
            'authorization_type_id' => $this->newUser['authorization_type_id'],
            'active' => (bool)$this->newUser['active'],
        ];

        if (!empty($this->newUser['password'])) {
            $data['password'] = Hash::make($this->newUser['password']);
        }

        $user->update($data);

        $this->showUserModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The user <b><i>{$user->first_name} {$user->last_name}</i></b> has been updated",
        ]);
    }

    // delete a user
    public function deleteUser(User $user)
    {
        $files = File::where('uploading_user_id', $user->id)->get();
        $invoices = Invoice::where('submitting_user_id', $user->id)->get();

        if(!$files->isEmpty() ){
            $this->dispatchBrowserEvent('swal:confirm', [
                'icon' => 'error',
                'title' => 'Error',
                'showCancelButton' => false,
                'confirmButtonText' => 'Okay',
                'text' => "The user <b><i>{$user->first_name} {$user->last_name}</i></b> cannot be deleted because they have one or more files uploaded.",
            ]);
        }

        elseif(!$invoices->isEmpty()){
            $this->dispatchBrowserEvent('swal:confirm', [
                'icon' => 'error',
                'title' => 'Error',
                'showCancelButton' => false,
                'confirmButtonText' => 'Okay',
                'text' => "The user <b><i>{$user->first_name} {$user->last_name}</i></b> cannot be deleted because they have one or more invoices uploaded.",
            ]);

        }

        else{
            $user->delete();
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'success',
                'html' => "The user <b><i>{$user->first_name} {$user->last_name}</i></b> has been deleted",
            ]);
        }

    }

    // pass information for chosen user to the user information modal
    public function setUserInformationModal(User $user){
        $this->newUser['id'] = $user->id;
        $this->newUser['authorization_type_id'] = $user->authorization_type_id;
        $this->newUser['staff_role_id'] = $user->staff_role_id;
        $this->newUser['first_name'] = $user->first_name;
        $this->newUser['last_name'] = $user->last_name;
        $this->newUser['email'] = $user->email;
        $this->newUser['allergy'] = $user->allergy;
        $this->newUser['telephone'] = $user->telephone;
        $this->newUser['bank_account_number'] = $user->bank_account_number;
        $this->newUser['date_of_birth'] = $user->date_of_birth;
        $this->newUser['phoneFormat'] = $user->phoneFormat;

        $this->showUserInformationModal = true;
    }

    // set the user information
    public function setNewUser(User $user = null)
    {
        $this->resetErrorBag();
        if ($user) {
            $this->newUser['id'] = $user->id;
            $this->newUser['authorization_type_id'] = $user->authorization_type_id;
            $this->newUser['staff_role_id'] = $user->staff_role_id;
            $this->newUser['first_name'] = $user->first_name;
            $this->newUser['last_name'] = $user->last_name;
            $this->newUser['email'] = $user->email;
            $this->newUser['allergy'] = $user->allergy;
            $this->newUser['telephone'] = $user->telephone;
            $this->newUser['bank_account_number'] = $user->bank_account_number;
            $this->newUser['date_of_birth'] = $user->date_of_birth;
            $this->newUser['active'] = $user->active;
            $this->newUser['password'] = "";
        } else {
            $this->reset('newUser');
        }
        $this->showUserModal = true;

    }

    // render the users page, with the active users shown by default
    public function render()
    {
        $users = User::orderBy('first_name')
            ->where(function ($query) {
                $query->where('first_name', 'like', "%{$this->name}%")
                    ->orWhere('last_name', 'like', "%{$this->name}%")
                    ->orWhereHas('staff_role', function ($query) {
                        $query->where('name', 'like', "%{$this->name}%");
                    });
            })
            ->when($this->active, function ($query, $active) {
                $query->where('active', $active === 'true');
            })
            ->paginate($this->perPage);

        return view('livewire.admin.users', compact('users'))
            ->layout('layouts.staff-management', [
                'description' => 'Manage your users',
                'title' => 'Users',
            ]);
    }

    // mount the staff roles and authorization types
    public function mount()
    {
        $this->staffRoles = StaffRole::orderBy('name')->get();
        $this->authorizations = AuthorizationType::all();
    }
}
