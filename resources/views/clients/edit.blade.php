@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Edit Client | '.$organization->org_name)
@section('page_title', 'Edit Client Info ')

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

    <div class="col-sm-12">
        <form method="post" action="{{ route('clients.update', ['client' => $client]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card card-outline card-success elevation-3">
                <div class="card-body">
                    <div class="form-group">
                        <label for="branch_id">Branch Name *</label>
                        <div class="input-group">
                            <select class="form-control select2" id="branch_id" name="branch_id" required>
                                <option>--Select Branch</option>
                                @foreach ($branches as $branch)
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            @if ($client->branch_id === $branch->id) selected @endif>
                                            {{ $branch->branch_name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        @error('branch_id')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="registration_date">Registration Date:</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input id="registration_date" name="registration_date" type="text"
                                class="form-control datetimepicker-input" data-target="#reservationdate"
                                value="{{ $client->registration_date }}" placeholder="YYYY-MM-DD" required>
                        </div>
                        @error('registration_date')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_number">Client Number*</label>
                                <input type="text" class="form-control" id="client_number" name="client_number"
                                    value="{{ $client->client_number }}" placeholder="Enter Client Number" required>
                                @error('client_number')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ipps_number">IPPS Number</label>
                                <input type="text" class="form-control" id="ipps_number" name="ipps_number"
                                    value="{{ $client->ipps_number }}" placeholder="Enter Client Number">
                                @error('ipps_number')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="surname">Surname*</label>
                                <input type="text" class="form-control" id="surname" name="surname"
                                    value="{{ $client->surname }}" placeholder="Enter Surname" required>
                                @error('surname')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="given_name">Given Name(s)*</label>
                                <input type="text" class="form-control" id="given_name" name="given_name"
                                    value="{{ $client->given_name }}" placeholder="Enter Given Name" required>
                                @error('given_name')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender *</label>
                                <select class="form-control select2" id="gender" name="gender" required>
                                    <option value="{{ $client->gender }}" selected>{{ $client->gender }}</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                @error('gender')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dob">Date of Birth *</label>
                                <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                    <div class="input-group-prepend" data-target="#reservationdate1"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input id="dob" name="dob" type="text"
                                        class="form-control datetimepicker-input" data-target="#reservationdate1"
                                        value="{{ $client->dob }}" placeholder="YYYY-MM-DD" required>
                                </div>
                                @error('dob')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone"> Phone Number *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        data-inputmask='"mask": "+256999999999"' data-mask value="{{ $client->phone }}"
                                        required>
                                </div>
                                @error('phone')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alt_phone">Second Phone Number if any.</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="alt_phone" name="alt_phone"
                                        data-inputmask='"mask": "+256999999999"' data-mask
                                        value="{{ $client->alt_phone }}">
                                </div>
                                @error('alt_phone')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email_address">Email Address</label>
                        <input type="email_address" class="form-control" id="email_address" name="email_address"
                            value="{{ $client->email_address }}" placeholder="Enter Email Address">
                        @error('email_address')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="street_address">Street Address *</label>
                        <p class="text-sm">Enter Client's physical address.</p>
                        <input type="text" class="form-control" id="street_address" name="street_address"
                            value="{{ $client->street_address }}" placeholder="Enter Street Address" required>
                        @error('street_address')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="district">District *</label>
                                <input type="text" class="form-control" id="district" name="district"
                                    value="{{ $client->district }}" placeholder="Enter district" required>
                                @error('district')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    value="{{ $client->city }}" placeholder="Enter city" required>
                                @error('city')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="county">County </label>
                                <input type="text" class="form-control" id="county" name="county"
                                    value="{{ $client->county }}" placeholder="Enter county">
                                @error('county')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sub_county">Sub County</label>
                                <input type="text" class="form-control" id="sub_county" name="sub_county"
                                    value="{{ $client->sub_county }}" placeholder="Enter Sub County">
                                @error('sub_county')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parish">Parish </label>
                                <input type="text" class="form-control" id="parish" name="parish"
                                    value="{{ $client->parish }}" placeholder="Enter parish">
                                @error('parish')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="village">Village *</label>
                                <input type="text" class="form-control" id="village" name="village"
                                    value="{{ $client->village }}" placeholder="Enter village" required>
                                @error('village')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p class="text-success">Next of Kin Details</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kin_name">Next Of Kin Name * </label>
                                <input type="text" class="form-control" id="kin_name" name="kin_name"
                                    value="{{ $client->kin_name }}" placeholder="Enter Next Of Kin Name" required>
                                @error('kin_name')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kin_phone">Next of Kin's Phone Number.</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="kin_phone" name="kin_phone"
                                        data-inputmask='"mask": "+256999999999"' data-mask
                                        value="{{ $client->kin_phone }}">
                                </div>
                                @error('kin_phone')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p class="text-success">More Information</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="home_village">Home Village </label>
                                <p class="text-sm">If client's Home Village is different from their current Village</p>
                                <input type="text" class="form-control" id="home_village" name="home_village"
                                    value="{{ $client->home_village }}" placeholder="Enter Client's Home Village">
                                @error('home_village')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="home_district">Home District</label>
                                <p class="text-sm">If client's Home District is different from their current District</p>
                                <input type="text" class="form-control" id="home_district" name="home_district"
                                    value="{{ $client->home_district }}" placeholder="Enter Client's Home District">
                                @error('home_district')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="father_name">Father's Name </label>
                                <input type="text" class="form-control" id="father_name" name="father_name"
                                    value="{{ $client->father_name }}" placeholder="Enter Father's Name">
                                @error('father_name')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="father_phone">Father's Phone Number.</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="father_phone" name="father_phone"
                                        data-inputmask='"mask": "+256999999999"' data-mask
                                        value="{{ $client->father_phone }}">
                                </div>
                                @error('father_phone')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mother_name">Mother's Name </label>
                                <input type="text" class="form-control" id="mother_name" name="mother_name"
                                    value="{{ $client->mother_name }}" placeholder="Enter mother's Name">
                                @error('mother_name')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mother_phone">Mother's Phone Number.</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="mother_phone" name="mother_phone"
                                        data-inputmask='"mask": "+256999999999"' data-mask
                                        value="{{ $client->mother_phone }}">
                                </div>
                                @error('mother_phone')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p class="text-success">Identification</p>
                    <div class="form-group">
                        <label for="id_number">National Identification Number(NIN) </label>
                        <input type="text" class="form-control" id="id_number" name="id_number"
                            value="{{ $client->id_number }}" placeholder="NIN like CM9805582W0H3L">
                        @error('id_number')
                            <div class="text-sm text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <p class="text-success">Identification Files (only upload photos)</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="profile_photo">Passport Photo</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input"
                                            id="profile_photo" name="profile_photo" value="">
                                        @error('profile_photo')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="profile_photo">Choose a file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <button type="button" class="btn btn-sm btn-danger p-0"
                                                onclick="clearFileInput()">Cancel</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <img id="profilePreview" class="m-2" src="" alt="profile_photo"
                                        style="width: 45%; max-width: 100px; display: none;">
                                    @if ($client->profile_photo)
                                        <img class="m-2" style="width: 45%;"
                                            src="{{ asset('storage/' . $client->profile_photo) }}" alt="Profile_photo">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_photo">ID Photo</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="id_photo"
                                            name="id_photo" value="">
                                        @error('id_photo')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="id_photo">Choose a file</label>
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
                                    <img id="logoPreview1" class="m-2" src="" alt="id_photo"
                                        style="max-width: 100px; display: none;">
                                    @if ($client->id_photo)
                                        <img class="m-2" style="width: 45%;"
                                            src="{{ asset('storage/' . $client->id_photo) }}" alt="id_photo">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="signature">Signature</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="signature"
                                            name="signature" value="{{ $organization->signature }}">
                                        @error('signature')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="signature">Choose a file</label>
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
                                    <img id="logoPreview2" class="m-2" src="" alt="signature"
                                        style="max-width: 100px; display: none;">
                                    @if ($client->signature)
                                        <img class="m-2" style="width: 45%;"
                                            src="{{ asset('storage/' . $client->signature) }}" alt="signature">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="other_file">Any other File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" class="custom-file-input" id="other_file"
                                            name="other_file" value="">
                                        @error('other_file')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                        <label class="custom-file-label" for="other_file">Choose a file</label>
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
                                <div class="col-md-6">
                                    <img id="logoPreview3" class="mt-2" src="" alt="other_file"
                                        style="max-width: 100px; display: none;">
                                    @if ($client->other_file)
                                        <img class="m-2" style="width: 45%;"
                                            src="{{ asset('storage/' . $client->other_file) }}" alt="other_file">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="text" name="org_id" value="{{ $organization->id }}" required hidden>
                @error('org_id')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="card-tools text-right">
                        <button name="submit" type="submit" class="btn btn-success">Update Client</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-sm-12">
        <div class="m-3">
            <!-- Delete Button -->
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
                Delete Client
            </button>
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
                        Are you sure you want to delete this client?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form action="{{ route('clients.destroy', ['client' => $client]) }}" method="POST"
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
        // JavaScript to display image preview when a file is selected profile
        document.getElementById('profile_photo').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('profilePreview');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file id
        function clearFileInput() {
            document.getElementById('profile_photo').value = '';
            document.getElementById('profilePreview').style.display = 'none';
        }

        // JavaScript to display image preview when a file is id
        document.getElementById('id_photo').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('logoPreview1');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file input
        function clearFileInput1() {
            document.getElementById('id_photo').value = '';
            document.getElementById('logoPreview1').style.display = 'none';
        }

        // JavaScript to display image preview when a file is signature
        document.getElementById('signature').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('logoPreview2');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file input
        function clearFileInput2() {
            document.getElementById('signature').value = '';
            document.getElementById('logoPreview2').style.display = 'none';
        }

        // JavaScript to display image preview when a file is other_file
        document.getElementById('other_file').addEventListener('change', function() {
            var input = this;
            var preview = document.getElementById('logoPreview3');
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        // Function to clear the file input
        function clearFileInput3() {
            document.getElementById('other_file').value = '';
            document.getElementById('logoPreview3').style.display = 'none';
        }
    </script>

@endsection
