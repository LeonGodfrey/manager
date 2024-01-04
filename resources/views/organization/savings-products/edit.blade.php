@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $organization->org_name)
@section('page_title', 'Edit Savings Product')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
        <li class="breadcrumb-item btn btn-outline-success btn-sm "><a
                href={{ route('settings.savings-products.index') }}>Savings Products</a></li>
    </ol>
@endsection

@section('main_content')

    <div class="col-sm-12">
        <form method="post" action="{{ route('settings.savings-products.update', ['savings_product' => $savings_product]) }}">
            @csrf
            @method('PUT')
            <div class="card card-success card-outline">
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $savings_product->name }}" required>
                        @error('name')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="savings_product_type">Savings Product Type*</label>
                        <p class="text-sm">A <b>Standard</b> savings product is your basic Savings account without the need
                            for a fixed time period. <br> A <b>Fixed</b> savings product account is a Fixed Deposit account
                            where a client will store his/her money for a fixed period of time as it accrues interest.</p>
                        <select class="form-control select2" id="saving_product_type" name="saving_product_type" required>
                            <option value="{{ $savings_product->saving_product_type }}" selected>
                                {{ $savings_product->saving_product_type }}</option>
                            <option value="standard">standard</option>
                            <option value="fixed">fixed</option>
                        </select>
                        @error('saving_product_type')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="opening_balance">Openning Balance</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $organization->currency_code }}</span>
                                </div>
                                <input type="number" class="form-control" id="opening_balance" name="opening_balance"
                                    value="{{ $savings_product->opening_balance }}" required>
                                @error('opening_balance')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="min_balance">Minimum Balance</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $organization->currency_code }}</span>
                                </div>
                                <input type="number" class="form-control" id="min_balance" name="min_balance"
                                    value="{{ $savings_product->min_balance }}" required>
                                @error('min_balance')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="deposit_fee">Deposit Fee</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $organization->currency_code }}</span>
                                </div>
                                <input type="number" class="form-control" id="deposit_fee" name="deposit_fee"
                                    value="{{ $savings_product->deposit_fee }}" required>
                                @error('deposit_fee')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="withdrawal_fee">Withdraw Fee </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $organization->currency_code }}</span>
                                </div>
                                <input type="number" class="form-control" id="withdrawal_fee" name="withdrawal_fee"
                                    value="{{ $savings_product->withdrawal_fee }}" required>
                                @error('withdrawal_fee')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="monthly_fee">Monthly Fee</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $organization->currency_code }}</span>
                                </div>
                                <input type="number" class="form-control" id="monthly_fee" name="monthly_fee"
                                    value="{{ $savings_product->monthly_fee }}" required>
                                @error('monthly_fee')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="interest_rate">Interest Rate</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="interest_rate" name="interest_rate"
                                    value="{{ $savings_product->interest_rate }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('interest_rate')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="interest_frequency">Interest Rate Frequency</label>
                                <select class="form-control select2" id="interest_frequency" name="interest_frequency">
                                    <option value="{{ $savings_product->interest_frequency }}" selected>
                                        {{ $savings_product->interest_frequency }}</option>
                                    <option value="Daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Quarterly">Quarterly</option>
                                    <option value="Annually">Annually</option>
                                </select>
                                @error('interest_frequency')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <label for="penalty_rate">Penalty Rate</label>
                    <p class="text-sm">The proportion of the accumulated interest that is charged for early withdrawals
                        from <b>fixed deposit accounts</b>.</p>
                    <div class="input-group">
                        <input type="number" class="form-control" id="penalty_rate" name="penalty_rate"
                            value="{{ $savings_product->penalty_rate }}">
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('penalty_rate')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="card-tools text-right">
                        <button name="submit" type="submit" class="btn btn-success">Update Savings Product</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
