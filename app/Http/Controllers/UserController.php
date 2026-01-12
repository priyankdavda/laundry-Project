<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\City;
use App\Models\State;

class UserController extends Controller
{
    public function __construct()
    {
        $roles = Role::all();
        view()->share('roles',$roles);
    }
    public function index()
    {
         $data = User::role('user')
                ->orderBy('id', 'DESC')
                ->get();
        return view('admin.user.index', compact('data'));
    }
    public function create()
    {
        $cities = City::all();
        $states = State::all();

        return view('admin.user.create', compact('cities', 'states'));
        // return view('admin.user.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            'mobile' => 'required|unique:users,mobile',
            'user_type' => 'required',
            'house_no' => 'required',
            'landmark' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'pincode' => 'required',
            'wallet_balance' => 'required'
        ]);

        $nextId = (User::max('id') ?? 0) + 1;
        $customerCode = 'LCUST' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'customer_code' => $customerCode, // MUST
            'mobile' => $request->mobile,
            'user_type' => $request->user_type,
            'house_no' => $request->house_no,
            'landmark' => $request->landmark,
            'address' => $request->address,
            'city_id' => $request->city_id,
            'state_id' => $request->state_id,
            'pincode' => $request->pincode,
            'wallet_balance' => $request->wallet_balance,
            'company_name' => $request->company_name,
            'gstin' => $request->gstin,
            'status' => 'ACTIVE',
            // 'status' => $request->status,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.user.index')
            ->with('success', 'User created successfully.');
    }


    public function edit($id)
    {
        $user = User::where('id',decrypt($id))->first();
        $cities = City::all();
        $states = State::all();

    return view('admin.user.edit', compact('user','cities','states'));
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'           => ['required', 'string'],

            // NEW FIELDS
            'mobile'         => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'user_type'      => ['required', Rule::in(['USER','ONLINE_USER','RUNNER'])],
            'house_no'       => ['required', 'string', 'max:100'],
            'landmark'       => ['required', 'string', 'max:150'],
            'address'        => ['required', 'string', 'max:255'],
            'city_id'        => ['required', 'integer'],
            'state_id'       => ['required', 'integer'],
            'pincode'        => ['required', 'string', 'max:10'],
            'wallet_balance' => ['required', 'numeric'],
            'company_name'   => ['nullable', 'string', 'max:150'],
            'gstin'          => ['nullable', 'string', 'max:30'],
            'status'         => ['required', Rule::in(['ACTIVE','INACTIVE'])],
        ]);

        // Find user by ID
        $user = User::find($request->id);

        // Update user details
        $user->update([
            'name'           => $request->name,
            'email'          => $request->email,
            'mobile'         => $request->mobile,
            'user_type'      => $request->user_type,
            'house_no'       => $request->house_no,
            'landmark'       => $request->landmark,
            'address'        => $request->address,
            'city_id'        => $request->city_id,
            'state_id'       => $request->state_id,
            'pincode'        => $request->pincode,
            'wallet_balance' => $request->wallet_balance,
            'company_name'   => $request->company_name,
            'gstin'          => $request->gstin,
            'status'         => $request->status,
        ]);

        // Assign role
        $user->syncRoles([$request->role]);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        User::where('id',decrypt($id))->delete();
        return redirect()->back()->with('success','User deleted successfully.');
    }
}
