<?php

namespace App\Http\Livewire\User;

use App\Models\Event;
use App\Models\MealPlan;
use App\Models\MealSubscription;
use App\Models\User;
use App\Models\UserAllergy;
use Livewire\Component;

class Schedule extends Component
{
    public $showSection = false;
    public $sectionEvent = null;
    public $isEvent = null;
    public $taskMembers = null;
    public $mealMembers = null;
    public $subscribed = false;
    public $showModal = false;
    public $loading = 'Please wait...';
    public $color = [
        'task' => null,
        'appointment' => null,
        'meal' => null,
    ];

    public function mount()
    {
        $this->color['task'] = '#' . auth()->user()->task_color;
        $this->color['appointment'] = '#' . auth()->user()->appointment_color;
        $this->color['meal'] = '#' . auth()->user()->meal_color;
    }

    protected $listeners = ['setEvent' => 'setEvent'];

    public function setEvent($type, $id)
    /**
    Set the event details based on the provided type and ID.
    @param int $type The type of event (1 for regular event, 2 for meal plan).
    @param int $id The ID of the event or meal plan.
     */
    {
        $this->dispatchBrowserEvent('calendar:render',['event'=> true]);
        $this->showModal = true;
        $this->showSection = true;
        $this->subscribed = false;
        // Set event details based on the type
        switch ($type) {
            // task or appointment
            case 1:
                $this->isEvent = true;
                $this->sectionEvent = Event::where('id', $id)->first();
                $this->taskMembers = null;
                foreach ($this->sectionEvent->event_members as $eventMember) {
                    $user = User::find($eventMember->user_id);
                    $this->taskMembers[] = $user;
                }
                break;
            // meal plan
            case 2:
                $this->isEvent = false;
                $this->sectionEvent = MealPlan::with('meal_type')->with('meal')->where('id', $id)->first();
                $this->mealMembers = null;
                foreach ($this->sectionEvent->meal_subscriptions as $subscription) {
                    $user = User::find($subscription->user_id);
                    if ($subscription->user_id == auth()->user()->id) $this->subscribed = true;
                    $this->mealMembers[] = $user;
                }
                break;
        }

    }

    public function subscribeMeal(MealPlan $mealPlan)
    /**
    Subscribe the authenticated user to a meal plan.
    @param MealPlan $mealPlan The meal plan to subscribe to.
     */
    {
        $this->resetEvent();
        MealSubscription::create(
            [
                'user_id' => auth()->user()->id,
                'meal_plan_id' => $mealPlan->id,
            ]);

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'You successfully subscribed to the meal plan',
        ]);
    }

    public function unsubscribeMeal(MealPlan $mealPlan)
    /**
    Unsubscribe the authenticated user from a meal plan.
    @param MealPlan $mealPlan The meal plan to unsubscribe from.
     */
    {
        $this->resetEvent();
        foreach ($mealPlan->meal_subscriptions as $subscription)
            if ($subscription->user_id == auth()->user()->id) {
                $subscription->delete();
                $this->dispatchBrowserEvent('swal:toast', [
                    'background' => 'success',
                    'html' => "You have succesfully unsubscribed from the meal plan <b><i>{$mealPlan->name}</i></b>",
                ]);
            }
    }

    public function updateColor()
    /**
    Update the color preferences of the authenticated user.
     */
    {
        auth()->user()->update([
            'task_color' => ltrim($this->color['task'],'#'),
            'appointment_color' => ltrim($this->color['appointment'],'#'),
            'meal_color' => ltrim($this->color['meal'],'#'),
        ]);
        $this->dispatchBrowserEvent('calendar:render',['event'=> true]);
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The color has been successfully updated",]);
    }

    public function resetEvent()
    /**
    Reset the event details and state variables.
     */
    {
        $this->dispatchBrowserEvent('calendar:render',['event'=> true]);
        $this->showSection = false;
        $this->sectionEvent = null;
        $this->isEvent = null;
        $this->taskMembers = null;
        $this->mealMembers = null;
    }

    public function render()
    {
        return view('livewire.user.schedule')
            ->layout('layouts.staff-management', [
                'description' => 'View Schedule',
                'title' => 'Schedule',
            ]);
    }
}
