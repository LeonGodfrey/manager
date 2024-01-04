@extends('layouts.app2')

@section('title', 'Login | Microfin LTD')

@section('main_content')


    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p class="text-sm"> {{ Session::get('error') }} </p>
        </div>
    @endif


    <div class="card card-outline card-success">
        <div class="card-header text-center">
            <a href="{{ route('login') }}" class="h1 text-success"><b>MICROFIN</b> LTD</a>            
        </div>
        <div class="card-body">
            
            <p class="login-box-msg">Welcome!</p>

            <form method="post" action="{{ route('loggedin') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="user_name" name="user_name"
                        value="{{ old('user_name') }}" required placeholder="Enter your username">

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                @error('user_name')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password" name="password" value=""
                        placeholder="Enter your password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <input type="checkbox" id="showPasswordCheckbox"> Show Password
                @error('password')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
                <div class="row">
                    <div class="col-8">

                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-success btn-block">Log In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.card-body -->
    </div>

    </div>
    {{-- javascript --}}
    <script>
        const passwordInput = document.getElementById('password');
        const showPasswordCheckbox = document.getElementById('showPasswordCheckbox');

        showPasswordCheckbox.addEventListener('change', function() {
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    </script>

@endsection
