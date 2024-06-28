@extends('layouts.newstyle')

@section('title', 'Add Product')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Product</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add Product</li>
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
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id">Product Category<span class="text-danger"> *</span></label>

                                        <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                            <option disabled {{ old('category_id') ? '' : 'selected' }}>Select Product Category</option>

                                            @foreach ($active_category as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                                            @endforeach
                                        </select>

                                        @error('category_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="child_category_id">Child Category</label>

                                        <select class="form-control @error('child_category_id') is-invalid @enderror" id="child_category_id" name="child_category_id">
                                            <option disabled selected>Select Child Category</option>
                                        </select>

                                        @error('child_category_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Product Name<span class="text-danger"> *</span></label>

                                        <input type="text" class="form-control @error('title') is-invalid @enderror" placeholder="{{ __('Enter Your Product Name') }}" id="title" name="title" value="{{ old('title') }}">

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
                                            <option disabled {{ old('status') ? '' : 'selected' }}>Select Product Status</option>

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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>

                                        <textarea class="form-control @error('description') is-invalid @enderror" rows="3" id="description" name="description" placeholder="{{ __('Enter Your Product Description') }}">{{ old('description') }}</textarea>

                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Product Quantity<span class="text-danger"> *</span></label>

                                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" placeholder="{{ __('Enter Your Product Quantity') }}" id="quantity" name="quantity" value="{{ old('quantity') }}">

                                        @error('quantity')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Price<span class="text-danger"> *</span></label>

                                        <div class="input-group">
                                            <input type="text" class="form-control @error('price') is-invalid @enderror" placeholder="{{ __('Price Of Your Product ') }}" id="price" name="price" value="{{ old('price') }}">

                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="border-radius: 0rem .25rem .25rem 0rem;">₹</span>
                                            </div>
                                        </div>

                                        @error('price')
                                            <span class="invalid-feedback @error('price') d-block @enderror" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="image">Product Image</label>

                                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" accept="image/png, image/jpeg, image/jpg" />

                                        @error('image')
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
                            <a href="{{ route('product.list') }}" class="btn btn-danger">Cancle</a>
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
    $(document).ready(function() {
        $('#category_id').change(function() {
            var category_id = $("#category_id").val();

            $.ajax({
                url: "{{ route('product.childcategory') }}",
                type: 'GET',
                data: { 
                    _token: "{{ csrf_token() }}",
                    category_id: category_id
                },
                success: function(response) {
                    var child_category_id = $('#child_category_id');
                    child_category_id.empty();

                    if(response.status && response.status.length > 0) {
                        child_category_id.append('<option disabled selected>Select Child Category</option>');
                        $.each(response.status, function (index, value){
                            var selected = (value.id == '{{ old("child_category_id") }}') ? 'selected' : '';
                            child_category_id.append('<option value="'+value.id+'" '+selected+'>'+value.title+'</option>');
                        });
                    } else {
                        child_category_id.append('<option disabled selected>No Child Category</option>');
                    }
                }
            });
        });
    });
</script>
<!-- /.Page specific script -->

@endsection