@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Import Loans | ' . $organization->org_name)
@section('page_title', 'Import Loans')


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
                            href="{{ route('settings.data-imports.loans.template') }}">Download Loans
                            Template</a>
                    </li>
                    <hr>
                    <h4>Note:</h4>
                    <li><b>The maximum number of rows accepted per file is 5,000</b></li>
                    <li><b>Do not delete the first row of headers</b></li>
                    <li><b>Imports cannot be undone</b></li>
                    <hr>
                    <li>Upload csv files only</li>
                    <li>All dates must be in the form <span class="text-monospace"> <b>YYYY-MM-DD </b></span> ie
                        <b> 2024-01-26</b>.
                    </li>
                    <li> <span class="text-monospace"> <b>client_number</b> </span> must be unique, double check to
                        avoid repetitions.</li>
                    <li> <span class="text-monospace"> <b>last_payment_date</b> </span>is the date when the last installment was paid by the Client.</li>
                    <br>
                    <li> Use the table below to specify a <span class="text-monospace"> <b>loan_product_id </b> </span>for
                        each Loan.</li>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Id</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @unless ($loan_products->isEmpty())
                                        @foreach ($loan_products as $loan_product)
                                            <tr class="text-nowrap">
                                                <td>
                                                    <span>{{ $loan_product->name }}</span>
                                                    <button class="btn btn-sm btn-info copy-btn"
                                                        data-clipboard-text="{{ $loan_product->name }}">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <span>{{ $loan_product->id }}</span>
                                                    <button class="btn btn-sm btn-info copy-btn"
                                                        data-clipboard-text="{{ $loan_product->id }}">
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
                        <div class="col-md-6">
                        </div>
                    </div>


                    <br>
                    <li>Column <span class="text-monospace"> <b>branch</b> </span> specifies the branch to which the
                        loan belongs.</li>
                    <li>Use the table below to specify a <b>branch</b> to which each loan belongs.</li>
                    <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                        </div>
                    </div>
                </ul>

                

            </div>
        </div> <!-- /.card-body -->
    </div>


    <div class="col-sm-12">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h4>Import Savings Accounts</h4>
            </div>
            <form method="post" enctype="multipart/form-data" action="{{ route('settings.data-imports.savings-accounts.store') }}">
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
                        <button name="submit" type="submit" class="btn btn-success">Import Savings Accounts</button>
                    </div>
                </div>
        </div>
        </form>
    </div>
    </div>

@endsection
