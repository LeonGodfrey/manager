@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Import Clients | ' . $organization->org_name)
@section('page_title', 'Import Clients')


@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
        <li class="breadcrumb-item btn btn-outline-success btn-sm "><a
                href={{ route('settings.data-imports.index') }}>Imports</a></li>
    </ol>
@endsection

@section('main_content')

    <div class="col-sm-12">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h4>Follow this guide</h4>
            </div>
            <div class="card-body mr-5 ml-5">

                <ul>
                    <li>Click this link to Download the data template <a
                            href="{{ route('settings.data-imports.clients.template') }}">Download Client Template</a>
                    </li>
                    <hr>
                    <li>Upload csv files only</li>
                    <li>All dates must be in the form <span class="text-monospace bg-info"> YYYY-MM-DD </span> ie
                        2024-01-26.</li>
                    <li> <span class="text-monospace bg-info"> client_number </span> must be unique, double check to
                        avoid repetitions.</li>
                    <li>Column <span class="text-monospace bg-info"> branch </span> specifies the branch to which the
                        client belongs.</li>
                    <br>
                    <li>Use the table below to specify a branch to which each client belongs.</li>
                </ul>

                <div class="mr-5">
                    <table class="table table-striped table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th>Id</th>
                            </tr>
                        </thead>
                        <tbody>
                            @unless ($branches->isEmpty())
                                @foreach ($branches as $branch)
                                    <tr class="text-nowrap">
                                        <td>
                                            <span>{{ $branch->branch_name }}</span>
                                            <button class="btn btn-sm btn-info copy-btn"
                                                data-clipboard-text="{{ $branch->branch_name }}">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <span>{{ $branch->id }}</span>
                                            <button class="btn btn-sm btn-info copy-btn"
                                                data-clipboard-text="{{ $branch->id }}">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            @endunless
                        </tbody>
                    </table>
                </div>

            </div>
        </div> <!-- /.card-body -->
    </div>


    <div class="col-sm-12">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h4>Import Clients</h4>
            </div>
            <form method="post" enctype="multipart/form-data" action="{{ route('settings.data-imports.clients.store') }}">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="2" placeholder="Enter description"
                            required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="upload_file">Upload a CSV file</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" accept="text/csv" class="custom-file-input" id="upload_file"
                                    name="upload_file" value="">
                                @error('upload_file')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                                <label class="custom-file-label" for="upload_file">Choose a file</label>
                            </div>

                        </div>
                    </div>


                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="card-tools text-right">
                        <button name="submit" type="submit" class="btn btn-success">Import individuals</button>
                    </div>
                </div>
        </div>
        </form>
    </div>
    </div>

@endsection
