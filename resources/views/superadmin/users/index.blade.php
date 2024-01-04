@extends('layouts.app1')

@section('org_name', 'Super Admin')
@section('title', 'Users')
@section('page_title', 'Users')

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
                        <a href="{{ route('super-admin.branches.index') }}" class="btn btn-default btn-success">Branches</a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.users.index') }}" class=" btn btn-success">Users</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h4 class="card-title text-success"><b>Users</b></h4>
                <div class="card-tools">
                    <a href="{{ route('super-admin.users.create') }}" class="btn float-right bg-success"><i
                            class="fa fa-plus"></i> New
                        User
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table id="example2" class="table table-hover table-head-fixed table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Organization</th>                           
                            <th>Branch</th>                            
                            <th>Name</th>
                            <th>Username</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @unless ($users->isEmpty())
                            @foreach ($users as $user)
                                <tr class="text-nowrap">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->organization->org_name }}</td>
                                    <td>{{ $user->branch->branch_name }}</td> <!-- Access the organization name via the relationship -->
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->user_name }}</td>
                                    <td>{{ $user->user_phone }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><a href="{{ route('super-admin.users.edit', ['user' => $user]) }}"
                                            class="btn btn-xs btn-info">Edit</a></td>
                                    <!-- Add other table cells here -->
                                </tr>
                            @endforeach
                        @else
                            <tr class="border-gray-300">
                                <td colspan="10">
                                    <p class="text-center">No Users Found</p>
                                </td>
                            </tr>
                        @endunless
                    </tbody>
                </table>
            </div> <!-- /.card-body -->
        </div>
    </div>

@endsection
