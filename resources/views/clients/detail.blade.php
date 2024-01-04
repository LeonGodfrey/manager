@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Client Details | '.$organization->org_name)
@section('page_title',  $client->surname . ' ' . $client->given_name)

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
        <div class="card card-outline card-success">
            <div class="card-header">
                <h4 class="card-title text-success"><b>Client Information</b></h4>
                <div class="card-tools">
                    <button type="button" class="btn btn-success" id="pdfButton">
                        Save as PDF
                    </button>                   
                </div>
            </div>
            
        <form method="post" action="" id="myFormId" enctype="multipart/form-data">
            @csrf
            @method('PUT')           
                <div class="card-body">
                    <div class="form-group">
                        <label for="branch_id">Branch Name *</label>
                        <div class="input-group">
                            <select class="form-control select2" id="branch_id" name="branch_id" disabled>
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
                    </div>
                    <div class="form-group">
                        <label for="registration_date">Registration Date:</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input id="registration_date" name="registration_date" type="text"
                                class="form-control datetimepicker-input" data-target="#reservationdate"
                                value="{{ $client->registration_date }}" placeholder="YYYY-MM-DD" disabled>
                        </div>                        
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_number">Client Number*</label>
                                <input type="text" class="form-control" id="client_number" name="client_number"
                                    value="{{ $client->client_number }}" placeholder="Enter Client Number" disabled>                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ipps_number">IPPS Number</label>
                                <input type="text" class="form-control" id="ipps_number" name="ipps_number"
                                    value="{{ $client->ipps_number }}" placeholder="Enter Client Number" disabled>                                
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="surname">Surname*</label>
                                <input type="text" class="form-control" id="surname" name="surname"
                                    value="{{ $client->surname }}" placeholder="Enter Surname" disabled>                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="given_name">Given Name(s)*</label>
                                <input type="text" class="form-control" id="given_name" name="given_name"
                                    value="{{ $client->given_name }}" placeholder="Enter Given Name" disabled>                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender *</label>
                                <select class="form-control select2" id="gender" name="gender" disabled>
                                    <option value="{{ $client->gender }}" selected>{{ $client->gender }}</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>                               
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
                                        value="{{ $client->dob }}" placeholder="YYYY-MM-DD" disabled>
                                </div>                                
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
                                        disabled>
                                </div>                                
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
                                        value="{{ $client->alt_phone }}" disabled>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email_address">Email Address</label>
                        <input type="email_address" class="form-control" id="email_address" name="email_address"
                            value="{{ $client->email_address }}" placeholder="Enter Email Address" disabled>                       
                    </div>
                    <div class="form-group">
                        <label for="street_address">Street Address *</label>
                        <p class="text-sm">Enter Client's physical address.</p>
                        <input type="text" class="form-control" id="street_address" name="street_address"
                            value="{{ $client->street_address }}" placeholder="Enter Street Address" disabled>                       
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="district">District *</label>
                                <input type="text" class="form-control" id="district" name="district"
                                    value="{{ $client->district }}" placeholder="Enter district" disabled>                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    value="{{ $client->city }}" placeholder="Enter city" disabled>                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="county">County </label>
                                <input type="text" class="form-control" id="county" name="county"
                                    value="{{ $client->county }}" placeholder="Enter county" disabled>                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sub_county">Sub County</label>
                                <input type="text" class="form-control" id="sub_county" name="sub_county"
                                    value="{{ $client->sub_county }}" placeholder="Enter Sub County" disabled>                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parish">Parish </label>
                                <input type="text" class="form-control" id="parish" name="parish"
                                    value="{{ $client->parish }}" placeholder="Enter parish" disabled>                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="village">Village *</label>
                                <input type="text" class="form-control" id="village" name="village"
                                    value="{{ $client->village }}" placeholder="Enter village" disabled>                                
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
                                    value="{{ $client->kin_name }}" placeholder="Enter Next Of Kin Name" disabled>                                
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
                                        value="{{ $client->kin_phone }}" disabled>
                                </div>                                
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
                                    value="{{ $client->home_village }}" placeholder="Enter Client's Home Village" disabled>                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="home_district">Home District</label>
                                <p class="text-sm">If client's Home District is different from their current District</p>
                                <input type="text" class="form-control" id="home_district" name="home_district"
                                    value="{{ $client->home_district }}" placeholder="Enter Client's Home District" disabled>                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="father_name">Father's Name </label>
                                <input type="text" class="form-control" id="father_name" name="father_name"
                                    value="{{ $client->father_name }}" placeholder="Enter Father's Name" disabled>                                
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
                                        value="{{ $client->father_phone }}" disabled>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mother_name">Mother's Name </label>
                                <input type="text" class="form-control" id="mother_name" name="mother_name"
                                    value="{{ $client->mother_name }}" placeholder="Enter mother's Name" disabled>                               
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
                                        value="{{ $client->mother_phone }}" disabled>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p class="text-success">Identification</p>
                    <div class="form-group">
                        <label for="id_number">National Identification Number(NIN) </label>
                        <input type="text" class="form-control" id="id_number" name="id_number"
                            value="{{ $client->id_number }}" placeholder="NIN like CM9805582W0H3L" disabled>
                    </div>
                    <p class="text-success">Identification Files (only upload photos)</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="profile_photo">Passport Photo</label>
                                <br>
                                @if ($client->profile_photo)
                                <img class="m-2" style="width: 45%;"
                                src="{{ asset('storage/' . $client->profile_photo) }}" alt="profile_photo">
                                @else
                                    <div class="ml-4 d-inline">No File Attached!</div>
                                @endif 
                            </div>                           
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_photo">ID Photo</label>
                                <br>
                                @if ($client->id_photo)
                                <img class="m-2" style="width: 45%;"
                                src="{{ asset('storage/' . $client->id_photo) }}" alt="id_photo">
                                @else
                                    <div class="ml-4 d-inline">No File Attached!</div>
                                @endif 
                            </div>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="signature">Signature</label>
                                <br>
                                @if ($client->signature)
                                <img class="m-2" style="width: 45%;"
                                src="{{ asset('storage/' . $client->signature) }}" alt="signature">
                                @else
                                    <div class="ml-4 d-inline">No File Attached!</div>
                                @endif 
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="other_file">Any other File</label>
                                <br>
                                @if ($client->other_file)
                                <img class="m-2" style="width: 45%;"
                                src="{{ asset('storage/' . $client->other_file) }}" alt="other_file">
                                @else
                                    <div class="ml-4 d-inline">No File Attached!</div>
                                @endif                                
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->                
            </div>
        </form>
    </div>
    <script>
        document.getElementById('pdfButton').addEventListener('click', function () {
            var element = document.getElementById('myFormId'); // Replace 'myFormId' with the actual form ID
            html2pdf(element);
        });
    </script>
    
@endsection
