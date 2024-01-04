@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Payments | ' . $organization->org_name)
@section('page_title', 'Make Payments')

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


    <div class="col-12">
        <div class="form-group">
            <div role="group" class="btn-group">
                <ul class="nav">
                    <li>
                        <a href="{{ route('loans.loan', ['loan' => $loan]) }}" class="btn btn-default btn-success">Loan Details</a>
                    </li>
                    <li>
                        <a href="{{ route('loans.schedule', ['loan' => $loan]) }}"
                            class="btn  btn-default btn-success">Loan Schedule</a>
                    </li>
                    <li>
                        <a href="{{ route('loans.ledger', ['loan' => $loan]) }}" class="btn  btn-default btn-success">Loan Ledger</a>
                    </li>
                    <li>
                        <a href="{{ route('loans.payment-create', ['loan' => $loan]) }}" class="btn btn-success">Make
                            Payment</a>
                    </li>   
                </ul>
            </div>
        </div>
    </div>

    {{-- payments --}}
    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3 mt-3 mb-3">
            <div class="card-header">
                <h4 class="card-title text-success"><b>Loan details</b></h4>
            </div>
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-sm-6">
                        <p><b> Installments in Arrears </b></p>
                        <table class="table table-striped table-sm table-bordered">
                            <thead>
                                <tr>
                                <tr>
                                    <th>Due Date</th>
                                    <th>Principal</th>
                                    <th>Interest</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $arrears = 0;
                                $penalties = 0;
                                @endphp
                                @forelse ($installmentsInArrears as $item)
                                @php
                                $arrears += $item['principal'];
                                $arrears += $item['interest'];
                                $penalties += $item['penalties'];

                                @endphp
                                    <tr>
                                        <td>{{ $item['date'] }}</td>
                                        <td> {{ number_format($item['principal'], 0, '.', ',') }}</td>
                                        <td>{{ number_format($item['interest'], 0, '.', ',') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">No Installments over due.</td>
                                    </tr>
                                @endforelse
                                <tr>
                                   <td colspan="2"><b>Total for Installments </b></td>
                                    <td><b>{{ number_format($arrears, 0, '.', ',') }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    

                    </div>
                    <div class="col-sm-6">
                        <p> <b> Payments Required </b></p>
                        <table class="table table-striped table-sm table-bordered">
                            <thead>
                                <tr>
                                <tr>
                                    <th colspan="2">Item</th>
                                    <th>Amount</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td colspan="2">Penalties</td>
                                        <td>{{ number_format($penalties, 0, '.', ',') }}</td>
                                    </tr>                                    
                                    <tr>
                                        <td colspan="2">Total for Installments</td>
                                        <td>{{ number_format($arrears, 0, '.', ',') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Total Arrears Payment</b></td>
                                         <td><b>{{ number_format($arrears + $penalties, 0, '.', ',') }}</b></td>
                                     </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- /.card-body -->
        </div>

    </div>

    {{-- payment --}}
    <div class="col-sm-12">
        <div class="card card-outline card-success elevation-3 mt-3">
            <div class="card-header">
                <h3 class="card-title col-xs-6 font-weight-bold">Loan payment</h3>
            </div>
            {{-- form --}}
            <form class="loanForm" method="post" action="{{ route('loans.payment-store', ['loan' => $loan]) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                {{-- <input autocomplete="off" class="form-control" min="0" required="" type="text" id="amount" --}}
                {{-- name="amount-input" placeholder=""> --}}
                <div class="card-body">
                    <div class="form-group">
                        <label for="amount">Total Amount Paid *</label>
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

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="paid_principal">Principal Paid *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ $organization->currency_code }}</span>
                                    </div>
                                    <input type="text" autocomplete="off" min="0"
                                        class="form-control thousand-separator" id="paid_principal" name="paid_principal"
                                        value="0" required>
                                </div>
                                @error('paid_principal')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="paid_interest">Interest Paid *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ $organization->currency_code }}</span>
                                    </div>
                                    <input type="text" autocomplete="off" min="0"
                                        class="form-control thousand-separator" id="paid_interest" name="paid_interest"
                                        value="0" required>
                                </div>
                                @error('paid_interest')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="paid_penalties">Penalties Paid</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ $organization->currency_code }}</span>
                                    </div>
                                    <input type="text" autocomplete="off" min="0"
                                        class="form-control thousand-separator" id="paid_penalties" name="paid_penalties"
                                        value="">
                                </div>
                                @error('paid_penalties')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <p class="text-danger text-monospace" id="payment_error"><b>Total Amount Paid</b> should be equal to
                        <b>(Principal + Interest + Penalties)</b>.
                    </p>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Payment Date *</label>
                                <div class="input-group date" id="date-payment" data-target-input="nearest">
                                    <div class="input-group-prepend" data-target="#reservationdate-payment"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input id="date" name="date" type="text"
                                        class="form-control datetimepicker-input datepicker"
                                        data-target="#reservationdate-payment" value="" placeholder="YYYY-MM-DD"
                                        required>
                                </div>
                                @error('date')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="receipt_number">Receipt Number</label>
                                <input type="text" autocomplete="off" class="form-control" id="receipt_number"
                                    name="receipt_number" value="{{ old('receipt_number') }}">
                                @error('receipt_number')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <input type="text" name="org_id" value="{{ $organization->id }}" required hidden>
                    <input type="text" name="branch_id" value="{{ $user->branch_id }}" required hidden>
                    <input type="text" name="user_id" value="{{ $user->id }}" required hidden>
                    <input type="text" name="client_id" value="{{ $client->id }}" required hidden>
                    <input type="text" name="loan_product_id" value="{{ $loan->loan_product_id }}" required hidden>


                </div>
                <!-- /.card-body -->
                @if ($cash_account_id)
                    <div class="card-footer pr-5">
                        <div class="card-tools text-right">
                            <button name="submit" type="submit" class="btn btn-success">Make Payment</button>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger alert-dismissible m-3">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <p> You do not have a cash account. You will not be able to transact on this page. </p>
                    </div>
                @endif
            </form>
        </div>
    </div>

    
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     const unformatNumber = (value) => {
        //         return parseFloat(value.replace(/[^\d.-]/g,
        //             '')); // Remove non-numeric characters except dots and minus signs
        //     };

        //     const numberWithCommas = (value) => {
        //         return parseFloat(value).toLocaleString('en-US');
        //     };

        //     document.querySelectorAll('.thousand-separator').forEach(function(input) {
        //         input.addEventListener('input', function() {
        //             let unformattedValue = unformatNumber(this.value);
        //             this.value = numberWithCommas(unformattedValue);

        //         });
        //     });

        //     document.querySelector('.loanForm').addEventListener('submit', function() {
        //         document.querySelectorAll('.thousand-separator').forEach(function(input) {
        //             input.value = unformatNumber(input.value);
        //         });
        //     });
        // });

        document.addEventListener('DOMContentLoaded', function() {
            const unformatNumber = (value) => {
                return parseFloat(value.replace(/[^\d.-]/g, '').replace(/,/g,
                '')); // Remove non-numeric characters, including commas and keep dots and minus signs
            };

            const formatInput = (input) => {
                let unformattedValue = unformatNumber(input.value);
                if (!isNaN(unformattedValue)) {
                    input.value = unformattedValue.toLocaleString('en-US');
                } else {
                    input.value = ''; // Clear the field if it's not a valid number
                }
            };

            document.querySelectorAll('.thousand-separator').forEach(function(input) {
                input.addEventListener('input', function() {
                    formatInput(this);
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
