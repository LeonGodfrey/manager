@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Data Imports | ' . $organization->org_name)
@section('page_title', 'Data Imports')

@section('bread_crumb')

@endsection

@section('main_content')

    <div class="col-sm-12">
        <div class="card card-success card-outline">
            <div class="card-header">
                <p>These resources should be used during onboarding only.</p>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-head-fixed table-sm ">
                    <thead>
                        <tr>
                            <th>Resource</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> <a href="{{route('settings.data-imports.clients.create')}}">  Clients </a> </td>
                        </tr>
                        <tr>
                            <td> <a href="{{route('settings.data-imports.savings-accounts.create')}}">  Savings Accounts </a> </td>
                        </tr>
                        <tr>
                            <td>Loans</td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- /.card-body -->
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h4>Imports</h4>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-head-fixed table-sm ">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Import File</th>
                            <th>Status</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Error file</th>                   

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($imports as $import)
                        <tr>
                                <td>{{ $import->type }}</td>
                                <td>{{ $import->description }}</td>
                                <td><a href="{{ Storage::url($import->import_file) }}" download>import file</a></td>                                <td>{{ $import->status }}</td>
                                <td>{{ $import->message }}</td>
                                <td>{{ $import->created_at }}</td>
                                <td>
                                    @if($import->error_file)
                                        <a href="{{ Storage::url($import->error_file) }}" download>errors file</a>
                                    @else
                                        No errors
                                    @endif
                                </td>                        
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">No imports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> <!-- /.card-body -->
        </div>
    </div>

@endsection
