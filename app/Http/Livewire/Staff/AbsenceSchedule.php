<?php

namespace App\Http\Livewire\Staff;

use App\Models\ScheduledAbsence;
use Livewire\Component;

class AbsenceSchedule extends Component
{
    public $showSection = false;
    public $sectionEvent = null;
    public $showEventModal = false;
    public $newSectionEvent =
        [
            'id' => null,
            'start_date_time' => null,
            'end_date_time' => null,
            'user_id' => null,
            'reason_of_absence' => null,
        ];
    public $loading = 'Please wait...';
    public bool $showModal;

    protected $listeners = ['setEvent' => 'setEvent', 'delete-absence' => 'deleteSectionEvent'];

    //validation rules
    protected function rules()
    {
        return [
            'newSectionEvent.start_date_time' => 'required',
            'newSectionEvent.end_date_time' => 'required|after:newSectionEvent.start_date_time',
            'newSectionEvent.reason_of_absence' => 'required|regex:/^[^<>]+$/',
        ];
    }

    protected $validationAttributes = [
        'newSectionEvent.start_date_time' => 'start date',
        'newSectionEvent.end_date_time' => 'end date',
        'newSectionEvent.reason_of_absence' => 'reason',

    ];


    public function setEvent($type, $id)
    /**
    Set the details of a scheduled absence event.
    @param int $type The type of event, type is not used for this function (absence has 1 type)
    @param int $id The ID of the event.
     */
    {
        $this->dispatchBrowserEvent('calendar:render',['event'=> false]);
        $this->showSection = true;
        $this->showEventModal = true;
        $this->sectionEvent = ScheduledAbsence::where('id', $id)->first();
    }

    public function resetEvent()
    /**
    Reset the event details and state variables.
     */
    {
        $this->dispatchBrowserEvent('calendar:render',['event'=> false]);
        $this->showSection = false;
        $this->sectionEvent = null;
    }

    public function setNewSectionEvent(ScheduledAbsence $sectionEvent)
    /**
    Set the details of a new section event for editing.
    @param ScheduledAbsence $sectionEvent The section event to set.
     */
    {
        $this->dispatchBrowserEvent('calendar:render',['event'=> false]);
        $this->resetErrorBag();

        $this->newSectionEvent['id'] = $sectionEvent->id;
        $this->newSectionEvent['start_date_time'] = $sectionEvent->start_date_time;
        $this->newSectionEvent['end_date_time'] = $sectionEvent->end_date_time;
        $this->newSectionEvent['reason_of_absence'] = $sectionEvent->reason_of_absence;

        $this->showModal = true;
    }

    public function resetSectionEvent()
    /**
    Reset the section event details and state variables.
     */
    {
        $this->dispatchBrowserEvent('calendar:render',['event'=> false]);
        $this->reset('newSectionEvent');
        $this->showModal = false;
    }

    public function updateSectionEvent(ScheduledAbsence $sectionEvent)
    /**
    Update the details of a section event.
    @param ScheduledAbsence $sectionEvent The section event to update.
     */
    {
        $this->validate();
        $sectionEvent->update([
            'id' => $this->newSectionEvent['id'],
            'start_date_time' => $this->newSectionEvent['start_date_time'],
            'end_date_time' => $this->newSectionEvent['end_date_time'],
            'reason_of_absence' => $this->newSectionEvent['reason_of_absence'],
        ]);
        $this->reset('newSectionEvent');
        $this->dispatchBrowserEvent('calendar:render',['event'=> false]);
        $this->showModal = false;
        $this->showEventModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The scheduled absence has been updated",
        ]);
    }

    public function deleteSectionEvent(ScheduledAbsence $sectionEvent)
    /**
    Delete a section event.
    @param ScheduledAbsence $sectionEvent The section event to delete.
     */
    {
        $sectionEvent->delete();
        $absentUser = $sectionEvent->user->first_name . ' ' . $sectionEvent->user->last_name;
        $this->dispatchBrowserEvent('calendar:render',['event'=> false]);
        $this->showSection = false;
        $this->showEventModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The scheduled absence for <b><i>{$absentUser}</i></b> has been deleted",
        ]);
        $this->resetEvent();

    }



    public function render()
    {
        return view('livewire.staff.absence-schedule')
            ->layout('layouts.staff-management', [
                'description' => 'View absence schedule',
                'title' => 'Absence schedule',
            ]);
    }
}
