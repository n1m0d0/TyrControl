<?php

namespace App\Livewire;

use App\Enums\DocumentTypeEnum;
use App\Livewire\Forms\ClientForm;
use App\Models\Client;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentClient extends Component
{
    use WireToast;
    use WithPagination;

    public $activity = 'create';
    public $search = '';

    public $document_types;

    public ClientForm $form;

    public function mount()
    {
        $this->document_types = DocumentTypeEnum::cases();
    }

    public function render()
    {
        $clients = Client::query()
            ->when($this->search, fn($query) => $query->whereAny([
                'name',
                'document_identifier',
            ], 'like', '%' . $this->search . '%'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-client', compact('clients'));
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

        Flux::modal('client-form')->close();
    }

    public function showForm($id = null)
    {
        $this->form->resetForm();

        if ($id) {
            $client = Client::findOrFail($id);
            $this->form->setClient($client);
            $this->activity = "edit";
        } else {
            $this->activity = "create";
        }

        Flux::modal('client-form')->show();
    }

    public function showDelete($id)
    {
        $client = Client::findOrFail($id);
        $this->form->setClient($client);

        Flux::modal('client-delete')->show();
    }

    public function closeDelete()
    {
        Flux::modal('client-delete')->close();
    }

    public function delete()
    {
        $this->form->delete();

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('client-delete')->close();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
