@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Client | ' . $organization->org_name)
@section('page_title', $client->surname . ' ' . $client->given_name)

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
        <a href={{ route('clients.index') }}>
            <li class="breadcrumb-item btn btn-outline-success btn-sm ">Clients</li>
        </a>
    </ol>
@endsection

@section('main_content')

@include('layouts.client-info')

    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3 mt-3">
            <div class="card-header">
                <h3 class="card-title col-xs-6"><b>Savings Accounts</b></h3>
                <div class="btn-group-links col-xs-6 text-right">
                    <p><a href="{{ route('savings-accounts.create', ['client' => $client]) }}">
                            Open New Account
                        </a></p>
                </div>
            </div>
            <div class="card-body">
                @forelse ($savings_accounts as $savings_account)
                <div class="row">
                    <div class="col-6">
                        <a class="text-success" href="{{ route('savings-accounts.index', ['client' => $client, 'account' => $savings_account]) }}">
                             <h5><b>{{$savings_account->account_name.' | '}}{{$savings_account->savingsProduct->name}}</b></h5>
                        </a>
                        <p>{{$savings_account->id}} . <a href="{{ route('savings-accounts.ledger', ['client' => $client, 'account' => $savings_account]) }}">View Ledger</a></p>
                    </div>
                    <div class="col-6">
                        <h5 class="text-right"><b><span
                            class="text-success">{{ $organization->currency_code }}</span>
                        {{ number_format($savings_account->balance, 0, '.', ',') }}
                    </b></h5>
                        <p class="text-right"><a href="{{ route('savings-accounts.deposit', ['client' => $client, 'account' => $savings_account]) }}">Deposit</a> | <a
                                href="{{ route('savings-accounts.withdraw', ['client' => $client, 'account' => $savings_account]) }}">Withdraw</a></p>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                @empty
                    <p><b>Client has no Active Account.</b></p>
                @endforelse

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3 mt-3">
            <div class="card-header">
                <h3 class="card-title col-xs-6"><b>Loans</b></h3>
                <div class="btn-group-links col-xs-6 text-right">
                    <p class="text-right"><a href="{{ route('loans.create', ['client' => $client]) }}">Apply for New
                            Loan</a> | <a href="cleared-loan.php">Cleared Loan</a> | <a href="deleted-loan.php">deferred
                            Loan</a></p>
                </div>
            </div>
            <div class="card-body">
                @forelse ($loans as $loan)
                    <div class="row">
                        <div class="col-6">
                            <a class="text-success" href="{{ route('loans.loan', ['loan' => $loan]) }}">
                                <h5><b>{{ $loan->loanProduct->name }}</b> <span
                                        class="btn btn-success btn-xs p-0 ml-3">{{ $loan->status }}</span></h5>
                            </a>
                            <p class="m-1">{{ $loan->id }} | {{ $loan->purpose }}</p>
                        </div>
                        <div class="col-6">
                            @if ($loan->status == 'pending_appraisal')
                                <h5 class="text-right"><b><span
                                            class="text-success">{{ $organization->currency_code }}</span>
                                        {{ number_format($loan->application_amount, 0, '.', ',') }}
                                    </b></h5>
                            @endif
                            @if ($loan->status == 'pending_approval')
                                <h5 class="text-right"><b><span
                                            class="text-success">{{ $organization->currency_code }}</span>
                                        {{ number_format($loan->appraisal_amount, 0, '.', ',') }}
                                    </b></h5>
                            @endif
                            @if ($loan->status == 'approved')
                                <h5 class="text-right"><b><span
                                            class="text-success">{{ $organization->currency_code }}</span>
                                        {{ number_format($loan->approved_amount, 0, '.', ',') }}
                                    </b></h5>
                            @endif
                            @if ($loan->status == 'disbursed')
                                <h5 class="text-right"><b><span
                                            class="text-success">{{ $organization->currency_code }}</span>
                                        {{ number_format($loan->approved_amount, 0, '.', ',') }}
                                    </b></h5>
                                <p class="text-right"><a href="{{ route('loans.payment-create', ['loan' => $loan]) }}">Make
                                        Payment</a></p>
                            @endif
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                @empty
                    <p><b>Client has no Active Loans.</b></p>
                @endforelse

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </div>

    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3 mt-5 mb-5">
            <div class="card-header">
                <h4 class="card-title text-success"><b>Fees paid by Client</b></h4>
                <div class="card-tools">
                    <a href="{{ route('transactions.client-fees', ['client' => $client]) }}"
                        class="btn float-right bg-success"><i class="fa fa-plus"></i> Add
                        Fees
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table id="example2" class="table table-hover table-head-fixed table-sm table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Transaction Date</th>
                            <th>Branch</th>
                            <th>Particulars</th>
                            <th>Income Account</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr class="text-nowrap @if ($transaction->is_reversed or $transaction->reverses) text-muted @endif">
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->date }}</td>
                                <td>{{ $transaction->branch->branch_name }}</td>
                                <td>{{ $transaction->client->surname . ' ' . $transaction->client->given_name }}</td>
                                @php $incomeAccount = '' @endphp
                                @foreach ($details[$transaction->id] as $detail)
                                    @if ($transaction->reverses)
                                        @if ($detail->debit_credit == 'Debit')
                                            @php $incomeAccount = $detail->account->name @endphp
                                        @endif
                                    @else
                                        @if ($detail->debit_credit == 'Credit')
                                            @php $incomeAccount = $detail->account->name @endphp
                                        @endif
                                    @endif
                                @endforeach
                                <td>{{ $incomeAccount }}</td>
                                <td><b>
                                        {{ number_format($transaction->amount, 0, '.', ',') }}</b></td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    @if ($transaction->is_reversed)
                                        Reversed by #{{ $transaction->reversed_by }} : {{ $transaction->reversal_reason }}
                                    @elseif($transaction->reverses)
                                        Reverses #{{ $transaction->reverses }}
                                    @else
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">No Transactions found.</td>
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
