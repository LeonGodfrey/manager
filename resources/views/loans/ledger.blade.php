@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Loan Ledger | ' . $organization->org_name)
@section('page_title', 'Loan Legder')

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
                        <a href="{{ route('loans.ledger', ['loan' => $loan]) }}" class="btn btn-success">Loan Ledger</a>
                    </li>
                    @if($loan->status !==  'cleared')
                    <li>
                        <a href="{{ route('loans.payment-create', ['loan' => $loan]) }}" class="btn btn-default btn-success">Make
                            Payment</a>
                    </li>
                    @endif    
                </ul>
            </div>
        </div>
    </div>


    {{-- payments --}}
    <div class="col-sm-12">

        <div class="invoice p-3 mb-3 mb-5">

            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-2 invoice-col">
                    @if ($organization->org_logo)
                        <img src="{{ asset('storage/' . $organization->org_logo) }}" alt="Logo"
                            class="img-fluid mx-auto " style="width: 80%;">
                    @endif
                </div>
                <div class="col-sm-5 invoice-col">
                    {{-- company  --}}

                    <address>
                        <strong>{{ $organization->org_name }}</strong><br>
                        <strong>{{ $user->branch->branch_name }}</strong><br>
                        Phone: {{ $user->branch->branch_phone }}<br>
                        Email: {{ $user->branch->branch_email }}<br>
                        {{ $user->branch->branch_street_address }}
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-5 invoice-col">
                    Client
                    <address>
                        <strong>{{ $client->surname . ' ' . $client->given_name }}</strong><br>
                        Client Number: {{ $client->client_number }}<br>
                        Phone: {{ $client->phone }}<br>
                        Address: {{ $client->street_address }}<br>

                    </address>
                </div>

            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <p>
                        Loan Product: <b> {{ $loan->loanProduct->name }} </b><br>
                        Loan Amount: 
                        <b> <span class="text-success">{{ $organization->currency_code }}</span> {{ number_format($loan->approved_amount, 0, '.', ',') }}</b> <br>
                        Disbursed on: <b> {{ $loan->disbursement_date }}</b><br>
                        Repayment Period: <b> {{ $loan->approved_period }} Months</b><br>
                        </p>
                    <h3>Loan Ledger</h3>
                    <hr>
                </div>
            </div>

            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table table id="example2" class="table table-hover table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                            <tr>
                                <th>Date</th>
                                <th>Commets</th>
                                <th>Principal</th>
                                <th>Interest</th>
                                <th>Total Due</th>
                                <th>Total Paid</th>
                                <th>Balace Principal</th>
                                <th>Balance Interest</th>
                                <th>Total</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $interest = $loan->approved_amount * ($loan->approved_interest_rate / 100)* $loan->approved_period;
                            $principal = $loan->approved_amount;
                            $total = $loan->approved_amount + $interest;
                            $total1 = $total;
                            @endphp
                            <tr>
                                <td>{{$loan->disbursement_date}}</td>
                                <td>Disbursement</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ number_format($principal, 0, '.', ',') }}</td>
                                <td>{{ number_format($interest, 0, '.', ',') }}</td>
                                <td> {{ number_format($total, 0, '.', ',') }}</td>
                            </tr>
                            @php
                                $amount_due = 0;
                                $counter = 1;
                                $total_paid = 0;
                               
                            @endphp
                            @forelse ($installments as $item)
                            @php
                                
                                if($counter == 1){
                                if($item->type == 'Schedule'){
                                $amount_due += ($item['principal'] + $item['interest']);
                                }else {
                                    $amount_due -= ($item['principal'] + $item['interest']);
                                    $total_paid += ($item['principal'] + $item['interest']);
                                }
                            }
                                @endphp
                                <tr class="@if($item->type == 'Payment')text-success @endif" >
                                    @if($item->type == 'Payment') 
                                    @php
                                    $principal -= $item['principal'];
                                    $interest -= $item['interest'];
                                    $total1 = $total - $total_paid;
                                    @endphp
                                    @endif
                                    <td>{{ $item['date'] }}</td>
                                    <td>@if($item->type == 'Schedule') Installment @else {{$item->type}}  @endif</td>
                                    <td> {{ number_format($item['principal'], 0, '.', ',') }}</td>
                                    <td>{{ number_format($item['interest'], 0, '.', ',') }}</td>                                    
                                    <td>{{ number_format($amount_due, 0, '.', ',') }}</td>
                                    <td>@if($item->type == 'Payment') {{ number_format($total_paid, 0, '.', ',') }} @else  @endif</td>
                                    <td>@if($item->type == 'Payment') {{ number_format($principal, 0, '.', ',') }} @else  @endif</td>
                                    <td>@if($item->type == 'Payment') {{ number_format($interest, 0, '.', ',') }} @else  @endif</td>
                                    <td>{{ number_format($total1, 0, '.', ',') }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">No Transactions found.</td>
                                </tr>
                            @endforelse
                            
                        </tbody>
                    </table>
                    <hr>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                
                <div class="col-6">
                    <p class=""><b>Issued On: </b>{{ date('d-m-Y') }}</p>
                    <p class=""><b> by:</b> .......................</p>
                    <p class=""> <b> Sign:</b> .....................</p>                   
                </div>
                <!-- /.col -->
                <div class="col-6">
                   
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-12 text-right">
                    {{-- <a href="{{ route('loans.schedule-print', ['loan' => $loan]) }}" rel="noopener" target="_blank" class="btn btn-success"><i
                            class="fas fa-print "></i> Print</a> --}}

                </div>
            </div>
        </div>


    </div>




@endsection
