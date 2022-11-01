@extends('auth.app')

@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>{{__('Admin')}}</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body" style="text-align: right">
      <p class="login-box-msg">{{__('Reset Password')}}</p>
        @include('layouts.message')
        @if(Session::has('status'))
        <div class="col-sm-12 mt-3">
        <div class="alert alert-success background-success">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
           <p>{{Session::get('status')}}</p>
        </div>
        </div>
        @endif
      <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="input-group mb-3">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Email">
          </div>
        @error('email')
          <p class="text-danger">{{ $errors->first('email') }}</p>
        @enderror
        <div class="input-group mb-3">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="New Password">
        </div>
        @error('password')
          <p class="text-danger">{{ $errors->first('password') }}</p>
        @enderror
        <div class="input-group mb-3">
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm New Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        @error('password_confirmation')
          <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
        @enderror
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">{{__('Submit')}}</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-3 mb-0">
        <a href="{{ route('login') }}" class="text-center">{{__('I already have a membership')}}</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
@endsection
