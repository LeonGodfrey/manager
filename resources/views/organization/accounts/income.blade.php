@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $organization->org_name)
@section('page_title', 'Accounts')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
        <a href="{{ route('settings.accounts.create') }}" class="btn float-right bg-success"><i
            class="fa fa-plus"></i> New
        Accounts
    </a>
    </ol>
@endsection

@section('main_content')

<div class="col-12">
    <div class="form-group">
        <div role="group" class="btn-group">
            <ul class="nav">
                <li>
                    <a href="{{ route('settings.accounts.index') }}" class="btn btn-default btn-success">Assets</a>
                </li>
                <li>
                    <a href="{{ route('settings.accounts.equity') }}" class="btn btn-default btn-success">Equity</a>
                </li>
                <li>
                    <a href="{{ route('settings.accounts.expense') }}" class="btn btn-default btn-success">Expenses</a>
                </li> 
                <li>
                    <a href="{{ route('settings.accounts.income') }}" class="btn btn-success">Income</a>
                </li>
                <li>
                    <a href="{{ route('settings.accounts.liability') }}" class="btn btn-default btn-success">Liabilities</a>
                </li>               
            </ul>
        </div>
    </div>
</div>
    
<div class="col-sm-12">
        <div class="card card-success card-outline">
           
            <div class="card-body table-responsive">
                <table id="example2" class="table table-hover table-head-fixed table-sm table-striped ">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Account Name</th>
                            <th>Type</th>
                            <th>Sub-type</th>
                            <th>A/C number</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr class="text-nowrap">
                                <td>{{ $account->id }}</td>
                                <td><a
                                        href="{{ route('settings.accounts.edit', ['account' => $account]) }}">{{ $account->name }}</a>
                                </td>
                                <td>{{ $account->type }}</td>
                                <td>{{ $account->subtype }}</td>
                                <td>{{ $account->number }}</td>
                                <td>{{ number_format($account->balance, 0, '.', ',') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No accounts found.</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div> <!-- /.card-body -->
        </div>
    </div>

@endsection
