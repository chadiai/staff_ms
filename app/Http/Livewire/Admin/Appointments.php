<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Event;
use App\Models\EventCreationRequest;
use App\Models\EventMember;
use App\Models\Recurrence;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Appointments extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $superCategoryId = 0;
    public $subCategoryId = 0;
    public $allCategories = null;
    public $superCategories = null;
    public $subCategories = null;
    public $showModal = false;
    public $loading = 'Please wait...';
    public $showDescriptionModal = false;
    public $showRecurring = false;
    public $members = [];
    public $currentAppointment;
    public $filter;
    public $orderBy = 'start_date_time';
    public $orderAsc = true;
    public $appointmentMembers;

    public function mount()
    {
        $this->allCategories = Category::get();
        $this->superCategories = Category::where('super_category_id', null)->get();
        $this->subCategories = Category::whereNot('super_category_id')->get();
    }

    public $newEventRequest = [
        'number_of_occurrences' => null,
        'recurrence_id' => null,
    ];
    public $newAppointment = [
        'id' => null,
        'name' => null,
        'category_id' => null,
        'sup_category_id' => null,
        'sub_category_id' => null,
        'description' => null,
        'start_date_time' => null,
        'end_date_time' => null,
        'location' => null,
        'is_appointment' => null,
    ];

    protected $listeners = [
        'delete-appointment' => 'deleteAppointment',
    ];


    protected function rules()
    {
        return [
            'newAppointment.name' => 'required|regex:/^[^<>]+$/',
            'newAppointment.sup_category_id' => 'required',
            'newAppointment.description' => 'required|regex:/^[^<>]+$/',
            'newAppointment.location' => 'nullable|regex:/^[^<>]+$/',
            'newAppointment.start_date_time' => 'required',
            'newAppointment.end_date_time' => 'required|after:newAppointment.start_date_time',
        ];
    }
    protected $validationAttributes = [
        'newAppointment.name' => 'name',
        'newAppointment.sup_category_id' => 'category',
        'newAppointment.description' => 'description',
        'newAppointment.start_date_time' => 'start date and time',
        'newAppointment.end_date_time' => 'end date and time',

    ];

    public function updated($propertyName, $propertyValue)
    {
        $this->resetPage();
    }

    public function resort($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderAsc = true;
        }
        $this->orderBy = $column;
    }

    public function resetSubCategory()
    /**
    Resets the sub-category ID and the value of the sub-category field in the new appointment form.
    This function is called when the user changes the super-category of the appointment and the sub-category
    needs to be reset to the default value.
     */
    {
        $this->subCategoryId = 0;
        $this->newAppointment['sub_category_id'] = null;
    }

    public function resetDescription()
    /**
    This function is used to reset the description modal of the current appointment by setting the "currentAppointment"
     and "appointmentMembers" properties to null. It also hides the description modal by
     setting the "showDescriptionModal" property to false.
     */
    {
        $this->currentAppointment = null;
        $this->appointmentMembers = null;
        $this->showDescriptionModal = false;
    }

    public function setNewAppointment(Event $appointment = null)
    /**
    Sets a new appointment to be displayed in a modal window. If an event is passed as an argument, it will populate the appointment fields
    with the event data. If no event is passed, the appointment fields will be reset to default values.
    @param Event|null $appointment An event object to be used to populate the appointment fields.
     */
    {
        $this->resetErrorBag();
        $this->members = [];
        if ($appointment) {
            if ($appointment->category->super_category_id != null){
                $this->newAppointment['sup_category_id'] = $appointment->category->super_category->id;
                $this->newAppointment['sub_category_id'] = $appointment->category->id;
            }
            else {
                $this->newAppointment['sup_category_id'] = $appointment->category->id;
                $this->newAppointment['sub_category_id'] = null;
            }
            $this->newAppointment['id'] = $appointment->id;
            $this->newAppointment['category_id'] = $appointment->category->id;
            $this->newAppointment['name'] = $appointment->name;
            $this->newAppointment['description'] = $appointment->description;
            $this->newAppointment['start_date_time'] = $appointment->start_date_time;
            $this->newAppointment['end_date_time'] = $appointment->end_date_time;
            $this->newAppointment['location'] = $appointment->location;
            foreach ($appointment->event_members as $eventMember) {
                if ($eventMember->user_id != auth()->user()->id) {
                    $this->members[] = $eventMember->user_id . '';
                }
            }


        } else {
            $this->reset('newEventRequest');
            $this->reset('newAppointment');
            $this->superCategoryId = 0;
            $this->subCategoryId = null;
        }
        $this->showModal = true;
    }

    public function setDescription(Event $appointment)
    /**
    Sets the current appointment and displays the description modal window.
    If an appointment object is passed as an argument, it sets the current appointment and
    populates the appointment members array with the users who are attending the event.
    @param Event $appointment The appointment object to be used to set the current appointment and populate the appointment members array.
     */
    {
        $this->appointmentMembers = [];
        if ($appointment) {
            $this->currentAppointment = $appointment;
            foreach ($appointment->event_members as $eventMember) {
                $user = User::find($eventMember->user_id);
                $this->appointmentMembers[] = $user;
            }
        }
        $this->showDescriptionModal = true;

    }


    public function createAppointment()
        /**
        Creates a new appointment based on the newAppointment data provided.
        If sub_category_id is null, category_id is set to sup_category_id. If sub_category_id is -1,
        category_id is also set to sup_category_id. Otherwise, category_id is set to sub_category_id.
        Validates the data, creates an event creation request, creates an event and adds it to the database.
        Also creates an EventMember record for the authenticated user and each member added to the appointment.
        Resets newEventRequest, newAppointment, superCategoryId, and subCategoryId. Closes the modal and displays
        a toast message with a success status.
        @return void
         */
    {
        if ($this->newAppointment['sup_category_id'] == null) {
            $this->newAppointment['category_id'] = null;
        } else if (is_null($this->newAppointment['sub_category_id'])) {
            $this->newAppointment['category_id'] = $this->newAppointment['sup_category_id'];
        } else if ($this->newAppointment['sub_category_id'] == -1) {
            $this->newAppointment['category_id'] = $this->newAppointment['sup_category_id'];
        } else {
            $this->newAppointment['category_id'] = $this->newAppointment['sub_category_id'];
        }

        $this->validate();
        $this->newEventRequest['recurrence_id'] = 1;
        $this->newEventRequest['number_of_occurrences'] = 1;
        $request = EventCreationRequest::create([
            'number_of_occurrences' => $this->newEventRequest['number_of_occurrences'],
            'recurrence_id' => $this->newEventRequest['recurrence_id'],
            'user_id' => auth()->user()->id,
        ]);
        $appointment = Event::create([
            'event_creation_request_id' => $request->id,
            'category_id' => $this->newAppointment['category_id'],
            'name' => $this->newAppointment['name'],
            'description' => $this->newAppointment['description'],
            'start_date_time' => $this->newAppointment['start_date_time'],
            'end_date_time' => $this->newAppointment['end_date_time'],
            'location' => $this->newAppointment['location'],
            'is_appointment' => true,
        ]);
        EventMember::create([
            'user_id' => auth()->user()->id,
            'event_id' => $appointment->id,
        ]);
        foreach ($this->members as $member) {
            EventMember::create([
                'user_id' => $member,
                'event_id' => $appointment->id,
            ]);
        }
        $this->reset('newEventRequest');
        $this->reset('newAppointment');
        $this->reset("superCategoryId");
        $this->reset("subCategoryId");
        $this->showModal = false;
        //toast message
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'Appointment added successfully',
        ]);
    }

    public function updateAppointment(Event $appointment)
    /**
    Updates the given appointment object with the new appointment data, after validating the input data.
    @param Event $appointment The appointment object to be updated.
     */
    {
        $this->validate();
        // sets category_id to either the super- or sub category
        if ($this->newAppointment['sup_category_id'] == null) {
            $this->newAppointment['category_id'] = null;
        } else if (is_null($this->newAppointment['sub_category_id'])) {
            $this->newAppointment['category_id'] = $this->newAppointment['sup_category_id'];
        } else if ($this->newAppointment['sub_category_id'] == -1) {
            $this->newAppointment['category_id'] = $this->newAppointment['sup_category_id'];
        } else {
            $this->newAppointment['category_id'] = $this->newAppointment['sub_category_id'];
        }
        $appointment->update([
            'name' => $this->newAppointment['name'],
            'category_id' => $this->newAppointment['category_id']  == -1 ? null : $this->newAppointment['category_id'],
            'description' => $this->newAppointment['description'],
            'start_date_time' => $this->newAppointment['start_date_time'],
            'end_date_time' => $this->newAppointment['end_date_time'],
            'location' => $this->newAppointment['location'],
        ]);

        // member association
        foreach ($this->members as $member) {
            if (!$appointment->event_members->where('user_id', $member)->first()) {

                EventMember::create([
                    'user_id' => (int)$member,
                    'event_id' => $appointment->id,
                ]);
            }
        }
        foreach ($appointment->event_members as $event_member) {
            if (!in_array($event_member->user_id, $this->members) and $event_member->user_id != auth()->user()->id) {
                $event_member->delete();
            }
        }
        $this->showModal = false;
        //toast message
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The appointment <b><i>{$appointment->name} </i></b> has been updated",
        ]);
    }

    public function deleteAppointment(Event $appointment)
        /**
        Deletes the given appointment object from the database and displays a success toast message using sweetalert2.
        @param Event $appointment The appointment object to be deleted from the database.
         */
    {
        $appointment->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The appointment <b><i>{$appointment->name}</i></b> has been deleted",
        ]);
    }

    public function render()
    {
        $users = User::whereHas('authorization_type',
            function (Builder $query) {$query->where('name',"Grandson");})->orderBy('name')->get();
        $recurring = Recurrence::orderBy('id')->get();
        $appointments = Event::where('is_task', false)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->whereHas('event_members', function (Builder $query) {
                $query->where('user_id', auth()->user()->id)
                    ->where([
                        ['name', 'like', "%{$this->filter}%"],
                    ]);})
            ->whereHas('category', function (Builder $query) {
                if ($this->superCategoryId != 0) {
                    $query->where('super_category_id', $this->superCategoryId);
                }
                if ($this->subCategoryId != 0) {
                    $query->where('id', $this->subCategoryId);
                }
            })
            ->paginate($this->perPage);
        $categories = Category::where('super_category_id', $this->superCategoryId)->get();
        return view('livewire.admin.appointments', compact('appointments', 'users', 'recurring','categories'))
            ->layout('layouts.staff-management', [
                'description' => 'Manage your appointments',
                'title' => 'Appointments',
            ]);
    }
}
