<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        #header {
            text-align: center;
        }

        #logo {
            max-width: 150px;
            max-height: 100px;
        }

        #bill-info {
            margin-top: 20px;
        }

        #items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        #items-table th, #items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #totals {
            margin-top: 20px;
            text-align: right;
        }

        .logo-div{
            background: black;padding: 5px;width: 147px;
        }

        .bill-info-left{
            width: 50%;
            float: left;
        }

        .bill-info-right{
            width: 50%;
            float: right;
            text-align: right;
        }

        h1.invoice-header{
            text-align: right;
        }

        #items-table thead tr{
            background: #232e38;
            color: #fff;
        }

        #items-table {
            border-collapse:separate;
            border-spacing:0 3px;
        }
        #items-table td:first-of-type, #items-table th:first-of-type{
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        #items-table td:last-of-type, #items-table th:last-of-type{
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
            
    </style>
</head>
<body>

    <div id="header">
        <div class="logo-div">
            <img id="logo" src="{{public_path('images/logo_white.png')}}" alt="Alita Infotech Logo">
        </div>
        <h2>Tax Invoice</h2>
    </div>

    <div id="bill-info">
        <div class="bill-info-left">
            <p><strong>From:</strong> {{$data['from']}}</p>
            <p><strong>Bill To:</strong> {{$data['name']}}</p>
        </div>
        <div class="bill-info-right">
            {{-- <h1 class="invoice-header">INVOICE</h1> --}}
            <p><strong>Invoice #:</strong> {{$data['invoice_no']}}</p>
            <p><strong>Date:</strong> {{$data['date']}}</p>
        </div>
    </div>
    <br>
    <table id="items-table" style="padding-top: 120px;">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($item_array))
            <!-- Add rows for each item -->
            @foreach ($item_array as $key=> $item)
            <tr>
                <td>{{$item['description']}}</td>
                <td>{{$item['quntity']}}</td>
                <td>{{$item['rate']}}</td>
                <td>{{$item['amount']}}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

    <div id="totals">
        <p><strong>Subtotal:</strong> ${{$data['sub_total']}}</p>
        <p><strong>SGST:</strong>  ${{$data['sgst']}}</p>
        <p><strong>CGST:</strong>  ${{$data['cgst']}}</p>
        <p><strong>Total:</strong> ${{$data['grandTotal']}}</p>
    </div>

</body>
</html>
