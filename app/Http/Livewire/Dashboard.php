<?php

namespace App\Http\Livewire;

use App\Models\Event;
use App\Models\EventMember;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Task;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Dashboard extends Component
{
    public $user;
    public $authorizationType;
    public $tasks;
    public $tasksCount;
    public $appointmentsCount;
    public $appointments;
    public $groupedTasks;
    public $groupedAppointments;
    public $eventMembers;

    public $loading = 'Please wait...';

    public $currentEvent;
    public $showModal = false;
    public $showDescriptionModal = false;

    public function setDescription(Event $event)
    /**
    Sets up the data for displaying the description modal for a given event, including
    the event current members.
    @param Event $event The event for which to display the description modal.
     */
    {
        $this->eventMembers = [];
        if ($event) {
            //select the event (task or appointment) that was clicked on
            $this->currentEvent = $event;
            $this->showDescriptionModal = true;
            //retrieve all the members that are assigned to the event
            foreach ($event->event_members as $eventMember) {
                $user = User::find($eventMember->user_id);
                $this->eventMembers[] = $user;
            }
        }

    }
    public function resetDescription()
        /**
        This function is used to reset the description modal of the current event by setting the "currentEvent"
        and "eventMembers" properties to null. It also hides the description modal by
        setting the "showDescriptionModal" property to false.
         */
    {
        $this->eventMembers = null;
        $this->showDescriptionModal = false;
    }

    public function render()
    {
        $today = now();
        //get the date of today and add 2 days to the time
        $twoDaysLater = $today->copy()->addDays(3);

        if (auth()->check()) {
            $user = auth()->user();

            //retrieve all the tasks between today and 2 days from now,
            // and where the logged-in user is assigned to the event itself.
            //sort by start_date_time to make the synchronous.
            $tasks = Event::where('is_task', true)
                ->where('end_date_time', '>=', $today)
                ->where('end_date_time', '<=', $twoDaysLater)
                ->whereHas('event_members', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                })->get()
                ->sortBy('start_date_time');

            //retrieve all the appointments between today and 2 days from now,
            // and where the logged-in user is assigned to the event itself.
            //sort by start_date_time to make the synchronous.
            $appointments = Event::where('is_task', false)
                ->where('start_date_time', '>=', $today)
                ->where('start_date_time', '<=', $twoDaysLater)
                ->whereHas('event_members', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                })->get()
                ->sortBy('start_date_time');

            //count the total amount of tasks and appointments retrieved
            $this->tasksCount = count($tasks);
            $this->appointmentsCount = count($appointments);
        }
        else {
            $tasks = [];
            $appointments = [];
        }

        //group all the tasks and appointments by the day (today - tomorrow - 2 days from now)
        $this->groupTasks($tasks);
        $this->groupAppointments($appointments);

        //use a different layout if there is a user logged in or not
        $layout = auth()->check() ? 'layouts.staff-management' : 'components.login-register.layout';
        return view('livewire.dashboard')
            ->layout($layout, [
                'groupedTasks' => $this->groupedTasks,
                'groupedAppointments' => $this->groupedAppointments,
                'description' => 'View dashboard',
                'title' => 'Dashboard',
            ]);
    }

    public function groupTasks($tasks)
    {
        $groupedTasks = [];

        foreach ($tasks as $task) {
            $startDateTime = Carbon::parse($task->start_date_time)->setTimezone(config('app.timezone'));
            $dateKey = $startDateTime->format('d-m-Y');
            if (!isset($groupedTasks[$dateKey])) {
                //make a new array with the start_date_time as the 'title'
                $groupedTasks[$dateKey] = [];
            }
            //Group all the tasks by day
            //eg: "2023-05-04" => array:2 [▶] "2023-05-05" => array:3 [▶]
            $groupedTasks[$dateKey][] = $task;
        }

        $this->groupedTasks = $groupedTasks;
    }

    public function groupAppointments($appointments)
    {
        $groupedAppointments = [];

        foreach ($appointments as $appointment) {
            $startDateTime = Carbon::parse($appointment->start_date_time)->setTimezone(config('app.timezone'));
            $dateKey = $startDateTime->format('d-m-Y');
            //make a new array with the start_date_time as the 'title'
            if (!isset($groupedAppointments[$dateKey])) {
                $groupedAppointments[$dateKey] = [];
            }
            //Group all the appointments by day
            //eg: "2023-05-04" => array:2 [▶] "2023-05-05" => array:3 [▶]
            $groupedAppointments[$dateKey][] = $appointment;
        }

        $this->groupedAppointments = $groupedAppointments;
    }
}
