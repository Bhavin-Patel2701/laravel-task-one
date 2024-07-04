@extends('layouts.newstyle')

@section('title', 'Product Show')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Product Show</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Product Show</li>
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
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Category</label>

                                    <input type="text" class="form-control" value="{{ $product_entries->category_title }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Name</label>

                                    <input type="text" class="form-control" value="{{ $product_entries->title }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description</label>

                                    <textarea class="form-control" rows="3" readonly>{{ $product_entries->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>

                                    <input type="text" class="form-control" value="{{ ucwords(strtolower($product_entries->status)) }}" readonly
                                    @if($product_entries->status === "active")
                                        style="color: green;"
                                    @elseif($product_entries->status === "inactive")
                                        style="color: red;"
                                    @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Quantity</label>

                                    <input type="text" class="form-control" value="{{ $product_entries->quantity }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Price</label>

                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ $product_entries->price }}" readonly>

                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="border-radius: 0rem .25rem .25rem 0rem;">₹</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Image</label>

                                    <div class="input-group">
                                        @if($product_entries->image != null)
                                            <img src="{{ asset('storage/'. $product_entries->image) }}" alt="Product Image" width="150">
                                        @else
                                            <input type="text" class="form-control" value="No Product Image Uploaded" readonly>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.card-body -->
                    <div class="card-footer">
                        <a href="{{ route('product.list') }}" class="btn btn-primary">Back To List</a>
                        <a href="{{ route('product.edit', $product_entries->id) }}" class="btn btn-primary">Edit This Record</a>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection