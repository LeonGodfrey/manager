@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $organization->org_name)
@section('page_title', 'Edit Account')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
        <li class="breadcrumb-item btn btn-outline-success btn-sm "><a
                href={{ route('settings.accounts.index') }}>Accounts</a></li>
    </ol>
@endsection

@section('main_content')

    <div class="col-sm-12">
        <form method="post" action="{{ route('settings.accounts.update', ['account' => $account]) }}">
            @csrf
            @method('PUT')
            <div class="card card-success card-outline">
                <div class="card-body">                
                <div class="form-group">
                    <label for="name">Account Name *</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $account->name }}"
                        required>
                    @error('name')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="number">Account Number - Optional</label>
                    <input type="text" class="form-control" id="number" name="number" value="{{ $account->number }}">
                    @error('number')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="type">Account Type*</label>
                    <select class="form-control select2" id="type" name="type" required>
                        <option value="{{ $account->type }}" selected>{{ $account->type }}</option>
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
                        <option value="{{ $account->subtype }}" selected>{{ $account->subtype }}</option>
                        <option value="User Cash Account">(Asset) User Cash Account</option>
                        <option value="Vualt Account">(Asset) Vault Account</option>
                        <option value="Cash at Bank">(Asset) Cash at Bank</option>
                        <option value="Loan Product">(Asset) Loan Product</option>
                        <option value="Savings Product">(Liability) Savings Product</option>
                    </select>
                    @error('subtype')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <div class="card-tools text-right">
                    <button name="submit" type="submit" class="btn btn-success">Update Branch</button>
                </div>
            </div>
    </div>
    </form>
    </div>

@endsection
