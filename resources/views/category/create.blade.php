@extends('layouts.newstyle')

@section('title', 'Add Category')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Category</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add Category</li>
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
                    <form action="{{ route('category.store') }}" method="POST">
                        @csrf

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="parent_id">Parent Category</label>

                                        <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                            <option disabled {{ old('parent_id') ? '' : 'selected' }}>Select Parent Category</option>

                                            @foreach ($active_entries as $entries)
                                                <option value="{{ $entries->id }}" {{ old('parent_id') == $entries->id ? 'selected' : '' }}>{{ $entries->title }}</option>
                                            @endforeach
                                        </select>

                                        @error('parent_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Category Name<span class="text-danger"> *</span></label>

                                        <input type="text" class="form-control @error('title') is-invalid @enderror" placeholder="{{ __('Enter Your Last Name') }}" id="title" name="title" value="{{ old('title') }}">

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="status">Status<span class="text-danger"> *</span></label>

                                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                            <option disabled {{ old('status') ? '' : 'selected' }}>Select Category Status</option>

                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>

                                        @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('category.list') }}" class="btn btn-danger">Cancle</a>
                        </div>

                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection