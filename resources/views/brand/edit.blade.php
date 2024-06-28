@extends('layouts.newstyle')

@section('title', 'Edit Brand')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Brand</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Edit Brand</li>
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
                    <form action="{{ route('brand.update', $brand_entries->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">First Name<span class="text-danger"> *</span></label>

                                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $brand_entries->title) }}">

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status<span class="text-danger"> *</span></label>

                                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                            <option disabled {{ old('status') ? '' : 'selected' }}>Select Category Status</option>

                                            <option value="active" {{ (old('status') == 'active' || $brand_entries->status == 'active') ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ (old('status') == 'inactive' || $brand_entries->status == 'inactive') ? 'selected' : '' }}>Inactive</option>
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
                            <a href="{{ route('brand.list') }}" class="btn btn-danger">Cancle</a>
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

@section('pagescript')

<!-- Page specific script -->
<script>
    $("#mobile_number").attr({
        "maxlength" : "10",
        "minlength" : "10",
        "type"      : "text"
    });
</script>
<!-- /.Page specific script -->

@endsection