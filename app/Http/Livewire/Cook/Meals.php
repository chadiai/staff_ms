<?php

namespace App\Http\Livewire\Cook;

use App\Models\Meal;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MealPlan;

class Meals extends Component
{

    use WithPagination;

    // public properties
    public $orderBy = 'name';
    public $orderAsc = true;
    public $newMeal;
    public $editMeal = ['id' => null, 'name' => null];
    public $name;
    public $perPage = 5;
    public $loading = 'Please wait...';

    // listeners to listen for events
    protected $listeners = [
        'delete-meal' => 'deleteMeal',
    ];

    // validation rules
    public function rules()
    {
        return [
            'newMeal' => 'required|min:3|unique:meals,name|regex:/^[^<>]+$/',
            'editMeal.name' => 'required|min:3|regex:/^[^<>]+$/|unique:meals,name,' . $this->editMeal['id'],
        ];
    }

    // rename validation attributes for clarity
    protected $validationAttributes = [
        'editMeal.name' => 'meal name',
    ];

    // validation messages
    protected $messages = [
        'newMeal.required' => 'Please enter a meal name.',
        'newMeal.min' => 'The new meal name must contains at least 3 characters.',
        'newMeal.unique' => 'This meal name already exists.',
        'editMeal.name.required' => 'Please enter a meal name.',
        'editMeal.name.min' => 'This meal name is too short (must be at least 3 characters).',
        'editMeal.name.unique' => 'This meal already exists.',
    ];

    // edit an existing meal
    public function editExistingMeal(Meal $meal)
    {
        $this->editMeal = [
            'id' => $meal->id,
            'name' => $meal->name,
        ];
    }

    // reset the newMeal variable
    public function resetNewMeal()
    {
        $this->reset('newMeal');
        $this->resetErrorBag();
    }

    // reset the editMeal property
    public function resetEditMeal()
    {
        $this->reset('editMeal');
        $this->resetErrorBag();
    }

    // edit an existing meal
    public function updateMeal(Meal $meal)
    {
        $this->validateOnly('editMeal.name');
        $oldName = $meal->name;
        $meal->update([
            'name' => trim($this->editMeal['name']),
        ]);
        $this->resetEditMeal();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The meal <b><i>{$oldName}</i></b> has been updated to <b><i>{$meal->name}</i></b>",
        ]);
    }

    // delete a meal
    public function deleteMeal(Meal $meal)
    {
        $mealPlans = MealPlan::where('meal_id', $meal->id)->get();
        if (!$mealPlans->isEmpty()) {
            $this->dispatchBrowserEvent('swal:confirm', [
                'icon' => 'error',
                'title' => 'Error',
                'showCancelButton' => false,
                'confirmButtonText' => 'Okay',
                'text' => "The meal <b><i>{$meal->name}</i></b> cannot be deleted because it is assigned to one or more meal plans.",
            ]);

        } else {
            $meal->delete();
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'success',
                'html' => "The meal <b><i>{$meal->name}</i></b> has been deleted",
            ]);
        }
        $this->resetPage();
    }

    // create a new meal
    public function createMeal()
    {
        $this->validateOnly('newMeal');


        $meal = Meal::create([
            'name' => trim($this->newMeal),
        ]);
        $this->resetNewMeal();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'Meal <b>' . $meal->name . '</b> has been created successfully',
        ]);
        $this->resetErrorBag();
    }

    // resort meals by clicking on the column asc->desc/desc->asc
    public function resort($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderAsc = true;
        }
        $this->orderBy = $column;
    }

    // render the view with the meals, order by the meal name
    public function render()
    {
        $meals = Meal::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->searchName($this->name)
            ->paginate($this->perPage);

        return view('livewire.cook.meals', compact('meals'))
            ->layout('layouts.staff-management', [
                'description' => 'Manage your meals',
                'title' => 'Manage meals',
            ]);
    }
}
