@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'New Non Cash Transactions | ' . $organization->org_name)
@section('page_title', 'New Non Cash Transactions')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
        <a href={{ route('transactions.non-cash.index') }}>
            <li class="breadcrumb-item btn btn-outline-success btn-sm ">Non Cash Transactions</li>
        </a>
    </ol>
@endsection

@section('main_content')


    <div class="col-sm-12">
        <form id="loanForm" method="post" action="{{ route('transactions.non-cash.store') }}">
            @csrf
            <div class="card card-outline card-success elevation-3">
                <div class=" card-body">
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

                    <div class="form-group">
                        <label for="debit_type">Debit Account Type*</label>
                        <select class="form-control select2" id="debit_type" name="debit_type" required>
                            <option value="">--Select Debit Account type</option>
                            <option value="Asset">Asset</option>
                            <option value="Equity">Equity</option>
                            <option value="Expense">Expense</option>
                            <option value="Income">Income</option>
                            <option value="Liability">Liability</option>                       
                        </select>
                        @error('debit_type')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group" id="debitAccountField" style="display: none;">
                        <label for="debit_account"> Debit Account *</label>
                        <p class="text-sm m-0">The account to be debited by this transaction.</p>
                       
                            <select class="form-control select2" id="debit_account" name="debit_account" required>
                                <option value="">--Select Debit Account</option>
                                <!-- Options will be populated dynamically based on selection -->
                            </select>
                        
                        @error('debit_account')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="credit_type">Credit Account Type*</label>
                        <select class="form-control select2" id="credit_type" name="credit_type" required>
                            <option value="">--Select Debit Account type</option>
                            <option value="Asset">Asset</option>
                            <option value="Equity">Equity</option>
                            <option value="Expense">Expense</option>
                            <option value="Income">Income</option>
                            <option value="Liability">Liability</option>                       
                        </select>
                        @error('credit_type')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" id="creditAccountField" style="display: none;">
                        <label for="credit_account">Credit Account *</label>
                        <p class="text-sm m-0">The account to be credit by this transaction.
                        </p>
                            <select class="form-control select2" id="credit_account" name="credit_account" required>
                                <option value="">--Select Credit Account</option>
                                
                            </select>
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
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="2"
                            placeholder="Enter description of the transaction" required> {{ old('description') }}</textarea>
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
                    <input type="text" name="user_id" value="{{ $user->id }}" required hidden>
                    <input type="text" name="type" value="Non Cash" required hidden>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="card-tools text-right pr-5">
                            <button name="submit" type="submit" class="btn btn-success">Save Non Cash Transaction</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#debit_type').change(function() {
                var selectedType = $(this).val();
                if (selectedType !== '') {
                    $('#debitAccountField').show(); // Show the Debit Account field
                    $('#debit_account').empty(); // Clear the options
    
                    // Populate the Debit Account field with accounts of the selected type
                    @foreach ($accounts as $account)
                    @php
                    $balance = number_format($account->balance, 0, '.', ',');
                    @endphp
                    if ('{{ $account->type }}' === selectedType) {
                        $('#debit_account').append($('<option>', {
                            value: '{{ $account->id }}',
                            text: '{{ $account->id." - ".$account->name." (".$organization->currency_code." ".$balance." )" }}'
                        }));
                    }
                    @endforeach
                } else {
                    $('#debitAccountField').hide(); // Hide the Debit Account field if no type selected
                }
            });
        });

        $(document).ready(function() {
            $('#credit_type').change(function() {
                var selectedType = $(this).val();
                if (selectedType !== '') {
                    $('#creditAccountField').show(); // Show the credit Account field
                    $('#credit_account').empty(); // Clear the options
    
                    // Populate the credit Account field with accounts of the selected type
                    @foreach ($accounts as $account)
                    @php
                    $balance = number_format($account->balance, 0, '.', ',');
                    @endphp
                    if ('{{ $account->type }}' === selectedType) {
                        $('#credit_account').append($('<option>', {
                            value: '{{ $account->id }}',
                            text: '{{ $account->id." - ".$account->name." (".$organization->currency_code." ".$balance." )" }}'
                        }));
                    }
                    @endforeach
                } else {
                    $('#creditAccountField').hide(); // Hide the credit Account field if no type selected
                }
            });
        });
    </script>

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
