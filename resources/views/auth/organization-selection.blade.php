@extends('layouts.app2')

@section('title', 'Choose Institution')

@section('main_content')
{{-- abandoned --}}

    <div class="login-logo">
        <a href="#" class="text-success"><b>MFI </b>BUSINESS MANAGER</a>        

    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Welcome to your Business Manager </p>
                
                @if (Session::has('error'))                            
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p class="text-sm"> {{ Session::get('error') }} </p>                               
                  </div>
                @endif    
           
            <form method="post" action="{{ route('selected-organization') }}">
                @csrf
                <div class="form-group">
                    <label for="branchName">Enter your institution Name:</label>
                    <input type="text" class="form-control" id="org_name" name="org_name" value="{{ old('org_name') }}"
                        required placeholder="name of your institution">
                    @error('org_name')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success">Choose Institution</button>
        </div>
        </form>
    </div>
    <!-- /.login-card-body -->
    </div>

@endsection
