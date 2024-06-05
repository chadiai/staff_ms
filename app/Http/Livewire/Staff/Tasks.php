<?php

namespace App\Http\Livewire\Staff;

use App\Models\Category;
use App\Models\Event;
use App\Models\EventCreationRequest;
use App\Models\EventMember;
use App\Models\ScheduledAbsence;
use Carbon\Carbon;
use DateTime;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Recurrence;
use Livewire\WithPagination;

class Tasks extends Component
{
    use WithPagination;

    public $search;
    public $perPage = 5;
    public $loading = 'Please wait...';
    public $superCategories = null;
    public $subCategories = null;
    public $superCategoryId = 0;
    public $subCategoryId = 0;
    public $allCategories = null;
    public $showModal = false;
    public $showDescriptionModal = false;
    public $showRecurring = false;
    public $members = [];
    public $taskMembers;
    public $currentTask;
    public $filter;
    public $orderBy = 'start_date_time';
    public $orderAsc = true;

    public $newEventRequest = [
        'number_of_occurrences' => null,
        'recurrence_id' => null,
    ];
    public $newTask = [
        'event_creation_request_id' => null,
        'id' => null,
        'category_id' => null,
        'sup_category_id' => null,
        'sub_category_id' => null,
        'name' => null,
        'description' => null,
        'start_date_time' => null,
        'end_date_time' => null,
        'location' => null,
        'is_task' => null,
    ];

    protected function rules()
    {
        $rules = [
            'newTask.name' => 'required|regex:/^[^<>]+$/',
            'newTask.sup_category_id' => 'required',
            'newTask.description' => 'required|regex:/^[^<>]+$/',
            'newTask.start_date_time' => 'required',
            'newTask.location' => 'nullable|regex:/^[^<>]+$/',
            'newTask.end_date_time' => 'required|after:newTask.start_date_time',
        ];

        if ($this->showRecurring) {
            $rules['newEventRequest.number_of_occurrences'] = 'required_with:newEventRequest.recurrence_id|required';
            $rules['newEventRequest.recurrence_id'] = 'required_with:newEventRequest.number_of_occurrences';
        }

        return $rules;
    }

    protected $validationAttributes = [
        'newTask.name' => 'name',
        'newTask.sup_category_id' => 'category',
        'newTask.description' => 'description',
        'newTask.start_date_time' => 'start date and time',
        'newTask.location' => 'location',
        'newTask.end_date_time' => 'end date and time',
        'newEventRequest.number_of_occurrences' => 'number of occurrences',
        'newEventRequest.recurrence_id'=>'occurrence type',

    ];

    // reset the paginator
    public function updated($propertyName, $propertyValue)
    {
        $this->resetPage();
    }

    protected $listeners = [
        'delete-task' => 'deleteTask',
        'update-all-tasks' => 'updateAllTasks',
    ];

