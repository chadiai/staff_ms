<?php

namespace App\Http\Livewire\Staff;

use App\Http\Middleware\Authenticate;
use App\Models\ScheduledAbsence;
use http\Client\Curl\User;
use Livewire\Component;

class Absence extends Component
{
    public $newAbsence;
    public $staffMember = -1;
    public $loading = 'Please wait...';


    //validation rules
    protected function rules()
    {
        return [
            'newAbsence.startDateTime' => 'required',
            'newAbsence.endDateTime' => 'required|after:newAbsence.startDateTime',
            'newAbsence.reasonOfAbsence' => 'required',
        ];
    }

    protected $validationAttributes = [
        'newAbsence.startDateTime' => 'start date',
        'newAbsence.endDateTime' => 'end date',
        'newAbsence.reasonOfAbsence' => 'reason',

    ];


    public function setNewAbsence()
    {
        $this->resetErrorBag();
    }

    public function resetAbsence()
    {
        $this->reset('newAbsence');
        $this->reset('staffMember');
    }

    //create a new absence and show swal toast notification
    public function createAbsence()
    {
        $this->validate();
        if ($this->staffMember == -1) {
            $this->newAbsence['user_id'] = auth()->user()->id;
        } else {
            $this->newAbsence['user_id'] = $this->staffMember;
        }
        ScheduledAbsence::create([
            'start_date_time' => $this->newAbsence['startDateTime'],
            'end_date_time' => $this->newAbsence['endDateTime'],
            'reason_of_absence' => $this->newAbsence['reasonOfAbsence'],
            'user_id' => $this->newAbsence['user_id'],
        ]);
        $this->reset('newAbsence');
        $this->reset('staffMember');
        //toast message
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'Absence is declared',
        ]);
    }

    //add the staffmembers to the view
    public function mount()
    {
        $this->staffMembers = \App\Models\User::whereNotNull('staff_role_id')
            ->get();
    }

    //return the view
    public function render()
    {

        return view('livewire.staff.absence')
            ->layout('layouts.staff-management', [
                'description' => 'Manage your absence',
                'title' => 'Declare absence',
            ]);
    }
}
