@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'New Cash Transfer | ' . $organization->org_name)
@section('page_title', 'New Cash Transfer')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
        <a href={{ route('transactions.cash-transfer.index') }}>
            <li class="breadcrumb-item btn btn-outline-success btn-sm ">Cash Transfers</li>
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
        <form id="loanForm" method="post" action="{{ route('transactions.cash-transfer.store') }}">
            @csrf
            <div class="card card-outline card-success elevation-3">
                <div class=" card-body ">

                    @if ($user->branch->branch_name == 'Head Office')
                        <div class="form-group">
                            <label for="branch_id">Select Branch for this Transaction *</label>
                            <div class="input-group">
                                <select class="form-control select2" id="branch_id" name="branch_id" required>
                                    <option value="">--Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->branch_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('branch_id')
                                <div class="text-sm text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <input type="text" name="branch_id" value="{{ $user->branch_id }}" required hidden>
                    @endif

                    <div class="form-group">
                        <label for="subtype">Transfer Type *</label>
                        <select class="form-control select2" id="subtype" name="subtype" required>
                            <option value="">--Select Transfer Type</option>
                            <option value="Opening Balance">Opening Balance</option>
                            <option value="Buffer from the Bank">Buffer from the Bank</option>
                            <option value="Buffer from other Branches">Buffer from other Branches</option>
                            <option value="Buffer from HeadOffice">Buffer from HeadOffice</option>
                            <option value="Buffer from Agency Banking">Buffer from Agency Banking</option>
                            <option value="Closing Balance">Closing Balance</option>
                            <option value="Buffer to the Bank">Buffer to the Bank</option>
                            <option value="Buffer to other Branches">Buffer to other Branches</option>
                            <option value="Buffer to HeadOffice">Buffer to HeadOffice</option>
                            <option value="Buffer to Agency Banking">Buffer to Agency Banking</option>
                            <option value="Buffer to CEO">Buffer to CEO</option>
                        </select>
                        @error('subtype')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="credit_account">Source Account</label>
                        <p class="text-sm m-0">
                            The cash account from which the funds will be transferred.</p>
                        <div class="input-group">
                            <select class="form-control select2" id="credit_account" name="credit_account" required>
                                <option value="">--Select Account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->id . ' - ' . $account->name . ' (' . $organization->currency_code . ' ' . number_format($account->balance, 0, '.', ',') . ' )' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('credit_account')
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
                        <label for="debit_account">Destination Account</label>
                        <p class="text-sm m-0">The cash account to which the funds will be transferred.</p>
                        <div class="input-group">
                            <select class="form-control select2" id="debit_account" name="debit_account" required>
                                <option value="">--Select Account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->id . ' - ' . $account->name . ' (' . $organization->currency_code . ' ' . number_format($account->balance, 0, '.', ',') . ' )' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('debit_account')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="receipt_number">Receipt Number </label>
                        <input type="text" class="form-control" id="receipt_number" name="receipt_number"
                            value="{{ old('receipt_number') }}">
                        @error('receipt_number')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="2"
                            placeholder="Enter description of the transaction" required>{{ old('description') }}</textarea>
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
                            <input id="date" name="date" type="text"
                                class="form-control datetimepicker-input" data-target="#reservationdate" value=""
                                placeholder="YYYY-MM-DD" required>
                        </div>
                        @error('date')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="text" name="org_id" value="{{ $organization->id }}" required hidden>
                    <input type="text" name="user_id" value="{{ $user->id }}" required hidden>
                    <input type="text" name="type" value="Cash Transfer" required hidden>



                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="card-tools text-right pr-5">
                        @if ($cash_account_id)
                            <button name="submit" type="submit" class="btn btn-success">Save Cash Transfer</button>
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
