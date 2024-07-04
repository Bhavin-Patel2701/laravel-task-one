@extends('layouts.newstyle')

@section('title', 'Add User')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Users</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Add Users</li>
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
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname">First Name<span class="text-danger"> *</span></label>

                                        <input type="text" class="form-control @error('firstname') is-invalid @enderror" placeholder="{{ __('Enter Your First Name') }}" id="firstname" name="firstname" value="{{ old('firstname') }}">

                                        @error('firstname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname">Last Name<span class="text-danger"> *</span></label>

                                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" placeholder="{{ __('Enter Your Last Name') }}" id="lastname" name="lastname" value="{{ old('lastname') }}">

                                        @error('lastname')
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
                                        <label for="email">Email Address<span class="text-danger"> *</span></label>

                                        <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Enter Your Email Address') }}" id="email" name="email" value="{{ old('email') }}">

                                        @error('email')
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
                                        <label for="role">Role<span class="text-danger"> *</span></label>

                                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="vendor" {{ old('role') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>

                                        @error('role')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile_number">Mobile Number</label>

                                        <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" placeholder="{{ __('Enter Your Mobile Number') }}" id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}">

                                        @error('mobile_number')
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
                                        <label for="password">Password<span class="text-danger"> *</span></label>

                                        <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Enter Your Password') }}" id="password" name="password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password<span class="text-danger"> *</span></label>

                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('Enter Your Confirm Password') }}" id="password_confirmation" name="password_confirmation">

                                        @error('password_confirmation')
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
                            <a href="{{ route('users.list') }}" class="btn btn-danger">Cancle</a>
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