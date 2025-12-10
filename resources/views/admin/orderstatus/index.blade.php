<x-admin>
    @section('title','Order Status')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Order Status</h3>
            <div class="card-tools">
                <a href="{{ route('admin.orderstatus.create') }}" class="btn btn-sm btn-primary">Add</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="orderstatusTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        {{--  <th>Created</th>  --}}
                        <th>Action</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $orderstatus)
                        <tr>
                            <td>{{ $orderstatus->name }}</td>
                            {{--  <td>{{ $orderstatus->created_at }}</td>  --}}
                            <td>
                                <a href="{{ route('admin.orderstatus.edit',encrypt($orderstatus->id)) }}" class="btn btn-sm btn-secondary">
                                    <i class="far fa-edit"></i>
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('admin.orderstatus.destroy',encrypt($orderstatus->id)) }}" method="POST" onclick="confirm('Are you sure')">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                            </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @section('js')
        <script>
            $(function() {
                $('#orderstatusTable').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "responsive": true,
                });
            });
        </script>
    @endsection
</x-admin>
