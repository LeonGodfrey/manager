@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Bank Accounts | ' . $organization->org_name)
@section('page_title', 'Bank Accounts')

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
                        <a href="{{ route('cash-accounts.index') }}" class="btn btn-default btn-success">Cash Accounts</a>
                    </li>
                    <li>
                        <a href="{{ route('cash-accounts-bank') }}" class="btn btn-success">Bank Accounts</a>
                    </li>
                    <li>
                        <a href="{{ route('cash-accounts-vault') }}" class="btn btn-default btn-success">Vault Accounts</a>
                    </li>
                    <li>
                        <a href="{{ route('cash-accounts-mobile-money') }}" class="btn btn-default btn-success">Mobile Money
                            Accounts</a>
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
                <form action="{{ route('cash-accounts.index') }}" method="get">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="search" name="search" class="form-control form-control-md"
                                                placeholder="Enter Account Name" value="{{ request('search') }}">
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
                @forelse ($accounts as $account)
                    <div class="row">
                        <div class="col-6">
                            <a class="text-success" href="{{ route('cash-accounts.account', ['account' => $account]) }}">
                                <h5><b>{{ $account->name }}</b></h5>
                            </a>
                        </div>
                        <div class="col-6">
                            <h5 class=""> Account Balance: <b><span class="text-success">{{ $organization->currency_code }}</span>
                                <span class="" >{{ number_format($account->balance, 0, '.', ',') }}</span>
                            </b></h5>
                           
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                @empty
                    <p><b>No Accounts found.</b></p>
                @endforelse
            </div> <!-- /.card-body -->
        </div>

        <div class="pb-3">
            {{ $accounts->links() }}
        </div>
    </div>



@endsection
