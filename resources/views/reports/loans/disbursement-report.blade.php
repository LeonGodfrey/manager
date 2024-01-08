@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Loan Disbursement Report | ' . $organization->org_name)
@section('page_title', 'Loan Disbursement Report')

@section('bread_crumb')
    
@endsection

@section('main_content')

    <!-- filter -->
    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3">
            <!-- /.card-header -->
            <div class="card-body pb-0">
                <form action="{{ route('reports.loans.disbursement-filter') }}" method="get">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Starting Date</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" name="from_date" class="form-control datetimepicker-input"
                                                data-target="#reservationdate" value="{{ $from_date }}"
                                                required="required" />
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Closing Date</label>
                                        <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                            <input type="text" name="to_date" class="form-control datetimepicker-input"
                                                value="{{ $to_date }}" data-target="#reservationdate1" />
                                            <div class="input-group-append" data-target="#reservationdate1"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>By Branch</label>
                                        <div class="input-group ">
                                            <select class="form-control select2" id="branch_id" name="branch_id">
                                                <option value="">--All Branches</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        @if ($branch_id == $branch->id) selected @endif>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>By Credit Officer</label>
                                        <div class="input-group ">
                                            <select class="form-control select2" id="loan_officer_id"
                                                name="loan_officer_id">
                                                <option value="">--All Credit Officers</option>
                                                @foreach ($credit_officers as $credit_officer)
                                                    <option value="{{ $credit_officer->id }}"
                                                        @if ($credit_officer_id == $credit_officer->id) selected @endif>
                                                        {{ $credit_officer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>By Loan Product</label>
                                        <div class="input-group ">
                                            <select class="form-control select2" id="loan_product_id"
                                                name="loan_product_id">
                                                <option value="">--All Loan Products</option>
                                                @foreach ($loan_products as $loan_product)
                                                    <option value="{{ $loan_product->id }}"
                                                        @if ($loan_product_id == $loan_product->id) selected @endif>
                                                        {{ $loan_product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Submit</label>
                                        <input type="submit" class="btn bg-success form-control" value="Filter">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div><!-- /.card -->
    </div> <!-- filter -->

    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3">
            <div class="card-header">
                @if ($branch)
                    <p>Loan Disbursement Report for <b> {{ $branch->name }} </b> from the start of <b>{{ $from_date }} </b> to the
                        close of
                        <b> {{ $to_date }} </b>
                    </p>
                @endif
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table id="example2" class="table table-hover table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Loan ID</th>                            
                            <th>Client Name</th>
                            <th>Client Number</th>
                            <th>Gender</th>
                            <th>Credit Officer</th>
                            <th>Branch</th>
                            <th>Product</th>
                            <th>Disbursement Date</th>
                            <th>Expiry Date</th>
                            <th>Repayment Period</th>
                            <th>Interest Rate</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;                           
                        @endphp
                        @forelse ($loans as $loan)
                           
                            <tr>
                                <td class="text-nowrap">{{ $loan->id }}</td>
                                <td class="text-nowrap">{{ $loan->client->surname }}</td>
                                <td class="text-nowrap">{{ $loan->client->number }}</td>
                                <td class="text-nowrap">{{ $loan->client->gender }} </td>
                                <td class="text-nowrap">{{ $loan->id }}</td>
                                <td class="text-nowrap">{{ $loan->client->surname }}</td>
                                <td class="text-nowrap">{{ $loan->client->number }}</td>
                                <td class="text-nowrap">{{ $loan->client->gender }} </td>
                                <td class="text-nowrap">{{ $loan->id }}</td>
                                <td class="text-nowrap">{{ $loan->client->surname }}</td>
                                <td class="text-nowrap">{{ $loan->client->number }}</td>
                                <td class="text-nowrap">{{ $loan->client->gender }} </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9">No Records Found.</td>
                            </tr>
                        @endforelse

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="11"><b>Total </b></td>                            
                            <td><b>{{ number_format($total, 0, '.', ',') }}</b></td>
                        </tr>
                    </tfoot>

                </table>

            </div> <!-- /.card-body -->
        </div>
    </div>


@endsection
