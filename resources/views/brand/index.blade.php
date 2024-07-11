@extends('layouts.newstyle')

@section('title', 'Brands List')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Brands List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Brands List</li>
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

                        <div id="status_error">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('brand.list') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group d-flex align-items-end">
                                                <label for="search" class="mr-2">Search:</label>

                                                <input type="text" class="form-control" placeholder="{{ __('Search...') }}" id="search" name="search" value="{{ $search }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6" style="text-align: end;">
                                <a href="{{ route('brand.create') }}">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-plus"></i> Add Brand
                                    </button>
                                </a>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Brand Name</th>
                                    <th>Status</th>

                                    @if (Auth::user()->role === "admin")
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $entriesID = ($allbrand_entries->currentPage() - 1) * $allbrand_entries->perPage() + 1;
                                @endphp
                                @foreach ($allbrand_entries as $entries)
                                    <tr>
                                        <th>{{ $entriesID++ }}.</th>
                                        <td>{{ $entries->title }}</td>

                                        @if (Auth::user()->role !== "admin")
                                            <td>
                                                <span class="badge badge-{{ $entries->status === 'active' ? 'success' : 'danger' }}">{{ $entries->status }}</span>
                                            </td>
                                        @else
                                            <td>
                                                <span id="status-{{ $entries->id }}" class="badge badge-{{ $entries->status === 'active' ? 'success' : 'danger' }} toggle-status" data-id="{{ $entries->id }}" style="cursor: pointer;">{{ $entries->status }}</span>
                                            </td>
                                        @endif

                                        @if (Auth::user()->role === "admin")
                                            <td>
                                                <a href="{{ route('brand.show', $entries->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                                <a href="{{ route('brand.edit', $entries->id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                                <a href="{{ route('brand.destroy', $entries->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want move to trash this Brand ?');">Move to Trash</a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Brand Name</th>
                                    <th>Status</th>

                                    @if (Auth::user()->role === "admin")
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>

                        <div class="mt-4">
                            {{ $allbrand_entries->appends(['search' => request()->input('search')])->links('vendor.pagination.bootstrap-4') }}
                        </div>
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

@php /*
    <!-- Button to trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        Launch Modal
    </button>
    <!-- /.Button to trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Modal body content goes here -->
                    This is a popup modal example in AdminLTE 3.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal -->
*/ @endphp

@endsection

@section('pagescript')

<!-- Page specific script -->
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert-success, .alert-danger').fadeOut('slow');
        }, 2500);

        $('.toggle-status').on('click', function() {
            var id = $(this).data('id');

            $.ajax({
                url: "{{ route('brand.status', '') }}/"+id,
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