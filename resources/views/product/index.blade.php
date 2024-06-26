@extends('layouts.newstyle')

@section('title', 'Product List')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Product List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Product List</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.Content Header (Page header) -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- card -->
                <div class="card">
                    <div class="card-body">

                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="mb-3" style="text-align: end;">
                            <a href="{{ route('product.create') }}" class="btn btn-primary btn-block d-inline"><i class="fa fa-plus"></i> Add Product</a>
                        </div>

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Category Name</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price (₹)</th>
                                    <th>Product Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $entriesID = 1;
                                @endphp
                                @foreach ($allproduct_entries as $entries)
                                    <tr>
                                        <th>{{ $entriesID++ }}.</th>
                                        <td>{{ $entries->category_title }}</td>
                                        <td>{{ $entries->title }}</td>
                                        <td>{{ $entries->quantity }}</td>
                                        <td>{{ $entries->price }}</td>
                                        <td>
                                            @if(!empty($entries->image))
                                                <img alt="Product Image" src="{{ asset('storage/'. $entries->image) }}" width="40" height="40">
                                            @elseif (empty($entries->image))
                                                No Image Uploaded
                                            @endif
                                        </td>
                                        <td>
                                            <span id="status-{{ $entries->id }}" class="badge badge-{{ $entries->status === 'active' ? 'success' : 'danger' }} toggle-status" data-id="{{ $entries->id }}" style="cursor: pointer;">{{ $entries->status }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('product.show', $entries->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                            <a href="{{ route('product.edit', $entries->id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                            <a href="{{ route('product.destroy', $entries->id) }}" class="btn btn-sm btn-danger mt-1" onclick="return confirm('Are you sure you want move to trash this Product ?');">Move to Trash</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Category Name</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price (₹)</th>
                                    <th>Product Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

@section('pagescript')

<!-- Page specific script -->
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    $(document).ready(function() {
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 2500);

        $('.toggle-status').on('click', function() {
            var id = $(this).data('id');

            $.ajax({
                url: "{{ route('product.status', '') }}/"+id,
                type: 'GET',
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    var status_badge = $('#status-' + id);
                    status_badge.text(response.status);

                    if(response.status === 'active') {
                        status_badge.removeClass('badge-danger').addClass('badge-success');
                    } else {
                        status_badge.removeClass('badge-success').addClass('badge-danger');
                    }
                }
            });
        });
    });
</script>
<!-- /.Page specific script -->

@endsection