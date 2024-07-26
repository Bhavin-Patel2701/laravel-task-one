@extends('layouts.newstyle')

@section('title', 'Edit Product')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Edit Product</li>
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
                    <form action="{{ route('product.update', $product_entries->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">

                            @include('messages')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id">Product Category<span class="text-danger"> *</span></label>

                                        <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                            <option disabled {{ old('category_id') ? '' : 'selected' }}>Select Product Category</option>

                                            @foreach ($active_category as $category)
                                                <option value="{{ $category->id }}" {{ (old('category_id', $product_entries->category_id) == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
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
                                            <!-- <option disabled selected>Select Child Category</option> -->
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
                                <div class="col-md-{{ Auth::user()->role === 'admin' ? '6' : '12' }}">
                                    <div class="form-group">
                                        <label for="title">Product Name<span class="text-danger"> *</span></label>

                                        <input type="text" class="form-control @error('title') is-invalid @enderror" placeholder="{{ __('Enter Your Product Name') }}" id="title" name="title" value="{{ old('title', $product_entries->title) }}">

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                @if (Auth::user()->role === "admin")
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status<span class="text-danger"> *</span></label>

                                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                                <option disabled {{ old('status') ? '' : 'selected' }}>Select Category Status</option>

                                                <option value="active" {{ (old('status') == 'active' || $product_entries->status == 'active') ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ (old('status') == 'inactive' || $product_entries->status == 'inactive') ? 'selected' : '' }}>Inactive</option>
                                            </select>

                                            @error('status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>

                                        <textarea class="form-control @error('description') is-invalid @enderror" rows="3" id="description" name="description" placeholder="{{ __('Enter Your Product Description') }}">{{ old('description', $product_entries->description) }}</textarea>

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

                                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" placeholder="{{ __('Enter Your Product Quantity') }}" id="quantity" name="quantity" value="{{ old('quantity', $product_entries->quantity) }}">

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
                                            <input type="text" class="form-control @error('price') is-invalid @enderror" placeholder="{{ __('Price Of Your Product ') }}" id="price" name="price" value="{{ old('price', $product_entries->price) }}">

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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Main Product Image</label>

                                        @if(!empty($product_entries->image))
                                            <div class="input-group align-items-end">
                                                <img src="{{ asset('storage/'. $product_entries->image) }}" alt="Product Image" width="200" height="150">
                                                <div class="ml-2">
                                                    <a href="{{ route('product.removeimg', $product_entries->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want remove this Main Product image ?');">Remove</a>
                                                </div>
                                            </div>
                                        @else
                                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" accept="image/png, image/jpeg, image/jpg" />

                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                            <div class="row">
                                                <div class="col-md-2 d-inline-flex">
                                                    <img src="{{ asset('default-img/product-img.png') }}" alt="Product Image" width="100">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sku">SKU<span class="text-danger"> *</span></label>

                                        <input type="text" class="form-control @error('sku') is-invalid @enderror" placeholder="{{ __('Enter Your Product Name') }}" id="sku" name="sku" value="{{ old('sku', $product_entries->sku) }}">

                                        @error('sku')
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
                                        <label for="multi_image">Side Product Images</label>

                                        <div class="row">
                                            <div class="col-md-{{ $product_entries->multi_image != null ? '9' : '12' }}">
                                                <input type="file" class="form-control @if($errors->has('multi_image.*')) is-invalid @enderror" name="multi_image[]" id="multi_image" accept="image/png, image/jpeg, image/jpg" multiple/>
                                            </div>

                                            @if($product_entries->multi_image != null)
                                                <div class="col-md-3">
                                                    <a href="javascript:void(0);" id="multi-img-btn" class="btn btn-danger">Multiple Delete</a>
                                                    <a href="javascript:void(0);" id="cancle-multi-img-btn" class="btn btn-primary" onclick="location.reload();">Cancle Multiple Delete</a>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($errors->has('multi_image.*'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('multi_image.*') }}</strong>
                                            </span>
                                        @endif

                                        <div id="flash-message"></div>

                                        <div class="row multi-img">
                                            @if($product_entries->multi_image != null)
                                                @php
                                                    $multi_image = explode(', ', $product_entries->multi_image);
                                                @endphp

                                                <style>
                                                    [class*=icheck-]>input:first-child+label::before {
                                                        border: 3px solid #000000;
                                                    }
                                                    [class*=icheck-]>input:first-child:not(:checked):not(:disabled):hover+label::before {
                                                        border-width: 3px;
                                                    }
                                                    .multi-img .icheck-primary.position-absolute.m-0, #cancle-multi-img-btn {
                                                        display: none;
                                                    }
                                                </style>

                                                @foreach($multi_image as $image)

                                                    <div class="col-md-2 d-inline-flex mt-3">
                                                        <img src="{{ asset('storage/upload/multiple_images/'. $image) }}" alt="Product Image" width="150" height="150">
                                                        <a href="{{ route('product.removeimg', ['id' => $product_entries->id, 'img' => $image ?? '']) }}" class="btn btn-sm btn-danger position-absolute" onclick="return confirm('Are you sure you want delete this Product Image ?');"><i class="fa fa-trash"></i></a>

                                                        <div class="icheck-primary position-absolute m-0">
                                                            <input type="checkbox" class="imgCheckbox" id="{{ $image }}" value="{{ $image }}">
                                                            <label for="{{ $image }}">
                                                            </label>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            @else
                                                <div class="col-md-2 d-inline-flex mt-3">
                                                    <img src="{{ asset('default-img/product-img.png') }}" alt="Product Image" width="100">
                                                </div>
                                            @endif

                                            <div class="col-md-2 mt-3 d-none" id="delete-multi-img-btn">
                                                <a href="javascript:void(0);" class="btn btn-danger">Delete Images</a>
                                            </div>

                                        </div>
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
        var flashMessage = localStorage.getItem('flash-message');
        if (flashMessage) {
            var data = JSON.parse(flashMessage);

            var messageType = data.type;
            var message = data.message;
            var alertClass = messageType === 'success' ? 'alert-success' : 'alert-warning';

            var html = '<div class="alert '+alertClass+' alert-dismissible fade show mt-3 mb-0" role="alert">'+message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

            $('#flash-message').html(html);
            setTimeout(function() {
                $('.'+alertClass).fadeOut('slow');
            }, 2500);
            localStorage.removeItem('flash-message');
        }

        $("#multi-img-btn").click(function(){
            $("#multi-img-btn").css("display", "none");
            $("#cancle-multi-img-btn").css("display", "inline-block");
            $("#delete-multi-img-btn").removeClass('d-none').addClass('d-block');
            $(".multi-img .icheck-primary.position-absolute.m-0").css("display", "block");
            $(".row.multi-img").css("align-items", "end");
            $(".multi-img .btn.position-absolute").css("display", "none");
            $(".multi-img img").css("opacity", "0.5");
        });

        $("#delete-multi-img-btn a").click(function(){
            var imgNames = [];
            var id = "{{ $product_entries->id }}";

            $(".imgCheckbox:checked").each(function(){
                var imgname = $(this).val();
                imgNames.push(imgname);
            });

            if (imgNames.length === 0) {
                alert("Please select at least one image to delete.");
                return;
            }
            if (!confirm('Are you sure you want delete this Side Product Images?')) {
                return;
            }

            $.ajax({
                url: "{{ route('product.multiremoveimg') }}",
                type: 'GET',
                data: { 
                    _token: "{{ csrf_token() }}",
                    imgNames: imgNames,
                    id: id
                },
                success: function(response) {
                    localStorage.setItem('flash-message', JSON.stringify({ type: 'success', message: response.status }));
                    window.location.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        let response = xhr.responseJSON;
                        if (response.error) {
                            localStorage.setItem('flash-message', JSON.stringify({ type: 'error', message: response.error }));
                            window.location.reload();
                        }
                    }
                }
            });
        });

        function loadChildCategories(category_id, selectedChildCategoryId = null) {

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
                        // child_category_id.append('<option disabled selected>Select Child Category</option>');
                        $.each(response.status, function (index, value){
                            // var selected = (value.id == '{{ old("child_category_id") }}') ? 'selected' : '';
                            var selected = (value.id == selectedChildCategoryId) ? 'selected' : '';
                            child_category_id.append('<option value="'+value.id+'" '+selected+'>'+value.title+'</option>');
                        });
                    } else {
                        child_category_id.append('<option disabled selected>No Child Category</option>');
                    }
                }
            });
        }

        // Load child categories on page load if a parent category is already selected
        var initialCategoryId = "{{ old('category_id', $product_entries->category_id) }}";
        var initialChildCategoryId = "{{ old('child_category_id', $product_entries->child_category_id) }}";
        if (initialCategoryId) {
            loadChildCategories(initialCategoryId, initialChildCategoryId);
        }

        // Load child categories when the parent category changes
        $('#category_id').change(function() {
            var category_id = $(this).val();
            loadChildCategories(category_id);
        });
    });
</script>
<!-- /.Page specific script -->

@endsection