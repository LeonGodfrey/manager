@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $account->savingsProduct->name . ' | '. $organization->org_name)
@section('page_title', $account->name.' | '.$account->savingsProduct->name)

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
                            <th>Transaction</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th> Accounts</th>
                            
                            <th class="text-nowrap">Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr @if ($transaction->is_reversed or $transaction->reverses) class="text-muted" @endif>
                                <td class="text-nowrap">{{ $transaction->id }}</td>
                                <td class="text-nowrap">{{ $transaction->created_at->format('Y-m-d') }}</td>
                                <td class="text-nowrap">{{ $transaction->date }}</td>
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
