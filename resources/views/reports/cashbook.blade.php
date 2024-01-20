@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Cash Book | ' . $organization->org_name)
@section('page_title', 'Cash Book')

@section('bread_crumb')
    
@endsection

@section('main_content')

    <!-- filter -->
    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3">
            <!-- /.card-header -->
            <div class="card-body pb-0">
                <form action="{{ route('reports.cashbook-filter') }}" method="get">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <p class="text-sm">Apply filters to load cashbook, long date ranges will take time to load.</p> --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Account*</label>
                                        <div class="input-group ">
                                            <select class="form-control select2" id="account_id" name="account_id" required>
                                                <option value="">--Select Account</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}"
                                                        @if ($account_id == $account->id) selected @endif>
                                                        {{ $account->name }}
                                                    </option>
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
                                        <label>Starting Date:</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" name="from_date" class="form-control datetimepicker-input"
                                                data-target="#reservationdate" value="{{ $from_date }}"
                                                required="required" />
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Closing Date:</label>
                                        <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                            <input type="text" name="to_date" class="form-control datetimepicker-input"
                                                value="{{ $to_date }}" data-target="#reservationdate1" />
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
                @if ($account)
                    <p>Cash book for <b> {{ $account->name }} </b> from the start of <b>{{ $from_date }} </b> to the
                        close of
                        <b> {{ $to_date }} </b>
                    </p>
                @endif
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table id="example2" class="table table-hover table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nature</th>
                            <th>Date</th>
                            <th>Reporting A/C</th>
                            <th>Particulars</th>
                            <th>Affected A/Cs</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $balance = 0;
                            $total_credits = 0;
                            $total_debits = 0;
                        @endphp
                        @forelse ($transactions as $transaction)
                            @php
                                $surname = $transaction->client ? $transaction->client->surname : '';
                                $given_name = $transaction->client ? $transaction->client->given_name : '';
                                $client_number = $transaction->client ? $transaction->client->client_number : '';

                            @endphp
                            <tr @if ($transaction->is_reversed or $transaction->reverses) class="text-muted" @endif>
                                <td class="text-nowrap">{{ $transaction->id }}</td>
                                <td class="text-nowrap">{{ $transaction->type }} <br>
                                    @if ($transaction->is_reversed)
                                    Reversed by #{{ $transaction->reversed_by }} : {{ $transaction->reversal_reason }}
                                @elseif($transaction->reverses)
                                    Reverses #{{ $transaction->reverses }}
                                @else
                                   
                                @endif
                                </td>
                                <td class="text-nowrap">{{ $transaction->date }}</td>
                                <td class="text-nowrap">{{ $account->name }} </td>
                                <td class="text-nowrap">
                                    {{ $transaction->particular . ' ' . $surname . ' ' . $given_name . ' ' . $client_number . ' ' . $transaction->description }}
                                </td>
                                <td>
                                    @foreach ($details[$transaction->id] as $detail)
                                        @if ($detail->account_id == $account->id)
                                            @continue
                                        @endif
                                        <div class="text-nowrap">
                                            {{ $detail->account->name . ' (' . number_format($detail->amount, 0, '.', ',') . ')' }}
                                        </div>
                                    @endforeach
                                </td>

                                @foreach ($details[$transaction->id] as $detail)
                                    @if ($detail->account_id == $account->id && $detail->debit_credit == 'Debit')
                                        <td class="text-primary">{{ number_format($transaction->amount, 0, '.', ',') }}</td>
                                        <td>0</td>
                                        @php
                                            $balance += $transaction->amount;
                                            $total_debits += $transaction->amount;
                                        @endphp
                                    @endif
                                    @if ($detail->account_id == $account->id && $detail->debit_credit == 'Credit')
                                        <td>0</td>
                                        <td class="text-danger" >{{ number_format($transaction->amount, 0, '.', ',') }}</td>
                                        @php
                                            $balance -= $transaction->amount;
                                            $total_credits += $transaction->amount;
                                        @endphp
                                    @endif
                                @endforeach
                                </td>
                                <td class="text-primary"><b>{{ number_format($balance, 0, '.', ',') }}</b></td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9">No transactions Found.</td>
                            </tr>
                        @endforelse
                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6"><b>Totals </b></td>
                            <td class="text-primary" ><b>{{ number_format($total_debits, 0, '.', ',') }}</b></td>
                            <td class="text-danger" ><b>{{ number_format($total_credits, 0, '.', ',') }}</b></td>
                            <td class="text-primary" ><b>{{ number_format($balance, 0, '.', ',') }}</b></td>
                        </tr>
                    </tfoot>

                </table>

            </div> <!-- /.card-body -->
        </div>
    </div>


@endsection
