<?php

namespace App\Livewire;

use App\Enums\BoxSessionStatusEnum;
use App\Enums\DocumentTypeEnum;
use App\Enums\MovementTypeEnum;
use App\Enums\PaymentMethodEnum;
use App\Livewire\Forms\ClientForm;
use App\Models\BoxSession;
use App\Models\Detail;
use App\Models\Inventory;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use Carbon\Carbon;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;
use Illuminate\Support\Str;

class ComponentSale extends Component
{
    use WireToast;
    use WithPagination;

    public $warehouse_id;

    public $warehouses;
    public $products;

    public $search = '';

    public $product;
    public $available;
    public $quantity;

    public $item_id;
    public $items;
    public $total;

    public $document_types;

    public ClientForm $clientForm;

    public $client;

    public $box_session;

    public $payment_method;

    public $methods;

    public function mount()
    {
        $this->warehouse_id = null;

        $user_id =  Auth::id();
        $this->box_session = BoxSession::where('user_id', $user_id)
            ->where('status', BoxSessionStatusEnum::OPEN->value)
            ->first();

        $branch_id = $this->box_session?->box?->branch?->id;

        if (!$branch_id) {
            $this->warehouses = collect();

            toast()
                ->danger('No hay una sesión de caja activa.')
                ->push();
        } else {
            $this->warehouses = Warehouse::with('inventories')->where('branch_id', $branch_id)->get();
        }

        $this->total = 0.00;

        $this->items = collect();

        $this->document_types = DocumentTypeEnum::cases();

        $this->methods = PaymentMethodEnum::cases();
    }

    public function render()
    {
        $inventories = Inventory::query()
            ->when($this->warehouse_id !== null, function ($query) {
                return $query->where('warehouse_id', $this->warehouse_id)
                    ->when($this->search, function ($query) {
                        return $query->whereHas('product', function ($q) {
                            $q->where(function ($subQuery) {
                                $subQuery->whereAny([
                                    'name',
                                    'description',
                                    'sku',
                                    'code'
                                ], 'like', '%' . $this->search . '%');
                            });
                        });
                    })
                    ->orderBy('id', 'DESC');
            }, function ($query) {
                return $query->whereRaw('1 = 0');
            })
            ->paginate(10);

        return view('livewire.component-sale', compact('inventories'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showAddForm($id, $stock)
    {
        $this->product = Product::find($id);
        $this->available = $stock;
        $this->quantity = 1;

        Flux::modal('add-form')->show();
    }

    public function addItem()
    {
        $id = Str::uuid()->toString();

        $this->items->push([
            'id' => $id,
            'product_id' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'quantity' => $this->quantity,
            'total' => $this->product->price * $this->quantity,
        ]);

        $this->total = $this->items->sum('total');

        Flux::modal('add-form')->close();
    }

    public function showDeleteItem($id)
    {
        $this->item_id = $id;

        Flux::modal('item-delete')->show();
    }

    public function closeDeleteItem()
    {
        Flux::modal('item-delete')->close();
    }

    public function removeItem()
    {
        $this->items = $this->items->reject(function ($item) {
            return $item['id'] == $this->item_id;
        });

        $this->total = $this->items->sum('total');

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('item-delete')->close();
    }

    public function showClientForm()
    {
        $this->clientForm->resetForm();

        Flux::modal('client-form')->show();
    }

    public function saveClient()
    {
        $this->client = $this->clientForm->searchOrCreate();

        toast()
            ->success('El registro se guardó correctamente.')
            ->push();

        Flux::modal('client-form')->close();
    }

    public function verify()
    {
        if (!$this->client) {
            Flux::modal('sale-alert')->show();
        } else {
            //$this->saveSale();
            Flux::modal('sale-confirmed')->show();
        }
    }

    public function openConfirmed()
    {
        $this->closeSaleAlert();
        Flux::modal('sale-confirmed')->show();
    }

    public function closeSaleAlert()
    {
        Flux::modal('sale-alert')->close();
    }

    public function saveSale()
    {
        if ($this->items->isEmpty()) {
            toast()->warning('No hay items para procesar.')->push();
            return;
        }

        $box_session_id = $this->box_session->id;
        $client_id = null;

        if ($this->client) {
            $client_id = $this->client->id;
        }

        $items = $this->items;
        $warehouse_id = $this->warehouse_id;

        $method = $this->payment_method;

        try {
            DB::transaction(function () use ($box_session_id, $client_id, $warehouse_id, $method, $items) {
                $sale = Sale::create([
                    'box_session_id' => $box_session_id,
                    'client_id' => $client_id,
                    'sale_date' => now(),
                    'total' => $items->sum('total'),
                    'payment_method' => $method
                ]);

                foreach ($items as $item) {
                    Detail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                    ]);

                    $inventory = Inventory::firstOrCreate([
                        'warehouse_id' => $warehouse_id,
                        'product_id' => $item['product_id']
                    ]);

                    if ($inventory->stock < $item['quantity']) {
                        // Lanzar una excepción personalizada
                        throw new \Exception("No hay suficiente stock para el producto {$item['name']}. Stock disponible: {$inventory->stock}");
                    }

                    $inventory->decrement('stock', $item['quantity']);
                    $inventory->save();

                    Movement::create([
                        'warehouse_id' => $warehouse_id,
                        'product_id' => $item['product_id'],
                        'movement_type' => MovementTypeEnum::EXIT->value,
                        'quantity' => $item['quantity'],
                        'reason' => 'Venta'
                    ]);
                }
            });

            $this->total = 0.00;

            $this->items = collect();

            $this->client = null;

            // Mostrar un mensaje de éxito
            toast()
                ->success('El registro se guardó correctamente.')
                ->push();
        } catch (\Exception $e) {
            // Mostrar un mensaje de error
            toast()
                ->danger('Error al guardar la venta: ' . $e->getMessage())
                ->push();
        }

        Flux::modal('sale-confirmed')->close();
    }
}
