@extends('layouts.newstyle')

@section('title', 'Brand Show')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Brand Show</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Brand Show</li>
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
                                    <label>Brand Name</label>
                                    <input type="text" class="form-control" value="{{ $brand_entries->title }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>

                                    <input type="text" class="form-control" value="{{ ucwords(strtolower($brand_entries->status)) }}" readonly
                                    @if($brand_entries->status === "active")
                                        style="color: green;"
                                    @elseif($brand_entries->status === "inactive")
                                        style="color: red;"
                                    @endif>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.card-body -->
                    <div class="card-footer">
                        <a href="{{ route('brand.list') }}" class="btn btn-primary">Back To List</a>
                        <a href="{{-- route('brand.edit', $brand_entries->id) --}}" class="btn btn-primary">Edit This Record</a>
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