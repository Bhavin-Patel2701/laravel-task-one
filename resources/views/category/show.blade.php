@extends('layouts.newstyle')

@section('title', 'Category Show')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Category Show</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Category Show</li>
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
                                    <label>Parent Category</label>
                                    <input type="text" class="form-control" value="{{ isset($singale_entry->parent_category) ? $singale_entry->parent_category : 'No Parent Category' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category Name</label>
                                    <input type="text" class="form-control" value="{{ $singale_entry->title }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <input type="text" class="form-control" value="{{ ucwords(strtolower($singale_entry->status)) }}" readonly
                                    @if($singale_entry->status === "active")
                                        style="color: green;"
                                    @elseif($singale_entry->status === "inactive")
                                        style="color: red;"
                                    @endif>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.card-body -->
                    <div class="card-footer">
                        <a href="{{ route('category.list') }}" class="btn btn-primary">Back To List</a>
                        <a href="{{ route('category.edit', $singale_entry->id) }}" class="btn btn-primary">Edit This Record</a>
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