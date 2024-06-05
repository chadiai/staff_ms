<?php

// Managing the files is within the admins authority
namespace App\Http\Livewire\Admin;

// Getting all necessary resources
use App\Models\Category;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Image;
use Storage;

//use Illuminate\Database\Eloquent\SoftDeletes;

class Files extends Component
{
    // File uploading and Pagination
    use WithFileUploads;
    use WithPagination;

    // Filees per page, loading message, order criteria, and order direction variables
    public $perPage = 5;
    public $loading = 'Please wait...';
    public $orderBy = 'name';
    public $orderAsc = true;

    // Categories properties
    public $subCategory;
    public $superCategory;
    public $allCategories;
    public $superCategories;

    // Filter and new file properties
    public $name;
    public $superCategoryId = -1;
    public $subCategoryId = null;
    public $confidential = true;
    public $notConfidential = false;
    public $fileName;
    public $iteration;
    public $isArchived = false;

    // Show properties
    public $showModal = false;
    public $showFileSection = false;

    // Property to store original url in case user decides to not change the file anymore
    public $originalUrl;

    // Event listeners for deleting/archiving
    protected $listeners = [
        'soft-delete-file' => 'SoftDeleteFile',
        'hard-delete-file' => 'PermanentDeleteFile',
    ];

    // newFile to be used when creating/editing
    public $newFile = [
        'id' => null,
        'category_id' => null,
        'user_id' => null,
        'sup_category_id' => null,
        'sub_category_id' => null,
        'upload_date' => null,
        'name' => null,
        'url' => null,
        'is_confidential' => false,
    ];

    // Validation rules
    protected function rules(): array
    {
        return [
            'newFile.name' => 'required|regex:/^[^<>]+$/',
            'newFile.url' => 'required',
            'newFile.sup_category_id' => 'required',
            'newFile.is_confidential' => 'required',
        ];
    }

    // Custom validation attributes
    protected $validationAttributes = [
        'newFile.name' => 'name',
        'newFile.url' => 'uploaded file',
        'newFile.sup_category_id' => 'category',
        'newFile.is_confidential' => 'confidential status',
    ];

    // set/reset $newFile and validation
    public function setNewFile(File $file = null)
    {
        $this->resetErrorBag();
        if ($file) {
            // Check whether the category id of the file has a super category id in the database
            // If so, then assign the category as a sub category, and that super category id as the super category
            if ($file->category->super_category_id != null) {
                $this->newFile['sup_category_id'] = $file->category->super_category->id;
                $this->newFile['sub_category_id'] = $file->category->id;
            } else {
                $this->newFile['sup_category_id'] = $file->category->id;
                $this->newFile['sub_category_id'] = null;
            }
            $this->newFile['id'] = $file->id;
            $this->newFile['name'] = $file->name;
            $this->newFile['url'] = $file->url;
            $this->newFile['category_id'] = $file->category_id;
            $this->newFile['is_confidential'] = $file->is_confidential;
        } else {
            $this->reset('newFile');
        }
        // Show the pop-up modal for creating/editing
        $this->showModal = true;
    }

    // Set category of file based on chosen one from the dropdown
    public function setCategory(){
        if ($this->newFile['sup_category_id'] == null) {
            $this->newFile['category_id'] = null;
        } else if (is_null($this->newFile['sub_category_id'])) {
            $this->newFile['category_id'] = $this->newFile['sup_category_id'];
        } else if ($this->newFile['sub_category_id'] == -1) {
            $this->newFile['category_id'] = $this->newFile['sup_category_id'];
        } else {
            $this->newFile['category_id'] = $this->newFile['sub_category_id'];
        }
    }

    // Checking the extension of the uploaded file. Pdf, docx, excel, and images are accepted
    // We save the file to the disk by calling the method to do so
    public function setFileURL(){
        if ($this->newFile['url']) {
            $fileType = $this->newFile['url']->getClientOriginalExtension();
            if ($fileType === 'pdf') {
                $this->fileName = $this->newFile['url']->storeAs('Documents/Files', $this->newFile['name'] . date("YmdHis") . '.pdf', 'LSTdisk');
            } elseif ($fileType === 'docx') {
                $this->fileName = $this->newFile['url']->storeAs('Documents/Files', $this->newFile['name'] . date('YmdHis') . '.docx', 'LSTdisk');
            } elseif ($fileType === 'xlsx') {
                $this->fileName = $this->newFile['url']->storeAs('Documents/Files', $this->newFile['name'] . date('YmdHis') . '.xlsx', 'LSTdisk');
            } else {
                $file = Image::make($this->newFile['url']->getRealPath())->encode('jpg', 75);
                $this->saveToDisk($file, $fileType);
            }
            // Here it gets stored in the created disk LSTdisk (for security)
            $files = Storage::disk('LSTdisk')->files('livewire-tmp');
            foreach ($files as $file) {
                Storage::disk('LSTdisk')->delete($file);
            }
        }
    }

