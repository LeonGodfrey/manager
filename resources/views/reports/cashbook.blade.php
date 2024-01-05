@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Cash Book | ' . $organization->org_name)
@section('page_title', 'Cash Book')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
        <a href={{ route('cash-accounts.index') }}>
            <li class="breadcrumb-item btn btn-outline-success btn-sm ">Cash Accounts</li>
        </a>

    </ol>
@endsection

@section('main_content')

     <!-- filter -->
     <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3">
            <!-- /.card-header -->
            <div class="card-body pb-0">
                <form action="{{ route('reports.cashbook') }}" method="get">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Account</label>
                                        <div class="input-group ">
                                            <select class="form-control select2" id="account_id" name="account_id">
                                                <option value="">--All Accounts</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                @endforeach
                                            </select>                                            
                                        </div>
                                        @error('account_id')
                                                <div class="text-sm text-danger">{{ $message }}</div>
                                            @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>From:</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" name="from_date" class="form-control datetimepicker-input"
                                                data-target="#reservationdate" required="required" />
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>To:</label>
                                        <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                            <input type="text" name="to_date" class="form-control datetimepicker-input"
                                                data-target="#reservationdate1" />
                                            <div class="input-group-append" data-target="#reservationdate1"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>:</label>
                                        <input type="submit" class="btn bg-success form-control" value="Filter">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div><!-- /.card -->
    </div> <!-- filter -->

    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3">
            <div class="card-header">
                <h5 class=""> Account Balance: <b><span class="text-success">{{ $organization->currency_code }}</span>
                        <span class=""></span>
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
                                            {{ $detail->account->name . ' (' . number_format($detail->amount, 0, '.', ',') . ')' }}
                                            {{ $detail->debit_credit }},
                                        </div>
                                    @endforeach
                                </td>
                              
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
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">Apply filters to load transactions.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div> <!-- /.card-body -->
        </div>
        <div class="pb-3">
            @if($transactions)
            {{ $transactions->links() }}
            @endif
        </div>
    </div>


@endsection
