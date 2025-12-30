<x-admin>
    @section('title', 'Orders')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Orders</h3>
            <div class="card-tools">
                <a href="{{ route('admin.order.create') }}" class="btn btn-sm btn-primary">Add Order</a>
            </div>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Customer</th>
                        <th>Mobile</th>
                        <th>Total</th>
                        <th>Pending</th>
                        <th>Status</th>
                        <th>Pick Up Date / Time</th>
                        <th>Delivery Date / Time</th>
                        <th>Order Date</th>

                        <th width="100">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_mobile }}</td>

                            <td>
                                ₹ {{ number_format($order->total_amount, 2) }}
                            </td>

                            {{-- ✅ Pending Amount --}}
                            <td>
                                ₹ {{ number_format($order->pending_amount, 2) }}
                            </td>

                            {{-- ✅ Payment Status with Colors --}}
                            <td>
                                @if($order->payment_status === 'PAID')
                                    <span class="badge badge-success">PAID</span>
                                @elseif($order->payment_status === 'PARTIAL')
                                    <span class="badge badge-warning">PARTIAL</span>
                                @else
                                    <span class="badge badge-danger">UNPAID</span>
                                @endif
                            </td>

                            {{-- Pick Up --}}
                            <td>
                                {{ \Carbon\Carbon::parse($order->pickup_date)->format('d-m-Y') }}<br>
                                <small class="text-muted">{{ $order->pickup_timeslot }}</small>
                                <br>
                                 <button class="btn btn-sm btn-warning mb-1"
        onclick="openPickupModal(
    {{ $order->id }},
    '{{ \Carbon\Carbon::parse($order->pickup_date)->format('Y-m-d') }}',
    '{{ $order->pickup_timeslot }}'
)">
    Change Pick Up
</button>
                                <br>

                                    <button class="btn btn-sm btn-success"
                                            onclick="openAssignPickupModal({{ $order->id }})">
                                        Assign Pick Up
                                    </button>
                            </td>

                            {{-- Delivery --}}
                            <td>
                                {{ \Carbon\Carbon::parse($order->delivery_date)->format('d-m-Y') }}<br>
                                <small class="text-muted">{{ $order->delivery_timeslot }}</small>
                                <br>
                                <button
    class="btn btn-sm btn-warning btn-change-delivery"
    data-id="{{ $order->id }}"
    data-date="{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}"
    data-time="{{ $order->delivery_timeslot }}"
>
    Change Delivery
</button>

                                <br>
                                <button class="btn btn-sm btn-primary btn-assign-delivery"
                                        data-id="{{ $order->id }}"
                                        data-runner="{{ $order->delivery_assign_id }}"
                                        data-date="{{ $order->delivery_date }}"
                                        data-time="{{ $order->delivery_timeslot }}">
                                    Assign Delivery
                                </button>
                            </td>

                            {{-- Order Date --}}
                            <td>
                                {{ $order->created_at->format('d-m-Y') }}<br>
                                <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                            </td>


                            <td>
                                <button
                                    class="btn btn-sm btn-info btn-view-order"
                                    data-id="{{ $order->id }}">
                                    View Order
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $orders->links() }}
        </div>
    </div>
</x-admin>
<!-- View Order Modal -->
<div class="modal fade" id="viewOrderModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Order Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body" id="order-modal-body">
                <div class="text-center p-5">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="pickupModal">
  <div class="modal-dialog">
    <form id="pickupForm">
      @csrf
      <input type="hidden" id="pickup_order_id">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Update Pick Up</h5>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label>Pick Up Date</label>
            <input type="date" id="pickup_date" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Pick Up Time</label>
            <select id="pickup_timeslot" class="form-control">
              <option>9 AM to 11 AM</option>
              <option>11 AM to 1 PM</option>
              <option>1 PM to 3 PM</option>
            </select>
          </div>

          <div class="form-group">
            <label>Reason of Change</label>
            <textarea id="pickup_reason" class="form-control"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>



