<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\City;
use App\Models\State;
use App\Models\Category;
use App\Models\Product;

use App\Models\OrderStatus;


class OrderController extends Controller
{
    /**
     * Store new order
     */
    public function index()
    {
        $orders = Order::latest()->paginate(20);

        $runners = User::where('user_type', 'ASSIGN')
                    ->where('status', 'ACTIVE')
                    ->get();

        return view('admin.orders.index', compact('orders', 'runners'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        $cities = City::orderBy('name')->get();
        $states = State::orderBy('name')->get();

        $timeslots = [
            '09:00-11:00',
            '11:00-13:00',
            '14:00-16:00',
            '16:00-18:00',
        ];

        return view('admin.orders.create', compact('users','categories','products','cities','states','timeslots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_type'      => 'required|in:registered,new',
            'customer_name'      => 'required|string|max:150',
            'customer_mobile'    => 'required|string|max:20',
            'house_no'           => 'required|string|max:100',
            'landmark'           => 'required|string|max:150',
            'address'            => 'required|string|max:255',
            'city_id'            => 'nullable|integer',
            'state_id'           => 'nullable|integer',
            'pincode'            => 'required|string|max:10',

            'pickup_date'        => 'required|date',
            'pickup_timeslot'    => 'required|string|max:50',
            'delivery_date'      => 'required|date',
            'delivery_timeslot'  => 'required|string|max:50',

            'items_json'         => 'required|string',
            'discount_amount'    => 'nullable|numeric|min:0',
            'paid_amount'        => 'nullable|numeric|min:0',
        ]);

        $items = json_decode($request->items_json, true);
        if (!is_array($items) || empty($items)) {
            return back()->withErrors('Please add at least one product.');
        }

        DB::beginTransaction();
        try {

            /* ================= USER ================= */
            if ($request->customer_type === 'registered') {
                $user = User::findOrFail($request->registered_user_id);
            } else {
                $user = User::firstOrCreate(
                    ['mobile' => $request->customer_mobile],
                    [
                        'name'           => $request->customer_name,
                        'house_no'       => $request->house_no,
                        'landmark'       => $request->landmark,
                        'address'        => $request->address,
                        'pincode'        => $request->pincode,
                        'city_id'        => $request->city_id,
                        'state_id'       => $request->state_id,
                        'wallet_balance' => 0,
                    ]
                );
            }

            /* ================= SUBTOTAL ================= */
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += ((float)$item['unit_price']) * ((float)$item['quantity']);
            }

            $discount = (float) ($request->discount_amount ?? 0);
            $paid     = (float) ($request->paid_amount ?? 0);

            /* ================= WALLET AUTO APPLY (SAFE) ================= */
            $walletUsed = 0;
            $walletBalance = (float) ($user->wallet_balance ?? 0);

            if ($request->customer_type === 'registered' && $walletBalance > 0) {
                // 🔥 wallet sirf available amount tak hi use hoga
                $walletUsed = min($walletBalance, ($subtotal - $discount));

                $user->wallet_balance = $walletBalance - $walletUsed;
                $user->save();
            }

            /* ================= FINAL TOTALS ================= */
            $total   = $subtotal - $discount;
            $pending = $total - $walletUsed - $paid;

            /* ================= PAYMENT STATUS ================= */
            if ($pending <= 0 && $total > 0) {
                $paymentStatus = 'PAID';
            } elseif ($paid > 0 || $walletUsed > 0) {
                $paymentStatus = 'PARTIAL';
            } else {
                $paymentStatus = 'UNPAID';
            }

            /* ================= ORDER ================= */
            $order = Order::create([
                'order_number'        => 'TEMP-' . time(),
                'user_id'             => $user->id,
                'created_by_admin_id' => Auth::id(),
                'status_id'           => 1,

                'customer_name'       => $request->customer_name,
                'customer_mobile'     => $request->customer_mobile,
                'house_no'            => $request->house_no,
                'landmark'            => $request->landmark,
                'address'             => $request->address,
                'city'                => optional(City::find($request->city_id))->name,
                'state'               => optional(State::find($request->state_id))->name,
                'pincode'             => $request->pincode,

                'pickup_date'         => $request->pickup_date,
                'pickup_timeslot'     => $request->pickup_timeslot,
                'delivery_date'       => $request->delivery_date,
                'delivery_timeslot'   => $request->delivery_timeslot,

                'subtotal_amount'     => $subtotal,
                'discount_amount'     => $discount,
                'wallet_used_amount'  => $walletUsed,
                'total_amount'        => $total,
                'paid_amount'         => $paid,
                'pending_amount'      => $pending,
                'payment_status'      => $paymentStatus,
            ]);

            // 🔥 FINAL ORDER NUMBER → ORDA0001
            $order->order_number = 'ORDA' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
            $order->save();

            /* ================= ORDER ITEMS ================= */
            foreach ($items as $item) {
                // $product = Product::find($item['product_id']);

                // $price = $product
                //     ? (float) ($product->amount ?? $product->price)
                //     : (float) $item['unit_price'];

                $price = (float) $item['unit_price'];

                OrderItem::create([
                    'order_id'          => $order->id,
                    'category_id'       => $item['category_id'] ?? null,
                    'product_id'        => $item['product_id'],
                    'product_name'      => $item['product_name'],
                    // 'unit_price'        => $price,
                    'unit_price'        => (float) $item['unit_price'],
                    'quantity'          => (float) $item['quantity'],
                    // 'line_total_amount' => $price * (float) $item['quantity'],
                    'line_total_amount' => (float) $item['unit_price']  * (float) $item['quantity'],
                    'remark'            => $item['remark'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.order.index')
                ->with('success', 'Order created successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error($e);
            return back()->withErrors('Something went wrong while creating the order.');
        }
    }








    /**
     * Order number generator
     * Format: ORD + YYYYMMDD + LPAD(id, 4, '0')
     * Example: ORD20251211 0007
     */
    public function show($id)
    {
        $order = Order::with(['items','user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }



    private function generateOrderNumber(int $id): string
    {
        // $datePart = now()->format('Ymd');                 // 20251211
        $datePart = now()->format('Ymd_His');

        $idPart   = str_pad($id, 4, '0', STR_PAD_LEFT);  // 0007

        return 'ORD' . $datePart . $idPart;
    }
    public function getUser(User $user)
    {
        // If User has relations city/state as models, prefer IDs from there.
        $cityId  = $user->city_id ?? null;
        $stateId = $user->state_id ?? null;
        $cityName  = method_exists($user, 'city') && $user->city ? $user->city->name : ($user->city ?? null);
        $stateName = method_exists($user, 'state') && $user->state ? $user->state->name : ($user->state ?? null);

        return response()->json([
            'id'             => $user->id,
            'name'           => $user->name,
            'mobile'         => $user->mobile,
            'house_no'       => $user->house_no,
            'landmark'       => $user->landmark,
            'address'        => $user->address,
            'city_id'        => $cityId,
            'state_id'       => $stateId,
            'city'           => $cityName,
            'state'          => $stateName,
            'pincode'        => $user->pincode,
            'company_name'   => $user->company_name,
            'gstin'          => $user->gstin,
            'wallet_balance' => (float) $user->wallet_balance,
        ]);
    }

    public function byCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get(['id','name','amount']);
        // standardize keys
        return response()->json($products->map(function($p){
            return ['id'=>$p->id,'name'=>$p->name,'amount'=> (float) ($p->amount ?? $p->price ?? 0)];
        }));
    }
    public function getProduct(Product $product)
    {
        // choose the DB column you keep amounts in (amount or price)
        $amount = $product->amount ?? $product->price ?? 0;

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'amount' => (float) $amount,
            'category_id' => $product->category_id,
            // add any other fields you need
        ]);
    }

    // CHANGE PICK UP
    public function updatePickup(Request $request, Order $order)
    {
        $request->validate([
            'pickup_date'     => 'required|date',
            'pickup_timeslot' => 'required|string',
        ]);

        $order->update([
            'pickup_date'     => $request->pickup_date,
            'pickup_timeslot' => $request->pickup_timeslot,
        ]);

        return response()->json(['success' => true]);
    }

    // ASSIGN PICK UP
    public function assignPickup(Request $request)
    {
        $request->validate([
            'order_id'  => 'required|exists:orders,id',
            'runner_id' => 'required|exists:users,id',
        ]);

        Order::where('id', $request->order_id)->update([
            'assign_id' => $request->runner_id,
            'status_id' => 2, // PICKUP_ASSIGNED
        ]);

        return back()->with('success', 'Pickup assigned successfully');
    }
    public function changeDelivery(Request $request, Order $order)
    {
        $request->validate([
            'delivery_date' => 'required|date',
            'delivery_timeslot' => 'required|string',
        ]);

        $order->update([
            'delivery_date' => $request->delivery_date,
            'delivery_timeslot' => $request->delivery_timeslot,
        ]);

        return response()->json(['success' => true]);
    }
    public function assignDelivery(Request $request)
    {
        $request->validate([
            'order_id'           => 'required|exists:orders,id',
            'delivery_assign_id' => 'required|exists:users,id',
            // 'delivery_date'      => 'required|date',
            // 'delivery_timeslot'  => 'required|string|max:50',
        ]);

        Order::where('id', $request->order_id)->update([
            'delivery_assign_id' => $request->delivery_assign_id,
            // 'delivery_date'      => $request->delivery_date,
            // 'delivery_timeslot'  => $request->delivery_timeslot,
            // 'status_id'          => 4, // DELIVERY_ASSIGNED
        ]);

        return back()->with('success', 'Delivery assigned / updated successfully');
    }






}
