<?php

namespace App\Livewire\Forms;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CompanyForm extends Form
{
    public ?Company $company = null;

    public $name;
    public $logo;
    public $currentLogo = null;

    public function rules()
    {
        return [
            'name' => 'required|string|max:150',
            'logo' => [
                $this->company ? 'nullable' : 'required',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
            ],
        ];
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;
        $this->name = $company->name;
        $this->logo = null;
        $this->currentLogo = $company->logo;
    }

    public function store()
    {
        $this->validate();

        $this->logo = $this->storeLogo();

        $data = $this->collectData();
        Company::create($data);

        $this->resetForm();
    }

    public function update()
    {
        $this->validate();

        if ($this->logo) {
            Storage::disk('public')->delete($this->company->logo);
            $this->logo = $this->storeLogo();
        } else {
            $this->logo = $this->currentLogo;
        }

        $data = $this->collectData();
        $this->company->update($data);

        $this->resetForm();
    }

    public function delete()
    {
        Storage::disk('public')->delete($this->company->logo);

        $this->company->delete();

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'logo', 'currentLogo', 'company']);
    }

    private function collectData()
    {
        return [
            'name' => $this->name,
            'logo' => $this->logo,
        ];
    }

    protected function storeLogo(): string
    {
        return $this->logo->store('companies/logos', 'public');
    }
}
