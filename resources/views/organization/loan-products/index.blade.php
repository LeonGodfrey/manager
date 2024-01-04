@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $organization->org_name)
@section('page_title', 'Loan Products')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
        <a href="{{ route('settings.loan-products.create') }}" class="btn float-right bg-success"><i
            class="fa fa-plus"></i> New Loan Product
    </a>
    </ol>
@endsection

@section('main_content')

<div class="col-sm-12">
        <div class="card card-success card-outline">
           
            <div class="card-body table-responsive p-0">
                <table id="example2" class="table table-hover table-head-fixed table-sm table-striped ">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Interest Method</th>
                            <th>Interest Rate(%)</th>
                            <th>Payment Frequency</th>
                            <th>Penalty Rate(%)</th>
                            <th>Max Loan period(Months)</th>                        
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loan_products as $loan_product)
                            <tr class="text-nowrap">
                                <td><a href="{{ route('settings.loan-products.edit', ['loan_product' => $loan_product]) }}">{{ $loan_product->name }}</a>
                                </td>
                                <td>{{ $loan_product->interest_method }}</td>
                                <td>{{ $loan_product->interest_rate }}</td>
                                <td>{{ $loan_product->payment_frequency }}</td>
                                <td>{{ $loan_product->penalty_rate }}</td>
                                <td>{{ $loan_product->max_loan_period }}</td>
                                <td></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No Savings Products found.</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div> <!-- /.card-body -->
        </div>
    </div>

@endsection
