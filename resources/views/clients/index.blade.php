@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Clients | '.$organization->org_name)
@section('page_title', 'Clients')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
        <a href="{{ route('clients.create-client') }}" class="btn float-right bg-success"><i class="fa fa-plus"></i> New Client</a>
    </ol>
@endsection

@section('main_content')

    <!-- filter -->
    <div class="col-sm-12">
        <div class="card card-success card-outline elevation-3">
            <!-- /.card-header -->
            <div class="card-body pb-0">
                <form action="{{ route('clients.index') }}" method="get">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="search" name="search" class="form-control form-control-md"
                                                placeholder="Search Client by Name or Number"
                                                value="{{ request('search') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="submit" class="btn bg-success" value="Search">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div><!-- /.card -->
    </div> <!-- filter -->
    <div class="col-sm-12">
        <div class="card card-solid card-success card-outline elevation-3">
            <div class="card-body">
                @forelse ($clients as $client)
                    <div class="row">
                        <div class="col-md-1 text-center">
                            @if ($client->profile_photo)
                                <img src="{{ asset('storage/' . $client->profile_photo) }}" alt="User Profile"
                                    class="img-fluid mx-auto d-none d-md-block elevation-3" style="width: 100%;">
                            @else
                                @if ($client->gender === 'Male')
                                    <img src="{{ asset('storage/clients/male_profile.jpg') }}"
                                        alt="Male Profile Placeholder"
                                        class="img-fluid mx-auto d-none d-md-block elevation-3" style="width: 100%;">
                                @else
                                    <img src="{{ asset('storage/clients/female_profile.jpg') }}"
                                        alt="Female Profile Placeholder"
                                        class="img-fluid mx-auto d-none d-md-block elevation-3" style="width: 100%;">
                                @endif
                            @endif

                        </div>
                        <div class="col-md-6">
                            <a class="text-success" href="{{ route('clients.client', ['client' => $client]) }}">
                                <h5><b>{{ $client->surname . ' ' . $client->given_name }}</b> | {{ $client->client_number }}
                                </h5>
                            </a>
                            <P> <a href="tel:{{ $client->phone }}">{{ $client->phone }}</a> | <a
                                    href="mailto:{{ $client->email_address }}">{{ $client->email_address }}</a> <br>
                                <span class="btn btn-success btn-xs p-0">{{ $client->branch->branch_name }}</span>
                            </P>
                        </div>
                        <div class="col-md-5">
                            <p class="text-sm">{{ $client->street_address }}</p>
                            <p class="text-sm">{{ $client->village }} | {{ $client->district }} | {{ $client->city }}</p>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                @empty
                    <p><b>No Clients found.</b></p>
                @endforelse
            </div> <!-- /.card-body -->  
        </div> 
        
        <div class="pb-3">
            {{ $clients->links() }} 
          </div>     
    </div>
    

    
@endsection
