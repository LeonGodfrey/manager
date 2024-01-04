@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $account->name . ' | ' . $organization->org_name)
@section('page_title', $account->name)

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
        <a href={{ route('cash-accounts.index') }}>
            <li class="breadcrumb-item btn btn-outline-success btn-sm ">Cash Accounts</li>
        </a>

    </ol>
@endsection

@section('main_content')

    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3">
            <div class="card-header">
                <h5 class=""> Account Balance: <b><span class="text-success">{{ $organization->currency_code }}</span>
                    <span class="" >{{ number_format($account->balance, 0, '.', ',') }}</span>
                </b></h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table id="example2" class="table table-hover table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Created</th>
                            <th>Txn Date</th>
                            <th>Branch</th>                            
                            <th>Transaction</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th> Accounts</th>
                            
                            <th class="text-nowrap">Action/ Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr @if ($transaction->is_reversed or $transaction->reverses) class="text-muted" @endif>
                                <td class="text-nowrap">{{ $transaction->id }}</td>
                                <td class="text-nowrap">{{ $transaction->created_at->format('Y-m-d') }}</td>
                                <td class="text-nowrap">{{ $transaction->date }}</td>
                                <td class="text-nowrap">{{ $transaction->branch->branch_name }} </td>
                                <td class="text-nowrap">{{ $transaction->type }}</td>
                                <td class="text-nowrap"><b>{{ number_format($transaction->amount, 0, '.', ',') }} </b></td>
                                <td>
                                    @foreach ($details[$transaction->id] as $detail)
                                        <div class="text-nowrap"> {{ $detail->type }} </div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($details[$transaction->id] as $detail)
                                        <div class="text-nowrap">
                                            {{ $detail->account->name . ' (' . number_format($detail->amount, 0, '.', ',') . ')' }} {{ $detail->debit_credit }},
                                        </div>
                                    @endforeach
                                </td>
                                {{-- <td>
                                    <div class="text-nowrap"><b>{{ number_format($transaction->amount, 0, '.', ',') }}</b>
                                    </div>
                                    <div class="text-nowrap">&nbsp; </div>
                                </td>
                                <td>
                                    <div class="text-nowrap">&nbsp; </div>
                                    <div class="text-nowrap"><b>{{ number_format($transaction->amount, 0, '.', ',') }}</b>
                                    </div>
                                </td> --}}
                                <td>

                                    @if ($transaction->is_reversed)
                                        Reversed by #{{ $transaction->reversed_by }} : {{ $transaction->reversal_reason }}
                                    @elseif($transaction->reverses)
                                        Reverses #{{ $transaction->reverses }}
                                    @else
                                        <button name="submit" type="submit" class="btn btn-sm btn-danger p-1"
                                            data-toggle="modal"
                                            data-target="#modal-lg-{{ $transaction->id }}">Reverse</button>
                                    @endif
                                </td>
                                <!-- Modal -->
                                <div class="modal fade" id="modal-lg-{{ $transaction->id }}">
                                    <div class="modal-dialog modal-lg">
                                        @if ($transaction->type == 'Expense')
                                            <form method="post" action="{{ route('transactions.expense-reverse') }}">
                                        @endif
                                        @if ($transaction->type == 'Non Cash')
                                            <form method="post" action="{{ route('transactions.reverse-non-cash') }}">
                                        @endif
                                        @if ($transaction->type == 'Cash Transfer')
                                            <form method="post"
                                                action="{{ route('transactions.cash-transfer-reverse') }}">
                                        @endif
                                        @if ($transaction->type == 'Other Income')
                                            <form method="post" action="{{ route('transactions.other-income-reverse') }}">
                                        @endif
                                        @if ($transaction->type == 'Payment')
                                        <form method="post" action="{{ route('loans.payment-reverse') }}">
                                    @endif
                                    @if ($transaction->type == 'Deposit')
                                        <form method="post" action="{{ route('savings-accounts.reverse-deposit') }}">
                                    @endif
                                    @if ($transaction->type == 'Withdrawal')
                                            <form method="post" action="{{ route('savings-accounts.reverse-withdraw') }}">
                                        @endif
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Transaction Reversal</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <p class="bg-danger"> Are you sure you want to reverse this
                                                            transaction ? <br>
                                                            Note that this action cannot be undone!</p>
                                                        <p>
                                                            <b>Transaction Date: </b> {{ $transaction->date }} <br>
                                                            <b>Transaction Type: </b> {{ $transaction->type }} <br>
                                                            <b>Transaction Amount: </b>
                                                            {{ number_format($transaction->amount, 0, '.', ',') }}
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="reversal_reason">Reason for Reversal? *</label>
                                                            <textarea id="reversal_reason" name="reversal_reason" class="form-control" rows="2"
                                                                placeholder="Enter Reason for the transaction reversal" value="{{ old('reversal_reason') }}" required></textarea>
                                                            @error('reversal_reason')
                                                                <div class="text-sm text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date">Transaction Date *</label>
                                                            <div class="input-group date" id="date-{{ $transaction->id }}"
                                                                data-target-input="nearest">
                                                                <div class="input-group-prepend"
                                                                    data-target="#reservationdate-{{ $transaction->id }}"
                                                                    data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i
                                                                            class="fa fa-calendar"></i></div>
                                                                </div>
                                                                <input id="date" name="date" type="text"
                                                                    class="form-control datetimepicker-input datepicker"
                                                                    data-target="#reservationdate-{{ $transaction->id }}"
                                                                    value="" placeholder="YYYY-MM-DD" required>
                                                            </div>
                                                            <input type="text" name="transaction_id"
                                                                value="{{ $transaction->id }}" required hidden>
                                                            <input type="text" name="transaction_type"
                                                                value="{{ $transaction->type }}" required hidden>
                                                            <input type="text" name="user_id"
                                                                value="{{ $user->id }}" required hidden>
                                                            @error('date')
                                                                <div class="text-sm text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer ">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Reverse
                                                    Transaction</button>
                                            </div>
                                        </div>
                                        </form>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                {{-- datepicker --}}

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">No Transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div> <!-- /.card-body -->
        </div>
        <div class="pb-3">
            {{ $transactions->links() }}
        </div>
    </div>


@endsection
