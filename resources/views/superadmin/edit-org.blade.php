@extends('layouts.app1')

@section('org_name', 'Super Admin')
@section('title', 'Edit Organization')
@section('page_title', 'Edit Organization')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right btn btn-default">
            <li class="breadcrumb-item btn btn-outline-success btn-sm "><a href={{ route('super-admin.index') }}>Organizations</a></li>            
    </ol>
@endsection

@section('main_content')

    <div class="col-sm-12">
        <form method="post" action="{{ route('super-admin.organizations.update', ['organization' => $organization]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card card-success card-outline">
                <div class="card-body">

                    <div class="form-group">
                        <label for="org_name">Organization Name:</label>
                        <input type="text" class="form-control" id="org_name" name="org_name"
                            value="{{ $organization->org_name }}">
                        @error('org_name')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="country">Country:</label>
                        <input type="text" class="form-control" id="country" name="org_country"
                            value="{{ $organization->org_country }}">
                        @error('org_country')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="currencyCode">Currency Code:</label>
                        <input type="text" class="form-control" id="currencyCode" name="currency_code"
                            value="{{ $organization->currency_code }}">
                        @error('currency_code')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="incorporationDate">Incorporation Date:</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input id="incorporationDate" name="incorporation_date" type="text"
                                class="form-control datetimepicker-input" data-target="#reservationdate"
                                value="{{ $organization->incorporation_date }}">
                        </div>
                        @error('incorporation_date')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="registrationNumber">Business Registration Number:</label>
                        <input type="text" class="form-control" id="registrationNumber" name="business_reg_no"
                            value="{{ $organization->business_reg_no }}">
                        @error('business_reg_no')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="managerName">Manager Name:</label>
                        <input type="text" class="form-control" id="managerName" name="manager_name"
                            value="{{ $organization->manager_name }}">
                        @error('manager_name')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="managerContact">Manager Contact:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control" id="managerContact" name="manager_contact"
                                data-inputmask='"mask": "+256999999999"' data-mask
                                value="{{ $organization->manager_contact }}">
                        </div>
                        @error('manager_contact')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="orgLogo">Organization Logo (File):</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="orgLogo" name="org_logo"
                                value="">
                            @error('org_log')
                                <div class="text-sm text-danger">{{ $message }}</div>
                            @enderror
                            <label class="custom-file-label" for="orgLogo">Choose file</label>
                        </div>
                        <img id="logoPreview" class="mt-2" src="{{ asset('storage/' . $organization->org_logo) }}"
                            alt="Logo" style="max-width: 100px; display: none;">
                        <img src="{{ asset('storage/' . $organization->org_logo) }}" alt="Organization Logo">

                    </div>
                    <script>
                        // JavaScript to display image preview when a file is selected
                        document.getElementById('orgLogo').addEventListener('change', function() {
                            var input = this;
                            var preview = document.getElementById('logoPreview');
                            var file = input.files[0];
                            var reader = new FileReader();

                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                preview.style.display = 'block';
                            };

                            reader.readAsDataURL(file);
                        });
                    </script>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="card-tools text-right">
                        <button name="submit" type="submit" class="btn btn-success">Update Organization</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
