@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Deferred Loan | ' . $organization->org_name)
@section('page_title', 'Deferred Loan')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
        <a href={{ route('clients.client', ['client' => $client]) }}>
            <li class="breadcrumb-item btn btn-outline-success btn-sm ">{{ $client->surname . ' ' . $client->given_name }}
            </li>
        </a>
        <a href={{ route('clients.index') }}>
            <li class="breadcrumb-item btn btn-outline-success btn-sm ">Clients</li>
        </a>
    </ol>
@endsection

@section('main_content')

@include('layouts.client-info')
    

    <div class="col-sm-12">
        <div class="card card-outline card-success elevation-3">
            <div class="card-header">
                <h3 class="card-title col-xs-6 font-weight-bold">Loan Application Details</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="loan_product_id">Loan Product *</label>
                    <p class="text-sm m-0">Select the correct Loan Product for this Loan Application.</p>
                    <div class="input-group">
                        <select class="form-control select2" id="loan_product_id" name="loan_product_id" required disabled>
                            <option>--Select Loan Product</option>
                            @foreach ($loan_products as $loan_product)
                                <option value="{{ $loan_product->id }}" @if ($loan->loan_product_id === $loan_product->id) selected @endif>
                                    {{ $loan_product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('loan_product_id')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="application_amount">Loan Application Amount *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{ $organization->currency_code }}</span>
                        </div>
                        <input type="text" class="form-control thousand-separator" id="application_amount"
                            name="application_amount" value="{{ $loan->application_amount }}" required disabled>
                        @error('application_amount')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="application_period">Repayment Period in Months *</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="application_period" name="application_period"
                                    value="{{ $loan->application_period }}" required disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text">Months</span>
                                </div>
                                @error('application_period')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="application_date">Loan Application Date *</label>
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                <div class="input-group-prepend" data-target="#reservationdate"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                                <input id="application_date" name="application_date" type="text"
                                    class="form-control datetimepicker-input" data-target="#reservationdate"
                                    value="{{ $loan->application_date }}" placeholder="YYYY-MM-DD" required disabled>
                            </div>
                            @error('application_date')
                                <div class="text-sm text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="purpose">Purpose of the loan *</label>
                    <textarea id="purpose" name="purpose" class="form-control" rows="2" placeholder="Enter Purpose of the loan"
                        required disabled>{{ $loan->purpose }}</textarea>
                    @error('purpose')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    {{-- appraisal --}}
    <div class="col-sm-12">
        <div class="card card-outline card-success elevation-3">
            <div class="card-header">
                <h3 class="card-title col-xs-6 font-weight-bold">Loan Appraisal Details</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="loan_officer_id"> Credit Officer *</label>
                            <p class="text-sm m-0">Select the correct Loan Credit Officer for this Loan.</p>
                            <div class="input-group">
                                <select class="form-control select2" id="loan_officer_id" name="loan_officer_id" required
                                    disabled>
                                    <option>--Select Credit Officer</option>
                                    @foreach ($users as $nowuser)
                                        <option value="{{ $nowuser->id }}"
                                            @if ($loan->loan_officer_id === $nowuser->id) selected @endif>
                                            {{ $nowuser->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('loan_officer_id')
                                <div class="text-sm text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="appraisal_date">Loan Appraisal Date *</label>
                            <p class="text-sm m-0">Enter Loan Appraisal Date.</p>
                            <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                <div class="input-group-prepend" data-target="#reservationdate1"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                                <input id="appraisal_date" name="appraisal_date" type="text"
                                    class="form-control datetimepicker-input" data-target="#reservationdate1"
                                    value="{{ $loan->appraisal_date }}" placeholder="YYYY-MM-DD" required disabled>
                            </div>
                            @error('appraisal_date')
                                <div class="text-sm text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="appraisal_amount">Appraisal Loan Amount *</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $organization->currency_code }}</span>
                                </div>
                                <input type="text" class="form-control thousand-separator" id="appraisal_amount"
                                    name="appraisal_amount" value="{{ $loan->appraisal_amount }}" required disabled>
                                @error('appraisal_amount')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="appraisal_period">Appraisal Repayment Period *</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="appraisal_period" name="appraisal_period"
                                    value="{{ $loan->appraisal_period }}" required disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text">Months</span>
                                </div>
                                @error('appraisal_period')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="appraisal_comment">Credit Officer's Recommendation *</label>
                    <textarea id="appraisal_comment" name="appraisal_comment" class="form-control" rows="2"
                        placeholder="Credit Officer's Recommendation" required disabled> {{ $loan->appraisal_comment }} </textarea>
                    @error('appraisal_comment')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <hr>
                <p class="text-success"><b>Loan Appraisal Files</b></p>
                <div class="row">
                    <div class="col-12">
                        @if (
                            $loan->file_link1 ||
                                $loan->file_link2 ||
                                $loan->file_link3 ||
                                $loan->file_link4 ||
                                $loan->file_link5 ||
                                $loan->file_link6 ||
                                $loan->file_link7 ||
                                $loan->file_link8)
                            <p class="mb-2">Attached Appraisal and other loan files, click on the thumbnail to view
                                full
                                image.</p>
                            <div class="col-12">
                                @if ($loan->file_link1)
                                    <img src="{{ asset('storage/' . $loan->file_link1) }}" class="product-image p-4"
                                        alt="file">
                                @endif
                            </div>
                            <div class="col-12 product-image-thumbs">
                                @if ($loan->file_link1)
                                    <div class="product-image-thumb active p-1"><img
                                            src="{{ asset('storage/' . $loan->file_link1) }}" alt="file"></div>
                                @endif
                                @if ($loan->file_link2)
                                    <div class="product-image-thumb p-1"><img
                                            src="{{ asset('storage/' . $loan->file_link2) }}" alt="file"></div>
                                @endif
                                @if ($loan->file_link3)
                                    <div class="product-image-thumb p-1"><img
                                            src="{{ asset('storage/' . $loan->file_link3) }}" alt="file"></div>
                                @endif
                                @if ($loan->file_link4)
                                    <div class="product-image-thumb active p-1"><img
                                            src="{{ asset('storage/' . $loan->file_link4) }}" alt="file"></div>
                                @endif
                                @if ($loan->file_link5)
                                    <div class="product-image-thumb p-1"><img
                                            src="{{ asset('storage/' . $loan->file_link5) }}" alt="file"></div>
                                @endif
                                @if ($loan->file_link6)
                                    <div class="product-image-thumb p-1"><img
                                            src="{{ asset('storage/' . $loan->file_link6) }}" alt="file"></div>
                                @endif
                                @if ($loan->file_link7)
                                    <div class="product-image-thumb p-1"><img
                                            src="{{ asset('storage/' . $loan->file_link7) }}" alt="file"></div>
                                @endif
                                @if ($loan->file_link8)
                                    <div class="product-image-thumb p-1"><img
                                            src="{{ asset('storage/' . $loan->file_link8) }}" alt="file"></div>
                                @endif
                            </div>
                        @else
                            <p><b>No files attached.</b></p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- approval --}}
    <div class="col-sm-12">
        <div class="card card-outline card-success elevation-3">
            <div class="card-header">
                <h3 class="card-title col-xs-6 font-weight-bold">Loan Approval Details</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="approval_officer_id"> Approved by *</label>
                            <div class="input-group">
                                <select class="form-control select2" id="approval_officer_id" name="approval_officer_id"
                                    required disabled>
                                    <option>--Select Approval Officer</option>
                                    @foreach ($users as $nowuser)
                                        <option value="{{ $nowuser->id }}"
                                            @if ($loan->approval_officer_id === $nowuser->id) selected @endif>
                                            {{ $nowuser->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('approval_officer_id')
                                <div class="text-sm text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="approved_date">Loan Approval Date *</label>
                            <div class="input-group date" id="reservationdate2" data-target-input="nearest">
                                <div class="input-group-prepend"
                                    data-target="#reservationdate2
                                        data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                                <input id="approved_date" name="approved_date" type="text"
                                    class="form-control datetimepicker-input" data-target="#reservationdate2"
                                    value="{{ $loan->approved_date }}" placeholder="YYYY-MM-DD" required disabled>
                            </div>
                            @error('approved_date')
                                <div class="text-sm text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="approved_amount">Approved Loan Amount *</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $organization->currency_code }}</span>
                                </div>
                                <input type="text" class="form-control thousand-separator" id="approved_amount"
                                    name="approved_amount" value="{{ $loan->approved_amount }}" required disabled>
                                @error('approved_amount')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="approved_period">Approved Repayment Period *</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="approved_period" name="approved_period"
                                    value="{{ $loan->approved_period }}" required disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text">Months</span>
                                </div>
                                @error('approved_period')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="approved_interest_rate">Interest Rate *</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="approved_interest_rate"
                                    name="approved_interest_rate" value="{{ $loan->approved_interest_rate }}" required
                                    disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('approved_interest_rate')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="approved_comment">Approval Comment *</label>
                    <textarea id="approved_comment" name="approved_comment" class="form-control" rows="2"
                        placeholder="Enter Approval Comment" required disabled> {{ $loan->approved_comment }}</textarea>
                    @error('approved_comment')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- defer --}}
    <div class="col-sm-12">
        <div class="card mt-5">
            <!-- /.card-header -->
            <div class="card-body pb-1">
                {{-- form --}}
                <form method="post" action="{{ route('loans.defer', ['loan' => $loan]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12">
                            <p class=" text-danger">This Loan Aplcation was Deferred by:<b>
                                    @foreach ($users as $nowuser)
                                        @if ($loan->approval_officer_id === $nowuser->id)
                                            {{ $nowuser->name }}
                                        @endif
                                    @endforeach
                                </b>
                                on <b>{{ $loan->approved_date }}</b> because:</p>
                            <div class="form-group">
                                <textarea id="defer_comment" name="defer_comment" class="form-control" rows="2"
                                    placeholder="Enter Deferment Comment" required disabled>{{ $loan->defer_comment }}</textarea>
                                @error('defer_comment')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="text" name="status" value="deferred" required hidden>
                        </div>

                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>



    {{-- delete --}}
    <div class="col-sm-12">
        <div class="card mt-5">
            <div class="card-body pb-1">
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-sm text-danger"><b>Delete this Loan</b><br>*Note that this action cannot be
                            undone!</p>
                    </div>
                    <div class="col-md-6">
                        <div class="card-tools text-right">
                            <button name="submit" type="submit" class="btn btn-sm btn-danger text-sm"
                                data-toggle="modal" data-target="#confirmDeleteModal">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
            aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this Loan? <br>
                        Note that all assets associated with this Loan will be deleted!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form action="{{ route('loans.destroy', ['loan' => $loan]) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const unformatNumber = (value) => {
                return parseFloat(value.replace(/[^\d.-]/g,
                    '')); // Remove non-numeric characters except dots and minus signs
            };

            const numberWithCommas = (value) => {
                return parseFloat(value).toLocaleString('en-US');
            };

            document.querySelectorAll('.thousand-separator').forEach(function(input) {
                input.addEventListener('input', function() {
                    let unformattedValue = unformatNumber(this.value);
                    this.value = numberWithCommas(unformattedValue);
                });
            });

            document.querySelector('#loanForm').addEventListener('submit', function() {
                document.querySelectorAll('.thousand-separator').forEach(function(input) {
                    input.value = unformatNumber(input.value);
                });
            });
        });
    </script>
@endsection
