@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Loan Schedule | ' . $organization->org_name)
@section('page_title', 'Loan Schedule')

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
                        Phone: {{ $client->phone }}<br><br>


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
                        Repayment Frequency: <b> {{ $loan->loanProduct->payment_frequency }} </b><br>
                        Total Amount to be paid:
                        <b> <span class="text-success">{{ $organization->currency_code }}</span> {{ number_format($totalamount, 0, '.', ',') }}</b>
                    </p>
                    <h3>Loan Payment Schedule</h3>
                    <hr>
                </div>
            </div>

            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped table-bordered table-sm">
                        <thead>
                            <tr>
                            <tr>
                                <th>Due Date</th>
                                <th>Principal</th>
                                <th>Interest</th>
                                <th>Installment</th>
                                <th>Principal Balance</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> - </td>
                                <td> - </td>
                                <td> - </td>
                                <td> - </td>
                                <td> {{ number_format($loan->approved_amount, 0, '.', ',') }}</td>
                            </tr>
                            @php
                                $totalPrincipal = 0;
                                $totalInterest = 0;
                                $totalInstallment = 0;
                            @endphp
                            @forelse ($schedule as $item)
                            @php
                                $totalPrincipal += $item['principal'];
                                $totalInterest += $item['interest'];
                                $totalInstallment += $item['installment'];
                                @endphp
                                <tr>
                                    <td>{{ $item['due_date'] }}</td>
                                    <td> {{ number_format($item['principal'], 0, '.', ',') }}</td>
                                    <td>{{ number_format($item['interest'], 0, '.', ',') }}</td>
                                    <td>{{ number_format($item['installment'], 0, '.', ',') }}</td>
                                    <td> {{ number_format($item['balance'], 0, '.', ',') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">No Transactions found.</td>
                                </tr>
                            @endforelse
                            <tr>
                                <th>Total</th>
                                <td><b> <span class="text-success">{{ $organization->currency_code }}</span> {{ number_format($totalPrincipal, 0, '.', ',') }} </b></td>
                                <td><b> <span class="text-success">{{ $organization->currency_code }}</span> {{ number_format($totalInterest, 0, '.', ',') }} </b> </td>
                                <td><b> <span class="text-success">{{ $organization->currency_code }}</span> {{ number_format($totalInstallment, 0, '.', ',') }} </b></td>
                                <td>-</td>
                            </tr>
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
           
        </div>


    </div>



    <script>
        window.addEventListener("load", window.print());
      </script>
@endsection
