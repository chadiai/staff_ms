<?php

// Managing the invoices is within the admins authority
namespace App\Http\Livewire\Admin;

// Getting all necessary resources
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use Storage;

class Invoices extends Component
{
    // Pagination
    use WithPagination;

    // Defining necessary properties:

    // Invoices per page, loading message, order criteria, and order direction properties
    public $perPage = 5;
    public $loading = 'Please wait...';
    public $orderBy = 'title';
    public $orderAsc = true;

    // Categories properties
    public $subCategory;
    public $superCategory;
    public $allCategories;
    public $superCategories;

    // Filter properties
    public $title;
    public $invoiceNbr;
    public $paid;
    public $isArchived = false;

    // Show properties
    public $showModal = false;
    public $showDescriptionModal = false;

    // Event listeners for deleting/archiving
    protected $listeners = [
        'soft-delete-invoice' => 'SoftDeleteInvoice',
        'hard-delete-invoice' => 'PermanentDeleteInvoice',
    ];

    // newInvoice to be used when editing
    public $newInvoice = [
        'id' => null,
        'category_id' => null,
        'sup_category_id' => null,
        'sub_category_id' => null,
        'submitting_user_id' => null,
        'title' => null,
        'number' => null,
        'submission_date' => null,
        'due_date' => null,
        'amount' => null,
        'url' => null,
        'is_paid' => null,
    ];

    // showInvoice to be used when displaying extra information on a chosen invoice (when clicking the title)
    public $shownInvoice = [
        'submitting_user_first_name' => null,
        'submitting_user_last_name' => null,
        'title' => null,
        'submission_date' => null,
    ];

    // Validation rules
    protected function rules()
    {
        return [
            'newInvoice.title' => 'required|regex:/^[^<>]+$/',
            'newInvoice.due_date' => 'required|after_or_equal:newInvoice.submission_date',
            'newInvoice.sup_category_id' => 'required',
            'newInvoice.amount' => 'required',
            'newInvoice.url' => 'required',
            'newInvoice.is_paid' => 'required',
        ];
    }

    // Custom validation attributes
    protected $validationAttributes = [
        'newInvoice.title' => 'title',
        'newInvoice.url' => 'uploaded file',
        'newInvoice.submission_date' => 'invoice submission date',
        'newInvoice.sup_category_id' => 'category',
        'newInvoice.due_date' => 'invoice due date',
        'newInvoice.amount' => 'invoice amount',
    ];


    // set/reset $newInvoice and validation
    public function setNewInvoice(Invoice $invoice = null)
    {
        $this->resetErrorBag();
        if ($invoice) {
            // Check whether the category id of the invoice has a super category id in the database
            // If so, then assign the category as a sub category, and that super category id as the super category
            if ($invoice->category->super_category_id != null) {
                $this->newInvoice['sup_category_id'] = $invoice->category->super_category->id;
                $this->newInvoice['sub_category_id'] = $invoice->category->id;
            } //else set the category as a super category and the sub category is null
            else {
                $this->newInvoice['sup_category_id'] = $invoice->category->id;
                $this->newInvoice['sub_category_id'] = null;
            }
            $this->newInvoice['id'] = $invoice->id;
            $this->newInvoice['category_id'] = $invoice->category_id;
            $this->newInvoice['submitting_user_id'] = $invoice->submitting_user_id;
            $this->newInvoice['title'] = $invoice->title;
            $this->newInvoice['number'] = $invoice->number;
            $this->newInvoice['submission_date'] = $invoice->submission_date;
            $this->newInvoice['due_date'] = $invoice->due_date;
            $this->newInvoice['amount'] = $invoice->amount;
            $this->newInvoice['url'] = $invoice->url;
            $this->newInvoice['is_paid'] = $invoice->is_paid;
        } else {
            $this->reset('newInvoice');
        }
        // Show the pop-up modal for editing
        $this->showModal = true;
    }

