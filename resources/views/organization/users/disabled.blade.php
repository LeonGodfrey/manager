@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', $organization->org_name)
@section('page_title', 'Users')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
        <a href="{{ route('settings.users.create') }}" class="btn float-right bg-success"><i
            class="fa fa-plus"></i> New
        User
    </a>
    </ol>
@endsection

@section('main_content')

<div class="col-12">
    <div class="form-group">
        <div role="group" class="btn-group">
            <ul class="nav">
                <li>
                    <a href="{{ route('settings.users.index') }}" class="btn btn-default btn-success">Active Users</a>
                </li>
                <li>
                    <a href="{{ route('settings.users.disabled') }}" class="btn btn-success">Inactive</a>
                </li>           
            </ul>
        </div>
    </div>
</div>

    <div class="col-sm-12">
        <div class="card card-success card-outline">
            
            <div class="card-body table-responsive p-0">
                <table id="example2" class="table table-hover table-head-fixed table-sm  table-striped">
                    <thead>
                        <tr>                            
                            <th>Name</th>
                            <th>Username</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Branch</th>
                            <th>role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @unless ($users->isEmpty())
                            @foreach ($users as $nowuser)
                                <tr class="text-nowrap text-disabled">                                    
                                    <td><a href="{{ route('settings.users.edit', ['nowuser' => $nowuser]) }}">{{ $nowuser->name }}</a></td>
                                    <td>{{ $nowuser->user_name }}</td>
                                    <td>{{ $nowuser->user_phone }}</td>
                                    <td>{{ $nowuser->email }}</td>
                                    <td>{{ $nowuser->branch->branch_name }}</td> <!-- Access the organization name via the relationship -->
                                    <td>user</td>
                                    <td><form method="POST" action="{{ route('settings.users.enable', ['nowuser' => $nowuser->id]) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-xs btn-primary">Activate</button>
                                    </form></td>
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
