@extends('layouts.app1')

@section('org_name', 'Super Admin')
@section('title', 'Organizations')
@section('page_title', 'Organizations')

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
                        <a href="{{ route('super-admin.index') }}" class="btn btn-success">Organizations</a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.branches.index') }}" class="btn btn-default btn-success">Branches</a>
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
                <h4 class="card-title text-success"><b>Organizations</b></h4>
                <div class="card-tools">
                    <a href="{{ route('super-admin.organizations.create') }}" class="btn float-right bg-success"><i
                            class="fa fa-plus"></i> New
                        Organization
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table id="example2" class="table table-hover table-head-fixed table-sm table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>Currency Code</th>
                            <th>Incorporation Date</th>
                            <th>Reg No</th>
                            <th>Manager Name</th>
                            <th>Manager Contact</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @unless ($organizations->isEmpty())
                            @foreach ($organizations as $organization)
                                <tr class="text-nowrap">
                                    <td>{{ $organization->id }}</td>
                                    <td>{{ $organization->org_name }}</td>
                                    <td>{{ $organization->org_country }}</td>
                                    <td>{{ $organization->currency_code }}</td>
                                    <td>{{ $organization->incorporation_date }}</td>
                                    <td>{{ $organization->business_reg_no }}</td>
                                    <td>{{ $organization->manager_name }}</td>
                                    <td>{{ $organization->manager_contact }}</td>
                                    <td>{{ $organization->created_at }}</td>
                                    <td><a href="{{ route('super-admin.organizations.edit', ['organization' => $organization]) }}"
                                            class="btn btn-xs btn-info">Edit</a></td>
                                    <!-- Add other table cells here -->
                                </tr>
                            @endforeach
                        @else
                            <tr class="border-gray-300">
                                <td colspan="10">
                                    <p class="text-center">No Organizations Found</p>
                                </td>
                            </tr>
                        @endunless
                    </tbody>
                </table>
            </div> <!-- /.card-body -->
        </div>
    </div>

@endsection
