<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Sale note') }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: white;
            min-height: 100vh;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #cccccc;
        }

        /* HEADER OPTIMIZADO */
        .invoice-header {
            background: #2c2c2c;
            color: white;
            padding: 15px 30px;
            /* Reducido de 30px a 15px vertical */
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 1.8rem;
            /* Reducido de 2.5rem a 1.8rem */
            font-weight: 700;
            margin-bottom: 0;
            /* Eliminado margen inferior */
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        .invoice-title {
            font-size: 1rem;
            /* Reducido de 1.2rem a 1rem */
            font-weight: 300;
            opacity: 0.9;
            margin: 0;
        }

        .invoice-number {
            text-align: right;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .invoice-info {
            background: #f5f5f5;
            padding: 12px 20px;
            /* Reducido de 15px a 12px vertical */
            border: 1px solid #666666;
            margin: 5px 0;
            /* Reducido de 10px a 5px */
        }

        .info-row {
            margin-bottom: 6px;
            /* Reducido de 8px a 6px */
            padding: 3px 0;
            /* Reducido de 5px a 3px */
            border-bottom: 1px solid #cccccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #333333;
            font-size: 0.85rem;
            /* Ligeramente reducido */
        }

        .info-value {
            color: #000000;
            font-weight: 500;
            font-size: 0.85rem;
            /* Ligeramente reducido */
        }

        .details-section {
            padding: 15px 20px;
            /* Reducido de 20px a 15px vertical */
        }

        .section-title {
            font-size: 1.2rem;
            /* Reducido de 1.4rem a 1.2rem */
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 12px;
            /* Reducido de 15px a 12px */
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -6px;
            /* Reducido de -8px a -6px */
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            /* Reducido de 50px a 40px */
            height: 2px;
            background: #666666;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            /* Reducido de 15px a 10px */
            background: white;
            border: 1px solid #cccccc;
        }

        .products-table thead {
            background: #4a4a4a;
            color: white;
        }

        .products-table th {
            padding: 10px 8px;
            /* Reducido padding */
            text-align: left;
            font-weight: 600;
            font-size: 0.8rem;
            /* Ligeramente reducido */
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .products-table td {
            padding: 10px 8px;
            /* Reducido padding */
            border-bottom: 1px solid #dddddd;
            font-size: 0.85rem;
            /* Ligeramente reducido */
        }

        .products-table tbody tr:hover {
            background-color: #f0f0f0;
        }

        .products-table tbody tr:last-child td {
            border-bottom: none;
        }

        .product-name {
            font-weight: 600;
            color: #1a1a1a;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .price {
            font-weight: 500;
            color: #333333;
        }

        .total-section {
            background: #f5f5f5;
            padding: 12px 20px;
            /* Reducido de 15px a 12px vertical */
            border-top: 2px solid #666666;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.1rem;
            /* Ligeramente reducido */
            font-weight: 700;
            color: #1a1a1a;
        }

        .total-amount {
            background: #333333;
            color: white;
            padding: 6px 14px;
            /* Ligeramente reducido */
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            /* Reducido padding */
            background: #666666;
            color: white;
            font-size: 0.7rem;
            /* Ligeramente reducido */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }

            /* Optimización adicional para impresión */
            .invoice-header {
                padding: 10px 20px;
            }

            .company-name {
                font-size: 1.6rem;
            }

            .invoice-title {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 768px) {
            .invoice-container {
                margin: 10px;
                border-radius: 10px;
            }

            .invoice-header {
                flex-direction: column;
                text-align: center;
                padding: 12px 20px;
            }

            .company-name {
                font-size: 1.6rem;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 3px;
            }

            .products-table {
                font-size: 0.8rem;
            }

            .products-table th,
            .products-table td {
                padding: 8px 6px;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header Optimizado -->
        <div class="invoice-header">
            <div class="company-info">
                <h1 class="company-name">{{ config('app.name') }}</h1>
                <p class="invoice-title">{{ __('Sale note') }}</p>
            </div>
            <div class="invoice-number">
                <small>Nº {{ $sale->id ?? '001' }}</small>
            </div>
        </div>

        <!-- Invoice Information -->
        <div class="invoice-info">
            <div class="info-row">
                <span class="info-label">{{ __('Date') }}:</span>
                <span class="info-value">{{ $sale->sale_date->format('d/m/Y H:i:s') }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">{{ __('Payment method') }}:</span>
                <span class="info-value">{{ $sale->payment_method->label() }}</span>
            </div>
            
            @if ($sale->client)
                <div class="info-row">
                    <span class="info-label">{{ __('Client') }}:</span>
                    <span class="info-value">{{ $sale->client->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ $sale->client->document_type->label() }}:</span>
                    <span class="info-value">{{ $sale->client->document_identifier }}</span>
                </div>
            @else
                <div class="info-row">
                    <span class="info-label">{{ __('Client') }}:</span>
                    <span class="info-value">S/N</span>
                </div>
            @endif

        </div>

        <!-- Details Section -->
        <div class="details-section">
            <h2 class="section-title">{{ __('Detail') }}</h2>

            <table class="products-table">
                <thead>
                    <tr>
                        <th>{{ __('Product') }}</th>
                        <th class="text-center">{{ __('Quantity') }}</th>
                        <th class="text-right">{{ __('Unit price') }}</th>
                        <th class="text-right">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->details as $detail)
                        <tr>
                            <td class="product-name">{{ $detail->product->name }}</td>
                            <td class="text-center">
                                <span class="badge">{{ $detail->quantity }}</span>
                            </td>
                            <td class="text-right price">Bs.{{ number_format($detail->price, 2) }}</td>
                            <td class="text-right price">Bs.{{ number_format($detail->quantity * $detail->price, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span>{{ __('Total') }}:</span>
                <span class="total-amount">Bs.{{ number_format($sale->total, 2) }}</span>
            </div>
        </div>
    </div>
</body>

</html>
