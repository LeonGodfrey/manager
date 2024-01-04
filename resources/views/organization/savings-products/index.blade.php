@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $organization->org_name)
@section('page_title', 'Savings Products')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
        <a href="{{ route('settings.savings-products.create') }}" class="btn float-right bg-success"><i
            class="fa fa-plus"></i> New Savings Product
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
                            <th>Product Type</th>
                            <th>Openning Balance</th>
                            <th>Minimum Balance</th>
                            <th>Deposit Fee</th>
                            <th>Withdraw Fee</th>
                            <th>Monthly Fee</th>
                            <th>Interest rate</th>
                            <th>Interest Frequency</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($savings_products as $savings_product)
                            <tr class="text-nowrap">
                                <td><a
                                        href="{{ route('settings.savings-products.edit', ['savings_product' => $savings_product]) }}">{{ $savings_product->name }}</a>
                                </td>
                                <td>{{ $savings_product->saving_product_type }}</td>
                                <td>{{ $savings_product->opening_balance }}</td>
                                <td>{{ $savings_product->min_balance }}</td>
                                <td>{{ $savings_product->deposit_fee }}</td>
                                <td>{{ $savings_product->withdrawal_fee }}</td>
                                <td>{{ $savings_product->monthly_fee }}</td>
                                <td>{{ $savings_product->interest_rate }}</td>
                                <td>{{ $savings_product->interest_frequency }}</td>
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
