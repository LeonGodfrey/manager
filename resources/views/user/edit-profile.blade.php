@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Edit profile')
@section('page_title', 'Update Password ')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
    </ol>
@endsection

@section('main_content')

<div class="col-sm-12">
    <form method="POST" action="{{ route('user.update-password') }}">
        @csrf
        <div class="card card-success card-outline">
            <div class="card-body">
                <div class="form-group">
                    <label for="old_password">Old Password</label>
                    <input type="password" name="old_password" id="old_password" class="form-control">
                </div>
                <hr>            
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    @error('password')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
                </div>
                       
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">                    
                </div>            
                
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <div class="card-tools text-right">
                    <button name="submit" type="submit" class="btn btn-success">Change Password</button>
                </div>
            </div>
        </div>
    </form>
</div>
    
@endsection
