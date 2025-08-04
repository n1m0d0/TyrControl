<?php

namespace App\Livewire;

use App\Livewire\Forms\CompanyForm;
use App\Models\Company;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentCompany extends Component
{
    use WireToast;
    use WithPagination;
    use WithFileUploads;

    public $activity = 'create';
    public $search = '';

    public CompanyForm $form;
    
    public function render()
    {
        $companies = Company::query()
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-company', compact('companies'));
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

        Flux::modal('company-form')->close();
    }

    public function showForm($id = null)
    {
        $this->form->resetForm();

        if ($id) {
            $company = Company::findOrFail($id);
            $this->form->setCompany($company);
            $this->activity = "edit";
        } else {
            $this->activity = "create";
        }

        Flux::modal('company-form')->show();
    }

    public function showDelete($id)
    {
        $company = Company::findOrFail($id);
        $this->form->setCompany($company);

        Flux::modal('company-delete')->show();
    }

    public function closeDelete()
    {
        Flux::modal('company-delete')->close();
    }

    public function delete()
    {
        $this->form->delete();

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('company-delete')->close();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
