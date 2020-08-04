@extends('frontLayout.app')

@section('content')
    <div class="inner_services login_register">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form class="form-horizontal form-signin" role="form" method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}
            <h3 class="form-signin-heading">Reset Password</h3>
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" >E-Mail Address</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <button type="submit" class="btn btn-lg btn-primary btn-block">
                Send Password Reset Link
            </button>
        </form>
    </div>

    @endsection

@section('scripts')


@endsection
