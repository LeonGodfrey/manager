@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $organization->org_name)
@section('page_title', 'Branches')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
        <a href="{{ route('settings.branches.create') }}" class="btn float-right bg-success"><i
            class="fa fa-plus"></i> New
        Branch
    </a>
    </ol>
@endsection

@section('main_content')

       <div class="col-sm-12">
        <div class="card card-success card-outline">
           
            <div class="card-body table-responsive p-0">
                <table id="example2" class="table table-hover table-head-fixed table-sm table-striped ">
                    <thead>
                        <tr>                        
                            <th>Branch Name</th>
                            <th>Branch Prefix</th>
                            <th>Email Address</th>
                            <th>Branch Contact</th>
                            <th>Street Address</th>
                            <th>City</th>
                            <th>District</th>
                        </tr>
                    </thead>
                    <tbody>
                        @unless ($branches->isEmpty())
                            @foreach ($branches as $branch)
                                <tr class="text-nowrap">
                                    <td><a href="{{ route('settings.branches.edit', ['branch' => $branch]) }}">{{ $branch->branch_name }}</a></td>
                                    <td>{{ $branch->branch_prefix }}</td>
                                    <td>{{ $branch->branch_email }}</td>
                                    <td>{{ $branch->branch_phone }}</td>
                                    <td>{{ $branch->branch_street_address }}</td>
                                    <td>{{ $branch->branch_city }}</td>
                                    <td>{{ $branch->branch_district }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="border-gray-300">
                                <td colspan="10">
                                    <p class="text-center">No Branches Found</p>
                                </td>
                            </tr>
                        @endunless
                    </tbody>
                </table>
            </div> <!-- /.card-body -->
        </div>
    </div>

@endsection
