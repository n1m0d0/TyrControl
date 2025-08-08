<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductForm extends Form
{
    public ?Product $product = null;

    public $category;
    public $brand;
    public $name;
    public $description;
    public $sku;
    public $code;
    public $price = 0.00;
    public $image;
    public $minimum_stock = 0;
    public $currentImage = null;

    public function rules()
    {
        return [
            'category' => 'required|exists:categories,id',
            'brand' => 'required|exists:brands,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:250',
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($this->product?->id)
            ],
            'code' => [
                'nullable',
                'string',
                'max:150',
                Rule::unique('products', 'code')->ignore($this->product?->id)
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
            ],
            'minimum_stock' => 'required|integer|min:0',
        ];
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
        $this->category = $product->category_id;
        $this->brand = $product->brand_id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->sku = $product->sku;
        $this->code = $product->code;
        $this->price = $product->price;
        $this->image = null;
        $this->minimum_stock = $product->minimum_stock;
        $this->currentImage = $product->image;
    }

    public function store()
    {
        $this->validate();

        if ($this->image) {
            $this->image = $this->storeImage();
        }

        $data = $this->collectData();
        Product::create($data);

        $this->resetForm();
    }

    public function update()
    {
        $this->validate();

        if ($this->image) {
            if ($this->product->image) {
                Storage::disk('public')->delete($this->product->image);
            }

            $this->image = $this->storeImage();
        } else {
            $this->image = $this->currentImage;
        }

        $data = $this->collectData();
        $this->product->update($data);

        $this->resetForm();
    }

    public function delete()
    {
        $this->product->delete();

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['category', 'brand', 'name', 'description', 'sku', 'code', 'price', 'image', 'minimum_stock', 'product']);
    }

    private function collectData()
    {
        return [
            'category_id' => $this->category,
            'brand_id' => $this->brand,
            'name' => $this->name,
            'description' => $this->description,
            'sku' => $this->sku,
            'code' => $this->code,
            'price' => $this->price,
            'image' => $this->image,
            'minimum_stock' => $this->minimum_stock,
        ];
    }

    protected function storeImage(): string
    {
        return $this->image->store('products/images', 'public');
    }
}