<div class="modal fade" id="assignPickupModal">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.order.assignPickup') }}">
      @csrf
      <input type="hidden" name="order_id" id="assign_order_id">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Assign Pick Up</h5>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label>Select Runner</label>
            <select name="runner_id" class="form-control" required>
              @foreach($runners as $runner)
                <option value="{{ $runner->id }}">{{ $runner->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-success">Assign</button>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="changeDeliveryModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5>Change Delivery Date / Time</h5>
        <button class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="change_delivery_order_id">

        <div class="form-group">
          <label>Delivery Date</label>
          <input type="date" id="change_delivery_date" class="form-control">
        </div>


        <div class="form-group">
            <label>Pick Up Time</label>
            <select id="change_delivery_time" class="form-control">
              <option>9 AM to 11 AM</option>
              <option>11 AM to 1 PM</option>
              <option>1 PM to 3 PM</option>
            </select>
          </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-success" id="saveChangeDelivery">
            Update Delivery
        </button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="deliveryModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Assign / Change Delivery</h5>
        <button class="close" data-dismiss="modal">&times;</button>
      </div>

      <form method="POST" action="{{ route('admin.order.assignDelivery') }}">
        @csrf

        <div class="modal-body">
            <input type="hidden" name="order_id" id="delivery_order_id">

            <div class="form-group">
                <label>Delivery Runner</label>
                <select name="delivery_assign_id" id="delivery_runner_id" class="form-control" required>
                    <option value="">Select Runner</option>
                    @foreach($runners as $runner)
                        <option value="{{ $runner->id }}">{{ $runner->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-primary">Save</button>
        </div>
      </form>

    </div>
  </div>
</div>


<script>
function openAssignPickupModal(orderId) {
    document.getElementById('assign_order_id').value = orderId;
    $('#assignPickupModal').modal('show');
}
// Assign Delivery
$(document).on('click', '.btn-assign-delivery', function () {

    $('#delivery_order_id').val($(this).data('id'));
    $('#delivery_runner_id').val($(this).data('runner') || '');
    $('#delivery_date').val($(this).data('date') || '');
    $('#delivery_timeslot').val($(this).data('time') || '');

    $('#deliveryModal').modal('show');
});

// Change Delivery Date/Time only
$('.btn-change-delivery').click(function () {

    $('#change_delivery_order_id').val($(this).data('id'));
    $('#change_delivery_date').val($(this).data('date'));
    $('#change_delivery_time').val($(this).data('time'));

    $('#changeDeliveryModal').modal('show');
});

</script>


<script>
$(document).on('click', '.btn-view-order', function () {
    let orderId = $(this).data('id');

    $('#viewOrderModal').modal('show');
    $('#order-modal-body').html(`
        <div class="text-center p-5">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
        </div>
    `);

    let url = "{{ route('admin.order.show', ':id') }}";
    url = url.replace(':id', orderId);

    $.get(url)
        .done(function (response) {
            $('#order-modal-body').html(response);
        })
        .fail(function (xhr) {
            console.error(xhr.responseText);
            $('#order-modal-body').html(
                '<div class="alert alert-danger">Unable to load order details.</div>'
            );
        });
});
</script>


<script>
function openPickupModal(orderId, date, time) {
    $('#pickup_order_id').val(orderId);
    $('#pickup_date').val(date);
    $('#pickup_timeslot').val(time);
    $('#pickupModal').modal('show');
}

document.getElementById('pickupForm').addEventListener('submit', function(e){
    e.preventDefault();

    fetch(`/admin/orders/${pickup_order_id.value}/update-pickup`, {

        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            pickup_date: pickup_date.value,
            pickup_timeslot: pickup_timeslot.value,
            reason: pickup_reason.value
        })
    }).then(() => location.reload());
});
</script>
<script>
$('.btn-change-delivery').click(function () {
     let date = $(this).data('date');   // YYYY-MM-DD
    let time = $(this).data('time');
    $('#change_delivery_order_id').val($(this).data('id'));
    $('#change_delivery_date').val($(this).data('date'));
    $('#change_delivery_time').val($(this).data('time'));

    $('#changeDeliveryModal').modal('show');
});

$('#saveChangeDelivery').click(function () {

    let orderId = $('#change_delivery_order_id').val();

    $.post(`/admin/orders/${orderId}/change-delivery`, {
        _token: '{{ csrf_token() }}',
        delivery_date: $('#change_delivery_date').val(),
        delivery_timeslot: $('#change_delivery_time').val(),
    }, function () {
        location.reload();
    });
});
</script>
