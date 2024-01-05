@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Loan Application | ' . $organization->org_name)
@section('page_title', 'Loan Application')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
        <a href={{ route('clients.client', ['client' => $client]) }}>
            <li class="breadcrumb-item btn btn-outline-success btn-sm ">{{ $client->surname . ' ' . $client->given_name }}
            </li>
        </a>
        <a href={{ route('clients.index') }}>
            <li class="breadcrumb-item btn btn-outline-success btn-sm ">Clients</li>
        </a>
    </ol>
@endsection

@section('main_content')

    @include('layouts.client-info')

    <div class="col-sm-12">
        <div class="card card-outline card-success elevation-3 mb-5">
            <div class="card-header">
                <h3 class="card-title col-xs-6">Loan Application Details</h3>
            </div>
            <form id="loanForm" method="post" action="{{ route('loans.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="loan_product_id">Loan Product *</label>
                        <p class="text-sm m-0">Select the correct Loan Product for this Loan Application.</p>
                        <div class="input-group">
                            <select class="form-control select2" id="loan_product_id" name="loan_product_id" required>
                                <option value="">--Select Loan Product</option>
                                @foreach ($loan_products as $loan_product)
                                    <option value="{{ $loan_product->id }}">
                                        {{ $loan_product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('loan_product_id')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="application_amount">Loan Amount Applied For *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ $organization->currency_code }}</span>
                            </div>
                            <input autocomplete="off" type="text" class="form-control thousand-separator"
                                id="application_amount" name="application_amount" value="{{ old('application_amount') }}"
                                required>
                        </div>
                        @error('application_amount')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="application_period">Repayment Period in Months *</label>
                                <div class="input-group">
                                    <input autocomplete="off" type="number" class="form-control" id="application_period"
                                        name="application_period" value="{{ old('application_period') }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Months</span>
                                    </div>
                                </div>
                                @error('application_period')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="application_date">Loan Application Date *</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <div class="input-group-prepend" data-target="#reservationdate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input id="application_date" name="application_date" type="text"
                                        class="form-control datetimepicker-input" data-target="#reservationdate"
                                        value="" placeholder="YYYY-MM-DD" required>
                                </div>
                                @error('application_date')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="purpose">Purpose of the loan *</label>
                        <textarea id="purpose" name="purpose" class="form-control" rows="2" placeholder="Enter Purpose of the loan"
                            value="{{ old('purpose') }}" required></textarea>
                        @error('purpose')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <input type="text" name="org_id" value="{{ $organization->id }}" required hidden>
                <input type="text" name="branch_id" value="{{ $client->branch_id }}" required hidden>
                <input type="text" name="client_id" value="{{ $client->id }}" required hidden>
                <input type="text" name="status" value="pending_appraisal" required hidden>

                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="card-tools text-right">
                        <button name="submit" type="submit" class="btn btn-success">Submit Loan Application</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const unformatNumber = (value) => {
                return parseFloat(value.replace(/[^\d.-]/g,
                    '')); // Remove non-numeric characters except dots and minus signs
            };

            const numberWithCommas = (value) => {
                return parseFloat(value).toLocaleString('en-US');
            };

            document.querySelectorAll('.thousand-separator').forEach(function(input) {
                input.addEventListener('input', function() {
                    let unformattedValue = unformatNumber(this.value);
                    this.value = numberWithCommas(unformattedValue);
                });
            });

            document.querySelector('#loanForm').addEventListener('submit', function() {
                document.querySelectorAll('.thousand-separator').forEach(function(input) {
                    input.value = unformatNumber(input.value);
                });
            });
        });
    </script>




@endsection
