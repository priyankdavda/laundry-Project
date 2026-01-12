<x-admin>
    @section('title', 'Create Order')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create Order</h3>
            <div class="card-tools">
                <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-dark">Back</a>
            </div>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="order-form" action="{{ route('admin.order.store') }}" method="POST">
                @csrf

                {{-- =================== TOP SECTION: CUSTOMER + PICKUP + COMPANY =================== --}}
                <div class="row">

                    {{-- CUSTOMER DETAILS --}}
                    <div class="col-md-4">
                        <div class="border p-3 mb-3" style="background:#f5fff5;">
                            <h5 class="mb-3">Customer Details</h5>

                            {{-- Customer Type --}}
                            <div class="mb-2">
                                <label class="mr-3">
                                    <input type="radio" name="customer_type" value="registered"
                                           {{ old('customer_type','registered')=='registered' ? 'checked' : '' }}>
                                    Registered User
                                </label>
                                <label>
                                    <input type="radio" name="customer_type" value="new"
                                           {{ old('customer_type')=='new' ? 'checked' : '' }}>
                                    New User
                                </label>
                            </div>

                            {{-- Registered user dropdown --}}
                            <div class="form-group" id="registered-user-block">
                                <label>Registered User</label>
                                <select name="registered_user_id" id="registered_user_id" class="form-control">
                                    <option value="">Select user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('registered_user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->mobile }} - {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Snapshot fields --}}
                            <div class="form-group">
                                <label>Name *</label>
                                <input type="text" class="form-control" name="customer_name"
                                       id="customer_name" value="{{ old('customer_name') }}">
                                <x-error>customer_name</x-error>
                            </div>

                            <div class="form-group">
                                <label>Mobile *</label>
                                <input type="text" class="form-control" name="customer_mobile"
                                       id="customer_mobile" value="{{ old('customer_mobile') }}">
                                <x-error>customer_mobile</x-error>
                            </div>

                            <div class="form-group">
                                <label>House No *</label>
                                <input type="text" class="form-control" name="house_no"
                                       id="house_no" value="{{ old('house_no') }}">
                                <x-error>house_no</x-error>
                            </div>

                            <div class="form-group">
                                <label>Landmark *</label>
                                <input type="text" class="form-control" name="landmark"
                                       id="landmark" value="{{ old('landmark') }}">
                                <x-error>landmark</x-error>
                            </div>

                            <div class="form-group">
                                <label>Address *</label>
                                <input type="text" class="form-control" name="address"
                                       id="address" value="{{ old('address') }}">
                                <x-error>address</x-error>
                            </div>

                            <div class="form-group">
                                <label>City *</label>
                                <select name="city_id" id="city_id" class="form-control" required>
                                    <option value="">Select City</option>
                                    @foreach($cities as $c)
                                        <option value="{{ $c->id }}" {{ old('city_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-error>city_id</x-error>
                            </div>

                            <div class="form-group">
                                <label>State *</label>
                                <select name="state_id" id="state_id" class="form-control" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $s)
                                        <option value="{{ $s->id }}" {{ old('state_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-error>state_id</x-error>
                            </div>

                            <div class="form-group">
                                <label>Pincode *</label>
                                <input type="text" class="form-control" name="pincode"
                                       id="pincode" value="{{ old('pincode') }}">
                                <x-error>pincode</x-error>
                            </div>

                            <div class="form-group">
                                <label>Wallet Balance</label>
                                <!-- visible field: readonly for registered user, editable for new user -->
                                <input type="text" class="form-control" name="wallet_balance_visible"
                                    id="wallet_balance_visible"
                                    value="{{ old('wallet_balance_visible', old('wallet_balance', '')) }}"
                                    autocomplete="off">
                                <!-- hidden field to actually submit to server -->
                                <input type="hidden" name="wallet_balance" id="wallet_balance_hidden" value="{{ old('wallet_balance', 0) }}">
                                <x-error>wallet_balance</x-error>
                            </div>

                        </div>
                    </div>

                    {{-- PICKUP & DELIVERY --}}
                    <div class="col-md-4">
                        <div class="border p-3 mb-3" style="background:#fffaf0;">
                            <h5 class="mb-3">Pick Up &amp; Delivery</h5>

                            <div class="form-group">
                                <label>Pickup Date *</label>
                                <input type="date" name="pickup_date" class="form-control"
                                       value="{{ old('pickup_date', now()->toDateString()) }}">
                                <x-error>pickup_date</x-error>
                            </div>

                            <div class="form-group">
                                <label>Pickup Time *</label>
                                <select name="pickup_timeslot" class="form-control">
                                    <option value="">Select Pick Up Time</option>
                                    @foreach($timeslots ?? [] as $slot)
                                        <option value="{{ $slot }}"
                                            {{ old('pickup_timeslot') == $slot ? 'selected' : '' }}>
                                            {{ $slot }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-error>pickup_timeslot</x-error>
                            </div>

                            <div class="form-group">
                                <label>Delivery Date *</label>
                                <input type="date" name="delivery_date" class="form-control"
                                       value="{{ old('delivery_date') }}">
                                <x-error>delivery_date</x-error>
                            </div>

                            <div class="form-group">
                                <label>Delivery Time *</label>
                                <select name="delivery_timeslot" class="form-control">
                                    <option value="">Select Delivery Time</option>
                                    @foreach($timeslots ?? [] as $slot)
                                        <option value="{{ $slot }}"
                                            {{ old('delivery_timeslot') == $slot ? 'selected' : '' }}>
                                            {{ $slot }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-error>delivery_timeslot</x-error>
                            </div>
                        </div>
                    </div>

                    {{-- COMPANY DETAILS --}}
                    <div class="col-md-4">
                        <div class="border p-3 mb-3" style="background:#f5fff5;">
                            <h5 class="mb-3">Company Details</h5>

                            <div class="form-group">
                                <label>Company Name</label>
                                <textarea name="company_name" id="company_name" class="form-control" rows="2">{{ old('company_name') }}</textarea>
                                <x-error>company_name</x-error>
                            </div>

                            <div class="form-group">
                                <label>GSTIN</label>
                                <input type="text" name="gstin" id="gstin" class="form-control"
                                       value="{{ old('gstin') }}">
                                <x-error>gstin</x-error>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =================== BOTTOM SECTION: PRODUCTS + ORDER DETAILS =================== --}}
                <div class="row mt-3">

                    {{-- PRODUCTS (LEFT) --}}
                    <div class="col-md-4">
                        <div class="border p-3 mb-3" style="background:#f0f9ff;">
                            <h5 class="mb-3">Products</h5>

                            <div class="form-group">
                                <label>Category</label>
                                <select id="item_category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories ?? [] as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Product</label>
                                    <select id="item_product_id" class="form-control">
                                        <option value="">Select Product</option>
                                        @foreach($products ?? [] as $prod)
                                            <option value="{{ $prod->id }}" data-category="{{ $prod->category_id }}" data-amount="{{ number_format($prod->amount ?? $prod->price ?? 0, 2, '.', '') }}">
                                            {{ $prod->name }}
                                            </option>
                                        @endforeach
                                    </select>
                            </div>

                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" step="0.01" id="item_unit_price" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" step="0.01" id="item_quantity" class="form-control" value="1">
                            </div>

                            <div class="form-group">
                                <label>No. of Clothes (optional)</label>
                                <input type="number" id="item_clothes" class="form-control" value="1">
                            </div>

                            <div class="form-group">
                                <label>Remark</label>
                                <textarea id="item_remark" class="form-control" rows="2"></textarea>
                            </div>

                            <button type="button" class="btn btn-primary" id="btn-add-item">Add</button>
                            <span id="add-item-message" class="text-success ml-2" style="display:none;">
                                Item added successfully.
                            </span>
                        </div>
                    </div>

                    {{-- ORDER DETAILS (RIGHT) --}}
                    <div class="col-md-8">
                        <div class="border p-3 mb-3" style="background:#fff5f5;">
                            <h5 class="mb-3">Order Details</h5>

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;">S.No.</th>
                                            <th>Product Name (No. of Clothes)</th>
                                            <th class="text-right" style="width:80px;">Quantity</th>
                                            <th class="text-right" style="width:100px;">Price (Rs.)</th>
                                            <th class="text-right" style="width:110px;">Total Price (Rs.)</th>
                                            <th style="width:60px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="order-items-body">
                                        <tr id="no-items-row">
                                            <td colspan="6" class="text-center text-muted">No items in cart</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="1" class="text-right">Total Clothes:</th>
                                            <th id="total_items">0</th>
                                            <th class="text-right">Qty: <span id="total_qty">0</span></th>
                                            <th class="text-right">Totalcost</th>
                                            <th class="text-right" id="subtotal_display">0.00</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="text-right align-middle">Discount</td>
                                            <td>
                                                <input type="number" step="0.01" name="discount_amount"
                                                       id="discount_amount" class="form-control form-control-sm"
                                                       value="{{ old('discount_amount',0) }}">
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="text-right align-middle">Grand Total</td>
                                            <td class="text-right" id="grand_total_display">0.00</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Paid Amount (optional)</label>
                                        <input type="number" step="0.01" name="paid_amount"
                                               id="paid_amount" class="form-control"
                                               value="{{ old('paid_amount') }}">
                                    </div>
                                </div>
                                {{--  <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Wallet Used Amount (optional)</label>
                                        <input type="number" step="0.01" name="wallet_used_amount"
                                               id="wallet_used_amount" class="form-control"
                                               value="{{ old('wallet_used_amount') }}">
                                    </div>
                                </div>  --}}
                            </div>

                            <div class="form-group">
                                <label>Order Remark</label>
                                <textarea name="order_remark" class="form-control" rows="2"></textarea>
                            </div>

                        </div>
                    </div>
                </div>



                {{-- HIDDEN FIELD FOR ITEMS JSON --}}
                <input type="hidden" name="items_json" id="items_json">

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </div>

            </form>
        </div>
    </div>

    {{-- =================== SIMPLE JS CART LOGIC =================== --}}
<script>
(function () {
    // Elements (guarded)
    const itemsBody      = document.getElementById('order-items-body');
    const noItemsRow     = document.getElementById('no-items-row');
    const itemsJsonInput = document.getElementById('items_json');
    const subtotalEl     = document.getElementById('subtotal_display');
    const grandTotalEl   = document.getElementById('grand_total_display');
    const totalQtyEl     = document.getElementById('total_qty');
    const totalItemsEl   = document.getElementById('total_items');
    const discountInput  = document.getElementById('discount_amount');
    const addMsg         = document.getElementById('add-item-message');
    const btnAddItem     = document.getElementById('btn-add-item');

    // Wallet elements (may be missing; guarded)
    const walletVisible  = document.getElementById('wallet_balance_visible');
    const walletHidden   = document.getElementById('wallet_balance_hidden');

    let items = [];

    // Product/category elements
    const productSelect = document.getElementById('item_product_id');
    const priceInput    = document.getElementById('item_unit_price');
    const categorySelect = document.getElementById('item_category_id');

    // Build products map from existing options (normalize keys to strings)
    const productsByCategory = {};
    if (productSelect) {
        Array.from(productSelect.options).forEach(opt => {
            const val = opt.value;
            if (!val) return; // skip placeholder
            const cat = (opt.getAttribute('data-category') ?? '').toString().trim();
            const amount = opt.getAttribute('data-amount') ?? opt.getAttribute('data-price') ?? '';
            if (!productsByCategory[cat]) productsByCategory[cat] = [];
            productsByCategory[cat].push({
                id: val,
                text: opt.text,
                amount: amount
            });
        });
    }

    function makePlaceholder() {
        const ph = document.createElement('option');
        ph.value = '';
        ph.text = 'Select Product';
        return ph;
    }

    function populateProducts(list) {
        if (!productSelect) return;
        productSelect.innerHTML = '';
        productSelect.appendChild(makePlaceholder());

        (list || []).forEach(p => {
            const o = document.createElement('option');
            o.value = p.id;
            o.text = p.text;
            if (p.amount !== null && p.amount !== undefined && p.amount !== '') {
                o.setAttribute('data-amount', parseFloat(p.amount).toFixed(2));
            }
            productSelect.appendChild(o);
        });

        // trigger change so price auto-fills if needed
        productSelect.dispatchEvent(new Event('change'));
    }

    // Category change -> filter products
    if (categorySelect) {
        categorySelect.addEventListener('change', function () {
            const raw = (this.value ?? '').toString().trim();
            const catId = raw;
            let list = productsByCategory[catId] ?? null;

            if ((!list || !list.length) && Object.keys(productsByCategory).length) {
                for (const k of Object.keys(productsByCategory)) {
                    if (k.toString().trim() === catId.toString().trim() || k.toString().trim() === String(Number(catId))) {
                        list = productsByCategory[k];
                        break;
                    }
                }
            }

            if (!list || !list.length) {
                productSelect.innerHTML = '';
                productSelect.appendChild(makePlaceholder());
                if (priceInput) priceInput.value = '';
                return;
            }

            populateProducts(list);
        });

        // only trigger initial filter if a category is pre-selected (edit mode)
        if (categorySelect.value) {
            categorySelect.dispatchEvent(new Event('change'));
        } else {
            if (productSelect) {
                productSelect.innerHTML = '';
                productSelect.appendChild(makePlaceholder());
                if (priceInput) priceInput.value = '';
            }
        }
    }

    // Product change -> fill price from data-amount OR fetch from server if missing
    if (productSelect && priceInput) {
        productSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (!opt) {
                priceInput.value = '';
                return;
            }

            const dataAmount = opt.getAttribute('data-amount');
            if (dataAmount !== null && dataAmount !== undefined && dataAmount !== '') {
                priceInput.value = parseFloat(dataAmount).toFixed(2);
                return;
            }

            const prodId = opt.value;
            if (!prodId) {
                priceInput.value = '';
                return;
            }

            let url = "{{ route('admin.order.get-product', ['product' => '__ID__']) }}";
            url = url.replace('__ID__', prodId);

            fetch(url, { headers: { 'Accept': 'application/json' }})
                .then(resp => {
                    if (!resp.ok) throw new Error('Network response was not ok');
                    return resp.json();
                })
                .then(data => {
                    const amt = (data.amount !== undefined && data.amount !== null) ? data.amount : '';
                    if (amt !== '') {
                        priceInput.value = parseFloat(amt).toFixed(2);
                        try { opt.setAttribute('data-amount', parseFloat(amt).toFixed(2)); } catch(e){}
                    } else {
                        priceInput.value = '';
                    }

                    if (data.category_id && document.getElementById('item_category_id')) {
                        document.getElementById('item_category_id').value = data.category_id;
                        document.getElementById('item_category_id').dispatchEvent(new Event('change'));
                    }
                })
                .catch(err => {
                    console.error('Failed to load product:', err);
                    alert('Unable to load product details.');
                });
        });
        // do NOT auto-dispatch change here unless product is actually pre-selected in edit mode
    }

    // --- Wallet helpers ---
    function setWalletValue(amount, mode = 'registered') {
        // normalize
        const val = (amount !== undefined && amount !== null && amount !== '') ? parseFloat(amount).toFixed(2) : '';
        if (walletVisible) {
            walletVisible.value = val;
            walletVisible.setAttribute('value', val); // ⭐ VERY IMPORTANT for old()
        }
        if (walletHidden) walletHidden.value = (val === '' ? 0 : val);

        if (walletVisible) {
            if (mode === 'registered') {
                walletVisible.setAttribute('readonly', 'readonly');
                walletVisible.classList.add('bg-light');
            } else {
                walletVisible.removeAttribute('readonly');
                walletVisible.classList.remove('bg-light');
            }
        }
    }

    // sync visible -> hidden when admin edits visible (for new user)
    if (walletVisible && walletHidden) {
        walletVisible.addEventListener('input', function () {
            const v = this.value;
            walletHidden.value = (v === '' ? 0 : parseFloat(v || 0).toFixed(2));
        });
    }

    // --- cart helpers (render, totals, add item) ---
    function renderItems() {
        if (!itemsBody) return;
        itemsBody.innerHTML = '';

        if (!items.length) {
            if (noItemsRow) itemsBody.appendChild(noItemsRow.cloneNode(true));
            return;
        }

        items.forEach((item, index) => {
            const tr = document.createElement('tr');

            const tdIndex = document.createElement('td');
            tdIndex.textContent = index + 1;
            tr.appendChild(tdIndex);

            const tdName = document.createElement('td');
            let nameText = item.product_name || '';
            if (item.clothes && item.clothes > 0) nameText += ' (' + item.clothes + ')';
            if (item.remark) nameText += ' Remark : ' + item.remark;
            tdName.textContent = nameText;
            tr.appendChild(tdName);

            const tdQty = document.createElement('td');
            tdQty.classList.add('text-right');
            tdQty.textContent = (parseFloat(item.quantity) || 0).toFixed(2);
            tr.appendChild(tdQty);

            const tdPrice = document.createElement('td');
            tdPrice.classList.add('text-right');
            tdPrice.textContent = (parseFloat(item.unit_price) || 0).toFixed(2);
            tr.appendChild(tdPrice);

            const tdTotal = document.createElement('td');
            tdTotal.classList.add('text-right');
            tdTotal.textContent = ((parseFloat(item.unit_price) || 0) * (parseFloat(item.quantity) || 0)).toFixed(2);
            tr.appendChild(tdTotal);

            const tdAction = document.createElement('td');
            const btnDel = document.createElement('button');
            btnDel.type = 'button';
            btnDel.className = 'btn btn-sm btn-link text-danger';
            btnDel.setAttribute('aria-label', 'Remove item');
            btnDel.textContent = '🗑';
            btnDel.addEventListener('click', function () {
                items.splice(index, 1);
                updateAll();
            });
            tdAction.appendChild(btnDel);
            tr.appendChild(tdAction);

            itemsBody.appendChild(tr);
        });
    }

    function recalcTotals() {
        let subtotal = 0;
        let totalQty = 0;
        let totalClothes = 0;

        items.forEach(item => {
            const price = parseFloat(item.unit_price) || 0;
            const qty = parseFloat(item.quantity) || 0;
            const clothes = parseInt(item.clothes) || 0;
            subtotal += price * qty;
            totalQty += qty;
            totalClothes += clothes;
        });

        if (subtotalEl) subtotalEl.textContent = subtotal.toFixed(2);
        if (totalQtyEl) totalQtyEl.textContent = totalQty;
        if (totalItemsEl) totalItemsEl.textContent = totalClothes || items.length;

        const discount = parseFloat(discountInput ? discountInput.value : 0) || 0;
        const grand = subtotal - discount;
        if (grandTotalEl) grandTotalEl.textContent = grand.toFixed(2);

        if (itemsJsonInput) itemsJsonInput.value = JSON.stringify(items);
    }

    function updateAll() { renderItems(); recalcTotals(); }

    // Add item button
    if (btnAddItem) {
        btnAddItem.addEventListener('click', function () {
            const catId   = document.getElementById('item_category_id') ? document.getElementById('item_category_id').value : '';
            const prodSel = document.getElementById('item_product_id');
            const prodId  = prodSel ? prodSel.value : '';
            const prodName = prodSel ? (prodSel.options[prodSel.selectedIndex]?.text || '') : '';
            const price   = parseFloat(document.getElementById('item_unit_price')?.value || 0);
            const qty     = parseFloat(document.getElementById('item_quantity')?.value || 0);
            const clothes = parseInt(document.getElementById('item_clothes')?.value || 0);
            const remark  = document.getElementById('item_remark')?.value || '';

            if (!prodId || !qty || !price) {
                alert('Please select product, price and quantity.');
                return;
            }

            items.push({
                category_id: catId,
                product_id: prodId,
                product_name: prodName,
                unit_price: price,
                quantity: qty,
                clothes: clothes,
                remark: remark
            });

            // ---- CLEAR only the product form fields (not the whole order form) ----
            if (categorySelect) categorySelect.selectedIndex = 0;
            if (prodSel) prodSel.selectedIndex = 0;
            if (priceInput) priceInput.value = '';
            const qtyEl = document.getElementById('item_quantity');
            if (qtyEl) qtyEl.value = 1;
            const clothesEl = document.getElementById('item_clothes');
            if (clothesEl) clothesEl.value = 1;
            const remarkEl = document.getElementById('item_remark');
            if (remarkEl) remarkEl.value = '';

            updateAll();

            if (addMsg) {
                addMsg.style.display = 'inline';
                setTimeout(() => addMsg.style.display = 'none', 1500);
            }
        });
    }

    if (discountInput) discountInput.addEventListener('input', recalcTotals);

    const orderForm = document.getElementById('order-form');
    if (orderForm) {
        orderForm.addEventListener('submit', function (e) {

            const pickupDate   = document.querySelector('input[name="pickup_date"]')?.value;
            const pickupTime   = document.querySelector('select[name="pickup_timeslot"]')?.value;
            const deliveryDate = document.querySelector('input[name="delivery_date"]')?.value;
            const deliveryTime = document.querySelector('select[name="delivery_timeslot"]')?.value;

            // 🔴 Pickup & Delivery validation
            if (!pickupDate) {
                alert('Please select Pickup Date');
                e.preventDefault();
                return;
            }

            if (!pickupTime) {
                alert('Please select Pickup Time');
                e.preventDefault();
                return;
            }

            if (!deliveryDate) {
                alert('Please select Delivery Date');
                e.preventDefault();
                return;
            }

            if (!deliveryTime) {
                alert('Please select Delivery Time');
                e.preventDefault();
                return;
            }

            // 🔴 At least one product validation
            if (!items || items.length === 0) {
                alert('Please add at least one product to the order');
                e.preventDefault();
                return;
            }


            // ✅ All OK → continue submit
            recalcTotals();

            if (itemsJsonInput && !itemsJsonInput.value) {
                itemsJsonInput.value = JSON.stringify(items);
            }
        });
    }



    // ---------------- Registered user fetch (AUTO-FILL) ----------------
    const registeredSelect = document.getElementById('registered_user_id');
    if (registeredSelect) {
        registeredSelect.addEventListener('change', function () {
            const userId = this.value;
            if (!userId) {
                // cleared user -> clear wallet and other fields
                setWalletValue('', 'registered');
                return;
            }

            let url = "{{ route('admin.order.get-user', ['user' => '__ID__']) }}";
            url = url.replace('__ID__', userId);

            fetch(url, { headers: { 'Accept': 'application/json' }})
                .then(resp => {
                    if (!resp.ok) throw new Error('Network response was not ok');
                    return resp.json();
                })
                .then(data => {
                    if (document.getElementById('customer_name')) document.getElementById('customer_name').value = data.name ?? '';
                    if (document.getElementById('customer_mobile')) document.getElementById('customer_mobile').value = data.mobile ?? '';
                    if (document.getElementById('house_no')) document.getElementById('house_no').value = data.house_no ?? '';
                    if (document.getElementById('landmark')) document.getElementById('landmark').value = data.landmark ?? '';
                    if (document.getElementById('address')) document.getElementById('address').value = data.address ?? '';

                    if (document.getElementById('city_id')) {
                        if (data.city_id) {
                            document.getElementById('city_id').value = data.city_id;
                        } else if (data.city) {
                            const citySel = document.getElementById('city_id');
                            for (let i=0;i<citySel.options.length;i++){
                                if (citySel.options[i].text.trim().toLowerCase() === (data.city || '').toString().trim().toLowerCase()){
                                    citySel.selectedIndex = i; break;
                                }
                            }
                        }
                    }

                    if (document.getElementById('state_id')) {
                        if (data.state_id) {
                            document.getElementById('state_id').value = data.state_id;
                        } else if (data.state) {
                            const stateSel = document.getElementById('state_id');
                            for (let i=0;i<stateSel.options.length;i++){
                                if (stateSel.options[i].text.trim().toLowerCase() === (data.state || '').toString().trim().toLowerCase()){
                                    stateSel.selectedIndex = i; break;
                                }
                            }
                        }
                    }

                    if (document.getElementById('pincode')) document.getElementById('pincode').value = data.pincode ?? '';
                    if (document.getElementById('company_name')) document.getElementById('company_name').value = data.company_name ?? '';
                    if (document.getElementById('gstin')) document.getElementById('gstin').value = data.gstin ?? '';

                    // -- WALLET: read from server response keys (try multiple common keys)
                    const walletAmt = data.wallet_balance ?? data.wallet ?? data.wallet_amount ?? 0;
                    setWalletValue(walletAmt, 'registered');
                })
                .catch(err => {
                    console.error('Unable to load user details.', err);
                    alert('Unable to load user details.');
                    setWalletValue('', 'registered');
                });
        });
    }

    // Customer type toggle (registered/new) - keep behavior + wallet handling
    const radioCustomerType = document.querySelectorAll('input[name="customer_type"]');
    const registeredBlock   = document.getElementById('registered-user-block');
    if (radioCustomerType && radioCustomerType.length && registeredBlock) {
        radioCustomerType.forEach(r => {
            r.addEventListener('change', function () {
                if (this.value === 'registered') {
                    registeredBlock.style.display = 'block';
                    // make wallet readonly until a user is chosen (if any)
                    // if hidden has value keep it, else clear
                    const current = walletHidden ? walletHidden.value : '';
                    setWalletValue(current, 'registered');
                } else {
                    registeredBlock.style.display = 'none';
                    if (document.getElementById('registered_user_id')) document.getElementById('registered_user_id').value = '';

                    // new user: clear wallet and make editable
                    setWalletValue('', 'new');

                    // clear other customer fields
                    if (document.getElementById('customer_name')) document.getElementById('customer_name').value = '';
                    if (document.getElementById('customer_mobile')) document.getElementById('customer_mobile').value = '';
                    if (document.getElementById('house_no')) document.getElementById('house_no').value = '';
                    if (document.getElementById('landmark')) document.getElementById('landmark').value = '';
                    if (document.getElementById('address')) document.getElementById('address').value = '';
                    if (document.getElementById('city_id')) document.getElementById('city_id').value = '';
                    if (document.getElementById('state_id')) document.getElementById('state_id').value = '';
                    if (document.getElementById('pincode')) document.getElementById('pincode').value = '';
                    if (document.getElementById('company_name')) document.getElementById('company_name').value = '';
                    if (document.getElementById('gstin')) document.getElementById('gstin').value = '';
                }
            });
        });

        // initial
        const selectedType = document.querySelector('input[name="customer_type"]:checked')?.value || 'registered';
        registeredBlock.style.display = (selectedType === 'registered') ? 'block' : 'none';

        // if initial mode is new -> ensure wallet editable
        if (selectedType === 'new') setWalletValue('', 'new');
    }

    // If page loads and a registered user already selected (edit mode), trigger fetch to populate wallet & fields
    (function initialRegisteredLoad() {
        try {
            const sel = document.getElementById('registered_user_id');
            if (sel && sel.value) {
                sel.dispatchEvent(new Event('change'));
            } else {
                // if no registered user selected, ensure wallet behaves based on radio
                const selType = document.querySelector('input[name="customer_type"]:checked')?.value || 'registered';
                if (selType === 'registered') {
                    // keep empty readonly
                    setWalletValue(walletHidden ? walletHidden.value : '', 'registered');
                } else {
                    setWalletValue(walletHidden ? walletHidden.value : '', 'new');
                }
            }
        } catch(e) { /* ignore */ }
    })();

})();
</script>




</x-admin>
