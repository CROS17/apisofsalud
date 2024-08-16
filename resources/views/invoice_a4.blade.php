<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        hr {
            border: 1px solid #ccc;
            margin: 10px 0;
        }

        h3, h4 {
            margin: 10px 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .header img {
            height: 50px; /* Adjust the size of the logo */
        }

        .header .title {
            text-align: center;
            flex: 1;
        }

        .header .title h3 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .total-row td {
            font-weight: bold;
        }

        .total-row td:last-child {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="title">
            <h3>Comprobante Electrónico</h3>
        </div>
    </div>

    <p style="text-align: right">Fecha de impresión: {{ now()->format('d/m/Y') }}</p>

    <table>
        <tr>
            <td style="font-weight: bold;">Folio:</td>
            <td>{{ $data['invoice_number'] }}</td>
            <td style="font-weight: bold;">Fecha de Emisión:</td>
            <td>{{ $data['issue_date'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Cliente:</td>
            <td colspan="3">{{ $data['client'] }}</td>
        </tr>
    </table>

    <hr>

    <h4>Detalle de Items</h4>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;">#</th>
                <th>Descripción</th>
                <th style="text-align: center;">Precio Unitario</th>
                <th style="text-align: center;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['items'] as $index => $row)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $row->item->code }}-- {{ $row->item->description }}</td>
                    <td style="text-align: center;">{{ $row['unit_price'] }}</td>
                    <td style="text-align: center;">{{ $row['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Subtotal:</td>
                <td>{{ $data['subtotal'] }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">IGV:</td>
                <td>{{ $data['igv'] }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total:</td>
                <td>{{ $data['total'] }}</td>
            </tr>
        </tfoot>
    </table>

    <hr>

    <p>Gracias por su compra. No se acepta devolución.</p>

</body>

</html>
