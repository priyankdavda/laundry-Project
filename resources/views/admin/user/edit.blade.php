<x-admin>
    @section('title', 'Edit User')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit User</h3>
            <div class="card-tools">
                <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-dark">Back</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.user.update', $user) }}" method="POST">
                @method('PUT')
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">

                <div class="row">
                    <!-- Name -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Name:*</label>
                            <input type="text" class="form-control" name="name" required
                                value="{{ old('name', $user->name) }}">
                            <x-error>name</x-error>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email" class="form-label">Email:*</label>
                            <input type="email" class="form-control" name="email" required
                                value="{{ old('email', $user->email) }}">
                            <x-error>email</x-error>
                        </div>
                    </div>

                    <!-- Mobile -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="mobile" class="form-label">Mobile:*</label>
                            <input type="text" class="form-control" name="mobile" required
                                value="{{ old('mobile', $user->mobile) }}">
                            <x-error>mobile</x-error>
                        </div>
                    </div>

                    <!-- User Type -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="user_type" class="form-label">User Type:*</label>
                            <select name="user_type" id="user_type" class="form-control" required>
                                @foreach (['USER','ONLINE_USER','RUNNER'] as $type)
                                    <option value="{{ $type }}" {{ $user->user_type === $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            <x-error>user_type</x-error>
                        </div>
                    </div>

                    <!-- House No -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="house_no" class="form-label">House No:*</label>
                            <input type="text" class="form-control" name="house_no" required
                                value="{{ old('house_no', $user->house_no) }}">
                            <x-error>house_no</x-error>
                        </div>
                    </div>

                    <!-- Landmark -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="landmark" class="form-label">Landmark:*</label>
                            <input type="text" class="form-control" name="landmark" required
                                value="{{ old('landmark', $user->landmark) }}">
                            <x-error>landmark</x-error>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="address" class="form-label">Address:*</label>
                            <textarea class="form-control" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
                            <x-error>address</x-error>
                        </div>
                    </div>

                    <!-- City -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="city_id" class="form-label">City:*</label>
                            <select name="city_id" class="form-control" required>
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ $user->city_id == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error>city_id</x-error>
                        </div>
                    </div>

                    <!-- State -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="state_id" class="form-label">State:*</label>
                            <select name="state_id" class="form-control" required>
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ $user->state_id == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error>state_id</x-error>
                        </div>
                    </div>

                    <!-- Pincode -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="pincode" class="form-label">Pincode:*</label>
                            <input type="text" class="form-control" name="pincode" required
                                value="{{ old('pincode', $user->pincode) }}">
                            <x-error>pincode</x-error>
                        </div>
                    </div>

                    <!-- Wallet Balance -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="wallet_balance" class="form-label">Wallet Balance:*</label>
                            <input type="number" step="0.01" class="form-control" name="wallet_balance" required
                                value="{{ old('wallet_balance', $user->wallet_balance) }}">
                            <x-error>wallet_balance</x-error>
                        </div>
                    </div>

                    <!-- Company Name -->
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="company_name" class="form-label">Company Name</label>
                            <textarea class="form-control" name="company_name" rows="2">{{ old('company_name', $user->company_name) }}</textarea>
                            <x-error>company_name</x-error>
                        </div>
                    </div>

                    <!-- GSTIN -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="gstin" class="form-label">GSTIN</label>
                            <input type="text" class="form-control" name="gstin"
                                value="{{ old('gstin', $user->gstin) }}">
                            <x-error>gstin</x-error>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="status" class="form-label">Status:*</label>
                            <select name="status" id="status" class="form-control" required>
                                @foreach(['ACTIVE','INACTIVE'] as $status)
                                    <option value="{{ $status }}" {{ $user->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            <x-error>status</x-error>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="role" class="form-label">Role:*</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="" selected disabled>Select the role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $user->roles[0]['name'] === $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error>role</x-error>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-lg-12">
                        <div class="float-right">
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-admin>