    public function mount()
    {
        $this->allCategories = Category::get();
        $this->superCategories = Category::where('super_category_id', null)->get();
        $this->subCategories = Category::whereNot('super_category_id')->get();
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
    Resets the sub-category ID and the value of the sub-category field in the new task form.
    This function is called when the user changes the super-category of the task and the sub-category
    needs to be reset to the default value.
     */
    {
        $this->subCategoryId = 0;
        $this->newTask['sub_category_id'] = null;
    }

    public function resetDescription()
        /**
        This function is used to reset the description modal of the current task by setting the "currentTask"
        and "taskMembers" properties to null. It also hides the description modal by
        setting the "showDescriptionModal" property to false.
         */
    {
        $this->currentTask = null;
        $this->taskMembers = null;
        $this->showDescriptionModal = false;
    }

    public function setAll(Event $task)
    /**
    Sets all the necessary properties to edit a task.
    @param Event $task The task to be edited
    @return void
    */
    {

        $this->resetErrorBag();
        $this->members = [];
        $this->newTask['event_creation_request_id'] = $task->event_creation_request_id;
        $this->newTask['id'] = $task->id;
        // Determine the super and sub category IDs
        if ($task->category->super_category_id != null) {
            $this->newTask['sup_category_id'] = $task->category->super_category->id;
            $this->newTask['sub_category_id'] = $task->category->id;
        } else {
            $this->newTask['sup_category_id'] = $task->category->id;
            $this->newTask['sub_category_id'] = null;
        }
        $this->newTask['name'] = $task->name;
        $this->newTask['category_id'] = $task->category->id;
        $this->newTask['description'] = $task->description;
        $this->newTask['start_date_time'] = $task->start_date_time;
        $this->newTask['end_date_time'] = $task->end_date_time;
        $this->newTask['location'] = $task->location;
        foreach ($task->event_members as $eventMember) {
            if ($eventMember->user_id != auth()->user()->id) {
                $this->members[] = $eventMember->user_id . '';
            }
        }
        $this->showModal = true;
    }

    public function setNewTask(Event $task = null)
    /**
    Sets up the data for a new task in the application.
    @param Event|null $task The event that the new task is based on.
     */
    {
        $this->resetErrorBag();
        $this->members = [];
        if ($task) {
            // If the task has a super category, set the super category and sub category IDs
            if ($task->category->super_category_id != null) {
                $this->newTask['sup_category_id'] = $task->category->super_category->id;
                $this->newTask['sub_category_id'] = $task->category->id;
            } else {
                // Otherwise, set the super category ID and leave the sub category ID as null
                $this->newTask['sup_category_id'] = $task->category->id;
                $this->newTask['sub_category_id'] = null;
            }
            $this->newTask['event_creation_request_id'] = null;
            $this->newTask['id'] = $task->id;
            $this->newTask['category_id'] = $task->category_id;
            $this->newTask['name'] = $task->name;
            $this->newTask['description'] = $task->description;
            $this->newTask['start_date_time'] = $task->start_date_time;
            $this->newTask['end_date_time'] = $task->end_date_time;
            $this->newTask['location'] = $task->location;
            foreach ($task->event_members as $eventMember) {
                if ($eventMember->user_id != auth()->user()->id) {
                    $this->members[] = $eventMember->user_id . '';
                }
            }
        } else {
            // If no task is provided, reset the newEventRequest and newTask properties, as well as the category IDs
            $this->reset('newEventRequest');
            $this->reset('newTask');
            $this->superCategoryId = 0;
            $this->subCategoryId = null;
        }
        // Show the new task modal
        $this->showModal = true;
    }

    public function setDescription(Event $task)
    /**
    Sets up the data for displaying the description modal for a given task, including
    the task's current members.
    @param Event $task The task for which to display the description modal.
     */
    {
        // Reset the task members list
        $this->taskMembers = [];
        if ($task) {
            // Set the current task and add all members of the task to the task members list
            $this->currentTask = $task;
            foreach ($task->event_members as $eventMember) {
                $user = User::find($eventMember->user_id);
                $this->taskMembers[] = $user;
            }
        }
        // Show the description modal
        $this->showDescriptionModal = true;

    }

    public function createTask()
        /**
        Create a new task with the given information, including its name, description, location, start and end time,
        category and members.
         * The category ID is determined based on the selected subcategory, if any; otherwise, it
        defaults to the parent category ID.
         * If the task is not recurring, set the number of occurrences and the
        recurrence ID to their default values.
         * Create an EventCreationRequest object and set its attributes. For each
        occurrence of the task, set the start and end date times based on the start and end date time inputs and the
        specified recurrence.
         * Create an Event object with the given information and the ID of the EventCreationRequest
        object.
         * Add the current user and all selected members as EventMembers of the Event object.
         * Check if any of the selected members are absent on the start date of the task and display a warning if necessary.
         * Display a success message and reset the newEventRequest and newTask attributes.
         */
    {
        if ($this->newTask['sup_category_id'] == null) {
            $this->newTask['category_id'] = null;
        } else if (is_null($this->newTask['sub_category_id'])) {
            $this->newTask['category_id'] = $this->newTask['sup_category_id'];
        } else if ($this->newTask['sub_category_id'] == -1) {
            $this->newTask['category_id'] = $this->newTask['sup_category_id'];
        } else {
            $this->newTask['category_id'] = $this->newTask['sub_category_id'];
        }
        $this->validate();
        if (!$this->showRecurring) {
            $this->newEventRequest['recurrence_id'] = 1;
            $this->newEventRequest['number_of_occurrences'] = 1;
        }
        $request = EventCreationRequest::create([
            'number_of_occurrences' => $this->newEventRequest['number_of_occurrences'],
            'recurrence_id' => $this->newEventRequest['recurrence_id'],
            'user_id' => auth()->user()->id,
        ]);
        \Log::debug($request->id);
        for ($number = 0; $number < $this->newEventRequest['number_of_occurrences']; $number++) {
            $days = match ($this->newEventRequest['recurrence_id']) {
                '1' => 1,
                '2' => 7,
                '3' => 28,
                '4' => 364,
                default => 0
            };
            $number_days = $days * $number;
            $modifier = sprintf("+%d days", $number_days);
            $start_date_time = new DateTime($this->newTask['start_date_time']); // create a DateTime object from the datetime-local input value
            $start_date_time->modify($modifier); // add the specified number of days to the DateTime object
            $start_date = $start_date_time->format('Y-m-d\TH:i:s'); // format the modified DateTime object back to datetime-local format

            $end_date_time = new DateTime($this->newTask['end_date_time']); // create a DateTime object from the datetime-local input value
            $end_date_time->modify($modifier); // add the specified number of days to the DateTime object
            $end_date = $end_date_time->format('Y-m-d\TH:i:s'); // format the modified DateTime object back to datetime-local format

            $task = Event::create([
                'event_creation_request_id' => $request->id,
                'category_id' => $this->newTask['category_id'],
                'name' => $this->newTask['name'],
                'description' => $this->newTask['description'],
                'start_date_time' => $start_date,
                'end_date_time' => $end_date,
                'location' => $this->newTask['location'],
                'is_task' => true,
            ]);
            EventMember::create([
                'user_id' => auth()->user()->id,
                'event_id' => $task->id,
            ]);
            foreach ($this->members as $member) {
                EventMember::create([
                    'user_id' => $member,
                    'event_id' => $task->id,
                ]);
            }
        }
        $this->showModal = false;
        foreach (ScheduledAbsence::where('user_id', '=', $this->members)->get() as $absence) {
            $isAbsentStart = Carbon::parse($absence->start_date_time);
            $isAbsentEnd = Carbon::parse($absence->end_date_time);


            $user = User::find($absence->user_id);
            if ($start_date_time->format('Y-m-d H:i:s') >= $isAbsentStart && $start_date_time->format('Y-m-d H:i:s') <= $isAbsentEnd) {
                $this->dispatchBrowserEvent('swal:toast', [
                    'background' => 'danger',
                    'html' => "<b>{$user->first_name} {$user->last_name}</b> is absent that day!",
                ]);
                return;
            }
        }

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'Task added successfully',
        ]);

