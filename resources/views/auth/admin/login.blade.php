@extends('auth.app')

@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>{{__('Happy Seasons')}} - {{__('Admin Login')}}</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card animate__animated animate__zoomIn">
    <div class="card-body login-card-body" style="text-align: right;">
        <center>
            <img src="{{ asset('dist/img/happySeasonsLogo.jpeg') }}" alt="{{__('Happy Seasons')}}" class="brand-image img-circle elevation-3" style="opacity: 1;">
        </center>
        <p class="login-box-msg"style="padding-top: 20px">{{__('Sign in to start your session')}}</p>
      @include('layouts.message')
      <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="input-group mb-3">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
          <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="{{__('Email')}}">
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
          <input type="password" name="password" value="{{old('password')}}" class="form-control" placeholder="{{__('Password')}}">
        </div>
        @error('password')
        <p class="text-danger">{{ $errors->first('password') }}</p>
        @enderror
        <div class="row">
          <div class="col-6">
            <div class="icheck-primary">
                <span><input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}></span>
              <label for="remember">
                {{__('Remember Me')}}
              </label>
            </div>
          </div>
{{--          <div class="col-6">--}}
{{--            <div class="icheck-primary">--}}
{{--              <a href="{{route('admin.password.reset')}}" style="float: right;"> {{__('Forgot Password')}}</a>--}}
{{--            </div>--}}
{{--          </div>--}}
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">{{__('Sign In')}}</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
@endsection
