@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Other Incomes | ' . $organization->org_name)
@section('page_title', 'Other Incomes')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
        <a href="{{ route('transactions.other-income.create') }}" class="btn float-right bg-success"><i class="fa fa-plus"></i>
            New Other Income</a>

    </ol>
@endsection

@section('main_content')

    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3">

            <div class="card-body table-responsive">
                <table id="example2" class="table table-hover table-head-fixed table-sm table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Created</th>
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
                                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                <td>{{ $transaction->date }}</td>
                                <td>{{ $transaction->branch->branch_name }}</td>
                            <td>{{ $transaction->particular }} @if($transaction->client_id) {{$transaction->client->surname.' '.$transaction->client->given_name}} @endif</td>
                                @php $incomeAccount = '' @endphp
                                @foreach ($details[$transaction->id] as $detail)
                                    @if ($detail->debit_credit == 'Credit')
                                        @php $incomeAccount = $detail->account->name @endphp
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
