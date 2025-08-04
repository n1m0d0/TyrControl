<?php

namespace App\Livewire;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentCategory extends Component
{
    use WireToast;
    use WithPagination;

    public $activity = 'create';
    public $search = '';

    public $parents;

    public CategoryForm $form;

    public function mount()
    {
        $this->parents = collect();
    }

    public function render()
    {
        $categories = Category::with('parent')
            ->when($this->search, fn($query) => $query->whereAny([
                'name',
                'description'
            ], 'like', '%' . $this->search . '%'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-category', compact('categories'));
    }

    public function save()
    {
        if ($this->activity == "create") {
            $this->form->store();

            toast()
                ->success('El registro se guardó correctamente.')
                ->push();
        }

        if ($this->activity == "edit") {
            $this->form->update();

            toast()
                ->info('Los cambios se guardaron con éxito.')
                ->push();
        }

        $this->activity = "create";

        Flux::modal('category-form')->close();
    }

    public function showForm($id = null)
    {
        $this->form->resetForm();

        if ($id) {
            $this->parents = Category::select('id', 'name')->where('id', '!=', $id)->orderBy('id', 'DESC')->get();
            $category = Category::findOrFail($id);
            $this->form->setCategory($category);
            $this->activity = "edit";
        } else {
            $this->parents = Category::select('id', 'name')->orderBy('id', 'DESC')->get();
            $this->activity = "create";
        }

        Flux::modal('category-form')->show();
    }

    public function showDelete($id)
    {
        $category = Category::findOrFail($id);
        $this->form->setCategory($category);

        Flux::modal('category-delete')->show();
    }

    public function closeDelete()
    {
        Flux::modal('category-delete')->close();
    }

    public function delete()
    {
        $this->form->delete();

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('category-delete')->close();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
