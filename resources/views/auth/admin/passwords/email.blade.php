@extends('auth.app')

@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>{{__('Admin Login')}}</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">{{__('Forgot Password')}}</p>
        @include('layouts.message')
        @if(Session::has('status'))
        <div class="col-sm-12 mt-3">
        <div class="alert alert-success background-success">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
           <p>{{Session::get('status')}}</p>
        </div>
        </div>
        @endif
      <form method="POST" action="{{ route('admin.password.email') }}">
        @csrf
        <div class="input-group mb-3">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{__('Email')}}">
        </div>
        @error('email')
          <p class="text-danger">{{ $errors->first('email') }}</p>
        @enderror
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">{{__('Send Password Reset Link')}}</button>
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
<!-- /.login-box -->

@endsection
