<?php

namespace App\View\Components;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use App\Models\Order;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;


class Dashboard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $user = User::role('user')->count();
        view()->share('user',$user);

        $category = Category::count();
        view()->share('category',$category);

        $product = Product::count();
        view()->share('product',$product);

        $order = Order::count();
        view()->share('collection',$order);

        // $collection = Collection::count();
        // view()->share('collection',$collection);

         $orders = Order::where('status_id', '!=', 6);


        // AMOUNTS
        $totalAmount   = (clone $orders)->sum('total_amount');
        $paidAmount    = (clone $orders)->sum('paid_amount');
        $pendingAmount = (clone $orders)->sum('pending_amount');

        view()->share('totalAmount', $totalAmount);
        view()->share('paidAmount', $paidAmount);
        view()->share('pendingAmount', $pendingAmount);

        // Month Sell (Total / Paid / Pending)
        $monthTotal   = (clone $orders)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $monthPaid = (clone $orders)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('paid_amount');

        $monthPending = (clone $orders)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('pending_amount');

        view()->share('monthTotal', $monthTotal);
        view()->share('monthPaid', $monthPaid);
        view()->share('monthPending', $monthPending);

        // ❌ Cancelled exclude (assume status_id = 5)
        $orders = Order::where('status_id', '!=', 6);

        // DAILY SELL (Today)
        $dailySell = (clone $orders)
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        view()->share('dailySell', $dailySell);


        // ❌ Cancelled exclude (status_id = 5 assumed)
        $orders = Order::where('status_id', '!=', 6);

        // DAILY COLLECTION (Today Paid)
        $dailyCollection = (clone $orders)
            ->whereDate('created_at', Carbon::today())
            ->sum('paid_amount');

        view()->share('dailyCollection', $dailyCollection);


    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard');
    }
}
