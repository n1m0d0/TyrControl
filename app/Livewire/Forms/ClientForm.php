<?php

namespace App\Livewire\Forms;

use App\Enums\DocumentTypeEnum;
use App\Models\Client;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ClientForm extends Form
{
    public ?Client $client = null;

    public $name;
    public $document_type;
    public $document_identifier;

    public function rules()
    {
        return [
            'name' => 'required|string|max:150',
            'document_type' => ['required', new Enum(DocumentTypeEnum::class)],
            'document_identifier' => [
                'required',
                'string',
                'min:7',
                Rule::unique('clients')->ignore($this->client?->id)
            ],
        ];
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
        $this->name = $client->name;
        $this->document_type = $client->document_type;
        $this->document_identifier = $client->document_identifier;
    }

    public function store()
    {
        $this->validate();
        $data = $this->collectData();
        Client::create($data);

        $this->resetForm();
    }

    public function update()
    {
        $this->validate();
        $data = $this->collectData();
        $this->client->update($data);

        $this->resetForm();
    }

    public function delete()
    {
        $this->client->delete();

        $this->resetForm();
    }

    public function searchOrCreate()
    {
        $this->validate([
            'name' => 'required|string|max:150',
            'document_type' => ['required', new Enum(DocumentTypeEnum::class)],
            'document_identifier' => [
                'required',
                'string',
                'min:7'
            ],
        ]);

        $data = $this->collectData();
        $client = Client::firstOrCreate(
            ['document_identifier' => $this->document_identifier],
            $data
        );
        return $client;
    }

    public function resetForm()
    {
        $this->reset(['name', 'document_type', 'document_identifier', 'client']);
    }

    private function collectData()
    {
        return [
            'name' => $this->name,
            'document_type' => $this->document_type,
            'document_identifier' => $this->document_identifier,
        ];
    }
}
