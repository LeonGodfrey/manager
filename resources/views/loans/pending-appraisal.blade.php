@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Loan Appraisal | '.$organization->org_name)
@section('page_title', 'Loan Appraisal')

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
                    <h3 class="card-title col-xs-6">Loan Application Details</h3>
                </div>
                <div class="card-body ">
                    {{-- form --}}
    <form id="loanForm" method="post" action="{{ route('loans.update', ['loan' => $loan]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
                    <div class="form-group">
                        <label for="loan_product_id">Loan Product *</label>
                        <p class="text-sm m-0">Select the correct Loan Product for this Loan Application.</p>
                        <div class="input-group">
                            <select class="form-control select2" id="loan_product_id" name="loan_product_id" required>
                                <option>--Select Loan Product</option>
                                @foreach ($loan_products as $loan_product)
                                    <option value="{{ $loan_product->id }}"
                                        @if ($loan->loan_product_id === $loan_product->id) selected @endif>
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
                            <input type="text" autocomplete="off" class="form-control thousand-separator" id="application_amount" name="application_amount"
                                value="{{ $loan->application_amount }}" required>
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
                                    <input type="number" autocomplete="off" class="form-control" id="application_period"
                                        name="application_period" value="{{ $loan->application_period }}" required>
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
                                        value="{{ $loan->application_date }}" placeholder="YYYY-MM-DD" required>
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
                            required>{{ $loan->purpose }}</textarea>
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
                    <h3 class="card-title col-xs-6">Loan Appraisal Details</h3>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="loan_officer_id"> Credit Officer *</label>
                                <p class="text-sm m-0">Select the correct Loan Credit Officer for this Loan.</p>
                                <div class="input-group">
                                    <select class="form-control select2" id="loan_officer_id" name="loan_officer_id"
                                        required>
                                        <option value="">--Select Credit Officer</option>
                                        @foreach ($users as $nowuser)
                                            <option value="{{ $nowuser->id }}">
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
                                        value="{{ old('appraisal_date') }}" placeholder="YYYY-MM-DD" required>
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
                                    <input type="text" autocomplete="off" min="0" class="form-control thousand-separator" id="appraisal_amount"
                                        name="appraisal_amount" value="{{ old('appraisal_amount') }}" required>
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
                                    <input type="number" autocomplete="off" min="0" class="form-control" id="appraisal_period"
                                        name="appraisal_period" value="{{ old('appraisal_period') }}" required>
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
                            placeholder="Credit Officer's Recommendation" required></textarea>
                        @error('appraisal_comment')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>
                    <p class="text-success">Loan Appraisal Files (Take and Upload Photos Only! Size should not exceed 2 MB)
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="file_link1">Appraisal Form</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="file_link1"
                                            name="file_link1" value="">
                                        @error('file_link1')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="file_link1">Choose a file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <button type="button" class="btn btn-sm btn-danger p-0"
                                                onclick="clearFileInput1()">Cancel</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <img id="filePreview1" class="mt-2" src="" alt="file"
                                        style="max-width: 100px; display: none;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="file_link2">File 2</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="file_link2"
                                            name="file_link2" value="">
                                        @error('file_link2')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="file_link2">Choose a file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <button type="button" class="btn btn-sm btn-danger p-0"
                                                onclick="clearFileInput2()">Cancel</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <img id="filePreview2" class="mt-2" src="" alt="file"
                                        style="max-width: 100px; display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="file_link3">File 3</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="file_link3"
                                            name="file_link3" value="">
                                        @error('file_link3')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="file_link3">Choose a file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <button type="button" class="btn btn-sm btn-danger p-0"
                                                onclick="clearFileInput3()">Cancel</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <img id="filePreview3" class="mt-2" src="" alt="file"
                                        style="max-width: 100px; display: none;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="file_link4">File 4</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="file_link4"
                                            name="file_link4" value="">
                                        @error('file_link4')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="file_link4">Choose a file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <button type="button" class="btn btn-sm btn-danger p-0"
                                                onclick="clearFileInput4()">Cancel</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <img id="filePreview4" class="mt-2" src="" alt="file"
                                        style="max-width: 100px; display: none;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="file_link5">File 5</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="file_link5"
                                            name="file_link5" value="">
                                        @error('file_link5')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="file_link5">Choose a file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <button type="button" class="btn btn-sm btn-danger p-0"
                                                onclick="clearFileInput5()">Cancel</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <img id="filePreview5" class="mt-2" src="" alt="file"
                                        style="max-width: 100px; display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="file_link6">File 6</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="file_link6"
                                            name="file_link6" value="">
                                        @error('file_link6')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="file_link6">Choose a file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <button type="button" class="btn btn-sm btn-danger p-0"
                                                onclick="clearFileInput6()">Cancel</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <img id="filePreview6" class="mt-2" src="" alt="file"
                                        style="max-width: 100px; display: none;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="file_link7">File 7</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="file_link7"
                                            name="file_link7" value="">
                                        @error('file_link7')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="file_link7">Choose a file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <button type="button" class="btn btn-sm btn-danger p-0"
                                                onclick="clearFileInput7()">Cancel</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <img id="filePreview7" class="mt-2" src="" alt="file"
                                        style="max-width: 100px; display: none;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="file_link8">File 8</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="file_link8"
                                            name="file_link8" value="">
                                        @error('file_link8')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="file_link8">Choose a file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <button type="button" class="btn btn-sm btn-danger p-0"
                                                onclick="clearFileInput8()">Cancel</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <img id="filePreview8" class="mt-2" src="" alt="file"
                                        style="max-width: 100px; display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="text" name="status" value="pending_approval" required hidden>

                <!-- /.card-body -->
                <div class="card-footer ">
                    <div class="card-tools text-right">
                        <button name="submit" type="submit" class="btn btn-success">Submit For Approval</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    

    {{-- delete --}}    
    <div class="col-sm-12">        
        <div class="card mt-5">
            <div class="card-body ">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-sm text-danger"><b>Delete this Loan</b><br>*Note that this action cannot be
                                undone!</p>
                        </div>
                        <div class="col-md-6">
                            <div class="card-tools text-right">
                                <button name="submit" type="submit"
                                    class="btn btn-sm btn-danger text-sm" data-toggle="modal" data-target="#confirmDeleteModal">Delete</button>
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
                return parseFloat(value.replace(/[^\d.-]/g, '')); // Remove non-numeric characters except dots and minus signs
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

        // JavaScript to display image preview when a file is selected profile
        document.getElementById('file_link1').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('filePreview1');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file id
        function clearFileInput1() {
            document.getElementById('file_link1').value = '';
            document.getElementById('filePreview1').style.display = 'none';
        }

        // JavaScript to display image preview when a file is selected profile
        document.getElementById('file_link2').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('filePreview2');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file id
        function clearFileInput2() {
            document.getElementById('file_link2').value = '';
            document.getElementById('filePreview2').style.display = 'none';
        }

        // JavaScript to display image preview when a file is selected profile
        document.getElementById('file_link3').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('filePreview3');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file id
        function clearFileInput3() {
            document.getElementById('file_link3').value = '';
            document.getElementById('filePreview3').style.display = 'none';
        }

        // JavaScript to display image preview when a file is selected profile
        document.getElementById('file_link4').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('filePreview4');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file id
        function clearFileInput4() {
            document.getElementById('file_link4').value = '';
            document.getElementById('filePreview4').style.display = 'none';
        }

        // JavaScript to display image preview when a file is selected profile
        document.getElementById('file_link5').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('filePreview5');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file id
        function clearFileInput5() {
            document.getElementById('file_link5').value = '';
            document.getElementById('filePreview5').style.display = 'none';
        }

        // JavaScript to display image preview when a file is selected profile
        document.getElementById('file_link6').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('filePreview6');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file id
        function clearFileInput6() {
            document.getElementById('file_link6').value = '';
            document.getElementById('filePreview6').style.display = 'none';
        }

        // JavaScript to display image preview when a file is selected profile
        document.getElementById('file_link7').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('filePreview7');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file id
        function clearFileInput7() {
            document.getElementById('file_link7').value = '';
            document.getElementById('filePreview7').style.display = 'none';
        }

        // JavaScript to display image preview when a file is selected profile
        document.getElementById('file_link8').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('filePreview8');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file id
        function clearFileInput8() {
            document.getElementById('file_link8').value = '';
            document.getElementById('filePreview8').style.display = 'none';
        }

    </script>
@endsection
