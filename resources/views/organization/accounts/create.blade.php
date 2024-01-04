@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $organization->org_name)
@section('page_title', 'New Account')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
            <li class="breadcrumb-item btn btn-outline-success btn-sm "><a href={{ route('settings.accounts.index') }}>Accounts</a></li>            
    </ol>
@endsection

@section('main_content')
    
<div class="col-sm-12">
    <form method="post" action="{{ route('settings.accounts.store') }}" >
        @csrf
        <div class="card card-outline card-success">
            <div class="card-body">               
                <div class="form-group">
                    <label for="name">Account Name:</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="number">Account Number - Optional</label>
                    <input type="text" class="form-control" id="number" name="number"
                        value="{{ old('number') }}">
                    @error('number')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="type">Account Type*</label>
                    <select class="form-control select2" id="type" name="type" required>
                        <option value="">--Select Account Type</option>
                        <option value="Asset">Asset</option>
                        <option value="Equity">Equity</option>
                        <option value="Expense">Expense</option>
                        <option value="Income">Income</option>
                        <option value="Liability">Liability</option>                       
                    </select>
                    @error('type')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="subtype">Account SubType - Optional</label>
                    <select class="form-control select2" id="subtype" name="subtype">
                        <option value="">--Select Sub Account</option>
                        <option value="User Cash Account">(Asset) User Cash Account</option>
                        <option value="Vault Account">(Asset) Vault Account</option>
                        <option value="Cash at Bank">(Asset) Cash at Bank</option>
                        <option value="Loan Product">(Asset) Loan Product</option>
                        <option value="Mobile Money">(Asset) Mobile Money</option>
                        <option value="Savings Product">(Liability) Savings Product</option>                       
                    </select>
                    @error('subtype')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>                
                    <input type="text" name="org_id"
                        value="{{$organization->id}}" required hidden>                              
                
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <div class="card-tools text-right">
                    <button name="submit" type="submit" class="btn btn-success">Create Account</button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
