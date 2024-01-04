@extends('layouts.app1')

@section('org_name', 'Super Admin')
@section('title', 'New User')
@section('page_title', 'Add New User')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
        <a href={{ route('super-admin.users.index') }}>
            <li class="breadcrumb-item btn btn-outline-success btn-sm ">Users</li>
        </a>
    </ol>
@endsection

@section('main_content')

    <div class="col-sm-12">
        <form method="post" action="{{ route('super-admin.users.store') }}">
            @csrf
            <div class="card card-outline card-success">
                <div class="card-body">
                    <div class="form-group">
                        <label for="organization">Organization *</label>
                        <select class="form-control select2" id="org_id" name="org_id" required>
                            <option>--Select Organization</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->org_name }}</option>
                            @endforeach
                        </select>
                        @error('org_id')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="branchName">Branch Name *</label>
                        <select class="form-control select2" id="branch_id" name="branch_id" required>
                            <option>--Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">
                                    {{ $branch->branch_name }} ({{ $branch->organization->org_name }})
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                            placeholder="Enter name" required>
                        @error('name')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="user_name">Username *</label>
                        <input type="text" class="form-control" id="user_name" name="user_name"
                            value="{{ old('user_name') }}" placeholder="Enter username" required>
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
                                data-inputmask='"mask": "+256999999999"' data-mask value="{{ old('user_phone') }}"
                                required>
                        </div>
                        @error('user_phone')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email') }}" placeholder="Enter email" required>
                        @error('email')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <p class="text-sm">*password should be atleast 8 characters.</p>
                                <input type="password" class="form-control" id="password" name="password"
                                    value="{{ old('password') }}" required>
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
                                    name="password_confirmation" value="{{ old('password_confirmation') }}" required>
                                @error('password_confirmation')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="card-tools text-right">
                        <button name="submit" type="submit" class="btn btn-success">Create User</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
