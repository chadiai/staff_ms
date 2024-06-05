<?php

namespace App\Http\Livewire\Cook;

use App\Models\Meal;
use App\Models\MealPlan;
use App\Models\MealSubscription;
use App\Models\MealType;
use Livewire\Component;
use Livewire\WithPagination;

class ScheduleMeals extends Component
{
    use WithPagination;

    public $meals;
    public $resortName = 0;
    //preloader
    public $loading = 'Please wait...';
    public $types;
    public $newMealSchedule = [
        'id' => null,
        'start_date_time' => null,
        'end_date_time' => null,
        'meal_id' => null,
        'meal_type_id' => null,
    ];
    public $perPage = 5;
    public $showModal = false;
    public $name;
    public $filter;
    public $date;
    public $mealType = '%';

    // sort properties
    public $orderBy = 'start_date_time';
    public $orderDesc = true;

    public $listeners = [
        'delete-meal-plan' => 'deleteMealPlan',
    ];

    // get all the types from the database
    public function mount()
    {
        $this->types = MealType::get();
    }

    // set/reset $newMealSchedule
    public function setNewMealSchedule(MealPlan $mealPlan = null)
    {
        $this->resetErrorBag();
        if ($mealPlan) {
            $this->newMealSchedule['id'] = $mealPlan->id;
            $this->newMealSchedule['start_date_time'] = $mealPlan->start_date_time;
            $this->newMealSchedule['end_date_time'] = $mealPlan->end_date_time;
            $this->newMealSchedule['meal_id'] = $mealPlan->meal_id;
            $this->newMealSchedule['meal_type_id'] = $mealPlan->meal_type_id;
        } else {
            $this->reset('newMealSchedule');

        }
        $this->showModal = true;
    }

    // validation rules
    protected function rules()
    {
        return [
            'newMealSchedule.meal_id' => 'required',
            'newMealSchedule.meal_type_id' => 'required',
            'newMealSchedule.start_date_time' => 'required',
            'newMealSchedule.end_date_time' => 'required|after:newMealSchedule.start_date_time',
        ];
    }

    protected $validationAttributes = [
        'newMealSchedule.start_date_time' => 'start date',
        'newMealSchedule.end_date_time' => 'end date',
        'newMealSchedule.meal_id' => 'meal',
        'newMealSchedule.meal_type_id' => 'meal type',

    ];

    //make a new meal plan, close the modal and show a swal toast notification
    public function createMealPlan()
    {
        $this->validate();
        $mealplan = MealPlan::create([
            'start_date_time' => $this->newMealSchedule['start_date_time'],
            'end_date_time' => $this->newMealSchedule['end_date_time'],
            'meal_id' => $this->newMealSchedule['meal_id'],
            'meal_type_id' => $this->newMealSchedule['meal_type_id'],
        ]);
        $this->showModal = false;
        $this->reset("filter");
        $this->reset('newMealSchedule');
        $start_time = date('H:i', strtotime($mealplan->start_date_time));
        $end_time = date('H:i', strtotime($mealplan->end_date_time));
        $time_range = "from $start_time until $end_time";
        //toast message
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "<b><i>{$mealplan->meal->name}</i></b> is planned on <b><i>" . date('d/m/Y', strtotime($mealplan->start_date_time)) . "</i></b>  {$time_range}",
        ]);
    }

    //update a meal plan, close the modal and show a swal toast message
    public function updateMealPlan(MealPlan $mealSchedule)
    {
        $this->validate();
        $mealSchedule->update([
            'start_date_time' => $this->newMealSchedule['start_date_time'],
            'end_date_time' => $this->newMealSchedule['end_date_time'],
            'meal_id' => $this->newMealSchedule['meal_id'],
            'meal_type_id' => $this->newMealSchedule['meal_type_id'],
        ]);
        $this->showModal = false;
        $this->reset("filter");
        //toast message
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "Meal plan: <b><i>{$mealSchedule->meal->name}</i></b> is updated",
        ]);
    }

    // delete a mealPlan and show a swal toast notification
    // if there are people subscribed to the meal it will show a notification that it cannot be deleted
    public function deleteMealPlan(MealPlan $mealSchedule)
    {
        $subscriptions = MealSubscription::where('meal_plan_id', $mealSchedule->id)->get();
        if (!$subscriptions->isEmpty()) {
            $this->dispatchBrowserEvent('swal:confirm', [
                'icon' => 'error',
                'title' => 'Error',
                'showCancelButton' => false,
                'confirmButtonText' => 'Okay',
                'text' => "The meal plan for <b><i>{$mealSchedule->meal->name}</i></b> cannot be deleted because there are one or more people subscribed to it.",
            ]);

        } else {
            $mealSchedule->delete();
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'success',
                'html' => "The meal plan has been deleted",
            ]);
        }
        $this->resetPage();
    }

    // reset the pagination
    public function updated($propertyName, $propertyValue)
    {
        // reset if the $name, $mealType or $perPage property has changed (updated)
        if (in_array($propertyName, ['name', 'perPage', 'mealType']))
            $this->resetPage();
    }

    //add the meal types, the filtered meals and meal plans with pagination to the view
    public function render()
    {
        $allTypes = MealType::get();
        $filterMeals = Meal::where('name', 'like', "%{$this->filter}%")->get();

        $mealPlans = MealPlan::with('meal')
            ->orderBy($this->orderBy, $this->orderDesc ? 'desc' : 'asc')
            ->where(function ($query) {
                $query->where('start_date_time', 'like', "%{$this->name}%")
                    ->orWhereHas('meal', function ($subquery) {
                        $subquery->where('name', 'like', "%{$this->name}%");
                    });
            })
            ->where('meal_type_id', 'like', $this->mealType)
            ->paginate($this->perPage);

        return view('livewire.cook.schedule-meals', compact('mealPlans', 'allTypes', 'filterMeals'))
            ->layout('layouts.staff-management', [
                'description' => 'Schedule your meals',
                'title' => 'Meal plans',
            ]);
    }
}
