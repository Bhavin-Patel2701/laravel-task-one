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
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
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

                        @if(Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ Session::get('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(Session::has('alert'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                {{ Session::get('alert') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div id="status_error">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <a href="{{ route('product.export') }}">
                                    <button class="btn btn-secondary">
                                        <i class="fa fa-download"></i> Export Data
                                    </button>
                                </a>
                                <button class="btn btn-info" data-toggle="modal" data-target="#exampleModal">
                                    <i class="fa fa-plus"></i> Import Data
                                </button>

                                @if (Auth::user()->role === "admin")
                                    <a href="{{ asset('storage/download/importproducts_admin.csv') }}" download>
                                @else
                                    <a href="{{ asset('storage/download/importproducts_vendor.csv') }}" download>
                                @endif
                                    <button class="btn btn-success">
                                        <i class="fa fa-upload"></i> Demo File
                                    </button>
                                </a>
                            </div>
                            <div class="col-md-6" style="text-align: end;">
                                <a href="{{ route('product.create') }}">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-plus"></i> Add Product
                                    </button>
                                </a>
                            </div>
                        </div>

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Category</th>
                                    <th>Child Category</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price (₹)</th>
                                    <th>Product Image</th>
                                    <th>Status</th>

                                    @if (Auth::user()->role === "admin")
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $entriesID = 1;
                                @endphp
                                @foreach ($allproduct_entries as $entries)
                                    <tr>
                                        <th>{{ $entriesID++ }}.</th>

                                        <td class="{{ $entries->category_title === Null ? 'text-danger' : '' }}">
                                            {{ $entries->category_title !== null ? $entries->category_title : 'No Category' }}
                                        </td>
                                        
                                        <td class="{{ $entries->child_category_title === Null ? 'text-danger' : '' }}">
                                            {{ $entries->child_category_title !== null ? $entries->child_category_title : 'No Child Category' }}
                                        </td>

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

                                        @if (Auth::user()->role !== "admin")
                                            <td>
                                                <span class="badge badge-{{ $entries->status === 'active' ? 'success' : 'danger' }}">{{ $entries->status === 'active' ? 'Approved' : 'Not Approved' }}</span>
                                            </td>
                                        @else
                                            <td>
                                                <span id="status-{{ $entries->id }}" class="badge badge-{{ $entries->status === 'active' ? 'success' : 'danger' }} toggle-status" data-id="{{ $entries->id }}" style="cursor: pointer;">{{ $entries->status }}</span>
                                            </td>
                                        @endif

                                        @if (Auth::user()->role === "admin")
                                            <td>
                                                <a href="{{ route('product.show', $entries->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                                <a href="{{ route('product.edit', $entries->id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                                <a href="{{ route('product.destroy', $entries->id) }}" class="btn btn-sm btn-danger mt-1" onclick="return confirm('Are you sure you want move to trash this Product ?');">Move to Trash</a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Category</th>
                                    <th>Child Category</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price (₹)</th>
                                    <th>Product Image</th>
                                    <th>Status</th>

                                    @if (Auth::user()->role === "admin")
                                        <th>Action</th>
                                    @endif
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Product Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Modal body content goes here -->
                <form action="{{ route('product.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="csv_file">Import Product File</label>

                                <input type="file" class="form-control @error('csv_file') is-invalid @enderror" name="csv_file" id="csv_file" accept=".csv, application/vnd.ms-excel, text/plain" />

                                @error('csv_file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
<!-- /.Modal -->

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
            $('.alert-success, .alert-danger, .alert-warning').fadeOut('slow');
        }, 2500);

        $('.toggle-status').on('click', function() {
            var id = $(this).data('id');
            
            alert(id);

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
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        let response = xhr.responseJSON;
                        if (response.error) {
                            html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'+response.error+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            $('#status_error').html(html);
                            setTimeout(function() {
                                $('.alert-danger').fadeOut('slow');
                            }, 2500);
                        }
                    }
                }
            });
        });
    });
</script>
<!-- /.Page specific script -->

@endsection