        $this->reset('newEventRequest');
        $this->reset('newTask');
    }

    public function updateAllTasks($id)
        /**
        Updates all tasks for a given event creation request ID.
        @param int $id The ID of the event creation request to update tasks for.
        @return void
         */
    {
        $eventCreationRequest = EventCreationRequest::where('id', $id)->first();
        foreach ($eventCreationRequest['events'] as $event) {
            $this->validate();
            if ($this->newTask['sup_category_id'] == null) {
                $this->newTask['category_id'] = null;
            } else if (is_null($this->newTask['sub_category_id'])) {
                $this->newTask['category_id'] = $this->newTask['sup_category_id'];
            } else if ($this->newTask['sub_category_id'] == -1) {
                $this->newTask['category_id'] = $this->newTask['sup_category_id'];
            } else {
                $this->newTask['category_id'] = $this->newTask['sub_category_id'];
            }
            $event->update([
                'name' => $this->newTask['name'],
                'category_id' => $this->newTask['category_id'] == -1 ? null : $this->newTask['category_id'],
                'description' => $this->newTask['description'],
                'location' => $this->newTask['location'],
            ]);
        }
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The tasks have been updated",
        ]);
    }

    public function updateTask(Event $task)
    /**
    Updates the given task object with the new task data, after validating the input data.
    @param Event $task The appointment object to be updated.
     */
    {
        $this->validate();
        if ($this->newTask['sup_category_id'] == null) {
            $this->newTask['category_id'] = null;
        } else if (is_null($this->newTask['sub_category_id'])) {
            $this->newTask['category_id'] = $this->newTask['sup_category_id'];
        } else if ($this->newTask['sub_category_id'] == -1) {
            $this->newTask['category_id'] = $this->newTask['sup_category_id'];
        } else {
            $this->newTask['category_id'] = $this->newTask['sub_category_id'];
        }
        $task->update([
            'name' => $this->newTask['name'],
            'category_id' => $this->newTask['category_id'] == -1 ? null : $this->newTask['category_id'],
            'description' => $this->newTask['description'],
            'start_date_time' => $this->newTask['start_date_time'],
            'end_date_time' => $this->newTask['end_date_time'],
            'location' => $this->newTask['location'],
        ]);
        foreach ($this->members as $member) {
            if (!$task->event_members->where('user_id', $member)->first()) {
                EventMember::create([
                    'user_id' => (int)$member,
                    'event_id' => $task->id,
                ]);
            }
        }
        foreach ($task->event_members as $event_member) {
            if (!in_array($event_member->user_id, $this->members) and $event_member->user_id != auth()->user()->id) {
                $event_member->delete();
            }
        }
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The task <b><i>{$task->name} </i></b> has been updated",
        ]);
    }

    public function removeSelfTask(Event $task)
    /**
    Removes the authenticated user from the event member list of the given task.
    If the user is currently assigned to the task, the user's assignment will be deleted.
    The function sends a success message to the user via a SweetAlert toast.
    @param Event $task The task to remove the authenticated user from.
     */
    {
        foreach ($task->event_members as $eventMember) {
            if ($eventMember->user_id == auth()->user()->id) {
                $eventMember->delete();
                $this->dispatchBrowserEvent('swal:toast', [
                    'background' => 'success',
                    'html' => "You have succesfully removed yourself from the task <b><i>{$task->name}</i></b>",
                ]);
            }
        }
    }

    public function deleteTask(Event $task)
    /**
    Removes the authenticated user from the event member list of the given task.
    If the user is currently assigned to the task, the user's assignment will be deleted.
    The function sends a success message to the user via a SweetAlert toast.
    @param Event $task The task to remove the authenticated user from.
     */
    {
        $task->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The task <b><i>{$task->name}</i></b> has been deleted",
        ]);
    }



    public function render()
    {
        $users = User::whereNotIn('id', [auth()->user()->id])->orderBy('name')->get();
        $recurring = Recurrence::orderBy('id')->get();
        $tasks = Event::where('is_task', true)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->whereHas('event_members', function (Builder $query) {
                $query->where('user_id', auth()->user()->id)->where([
                    ['name', 'like', "%{$this->filter}%"],]);
            })
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
        return view('livewire.staff.tasks', compact('tasks', 'users', 'recurring', 'categories'))
            ->layout('layouts.staff-management', [
                'description' => 'Manage your tasks',
                'title' => 'Tasks',
            ]);
    }
}
