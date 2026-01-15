<style>
    /* Hide / Show amount on hover */
    .amount-real {
        display: none;
        font-weight: 600;
    }
    .amount-hover-box:hover .amount-hidden {
        display: none;
    }
    .amount-hover-box:hover .amount-real {
        display: inline;
    }

    /* Finance cards height & alignment */
    .amount-hover-box .inner {
        min-height: 125px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Titles */
    .finance-title {
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 6px;
        text-align: center;
    }

    /* Labels */
    .finance-label {
        font-size: 12px;
        opacity: 0.9;
    }

    /* Footer */
    .amount-box-footer {
        background: rgba(0,0,0,0.18);
        color: #fff;
        text-align: center;
        padding: 5px;
        font-size: 12px;
        border-top: 1px solid rgba(255,255,255,0.2);
    }
</style>

<div class="row">
@role('admin|vendor')

    {{-- 1. TOTAL USERS --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $user }}</h3>
                <p>Total Users</p>
            </div>
            <div class="icon"><i class="fa fa-users"></i></div>
            <a href="{{ route('admin.user.index') }}" class="small-box-footer">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- 2. TOTAL CATEGORIES --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $category }}</h3>
                <p>Total Categories</p>
            </div>
            <div class="icon"><i class="fas fa-list-alt"></i></div>
            <a href="{{ route('admin.category.index') }}" class="small-box-footer">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- 3. TOTAL PRODUCTS --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $product }}</h3>
                <p>Total Products</p>
            </div>
            <div class="icon"><i class="fas fa-th"></i></div>
            <a href="{{ route('admin.product.index') }}" class="small-box-footer">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- 4. TOTAL ORDERS --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $collection }}</h3>
                <p>Total Orders</p>
            </div>
            <div class="icon"><i class="fas fa-file-pdf"></i></div>
            <a href="{{ route('admin.order.index') }}" class="small-box-footer">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- 5. TOTAL SELL --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success amount-hover-box">
            <div class="inner">
                <div class="finance-title">Total Sell</div>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="finance-label">Amount</div>
                        <span class="amount-hidden">Rs. ******</span>
                        <span class="amount-real">Rs. {{ number_format($totalAmount, 2) }}</span>
                    </div>
                    <div class="col-4">
                        <div class="finance-label">Paid</div>
                        <span class="amount-hidden">Rs. ******</span>
                        <span class="amount-real">Rs. {{ number_format($paidAmount, 2) }}</span>
                    </div>
                    <div class="col-4">
                        <div class="finance-label">Pending</div>
                        <span class="amount-hidden">Rs. ******</span>
                        <span class="amount-real">Rs. {{ number_format($pendingAmount, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="amount-box-footer">Overall</div>
        </div>
    </div>

    {{-- 6. MONTH SELL --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary amount-hover-box">
            <div class="inner">
                <div class="finance-title">{{ now()->format('F Y') }} Sell</div>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="finance-label">Amount</div>
                        <span class="amount-hidden">Rs. ******</span>
                        <span class="amount-real">Rs. {{ number_format($monthTotal, 2) }}</span>
                    </div>
                    <div class="col-4">
                        <div class="finance-label">Paid</div>
                        <span class="amount-hidden">Rs. ******</span>
                        <span class="amount-real">Rs. {{ number_format($monthPaid, 2) }}</span>
                    </div>
                    <div class="col-4">
                        <div class="finance-label">Pending</div>
                        <span class="amount-hidden">Rs. ******</span>
                        <span class="amount-real">Rs. {{ number_format($monthPending, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="amount-box-footer">Monthly Summary</div>
        </div>
    </div>

    {{-- 7. DAILY SELL --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success amount-hover-box">
            <div class="inner text-center">
                <div class="finance-title">Daily Sell</div>
                <h4>
                    <span class="amount-hidden">Rs. ******</span>
                    <span class="amount-real">Rs. {{ number_format($dailySell, 2) }}</span>
                </h4>
            </div>
            <div class="amount-box-footer">{{ now()->format('d M Y') }}</div>
        </div>
    </div>

    {{-- 8. DAILY COLLECTION --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning amount-hover-box">
            <div class="inner text-center">
                <div class="finance-title">Daily Collection</div>
                <h4>
                    <span class="amount-hidden">Rs. ******</span>
                    <span class="amount-real">Rs. {{ number_format($dailyCollection, 2) }}</span>
                </h4>
            </div>
            <div class="amount-box-footer">{{ now()->format('d M Y') }}</div>
        </div>
    </div>

@endrole
</div>
