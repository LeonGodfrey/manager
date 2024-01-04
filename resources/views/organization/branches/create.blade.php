@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $organization->org_name)
@section('page_title', 'New Branch')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
            <li class="breadcrumb-item btn btn-outline-success btn-sm "><a href={{ route('settings.branches.index') }}>Branches</a></li>            
    </ol>
@endsection

@section('main_content')
    
<div class="col-sm-12">
    <form method="post" action="{{ route('settings.branches.store') }}" >
        @csrf
        <div class="card card-outline card-success">
            <div class="card-body">               
                <div class="form-group">
                    <label for="branchName">Branch Name:</label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name"
                        value="{{ old('branch_name') }}" required>
                    @error('branch_name')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="branchPrefix">Branch Prefix:</label>
                    <p class="text-sm">*Branch short name/code only 3 letters.</p>
                    <input type="text" class="form-control" id="branch_prefix" name="branch_prefix"
                        value="{{ old('branch_prefix') }}" >
                    @error('branch_prefix')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="emailAddress">Branch Email Address:</label>
                    <input type="email" class="form-control" id="branch_email" name="branch_email"
                        value="{{ old('branch_email') }}" required>
                    @error('branch_email')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="branch_phone">Branch Phone:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        <input type="text" class="form-control" id="branch_phone" name="branch_phone"
                            data-inputmask='"mask": "+256999999999"' data-mask
                            value="{{ old('branch_phone') }}" required>
                    </div>
                    @error('branch_phone')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="branch_street_address">Street Address:</label>
                    <input type="text" class="form-control" id="branch_street_address" name="branch_street_address"
                        value="{{ old('branch_street_address') }}" required>
                    @error('branch_street_address')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="branch_city">City:</label>
                    <input type="text" class="form-control" id="branch_city" name="branch_city"
                        value="{{ old('branch_city') }}" required>
                    @error('branch_city')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="branch_district">District:</label>
                    <input type="text" class="form-control" id="branch_district" name="branch_district"
                        value="{{ old('branch_district') }}" required>                        
                    @error('branch_district')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>     
                <div class="form-group">
                    <label for="branch_postcode">Postcode:</label>
                    <input type="text" class="form-control" id="branch_postcode" name="branch_postcode"
                        value="{{ old('branch_postcode') }}" required>                        
                    @error('branch_postcode')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                    <input type="text" name="org_id"
                        value="{{$organization->id}}" required hidden>
                    <input type="text" name="status"
                        value="active" required hidden>
                </div>                
                
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <div class="card-tools text-right">
                    <button name="submit" type="submit" class="btn btn-success">Create Branch</button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
