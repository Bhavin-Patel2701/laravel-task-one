@extends('layouts.newstyle')

@section('title', 'Users Show')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Users Show</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Users Show</li>
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
                                    <label>First Name</label>
                                    <input type="text" class="form-control" value="{{ $singale_entry->firstname }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="text" class="form-control" value="{{ $singale_entry->email }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <input type="text" class="form-control" value="{{ ucwords(strtolower($singale_entry->role)) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" value="{{ $singale_entry->lastname }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Email Verified Status</label>
                                    <input type="text" class="form-control" value="@if(!empty($singale_entry->email_verified_at)) Verified @else Not Verified @endif" readonly
                                    @if(!empty($singale_entry->email_verified_at))
                                        style="color: green;"
                                    @else
                                        style="color: red;"
                                    @endif>
                                </div>
                                <div class="form-group">
                                    <label>Mobile Number</label>
                                    <input type="text" class="form-control" value="@if(!empty($singale_entry->mobile_number)) $singale_entry->mobile_number @else Not Entered Mobile Number @endif" readonly
                                    @if(empty($singale_entry->mobile_number))
                                        style="color: red;"
                                    @endif>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.card-body -->
                    <div class="card-footer">
                        <a href="{{ route('users.list') }}" class="btn btn-primary">Back To List</a>
                        <a href="{{ route('users.edit', $singale_entry->id) }}" class="btn btn-primary">Edit This Record</a>
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