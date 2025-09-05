<?php

namespace App\Exports;

use App\Models\BoxSession;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BoxSessionExport implements
    FromArray,
    WithTitle,
    ShouldAutoSize,
    WithStyles
{
    protected $boxSessionId;

    public function __construct($boxSessionId)
    {
        $this->boxSessionId = $boxSessionId;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $boxSession = BoxSession::with(['box', 'user', 'sales.client'])
            ->findOrFail($this->boxSessionId);

        $data = [];

        // TÍTULO PRINCIPAL
        $data[] = ['REPORTE DE SESIÓN DE CAJA #' . $boxSession->id];
        $data[] = ['']; // Fila vacía

        // INFORMACIÓN DE LA SESIÓN
        $data[] = ['INFORMACIÓN DE LA SESIÓN'];
        $data[] = ['Caja:', $boxSession->box->name ?? 'N/A'];
        $data[] = ['Usuario:', $boxSession->user->name ?? 'N/A'];
        $data[] = ['Estado:', $boxSession->status->label()];
        $data[] = ['Fecha Apertura:', $boxSession->opened_at ? Carbon::parse($boxSession->opened_at)->format('d/m/Y H:i') : ''];
        $data[] = ['Monto Apertura:', '$' . number_format($boxSession->opening_amount, 2)];
        $data[] = ['Notas Apertura:', $boxSession->opening_notes ?? ''];

        if ($boxSession->closed_at) {
            $data[] = ['Fecha Cierre:', Carbon::parse($boxSession->closed_at)->format('d/m/Y H:i')];
            $data[] = ['Monto Cierre:', $boxSession->closing_amount ? '$' . number_format($boxSession->closing_amount, 2) : ''];
            $data[] = ['Monto Esperado:', $boxSession->expected_amount ? '$' . number_format($boxSession->expected_amount, 2) : ''];
            $data[] = ['Diferencia:', $boxSession->difference ? '$' . number_format($boxSession->difference, 2) : ''];
            $data[] = ['Notas Cierre:', $boxSession->closing_notes ?? ''];
        }

        $data[] = ['']; // Fila vacía
        $data[] = ['']; // Fila vacía

        // VENTAS ASOCIADAS
        $data[] = ['VENTAS DE LA SESIÓN'];
        $data[] = ['']; // Fila vacía

        if ($boxSession->sales->count() > 0) {
            // Encabezados de las ventas
            $data[] = [
                'ID Venta',
                'Cliente',
                'Fecha Venta',
                'Total',
                'Método de Pago',
                'Creado'
            ];

            // Datos de las ventas
            foreach ($boxSession->sales as $sale) {
                $data[] = [
                    $sale->id,
                    $sale->client->name ?? 'Cliente General',
                    Carbon::parse($sale->sale_date)->format('d/m/Y H:i'),
                    '$' . number_format($sale->total, 2),
                    $sale->payment_method->label(),
                    Carbon::parse($sale->created_at)->format('d/m/Y H:i')
                ];
            }

            $data[] = ['']; // Fila vacía

            // RESUMEN DE VENTAS
            $data[] = ['RESUMEN DE VENTAS'];
            $data[] = ['Total de Ventas:', $boxSession->sales->count()];
            $data[] = ['Total Vendido:', '$' . number_format($boxSession->sales->sum('total'), 2)];

            // Resumen por método de pago
            $paymentMethods = $boxSession->sales->groupBy('payment_method');
            foreach ($paymentMethods as $method => $sales) {
                $data[] = [
                    'Total ' . ucfirst($method) . ':',
                    '$' . number_format($sales->sum('total'), 2) . ' (' . $sales->count() . ' ventas)'
                ];
            }
        } else {
            $data[] = ['No hay ventas registradas en esta sesión'];
        }

        $data[] = ['']; // Fila vacía
        $data[] = ['Reporte generado el:', now()->format('d/m/Y H:i:s')];

        return $data;
    }

    /**
     * Título de la hoja
     */
    public function title(): string
    {
        return 'Sesión #' . $this->boxSessionId;
    }

    /**
     * Estilos del Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Título principal (fila 1)
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('4F46E5');
        $sheet->getStyle('A1')->getFont()->getColor()->setRGB('FFFFFF');

        // Sección "INFORMACIÓN DE LA SESIÓN" (fila 3)
        $sheet->mergeCells('A3:F3');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A3')->getFill()->getStartColor()->setRGB('E2E8F0');

        // Encontrar dinámicamente la fila de "VENTAS DE LA SESIÓN"
        $salesHeaderRow = null;
        $salesDataStartRow = null;

        foreach ($sheet->getRowIterator() as $row) {
            $cellValue = $sheet->getCell('A' . $row->getRowIndex())->getValue();
            if ($cellValue === 'VENTAS DE LA SESIÓN') {
                $salesHeaderRow = $row->getRowIndex();
                $salesDataStartRow = $salesHeaderRow + 2; // +2 porque hay una fila vacía después
                break;
            }
        }

        if ($salesHeaderRow) {
            // Título "VENTAS DE LA SESIÓN"
            $sheet->mergeCells('A' . $salesHeaderRow . ':F' . $salesHeaderRow);
            $sheet->getStyle('A' . $salesHeaderRow)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $salesHeaderRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle('A' . $salesHeaderRow)->getFill()->getStartColor()->setRGB('E2E8F0');

            // Encabezados de las ventas
            if ($salesDataStartRow) {
                $sheet->getStyle('A' . $salesDataStartRow . ':F' . $salesDataStartRow)->getFont()->setBold(true);
                $sheet->getStyle('A' . $salesDataStartRow . ':F' . $salesDataStartRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('A' . $salesDataStartRow . ':F' . $salesDataStartRow)->getFill()->getStartColor()->setRGB('F1F5F9');
                $sheet->getStyle('A' . $salesDataStartRow . ':F' . $salesDataStartRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        }

        // Encontrar y estilizar la sección "RESUMEN DE VENTAS"
        foreach ($sheet->getRowIterator() as $row) {
            $cellValue = $sheet->getCell('A' . $row->getRowIndex())->getValue();
            if ($cellValue === 'RESUMEN DE VENTAS') {
                $resumeRow = $row->getRowIndex();
                $sheet->mergeCells('A' . $resumeRow . ':F' . $resumeRow);
                $sheet->getStyle('A' . $resumeRow)->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A' . $resumeRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('A' . $resumeRow)->getFill()->getStartColor()->setRGB('FEF3C7');
                break;
            }
        }

        return [];
    }
}
