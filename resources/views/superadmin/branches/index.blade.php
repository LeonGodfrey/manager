@extends('layouts.app1')

@section('org_name', 'Super Admin')
@section('title', 'Branches')
@section('page_title', 'Branches')

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
                        <a href="{{ route('super-admin.index') }}"
                            class="btn btn-default btn-success">Organizations</a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.branches.index') }}" class="btn btn-success">Branches</a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.users.index') }}" class="btn btn-default btn-success">Users</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h4 class="card-title text-success"><b>Branches</b></h4>
                <div class="card-tools">
                    <a href="{{ route('super-admin.branches.create') }}" class="btn float-right bg-success"><i
                            class="fa fa-plus"></i> New
                        Branch
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table id="example2" class="table table-hover table-head-fixed table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>                            
                            <th>Branch Name</th>
                            <th>Organization</th>
                            <th>Branch Prefix</th>
                            <th>Email Address</th>
                            <th>Branch Contact</th>
                            <th>Street Address</th>
                            <th>City</th>
                            <th>District</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @unless ($branches->isEmpty())
                            @foreach ($branches as $branch)
                                <tr class="text-nowrap">
                                    <td>{{ $branch->id }}</td>
                                    <td>{{ $branch->branch_name }}</td>
                                    <td>{{ $branch->organization->org_name }}</td> <!-- Access the organization name via the relationship -->
                                    <td>{{ $branch->branch_prefix }}</td>
                                    <td>{{ $branch->branch_email }}</td>
                                    <td>{{ $branch->branch_phone }}</td>
                                    <td>{{ $branch->branch_street_address }}</td>
                                    <td>{{ $branch->branch_city }}</td>
                                    <td>{{ $branch->branch_district }}</td>
                                    <td><a href="{{ route('super-admin.branches.edit', ['branch' => $branch]) }}"
                                            class="btn btn-xs btn-info">Edit</a></td>
                                    <!-- Add other table cells here -->
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
