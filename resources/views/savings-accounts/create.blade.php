@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'New Savings Account | ' . $organization->org_name)
@section('page_title', 'New Savings Account')

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
        <div class="card card-outline card-success elevation-3 mt-3 mb-5 ">
            <form id="loanForm" method="post" action="{{ route('savings-accounts.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body pl-5 pr-5">
                    <div class="form-group">
                        <label for="savings_product_id">Savings Product *</label>
                        <div class="input-group">
                            <select class="form-control select2" id="savings_product_id" name="savings_product_id" required>
                                <option>--Select Savings Product</option>
                                @foreach ($savings_products as $savings_product)
                                    <option value="{{ $savings_product->id }}">
                                        {{ $savings_product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('savings_product_id')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="account_name">Account Name (optional)</label>
                        <input type="text" autocomplete="off" class="form-control" id="account_name" name="account_name"
                            value="{{ old('account_name') }}">
                        @error('account_name')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="amount">Opening Deposit Amount *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ $organization->currency_code }}</span>
                            </div>
                            <input type="text" autocomplete="off" min="0" class="form-control thousand-separator"
                                id="amount" name="amount" value="{{ old('amount') }}" required>
                        </div>
                        @error('amount')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="date">Transaction Date *</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input id="date" name="date" type="text" class="form-control datetimepicker-input"
                                data-target="#reservationdate" value="" placeholder="YYYY-MM-DD" required>
                        </div>
                        @error('date')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <input type="text" name="org_id" value="{{ $organization->id }}" required hidden>
                <input type="text" name="branch_id" value="{{ $client->branch_id }}" required hidden>
                <input type="text" name="client_id" value="{{ $client->id }}" required hidden>
                <input type="text" name="user_id" value="{{ $user->id }}" required hidden>

                <!-- /.card-body -->
                <div class="card-footer pl-5 pr-5">
                    @if ($cash_account_id)
                        <div class="card-tools text-right">
                            <button name="submit" type="submit" class="btn btn-success">Save New Account </button>
                        </div>
                    @else
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <p> You do not have a cash account. You will not be able to transact on this page. </p>
                        </div>
                    @endif
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