    // This method that gets called when clicking on the update button of the pop-up modal to edit an invoice
    // It updates the invoice and triggers a toast message when successful
    public function updateInvoice(Invoice $invoice = null)
    {
        // Validation
        $this->validate();

        // Updating the category based on the chosen category
        if ($this->newInvoice['sup_category_id'] == null) {
            $this->newInvoice['category_id'] = null;
        } else if (is_null($this->newInvoice['sub_category_id'])) {
            $this->newInvoice['category_id'] = $this->newInvoice['sup_category_id'];
        } else if ($this->newInvoice['sub_category_id'] == -1) {
            $this->newInvoice['category_id'] = $this->newInvoice['sup_category_id'];
        } else {
            $this->newInvoice['category_id'] = $this->newInvoice['sub_category_id'];
        }

        // Updating the attribute values of the invoice
        $invoice->update([
            'id' => $this->newInvoice['id'],
            'category_id' => $this->newInvoice['category_id'] == -1 ? null : $this->newInvoice['category_id'],
            'submitting_user_id' => $this->newInvoice['submitting_user_id'],
            'title' => $this->newInvoice['title'],
            'number' => $this->newInvoice['number'],
            //'submission_date' => $this->newInvoice['submission_date'],
            'due_date' => $this->newInvoice['due_date'],
            'amount' => $this->newInvoice['amount'],
            'url' => $this->newInvoice['url'],
            'is_paid' => (bool)$this->newInvoice['is_paid'],
        ]);
        // Close the modal
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The invoice <b><i>{$invoice->title}</i></b> has been updated",
        ]);
    }

    // This method that gets called when clicking on archive invoice
    // It implements soft delete and triggers a toast message when successful
    public function SoftDeleteInvoice(Invoice $invoice)
    {
        // Soft delete is done by simply updating the deleted_at column with the current time
        // The deleted_at column is rendered in the database when using $table->softDeletes(); We don't make it manually
        $invoice->update(['deleted_at' => now()]);
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The invoice <b><i>{$invoice->title}</i></b> has been archived",
        ]);
        // Resetting the page is necessary so the table rows or cards are updated accordingly with pagination
        $this->resetPage();
    }

    // This method gets called when clicking on the delete (trash) icon
    // It implements permanent hard delete for invoices and triggers a toast message when successful
    public function PermanentDeleteInvoice($invoice)
    {
        // withTrashed is necessary to include the archived invoices since they are considered trashed
        $deletedInvoice = Invoice::withTrashed()->find($invoice['id']);
        // Force delete the invoice. This is the way to hard delete when implementing soft delete
        $deletedInvoice->forceDelete();
        // Remove the invoice file form the disk
        Storage::disk('LSTdisk')->delete($deletedInvoice['url']);
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The invoice <b><i>{$deletedInvoice->title}</i></b> has been deleted",
        ]);
    }

    // This method gets called when clicking on the un-archive (arrow) icon
    // It implements restoration (undo) for archived invoices and triggers a toast message when successful
    // It takes the id of the chosen invoice to be restored as a parameter
    public function restoreInvoice($invoiceId)
    {
        // withTrashed is necessary to include the archived invoices since they are considered trashed
        $invoice = Invoice::withTrashed()->findOrFail($invoiceId);
        $invoice->restore();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The invoice <b><i>{$invoice->title}</i></b> has been restored"
        ]);
    }

    // This method gets called when clicking on the download icon
    // Downloading the invoice
    public function downloadInvoice($id)
    {
        // Identify the chosen invoice
        $invoice_uploads = DB::table('invoices')->where('id', $id)->first();
        // Get the url path of where it is stored
        $pathToInvoice = storage_path("app/StaffManagementDocuments/{$invoice_uploads->url}");
        // Download the invoice at that path
        return \Response::download($pathToInvoice);
    }

    // This method gets called when clicking on the title of an invoice in the table (big screens only)
    public function setDescription(Invoice $invoice)
    {
        // Set the values of shownInvoice's attributes
        $this->shownInvoice['submitting_user_first_name'] = $invoice->user->first_name;
        $this->shownInvoice['submitting_user_last_name'] = $invoice->user->last_name;
        $this->shownInvoice['title'] = $invoice->title;
        $this->shownInvoice['submission_date'] = $invoice->submission_date;

        // Show the description modal
        $this->showDescriptionModal = true;
    }

    // Mount method for the categories
    // We need the different categories for the dropdowns. We get them once with the mount method
    public function mount()
    {
        // all categories are shown in the dropdowns of the filter section
        $this->allCategories = Category::get();
        // super categories for the edit modal dropdown
        $this->superCategories = Category::where('super_category_id', null)->get();
    }

    //  resetting the sub category when changing the super category in the edit modal
    public function resetSubCategory()
    {
        $this->subCategory = null;
        $this->newInvoice['sub_category_id'] = null;
    }

    // Render function for the view
    public function render()
    {
        // We use queries for filtering
        // To start get the invoices with their user relationships, order them by the chosen criteria, and implement the search done using a scope
        $query = Invoice::with('user')
            ->with('category')
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->searchTitleNumber($this->title);

        // Filtering based on the values chosen in the dropdowns: (updating the query)...

        // If a category is chosen and the chosen payment status is true (paid)
        if (($this->subCategory || $this->superCategory) && $this->paid) {
            // If only a super category is chosen
            if ($this->superCategory && !$this->subCategory) {
                // Get the ids of all invoices with that super category regardless of the sub category which is technically the category_id if set
                $ids = array_column(Category::where('super_category_id', '=', $this->superCategory)->get()->toArray(), 'id');
                // Get the paid invoices with that super category
                $query->where('is_paid', $this->paid == 'true')
                    ->where(function ($q) use ($ids) {
                        $q->whereIn('category_id', $ids)
                            ->orWhere('category_id', '=', $this->superCategory);
                    });
            } // If both the super and sub category are set
            elseif ($this->superCategory && $this->subCategory) {
                // Get the paid invoices with the sub category as their category
                $query->whereCategoryId($this->subCategory)
                    ->where('is_paid', $this->paid == 'true');
            } // If only the sub category is set
            elseif (!$this->superCategory && $this->subCategory) {
                // Get the paid invoices with the sub category as their category (because it is the final category)
                $query->whereCategoryId($this->subCategory)
                    ->where('is_paid', $this->paid == 'true');
            }
            // If a category is chosen and the chosen payment status is false (unpaid)
        } elseif (($this->subCategory || $this->superCategory) && !$this->paid) {
            // Same as before...
            if ($this->superCategory && !$this->subCategory) {
                $ids = array_column(Category::where('super_category_id', '=', $this->superCategory)->get()->toArray(), 'id');

                $query->whereIn('category_id', $ids)
                    ->orWhere('category_id', '=', $this->superCategory);
            } elseif ($this->superCategory && $this->subCategory) {
                $query->whereCategoryId($this->subCategory);
            } elseif (!$this->superCategory && $this->subCategory) {
                $query->whereCategoryId($this->subCategory);
            }
        } elseif (!$this->subCategory && !$this->superCategory && $this->paid) {
            $query->where('is_paid', $this->paid == 'true');
        }

        // For filtering the invoices based on archive status
        if ($this->isArchived) {
            $query->withTrashed()->whereNotNull('deleted_at');
        } else {
            $query->whereNull('deleted_at');
        }

        // Getting the invoices from the query and pagination
        $invoices = $query->paginate($this->perPage);

        // Return the view with the invoices
        return view('livewire.admin.invoices', compact('invoices'))
            ->layout('layouts.staff-management', [
                'description' => 'Manage your invoices',
                'title' => 'Invoices',
            ]);
    }

    // Resort (order) the invoices by the given column (the chosen criteria)
    public function resort($column)
    {
        $this->orderBy = $column;
    }

    // Order direction for the invoices
    public function setOrderDirection($value)
    {
        $this->orderAsc = (bool)$value;
        $this->resort($this->orderBy);
    }


}