    // Create new invoice
    public function createFile()
    {
        // Assigning the category for the new file based on the chosen category
        $this->setCategory();

        // Assigning confidentiality status
        if (!isset($this->newFile['is_confidential'])) {
            $this->newFile['is_confidential'] = false;
        }

        // Setting the file's url attribute
        $this->setFileURL();

        // Validation
        $this->validate();

        // Creating the new file with the url set to the value of fileName
        // If confidentiality status is not set, default is always left on false (on the toggle in the blade file too)
        File::create([
            'category_id' => $this->newFile['category_id'],
            'uploading_user_id' => auth()->user()->id,
            'upload_date' => date('Y-m-d H:i:s'),
            'name' => $this->newFile['name'],
            'url' => $this->fileName,
            'is_confidential' => !isset($this->newFile['is_confidential']) ? false : (bool)$this->newFile['is_confidential'],
        ]);

        // Resetting properties
        $this->reset('newFile');
        $this->reset('superCategoryId');
        $this->reset('subCategoryId');
        $this->iteration++;
        $this->resetErrorBag();
        $this->showModal = false;
        // Toast message
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => 'File is uploaded',
        ]);
    }

    // This method is responsible for showing or hiding the Choose File section in the edit modal based on what the user decides
    public function toggleFileSection()
    {
        $this->showFileSection = !$this->showFileSection;
        // In case the user wants to change the file, the value of the old url gets stored just in case they change their mind
        $this->originalUrl = $this->newFile['url'];
        // The url is set then to null, so we can use the client origin function
        $this->newFile['url'] = null;
    }

    // This method is triggered when the user decides to not change the file by clicking the button "Keep original file"
    public function cancelNewFile()
    {
        // The url of the file is set back to the original one
        $this->newFile['url'] = $this->originalUrl;
        // The section for changing the file is closed
        $this->showFileSection = false;
    }

    // This method is triggered when clicking the update button with changing the original file
    // The philosophy here is that we delete the old file and create a new one
    public function updateWithNewFile(File $file = null)
    {
        // Validation
        $this->validate();

        // We delete the original one from the storage (from the disk)
        Storage::disk('LSTdisk')->delete($this->originalUrl);

        // Setting the category
        $this->setCategory();

        // Setting the file's url attribute
        $this->setFileURL();

        // Creating the newly updated file
        File::create([
            'category_id' => $this->newFile['category_id'],
            'uploading_user_id' => auth()->user()->id,
            'upload_date' => date('Y-m-d H:i:s'),
            'name' => $this->newFile['name'],
            'url' => $this->fileName,
            'is_confidential' => $this->newFile['is_confidential'],
        ]);

        // Deleting the old file from our records (from the table)
        // We use force delete not delete, because otherwise it won't remove it from the table. No need for trashed because only unarchived files can be edited
        $deletedFile = File::find($this->newFile['id']);
        $deletedFile->forceDelete();

        // Close the modal and choose file section
        $this->showModal = false;
        $this->showFileSection = false;

        // Toast message on success
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The file <b><i>{$this->newFile['name']}</i></b> has been updated",
        ]);

    }

    // This method is triggered when clicking the update button without changing the original file
    // Implements normal update
    public function updateFile(File $file = null)
    {
        // Validation
        $this->validate();

        // Resetting
        $this->resetErrorBag();
        $this->reset('fileName');

        // Setting the category
        $this->setCategory();

        // Update the file
        $file->update([
            'id' => $this->newFile['id'],
            'category_id' => $this->newFile['category_id'] == -1 ? null : $this->newFile['category_id'],
            'name' => $this->newFile['name'],
            'is_confidential' => (bool)$this->newFile['is_confidential'],
        ]);
        // Close the modal
        $this->showModal = false;
        // Toast message on success
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The file <b><i>{$file->name}</i></b> has been updated",
        ]);
    }

    // This method that gets called when clicking on archive file
    // It implements soft delete and triggers a toast message when successful
    public function SoftDeleteFile(File $file)
    {
        // Soft delete is done by simply updating the deleted_at column with the current time
        // The deleted_at column is rendered in the database when using $table->softDeletes(); We don't make it manually
        $file->update(['deleted_at' => now()]);
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The file <b><i>{$file->name}</i></b> has been archived",
        ]);
        // Resetting the page is necessary so the table rows or cards are updated accordingly with pagination
        $this->resetPage();
    }

    // This method gets called when clicking on the delete (trash) icon
    // It implements permanent hard delete for files and triggers a toast message when successful
    public function PermanentDeleteFile($file)
    {
        // withTrashed is necessary to include the archived files since they are considered trashed
        $deletedFile = File::withTrashed()->find($file['id']);
        // Force delete the file. This is the way to hard delete when implementing soft delete
        $deletedFile->forceDelete();
        // Remove the file form the disk
        Storage::disk('LSTdisk')->delete($deletedFile['url']);
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The file <b><i>{$deletedFile->name}</i></b> has been deleted",
        ]);
    }

    // This method gets called when clicking on the un-archive (arrow) icon
    // It implements restoration (undo) for archived files and triggers a toast message when successful
    // It takes the id of the chosen file to be restored as a parameter
    public function restoreFile($fileId)
    {
        // withTrashed is necessary to include the archived files since they are considered trashed
        $file = File::withTrashed()->findOrFail($fileId);
        $file->restore();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The file <b><i>{$file->name}</i></b> has been restored"
        ]);
    }

    // This method gets called when clicking on the download icon
    // Downloading the file
    public function downloadFile($id)
    {
        // Identify the chosen file
        $file_uploads = DB::table('files')->where('id', $id)->first();
        // Get the url path of where it is stored
        $pathToFile = storage_path("app/StaffManagementDocuments/{$file_uploads->url}");
        // Download the file at that path
        return \Response::download($pathToFile);
    }

    // Mount method for the categories
    // We need the different categories for the dropdowns. We get them once with the mount method
    public function mount()
    {
        // all categories are shown in the dropdowns of the filter section
        $this->allCategories = Category::get();
        // super categories for the new/edit modal dropdown
        $this->superCategories = Category::where('super_category_id', null)->get();
    }

    //  resetting the sub category when changing the super category in the edit modal
    public function resetSubCategory()
    {
        $this->subCategory = null;
        $this->newFile['sub_category_id'] = null;
    }

    // Render function for the view
    public function render()
    {
        // We use queries for filtering and ordering

        $categories = Category::where('super_category_id', '=', $this->superCategoryId)->get();

        $query = File::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->searchName($this->name);

        if ($this->superCategory && !$this->subCategory) {
            $ids = array_column(Category::where('super_category_id', '=', $this->superCategory)->get()->toArray(), 'id');

            $query->where(function ($q) use ($ids) {
                $q->whereIn('category_id', $ids)
                    ->orWhere('category_id', '=', $this->superCategory);
            });
        } elseif ($this->superCategory && $this->subCategory) {
            $query->Where('category_id', '=', $this->subCategory);
        } elseif (!$this->superCategory && $this->subCategory) {
            $query->Where('category_id', '=', $this->subCategory);
        }

        // Only the user with super admin rights can see the confidential files
        if (auth()->user()->isAdmin()) {
            $query->where('is_confidential', false);
        }

        // For filtering the files based on archive status
        if ($this->isArchived) {
            $query->withTrashed()->whereNotNull('deleted_at');
        } else {
            $query->whereNull('deleted_at');
        }

        // Getting the files from the query and pagination
        $files = $query->paginate($this->perPage);

        return view('livewire.admin.files', compact('files', 'categories'))
            ->layout('layouts.staff-management', [
                'description' => 'Manage your files',
                'title' => 'Files',
            ]);
    }

    // Resort (order) the files by the given column (the chosen criteria)
    public function resort($column)
    {
        $this->orderBy = $column;
    }

    // Order direction for the files
    public function setOrderDirection($value)
    {
        $this->orderAsc = (bool)$value;
    }

    // Save to disk method. This method takes the file and its type as parameters
    private function saveToDisk($file, $fileType)
    {
        // The file name is set as its name (entered by the user), the date and time it was uploaded, and its extension
        $this->fileName = 'Documents/Files/' . $this->newFile['name'] . date("YmdHis") . '.' . $fileType;
        // We then save the file in the LSTdisk for security
        Storage::disk('LSTdisk')->put($this->fileName, $file, 'public');
    }
}
