<?php

namespace App\Livewire\Forms;

use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BrandForm extends Form
{
    public ?Brand $brand = null;

    public $name;
    public $logo;
    public $currentLogo = null;

    public function rules()
    {
        return [
            'name' => 'required|string|max:150',
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
            ],
        ];
    }

    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;
        $this->name = $brand->name;
        $this->logo = null;
        $this->currentLogo = $brand->logo;
    }

    public function store()
    {
        $this->validate();

        if ($this->logo) {
            $this->logo = $this->storeLogo();
        }

        $data = $this->collectData();
        Brand::create($data);

        $this->resetForm();
    }

    public function update()
    {
        $this->validate();

        if ($this->logo) {
            if ($this->brand->logo) {
                Storage::disk('public')->delete($this->brand->logo);
            }

            $this->logo = $this->storeLogo();
        } else {
            $this->logo = $this->currentLogo;
        }

        $data = $this->collectData();
        $this->brand->update($data);

        $this->resetForm();
    }

    public function delete()
    {
        Storage::disk('public')->delete($this->brand->logo);

        $this->brand->delete();

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'logo', 'currentLogo', 'brand']);
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
        return $this->logo->store('brands/logos', 'public');
    }
}
