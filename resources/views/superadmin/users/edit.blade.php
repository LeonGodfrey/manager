@extends('layouts.app1')

@section('org_name', 'Super Admin')
@section('title', 'Edit User')
@section('page_title', 'Edit User')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
        <a href={{ route('super-admin.users.index') }}><li class="breadcrumb-item btn btn-outline-success btn-sm ">Users</li></a>
        </li>
    </ol>
@endsection

@section('main_content')

    <div class="col-sm-12">
        <form method="post" action="{{ route('super-admin.users.update', ['user' => $user]) }}">
            @csrf
            @method('PUT')
            <div class="card card-success card-outline">
                <div class="card-body">
                    <div class="form-group">
                        <label for="branchName">Branch Name *</label>
                        <select class="form-control select2" id="branch_id" name="branch_id" required>
                            <option>--Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @if ($user->branch_id === $branch->id) selected @endif>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}"
                            placeholder="Enter name" required>
                        @error('name')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="user_name">Username *</label>
                        <input type="text" class="form-control" id="user_name" name="user_name"
                            value="{{ $user->user_name }}" placeholder="Enter username" required>
                        @error('user_name')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="user_phone"> Phone Number *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control" id="user_phone" name="user_phone"
                                data-inputmask='"mask": "+256999999999"' data-mask value="{{ $user->user_phone }}" required>
                        </div>
                        @error('user_phone')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ $user->email }}" placeholder="Enter email" required>
                        @error('email')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <p class="text-sm">*password should be atleast 8 characters.</p>
                                <input type="password" class="form-control" id="password" name="password" value=""
                                    required>
                                @error('password')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password_confirmation">Password Confirmation *</label>
                                <p class="text-sm">*Enter the password here again.</p>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" value="" required>
                                @error('password_confirmation')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="card-tools text-right">
                            <button name="submit" type="submit" class="btn btn-success">Update User</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
