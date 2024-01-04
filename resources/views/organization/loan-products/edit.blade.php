@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $organization->org_name)
@section('page_title', 'Edit Loan Product')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
        <li class="breadcrumb-item btn btn-outline-success btn-sm "><a
                href={{ route('settings.loan-products.index') }}>Loan Products</a></li>
    </ol>
@endsection

@section('main_content')

    <div class="col-sm-12">
        <form method="post" action="{{ route('settings.loan-products.update', ['loan_product' => $loan_product]) }}">
            @csrf
            @method('PUT')
            <div class="card card-success card-outline">
                <div class="card-body">                
                    <div class="form-group">
                        <label for="name">Product Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $loan_product->name }}"
                            required placeholder="Enter name">
                        @error('name')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="interest_method">Interest Method*</label>
                                <p class="text-sm">The method used to accrue interest for this loan product.</p>
                                <select class="form-control select2" id="interest_method" name="interest_method" required>
                                    <option value="{{ $loan_product->interest_method }}" selected>{{ $loan_product->interest_method }}</option>
                                    <option value="Flat">Flat</option>
                                    <option value="Declining_balance">Declining_balance</option>
                                </select>
                                @error('interest_method')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="interest_rate">Interest Rate</label>
                            <p class="text-sm">Periodic interest rate based on the repayment frequency.</p>
                            <div class="input-group">
                                <input type="number" class="form-control" id="interest_rate" name="interest_rate"
                                    value="{{ $loan_product->interest_rate }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('interest_rate')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_frequency">Payment Frequency*</label>
                                <p class="text-sm">The period between dates in which the client must make loan installment
                                    repayments.</p>
                                <select class="form-control select2" id="payment_frequency" name="payment_frequency"
                                    required>
                                    <option value="{{ $loan_product->payment_frequency }}" selected>{{ $loan_product->payment_frequency }}</option>
                                    <option value="Daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Quarterly">Quarterly</option>
                                    <option value="Annually">Annually</option>
                                </select>
                                @error('payment_frequency')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="penalty_rate">Penalty Rate</label>
                            <p class="text-sm">The proportion of the loan's principal and interest in arrears that is
                                charged at payment.</p>
                            <div class="input-group">
                                <input type="number" class="form-control" id="penalty_rate" name="penalty_rate"
                                    value="{{ $loan_product->penalty_rate }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('penalty_rate')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="grace_period">Grace Period Installmentst</label>
                            <p class="text-sm">These installments, after disbursement, will not contain any due principal. </p>
                            <div class="input-group">
                                <input type="number" class="form-control" id="grace_period" name="grace_period"
                                    value="{{ $loan_product->grace_period }}" required>                            
                                @error('grace_period')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="charge_interest_grace_period">Should the system Charge Interest on Grace Period
                                    Installments?</label>
                                <p class="text-sm">Effective if grace period is set.</p>
                                <select class="form-control select2" id="charge_interest_grace_period"
                                    name="charge_interest_grace_period" required>
                                    <option value="{{ $loan_product->charge_interest_grace_period }}" selected>{{ $loan_product->charge_interest_grace_period }}</option>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                @error('charge_interest_grace_period')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="arrears_maturity_period">Arrears Maturity Period *</label>
                            <p class="text-sm">The period in days following a missed payment and the grace period if set
                                after which the loan will be considered in arrears.
                            </p>
                            <div class="input-group">
                                <input type="number" class="form-control" id="arrears_maturity_period"
                                    name="arrears_maturity_period" value="{{ $loan_product->arrears_maturity_period }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Days</span>
                                </div>
                                @error('arrears_maturity_period')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="max_loan_period">Maximum Loan Period in Months *</label>
                            <p class="text-sm">The Longest Loan Period for this Loan Product.</p>
                            <div class="input-group">
                                <input type="number" class="form-control" id="max_loan_period" name="max_loan_period"
                                    value="{{ $loan_product->max_loan_period }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Months</span>
                                </div>
                                @error('max_loan_period')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="card-tools text-right">
                        <button name="submit" type="submit" class="btn btn-success">Update Loan Product</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
