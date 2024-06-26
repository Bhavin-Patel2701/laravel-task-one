@extends('layouts.newstyle')

@section('title', 'Trash Records')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Trash Records</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Trash Records</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.Content Header (Page header) -->

@if(isset($trash_users) && $trash_users->isNotEmpty())

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- card -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Users Trash Records</h3>
                        </div>

                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Email Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trash_users as $users_entries)
                                        <tr>
                                            <td>{{ $users_entries->firstname }}</td>
                                            <td>{{ $users_entries->lastname }}</td>
                                            <td>{{ $users_entries->email }}</td>
                                            <td>
                                                @if(!empty($users_entries->email_verified_at))
                                                    <span class="badge badge-success">Verified</span>
                                                @else
                                                    <span class="badge badge-danger">Not Verified</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('users.restore', $users_entries->id) }}" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want restore this User ?');">Restore User</a>
                                                <a href="{{ route('users.delete', $users_entries->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want permanent delete this User ?');">Permanent Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Email Status</th>
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

@elseif(isset($trash_category) && $trash_category->isNotEmpty() && isset($all_entries) && $all_entries->isNotEmpty())

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- card -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Category Trash Records</h3>
                        </div>

                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Parent Category</th>
                                        <th>Category Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trash_category as $category_entries)
                                        <tr>

                                            @if (!empty($category_entries->parent_id))
                                                @foreach ($all_entries as $parent_category)
                                                    @if ($category_entries->parent_id === $parent_category->id)
                                                        <td>{{ $parent_category->title }}</td>
                                                    @endif
                                                @endforeach
                                            @else
                                                <td>No Parent Category</td>
                                            @endif

                                            <td>{{ $category_entries->title }}</td>
                                            <td>
                                                @if($category_entries->status === "active")
                                                    <span class="badge badge-success">Active</span>
                                                @elseif ($category_entries->status === "inactive")
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('category.restore', $category_entries->id) }}" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want restore this Category ?');">Restore Category</a>
                                                <a href="{{ route('category.delete', $category_entries->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want permanent delete this Category ?');">Permanent Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Parent Category</th>
                                        <th>Category Name</th>
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

@elseif(isset($trash_product) && $trash_product->isNotEmpty())

{{--dd($trash_product);--}}

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- card -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Product Trash Records</h3>
                        </div>

                        <div class="card-body">
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
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
                                    @foreach ($trash_product as $product_entries)
                                        <tr>
                                            <td>{{ $product_entries->category_title }}</td>
                                            <td>{{ $product_entries->title }}</td>
                                            <td>{{ $product_entries->quantity }}</td>
                                            <td>{{ $product_entries->price }}</td>
                                            <td>
                                                @if(!empty($product_entries->image))
                                                    <img alt="Product Image" src="{{ asset('storage/'. $product_entries->image) }}" width="40" height="40">
                                                @elseif (empty($product_entries->image))
                                                    No Image Uploaded
                                                @endif
                                            </td>
                                            <td>
                                                @if($product_entries->status === "active")
                                                    <span class="badge badge-success">Active</span>
                                                @elseif ($product_entries->status === "inactive")
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('product.restore', $product_entries->id) }}" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want restore this Product ?');">Restore Product</a>
                                                <a href="{{ route('product.delete', $product_entries->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want permanent delete this Product ?');">Permanent Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
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

@endif

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

        $("#example2").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

        $("#example3").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
<!-- /.Page specific script -->

@endsection