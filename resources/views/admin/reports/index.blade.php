<x-admin>
    @section('title', 'Report')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Report</h3>
        </div>

        <div class="card-body">

            {{-- FILTER --}}
            <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-2 mb-3">

                <div class="col-md-3">
                    <label>From Date</label>
                    <input
                        type="date"
                        name="from_date"
                        value="{{ request('from_date', $from) }}"
                        class="form-control"
                    >
                </div>

                <div class="col-md-3">
                    <label>To Date</label>
                    <input
                        type="date"
                        name="to_date"
                        value="{{ request('to_date', $to) }}"
                        class="form-control"
                    >
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm">Submit</button>
                    <a href="{{ route('admin.reports.index') }}"
                       class="btn btn-secondary btn-sm  ml-1">
                        Clear
                    </a>
                </div>



            </form>

            <div class="row">

                {{-- SELL --}}
                <div class="col-md-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>
                                <span class="amount-text" data-amount="{{ number_format($sell, 2) }}">
                                    ₹ ******
                                </span>

                                <a href="javascript:void(0)"
                                class="toggle-amount text-white ml-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </h3>
                            <p>Sell</p>
                        </div>
                    </div>
                </div>

                {{-- COLLECTION --}}
                <div class="col-md-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><span class="amount-text" data-amount="{{ number_format($collection, 2) }}">
                                    ₹ ******
                                </span>

                                <a href="javascript:void(0)"
                                class="toggle-amount text-white ml-2">
                                    <i class="fas fa-eye"></i>
                                </a></h3>
                            <p>Collection</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-admin>

<script>
    $(document).on('click', '.toggle-amount', function () {

        let icon = $(this).find('i');
        let amountSpan = $(this).siblings('.amount-text');

        if (icon.hasClass('fa-eye')) {
            amountSpan.text('₹ ' + amountSpan.data('amount'));
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            amountSpan.text('₹ ******');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
</script>

