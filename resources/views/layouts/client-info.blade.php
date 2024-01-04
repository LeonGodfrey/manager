<div class="col-sm-12">
    <div class="card card-success card-outline elevation-3">
        <div class="card-body pd-0">
            <div class="row">
                <div class="col-md-7">
                    <h5><b>{{ $client->surname . ' ' . $client->given_name }}</b> | {{ $client->client_number }}
                    </h5>
                    <p><a href="{{ route('clients.detail', ['client' => $client]) }}" class="btn btn-xs btn-success">View
                            Client Details</a> <a href="{{ route('clients.edit', ['client' => $client]) }}"
                            class=" btn btn-xs btn-info ml-4">Edit Info</a></p>

                    <p class="text-sm">Contact(s):<b> <a href="tel:{{ $client->phone }}"> {{ $client->phone }}</a> | <a
                                href="tel:{{ $client->alt_phone }}">
                                {{ $client->alt_phone }}</a> </b><br> Email: <b><a
                                href="mailto:{{ $client->email_address }}">{{ $client->email_address }}</a> </b></p>
                    <p class="text-sm">Address: <b>{{ $client->street_address }} | {{ $client->village }} |
                            {{ $client->district }}</b></p>
                    <p class="text-sm">DOB: <b>{{ $client->dob }} | Age: {{ $age }} Years</b> <span
                            class="btn btn-success btn-xs p-0 ml-3">{{ $client->branch->branch_name }}</span> </p>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-sm-12">
                            @if ($client->profile_photo)
                                <img class="img-fluid " style="width: 35%;"
                                    src="{{ asset('storage/' . $client->profile_photo) }}" alt="Client Profile">
                            @else
                                <div class="d-inline">No Profile Photo attached!</div>
                            @endif
                            @if ($client->id_photo)
                                <img class="img-fluid" style="width: 35%;"
                                    src="{{ asset('storage/' . $client->id_photo) }}" alt="Client ID">
                            @else
                                <div class="ml-4 d-inline">No ID attached!</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>