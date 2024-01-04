@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Add Fees Paid | ' . $organization->org_name)
@section('page_title', 'Add Fee Paid')

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

    <div class="col-sm-12">
        @if (!$cash_account_id)
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p> You do not have a cash account. You will not be able to transact on this page. </p>
            </div>
        @endif
    </div>

    <div class="col-sm-12">
        <form id="loanForm" method="post" action="{{ route('transactions.client-fees.store') }}">
            @csrf
            <div class="card card-outline card-success elevation-3">
                <div class=" card-body ">
                    <div class="pl-5 pr-5">
                    <div class="form-group">
                        <label for="account_id">Choose Income Account *</label>
                        <p class="text-sm m-0">The general ledger account to which this transaction will be recorded.</p>
                        <div class="input-group">
                            <select class="form-control select2" id="account_id" name="account_id" required>
                                <option value="">--Select Account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->id." - ".$account->name." (".$organization->currency_code." ".number_format($account->balance, 0, '.', ',')." )" }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('account_id')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ $organization->currency_code }}</span>
                            </div>
                            <input type="text" class="form-control thousand-separator" id="amount" name="amount"
                                value="{{ old('amount') }}" required>
                        </div>
                        @error('amount')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>                    

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="2"
                            placeholder="Enter description of the transaction" value="{{ old('description') }}" required></textarea>
                        @error('description')
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

                    <input type="text" name="org_id" value="{{ $organization->id }}" required hidden>
                    <input type="text" name="branch_id" value="{{ $user->branch_id }}" required hidden>
                    <input type="text" name="user_id" value="{{ $user->id }}" required hidden>
                    <input type="text" name="client_id" value="{{ $client->id }}" required hidden>
                    <input type="text" name="type" value="Other Income" required hidden>

                    @if ($cash_account_id)
                        <input type="text" name="cash_account_id" value="{{ $cash_account_id }}" required hidden>
                    @endif
                </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="card-tools text-right pr-5">
                        @if ($cash_account_id)                            
                            <button name="submit" type="submit" class="btn btn-success">Save Other Income</button>                        
                        @endif
                    </div>
                </div>
            </div>
        </form>
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
