@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Loans | ' . $organization->org_name)
@section('page_title', 'Loans')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
    </ol>
@endsection

@section('main_content')

    <div class="col-12">
        <div class="form-group">
            <div role="group" class="btn-group">
                <ul class="nav">
                    <li>
                        <a href="{{ route('loans.index') }}" class="btn btn-default btn-success">Pending Appraisal</a>
                    </li>
                    <li>
                        <a href="{{ route('loans.pending-approval') }}" class="btn btn-default btn-success">Pending
                            Approval</a>
                    </li>
                    <li>
                        <a href="{{ route('loans.approved') }}" class="btn  btn-default btn-success">Approved</a>
                    </li>
                    <li>
                        <a href="{{ route('loans.disbursed') }}" class="btn btn-default btn-success">Disbursed</a>
                    </li>
                    <li>
                        <a href="{{ route('loans.cleared') }}" class="btn btn-default btn-success">Cleared</a>
                    </li>
                    <li>
                        <a href="{{ route('loans.deferred') }}"
                            class="btn btn-success">Deferred</a>
                    </li>
                    <li>
                        <a href="{{ route('loans.waived') }}"
                            class="btn btn-default btn-success">Waived-off</a>
                    </li>                    
                </ul>
            </div>
        </div>
    </div>
    <!-- filter -->
    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3">
            <!-- /.card-header -->
            <div class="card-body pb-0">
                <form action="{{ route('loans.index') }}" method="get">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="search" name="search" class="form-control form-control-md"
                                                placeholder="Search Client by Name or Number"
                                                value="{{ request('search') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="submit" class="btn bg-success" value="Search">Search</button>
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
        <div class="card card-solid card-success card-outline elevation-3">
            <div class="card-body">
                @forelse ($loans as $loan)
                    <div class="row">
                        <div class="col-7">
                            <a class="text-success" href="{{ route('loans.loan', ['loan' => $loan]) }}">
                                <h5><b>{{ $loan->client->surname . ' ' . $loan->client->given_name }}</b> |
                                    {{ $loan->client->client_number }}</h5>
                            </a>
                            <P> {{ $loan->id }} | <b>{{ $loan->loanProduct->name }}</b> | {{ $loan->purpose }}
                            </P>
                        </div>
                        <div class="col-5">
                            <h5 class=""><b><span class="text-success">{{ $organization->currency_code }}</span>
                                    {{ number_format($loan->application_amount, 0, '.', ',') }}
                                </b> <span class="btn btn-success btn-xs p-0 ml-2">{{ $loan->branch->branch_name }}</span></h5>
                            <p class=""> Applied {{ $loan->application_date }}</p>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                @empty
                    <p><b>No Loans found.</b></p>
                @endforelse
            </div> <!-- /.card-body -->
        </div>

        <div class="pb-3">
            {{ $loans->links() }}
        </div>
    </div>



@endsection
