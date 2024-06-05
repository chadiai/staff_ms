<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Model;

class Categories extends Component
{
    use WithPagination;
    public $supercategories;
    public $subcategories;
    public $subcat;

    public $search;
    public $perPage = 5;
    public $loading = 'Please wait...';
    public $orderBy = 'name';
    public $name;
    public $resortName = 0;
    public $orderAsc = true;
    public $showModal = false;

    protected $listeners = [
        'delete-category' => 'deleteCategory',
    ];

    protected function rules()
    {
        return [
            'newCategory.id' => 'unique:categories,id,' . $this->newCategory['id'],
            'newCategory.name' => 'unique:categories,name,' . $this->newCategory['id'] . '|string|required|max:155|regex:/^[^<>]+$/',
            'newCategory.description' => 'nullable|string|max:255|regex:/^[^<>]+$/',
            'newCategory.color_hex_code' => 'required|string|min:7',
            'newCategory.super_category_id' => 'nullable|int',
        ];
    }

    protected $validationAttributes = [
        'newCategory.name' => 'category name',
        'newCategory.description' => 'description',
        'newCategory.color_hex_code' => 'color',
    ];

    public $newCategory = [
        'id' => null,
        'name' => null,
        'description' => null,
        'color_hex_code' => null,
        'super_category_id' => null,
    ];

    // set/reset $newCategory and validation
    public function setNewCategory(Category $category = null)
    {
        $this->resetErrorBag();
        if ($category) {
            $this->newCategory['id'] = $category->id;
            $this->newCategory['name'] = $category->name;
            $this->newCategory['description'] = $category->description;
            $this->newCategory['color_hex_code'] ='#' . $category->color_hex_code;
            $this->newCategory['super_category_id'] = $category->super_category_id;
        } else {
            $this->reset('newCategory');
        }
        $this->showModal = true;
    }

    // create a category
    public function createCategory()
    {
        $this->validate();
        $color_hex_code = ltrim($this->newCategory['color_hex_code'], '#');
        $category = Category::create([
            'name' => $this->newCategory['name'],
            'description' => $this->newCategory['description'],
            'color_hex_code' =>$color_hex_code,
            'super_category_id' => $this->newCategory['super_category_id'],
        ]);
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The category <b><i>{$category->name}</i></b> has been added",
        ]);
    }

    // update an existing category
    public function updateCategory(Category $category)
    {
        $this->validate();
        $color_hex_code = ltrim($this->newCategory['color_hex_code'], '#');
        $category->update([
            'name' => trim($this->newCategory['name']),
            'description' => $this->newCategory['description'],
            'color_hex_code' => $color_hex_code,
            'super_category_id' => $this->newCategory['super_category_id'] ?: null,
        ]);
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The category <b><i>{$category->name}</i></b> has been updated",
        ]);
    }
    // delete a category
    public function deleteCategory(Category $category)
    {
        $subcategories = Category::where('super_category_id', $category->id)->get();
        if($subcategories->isEmpty()) {
            $category->delete();
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'success',
                'html' => "The category <b><i>{$category->name}</i></b> has been deleted",
            ]);
        }
        else {
            $this->dispatchBrowserEvent('swal:confirm', [
                'icon' => 'error',
                'title' => 'Error',
                'showCancelButton' => false,
                'confirmButtonText' => 'Okay',
                'text' => "The category <b><i>{$category->name}</i></b> cannot be deleted because it has one or more sub categories under them.",
            ]);
        }
    }

    // reset the paginators
    public function updated($propertyName, $propertyValue)
    {
        if (in_array($propertyName, ['search', 'perPage'])) {
            $this->resetPage();
        }
    }

    // get all the super- and sub categories from the database (runs only once)
    public function mount()
    {
        $this->supercategories = Category::where('super_category_id', null)->orderBy('name')->get();
        $this->subcat = Category::whereNotNull('super_category_id')->get();
    }

    public function render()
    {
        //retrieve all the super categories and paginate
        $categories = Category::where('super_category_id', null)->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->searchNameOrDescription($this->search)
            ->paginate($this->perPage);

        //retrieve all the categories again after updating/creating...
        $this->subcat = Category::whereNotNull('super_category_id')->get();
        $this->supercategories = Category::where('super_category_id', null)->orderBy('name')->get();

        return view('livewire.admin.categories', compact('categories'))
            ->layout('layouts.staff-management', [
                'description' => 'Manage your categories',
                'title' => 'Categories',
            ]);

    }

    // resort the genres by the given column
    public function resort($column):int
    {
        if ($column === 'description') {
            if($this->resortName == 0){
                $this->resortName = 1;
            }
            return $this->resortName;
        } else {
            if ($this->orderBy === $column) {
                $this->orderAsc = !$this->orderAsc;
            } else {
                $this->orderAsc = true;
            }
            $this->resortName = 0;
            $this->orderBy = $column;
        }
    }

    public function resortName():int
    {
        if($this->resortName == 0){
            $this->resortName = 1;
        } elseif ($this->resortName == 1){
            $this->resortName = 2;
        } elseif ($this->resortName == 2){
            $this->resortName = 1;
        }
        return $this->resortName;
    }

    public function setOrderDirection($value)
    {
        $this->orderAsc = (bool)$value;
        $this->resort($this->orderBy);
    }
}
