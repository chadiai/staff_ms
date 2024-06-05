<?php

namespace App\Http\Livewire\Staff;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;
use Image;
use Livewire\Component;
use Storage;
use Livewire\WithFileUploads;

class SubmitInvoice extends Component
{

    use WithFileUploads;

    public $superCategoryId = -1;
    public $subCategoryId = null;
    public $loading = 'Please wait...';
    public $iteration;
    public $fileName;
    public $newInvoice =
        [
            'id' => null,
            'title' => null,
            'number' => null,
            'dueDate' => null,
            'amount' => null,
            'category_id' => null,
            'url' => null,
        ];

    //validation rules
    protected function rules()
    {
        return [
            'newInvoice.title' => 'required|regex:/^[^<>]+$/',
            'newInvoice.number' => 'nullable|unique:invoices,number|regex:/^[^<>]+$/',
            'newInvoice.dueDate' => 'required|after_or_equal:newInvoice.submission_date',
            'newInvoice.category_id' => 'required',
            'newInvoice.amount' => 'required|numeric',
            'newInvoice.url' => 'required',
        ];
    }

    protected $validationAttributes = [
        'newInvoice.title' => 'title',
        'newInvoice.dueDate' => 'due date',
        'newInvoice.category_id' => 'category',
        'newInvoice.amount' => 'amount',
        'newInvoice.number' => 'invoice number',
        'newInvoice.url' => 'file',
        'newInvoice.submission_date'
    ];

    // submit a new invoice
    public function createInvoice()
    {
        $this->validate();
        if ($this->superCategoryId == -1) {
            $this->superCategoryId = null;
        }
        if (is_null($this->subCategoryId)) {
            $this->newInvoice['category_id'] = $this->superCategoryId;
        } else {
            $this->newInvoice['category_id'] = $this->subCategoryId;
        }
        // it will make a jpg of all files that are not pdf, you can also upload pdf
        if ($this->newInvoice['url']) {
            $fileType = $this->newInvoice['url']->getClientOriginalExtension();
            if ($fileType !== 'pdf') {
                $file = Image::make($this->newInvoice['url']->getRealPath())->encode('jpg', 75);
                $this->saveToDisk($file, $fileType);
            } else {
                $this->fileName = $this->newInvoice['url']->storeAs('Documents/Invoices', $this->newInvoice['title'] . date("YmdHis") . '.pdf', 'LSTdisk');
            }
            $files = Storage::disk('LSTdisk')->files('livewire-tmp');
            foreach ($files as $file) {
                Storage::disk('LSTdisk')->delete($file);
            }
        }


        Invoice::create([
            'title' => $this->newInvoice['title'],
            'number' => $this->newInvoice['number'],
            'amount' => $this->newInvoice['amount'],
            'due_date' => $this->newInvoice['dueDate'],
            'submitting_user_id' => auth()->user()->id,
            'url' => $this->fileName,
            'category_id' => $this->newInvoice['category_id'],
            'submission_date' => date('Y-m-d H:i:s'),
            'is_paid' => false,

        ]);

        $this->reset('newInvoice');
        $this->reset('superCategoryId');
        $this->reset('subCategoryId');
        $this->iteration++;
        $this->resetErrorBag();
        //toast message
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'Invoice is submitted',
        ]);
    }

    public function resetInvoice()
    {
        $this->reset('newInvoice');
        $this->reset('superCategoryId');
        $this->reset('subCategoryId');
        $this->iteration++;
        $this->resetErrorBag();
    }

    //add the super and sub categories to the view
    public function render()
    {
        $superCategories = Category::where('super_category_id', null)->get();
        $categories = Category::where('super_category_id', $this->superCategoryId)->get();

        return view('livewire.staff.submit-invoice', compact('categories', 'superCategories'))
            ->layout('layouts.staff-management', [
                'description' => 'Submit your invoice',
                'title' => 'Submit invoice',
            ]);
    }

    private function saveToDisk($file, $fileType)
    {
        $this->fileName = 'Documents/Invoices/' . $this->newInvoice['title'] . date("YmdHis") . '.' . $fileType;
        Storage::disk('LSTdisk')->put($this->fileName, $file, 'public');
    }
}
