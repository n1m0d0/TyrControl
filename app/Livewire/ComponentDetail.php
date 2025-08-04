<?php

namespace App\Livewire;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class ComponentDetail extends Component
{
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        $sales = Sale::with('client')
            ->where('box_session_id', $this->id)
            /*->when($this->search, fn($query) => $query->whereAny([
                'name',
                'code'
            ], 'like', '%' . $this->search . '%'))*/
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-detail', compact('sales'));
    }

    public function exportPdf($id)
    {
        $sale = Sale::with([
            'client:id,name,document_identifier,document_type',
            'details:id,sale_id,product_id,quantity,price',
            'details.product:id,name'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.sale-note', compact('sale'))->output();

        if ($sale->client) {
            return response()->streamDownload(
                fn() => print($pdf),
                $sale->client->name . '_' . $sale->sale_date->format('Ymd') . '.pdf'
            );
        } else {
            $name = "Sin Nombre";
            return response()->streamDownload(
                fn() => print($pdf),
                $name . '_' . $sale->sale_date->format('Ymd') . '.pdf'
            );
        }
    }
}
