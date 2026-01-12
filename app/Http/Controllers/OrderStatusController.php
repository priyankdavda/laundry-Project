<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

use App\Models\OrderStatus;


class OrderStatusController  extends Controller
{
    public function index()
    {
        $data = OrderStatus::orderBy('id','ASC')->get();

        return view('admin.orderstatus.index', compact('data'));
    }

    public function create()
    {
        return view('admin.orderstatus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255',
        ]);

        $generatedCode = strtoupper(str_replace(' ', '_', $request->name));

        if ($request->id) {
            $status = OrderStatus::find($request->id);
            $msg = "Order Status updated successfully.";
        } else {
            $status = new OrderStatus();
            $msg = "Order Status created successfully.";
        }

        // Controller me timestamps disable
        $status->timestamps = false;
        $status->name = $request->name;
        $status->code = $generatedCode;
        $status->save();

        return redirect()->route('admin.orderstatus.index')->with('success', $msg);
    }

    public function edit($id)
    {
        $data = OrderStatus::where('id',decrypt($id))->first();
        return view('admin.orderstatus.edit',compact('data'));
    }

    public function destroy($id)
    {
        OrderStatus::where('id',decrypt($id))->delete();
        return redirect()->route('admin.orderstatus.index')->with('error','Order Status deleted successfully.');
    }
}
