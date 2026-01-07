<!DOCTYPE html>
<html>
<head>
    <title>{{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            font-size: 16px;
            margin: 0;
            padding: 0;
        }
        hr {
            width: 15%;
        }

        @page {
            margin: 0;
        }

        @media print {
            html, body {
                margin: 0;
                padding: 0;
            }
            button {
                display: none;
            }
        }

        .tag-box {
            padding: 10px 0;
        }
    </style>
</head>

<body>

@php
    $globalIndex = 1; // 🔥 CONTINUOUS LABEL COUNT
@endphp

{{-- 🔥 LOOP ITEMS --}}
@foreach($items as $item)

    @php
        $remark  = $item->remark ?: 'DC';
        // ✅ FIXED FIELD
        $clothes = ($item->no_of_clothes && $item->no_of_clothes > 0)
            ? $item->no_of_clothes
            : 1;
    @endphp

    {{-- 🔥 LOOP = NO OF CLOTHES --}}
    @for($i = 1; $i <= $clothes; $i++)

        <div class="tag-box">
            <strong>{{ $order->order_number }}</strong><br>

            {{ strtoupper($remark) }}
            - {{ $globalIndex }} ({{ $totalClothes }})<br>

            {{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}<br>

            {{ $order->address }}<br>

            {{ strtoupper($remark) }}
        </div>

        <hr>
        <div style="page-break-after: always;"></div>

        @php $globalIndex++; @endphp

    @endfor

@endforeach

<button onclick="window.print()">Print</button>

</body>
</html>
