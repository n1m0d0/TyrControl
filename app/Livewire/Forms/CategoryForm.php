<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryForm extends Form
{
    public ?Category $category = null;

    public $parent;
    public $name;
    public $description;

    public function rules()
    {
        return [
            'parent' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
        $this->parent = $category->parent;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function store()
    {
        $this->validate();
        $data = $this->collectData();
        Category::create($data);
        $this->resetForm();
    }

    public function update()
    {
        $this->validate();
        $data = $this->collectData();
        $this->category->update($data);
        $this->resetForm();
    }

    public function delete()
    {
        $this->category->delete();
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['parent', 'name', 'description', 'category']);
    }

    private function collectData()
    {
        return [
            'parent_id' => $this->parent,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
