<style>
    .invoice-box {
        width: 100%;
        font-size: 14px;
        color: #555;
    }
    .invoice-header {
        border-bottom: 2px solid #ddd;
        margin-bottom: 15px;
    }
    .invoice-title {
        font-size: 20px;
        color: #e91e63;
        font-weight: bold;
    }
    .invoice-table th {
        background: #f8f9fa;
        font-weight: 600;
    }
    .qr-text {
        color: #e91e63;
        font-weight: 600;
    }
</style>
<style>
@media print {

    body * {
        visibility: hidden;
    }

    #print-area,
    #print-area * {
        visibility: visible;
    }

    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 15px;
        background: #fff;
    }

    /* bootstrap grid fix */
    .row {
        display: flex !important;
        flex-wrap: wrap !important;
    }

    .col-md-6 {
        width: 50% !important;
    }

    .text-right {
        text-align: right !important;
    }

    .text-center {
        text-align: center !important;
    }

    button {
        display: none !important;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    table th,
    table td {
        border: 1px solid #ccc !important;
        padding: 6px !important;
    }

    img {
        max-width: 100% !important;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>

<div id="print-area">
<div class="invoice-box">

    {{-- HEADER --}}
    <div class="row invoice-header pb-2">
        <div class="col-md-6">
            <img src="{{ asset('admin/dist/img/LaundryHub.png') }}" height="60">
        </div>
        <div class="col-md-6 text-right">
            <p>
                Shop No-FFK-9, Galaxy Plaza<br>
                Gaur City-1, Greater Noida West<br>
                Noida Extension, UP - 201318<br>
                Phone: 9958701474
            </p>
        </div>
    </div>

    {{-- CUSTOMER + ORDER DETAILS --}}
    <div class="row border mb-3">
        <div class="col-md-6 p-3 border-right">
            <h6><b>Customer Details</b></h6>
            <p>
                <b>{{ $order->customer_name }}</b><br>
                Mobile : {{ $order->customer_mobile }}<br>
                Address : {{ $order->address }}<br>
                City : {{ $order->city }}<br>
                State : {{ $order->state }}<br>
                Wallet Amount : Rs. {{ number_format($order->user->wallet_balance ?? 0,2) }}
            </p>
        </div>

        <div class="col-md-6 p-3 text-center">
            <h6><b>Order Details</b></h6>
            <p>
                Invoice No : {{ $order->order_number }}<br>
                Pick Up : {{ $order->pickup_date }}<br>
                Delivery : {{ $order->delivery_date }}
            </p>

            <p class="qr-text">Kindly Scan and Pay Your Amount</p>
            <img src="{{ asset('images/gpay-qr.png') }}" height="120">
        </div>
    </div>

    {{-- ITEMS TABLE --}}
    <table class="table table-bordered invoice-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Product Name (No. of Clothes)</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>
                    {{ $item->product_name }}
                    @if($item->remark)
                        <br><small><b>Remark:</b> {{ $item->remark }}</small>
                    @endif
                </td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price,2) }}</td>
                <td class="text-right">{{ number_format($item->line_total_amount,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTALS --}}
    <table class="table table-sm">
        <tr>
            <th>Total Cost</th>
            <td class="text-right">{{ number_format($order->subtotal_amount,2) }}</td>
        </tr>
        <tr>
            <th>Discount</th>
            <td class="text-right">{{ number_format($order->discount_amount,2) }}</td>
        </tr>
        <tr>
            <th>Grand Total</th>
            <td class="text-right"><b>{{ number_format($order->total_amount,2) }}</b></td>
        </tr>
        <tr>
            <th>Pending Amount</th>
            <td class="text-right text-danger">
                <b>{{ number_format($order->pending_amount,2) }}</b>
            </td>
        </tr>
    </table>

    {{-- FOOTER --}}
    <div class="text-center mt-3">
        <button onclick="printOrder()" class="btn btn-success">
            Print Order
        </button>
    </div>

</div>
</div>
<script>
function printOrder() {
    window.print();
}
</script>

