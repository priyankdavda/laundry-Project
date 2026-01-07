<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from_date
            ?? now()->startOfMonth()->toDateString();

        $to = $request->to_date
            ?? now()->toDateString();

        return $this->reportData($from, $to);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date',
        ]);

        return $this->reportData(
            $request->from_date,
            $request->to_date
        );
    }

    private function reportData($from, $to)
    {
        $orders = Order::whereBetween('created_at', [
                $from . ' 00:00:00',
                $to   . ' 23:59:59',
            ]);

        $sell = (clone $orders)->sum('total_amount');

        $collection = (clone $orders)->sum('paid_amount');

        return view('admin.reports.index', compact(
            'sell',
            'collection',
            'from',
            'to'
        ));
    }
}
