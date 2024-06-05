<?php

namespace App\Http\Livewire\User;



use App\Models\AuthorizationType;
use App\Models\FAQ;
use Livewire\Component;
use Livewire\WithPagination;

class FAQs extends Component
{
    use WithPagination;

    // public properties
    public $perPage = 5;
    public $loading = 'Please wait...';
    public $showQuestionModal = false;
    public $name;
    public $newQuestion =[
        'id' => null,
        'question' => null,
        'answer' => null,
        'authorization_type_id' => null,
    ];
    public $authorizations;

    // listeners for events
    protected $listeners = [
        'delete-question' => 'deleteQuestion',
    ];

    // rename validation attributes for clarity
    protected $validationAttributes = [
        'newQuestion.question' => 'question',
        'newQuestion.answer' => 'answer',
        'newQuestion.authorization_type_id' => 'who is this question for',
    ];

    // validation rules
    protected function rules()
    {
        return [

            'newQuestion.question' => 'required|regex:/^[^<>]+$/',
            'newQuestion.answer' => 'required|regex:/^[^<>]+$/',

        ];
    }

    // set information for the question variable
    public function setNewQuestion(FAQ $question = null)
    {
        $this->resetErrorBag();
        if ($question) {
            $this->newQuestion['id'] = $question->id;
            $this->newQuestion['question'] = $question->question;
            $this->newQuestion['answer'] = $question->answer;
            $this->newQuestion['authorization_role_id'] = $question->authorization_role_id;
                    } else {
            $this->reset('newQuestion');
        }
        $this->showQuestionModal = true;

    }

    // reset the question variable
    public function resetNewQuestion()
    {
        $this->reset('newQuestion');
        $this->resetErrorBag();
    }

    // create a new FAQ
    public function createQuestion()
    {
        $this->validate();
        $question = FAQ::create([
            'question' => $this->newQuestion['question'],
            'answer' => $this->newQuestion['answer'],
            'authorization_type_id' => $this->newQuestion['authorization_type_id'] ?? null,
        ]);

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The question <b><i>{$question->question}</i></b>  has been created",
        ]);

        $this->showQuestionModal = false;
    }

    public function updated($propertyName, $propertyValue)
    {
        // dump($propertyName, $propertyValue);
        $this->resetPage();
    }

    // update an existing FAQ
    public function updateQuestion(FAQ $faq)
    {
        $this->validate();
        $data = [
            'question' => $this->newQuestion['question'],
            'answer' => $this->newQuestion['answer'],
            'authorization_type_id' => $this->newQuestion['authorization_type_id'] ?? null,

        ];

        $faq->update($data);

        $this->showQuestionModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The question <b><i>{$faq->question}</i></b> has been updated",
        ]);
    }

    // delete an FAQ
    public function deleteQuestion(FAQ $question)
    {

            $question->delete();
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'success',
                'html' => "The question <b><i>{$question->question}</i></b> has been deleted",
            ]);


    }

    // mount the authorization types
    public function mount()
    {
        $this->authorizations = AuthorizationType::all();
    }

    // render the view with the questions by ordering them by question and paginating them
    public function render()
    {
        $questions = FAQ::where('question', 'like', "%{$this->name}%")
            ->get();

        return view('livewire.user.f-a-qs', compact('questions'))
            ->layout('layouts.staff-management', [
                'description' => 'FAQs',
                'title' => 'FAQs',
            ]);
    }
}
