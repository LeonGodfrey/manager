@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Non Cash Transactions | ' . $organization->org_name)
@section('page_title', 'Non Cash Transactions')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
        <a href="{{ route('transactions.non-cash.create') }}" class="btn float-right bg-success"><i class="fa fa-plus"></i> New
            Non Cash Transaction</a>

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
                            <th>Created by</th>
                            <th>Branch</th>
                            <th>Description</th>
                            <th> Accounts</th>
                            <th>Debit</th>
                            <th>Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr class="text-nowrap">
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                <td>{{ $transaction->date }}</td>
                                <td>{{ $transaction->user->name }}</td>
                                <td>{{ $transaction->branch->branch_name }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td style="vertical-align: middle;">
                                    @foreach ($details[$transaction->id] as $detail)
                                        <div class="text-nowrap"> {{ $detail->account->name }} </div>
                                    @endforeach
                                </td>
                                <td style="vertical-align: middle;">
                                    <div class="text-nowrap"><b>
                                            {{ number_format($transaction->amount, 0, '.', ',') }}</b></div>
                                    <div class="text-nowrap">&nbsp; </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <div class="text-nowrap">&nbsp; </div>
                                    <div class="text-nowrap"><b>
                                            {{ number_format($transaction->amount, 0, '.', ',') }}</b></div>
                                </td>

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
