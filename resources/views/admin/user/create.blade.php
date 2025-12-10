<x-admin>
    @section('title', 'Create User')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create User</h3>
            <div class="card-tools">
                <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-dark">Back</a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf

                <div class="row">

                    {{-- Name --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Name *</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                            <x-error>name</x-error>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            <x-error>email</x-error>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Password *</label>
                            <input type="password" class="form-control" name="password" required>
                            <x-error>password</x-error>
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Role *</label>
                            <select name="role" class="form-control" required>
                                <option value="" disabled selected>Select role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error>role</x-error>
                        </div>
                    </div>

                    {{-- Mobile --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Mobile *</label>
                            <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}" required>
                            <x-error>mobile</x-error>
                        </div>
                    </div>

                    {{-- User Type --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>User Type *</label>
                            <select name="user_type" class="form-control" required>
                                <option value="USER" {{ old('user_type')=='USER'?'selected':'' }}>USER</option>
                                <option value="ONLINE_USER" {{ old('user_type')=='ONLINE_USER'?'selected':'' }}>ONLINE USER</option>
                                <option value="RUNNER" {{ old('user_type')=='RUNNER'?'selected':'' }}>RUNNER</option>
                            </select>
                            <x-error>user_type</x-error>
                        </div>
                    </div>

                    {{-- House No --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>House No *</label>
                            <input type="text" name="house_no" class="form-control" value="{{ old('house_no') }}" required>
                            <x-error>house_no</x-error>
                        </div>
                    </div>

                    {{-- Landmark --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Landmark *</label>
                            <input type="text" name="landmark" class="form-control" value="{{ old('landmark') }}" required>
                            <x-error>landmark</x-error>
                        </div>
                    </div>

                    {{-- Address (Textarea) --}}
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Address *</label>
                            <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                            <x-error>address</x-error>
                        </div>
                    </div>

                    {{-- City Dropdown --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>City *</label>
                            <select name="city_id" class="form-control" required>
                                <option value="">Select City</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error>city_id</x-error>
                        </div>
                    </div>

                    {{-- State Dropdown --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>State *</label>
                            <select name="state_id" class="form-control" required>
                                <option value="">Select State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error>state_id</x-error>
                        </div>
                    </div>

                    {{-- Pincode --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Pincode *</label>
                            <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}" required>
                            <x-error>pincode</x-error>
                        </div>
                    </div>

                    {{-- Wallet Balance --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Wallet Balance *</label>
                            <input type="number" step="0.01" name="wallet_balance" class="form-control"
                                   value="{{ old('wallet_balance', 0.00) }}" required>
                            <x-error>wallet_balance</x-error>
                        </div>
                    </div>

                    {{-- Company Name (Textarea) --}}
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Company Name</label>
                            <textarea name="company_name" class="form-control" rows="2">{{ old('company_name') }}</textarea>
                            <x-error>company_name</x-error>
                        </div>
                    </div>

                    {{-- GSTIN --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>GSTIN</label>
                            <input type="text" name="gstin" class="form-control" value="{{ old('gstin') }}">
                            <x-error>gstin</x-error>
                        </div>
                    </div>

                    {{-- Status --}}
                    {{--  <div class="col-lg-6">
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="ACTIVE" {{ old('status')=='ACTIVE'?'selected':'' }}>ACTIVE</option>
                                <option value="INACTIVE" {{ old('status')=='INACTIVE'?'selected':'' }}>INACTIVE</option>
                            </select>
                            <x-error>status</x-error>
                        </div>
                    </div>  --}}

                    {{-- Submit --}}
                    <div class="col-lg-12 text-right mt-3">
                        <button class="btn btn-primary" type="submit">Save User</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</x-admin>
