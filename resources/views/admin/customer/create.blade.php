@extends('layouts.app')

@section('header')
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css')}}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary" style="text-align: right;">
                <div class="card-header">
                    <h3 class="card-title" style="float: right">{{__('Add New Customer')}}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{route('admin.customer.store')}}" role="form" enctype="multipart/form-data" id="add-customer">
                    @csrf
                    <input type="hidden" name="country_code" value="{{old('country_code')??'IL'}}">
                    <div class="card-body">
                        <div class="form-group">
                          <label for="name">{{__('Name')}} <span class="text-red">*</span></label>
                          <input type="text" class="form-control @error('name'){{'is-invalid'}}@enderror" name="name" value="{{ old('name') }}" placeholder="{{__('Full name')}}">
                          @error('name')
                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('name') }}</span>
                          @enderror
                        </div>
                        <div class="form-group">
                          <label for="email">{{__('Email')}} <span class="text-red">*</span></label>
                          <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email'){{'is-invalid'}}@enderror" placeholder="{{__('Email')}}">
                          @error('email')
                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('email') }}</span>
                          @enderror
                        </div>
                        <div class="form-group">
                          <label for="phone">{{__('Phone')}}</label>
                          <div class="form-group input-group">
{{--                                  <div class="input-group-prepend">--}}
{{--                                      <select name="phone_code" id="phone_code" class="custom-select">--}}
{{--                                          @foreach(getCountryCodes() as $code)--}}
{{--                                              <option value="{{$code->dial_code}}" @if(old('phone_code') === $code->dial_code) selected  @endif data-country="{{$code->code}}">{{$code->dial_code}} ({{$code->code}})</option>--}}
{{--                                          @endforeach--}}
{{--                                      </select>--}}
{{--                                  </div>--}}
                              <input type="text" name="phone" class="form-control" value="{{old('phone')}}" id="phone" placeholder="{{__('Enter Phone')}}">
                              @if($errors->has('phone') || $errors->has('phone_code'))
                                  <span id="phone-error" class="error text-danger">{{ ($errors->has('phone'))?$errors->first('phone'):$errors->first('phone_code') }}</span>
                              @endif
                          </div>
                        </div>
                        {{-- <div class="form-group">
                          <label for="phone">Address</label>
                          <input type="text" name="address" value="{{ old('address') }}" class="form-control @error('address'){{'is-invalid'}}@enderror" placeholder="Address">
                          @error('address')
                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('address') }}</span>
                          @enderror
                        </div> --}}
{{--                        <div class="form-group">--}}
{{--                            <label for="date_of_birth">Date Of Birth <span class="text-red">*</span></label>--}}
{{--                            <input type="text" name="date_of_birth" class="form-control @error('date_of_birth'){{'is-invalid'}}@enderror" value="" id="date_of_birth" placeholder="Enter date of birth">--}}
{{--                            @error('date_of_birth')--}}
{{--                                <span id="date_of_birth-error" class="error invalid-feedback">{{ $errors->first('date_of_birth') }}</span>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                          <label for="gender">Gender <span class="text-red">*</span></label>--}}
{{--                          <select name="gender" class="custom-select @error('gender'){{'is-invalid'}}@enderror">--}}
{{--                            <option value="">Select gender</option>--}}
{{--                            <option @if(old('gender') === 'male') selected @endif value="male">Male</option>--}}
{{--                            <option @if(old('gender') === 'female') selected @endif value="female">Female</option>--}}
{{--                          </select>--}}
{{--                          @error('gender')--}}
{{--                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('gender') }}</span>--}}
{{--                          @enderror--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <label for="status">{{__('Status')}} <span class="text-red">*</span></label>
                            <select name="status" class="custom-select @error('status'){{'is-invalid'}}@enderror" id="status">
                              <option @if(old('status') == 'true') selected @endif value="true">{{__('Active')}}</option>
                              <option @if(old('status') == 'false') selected @endif value="false">{{__('InActive')}}</option>
                            </select>
                            @error('status')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('status') }}</span>
                            @enderror
                        </div>
                         <div class="form-group">
                          <label for="password">{{__('Password')}}</label>
                          <input type="password" name="password" id="password" required autocomplete="new-password" class="form-control @error('password'){{'is-invalid'}}@enderror" placeholder="{{__('Password')}}">
                          @error('password')
                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('password') }}</span>
                          @enderror
                        </div>
                        <div class="form-group">
                          <label for="password_confirmation">{{__('Confirm Password')}}</label>
                          <input type="password" class="form-control @error('password_confirmation'){{'is-invalid'}}@enderror" name="password_confirmation" required autocomplete="new-password" placeholder="{{__('Confirm password')}}">
                          @error('password_confirmation')
                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('password_confirmation') }}</span>
                          @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('js-body')
<!-- InputMask -->
<script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- jquery-validation -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script>
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(input).closest("div.entry").find('img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

    $('.card').on('change', "input[type='file']", function () {
        readURL(this);
    });

    //Date range picker
    $('#date_of_birth').daterangepicker({
        startDate: '{{(old('date_of_birth'))?:date('d-m-Y')}}',
        singleDatePicker: true,
        maxDate:new Date(),
        locale: {
            format: 'DD-MM-YYYY'
        }
    });

    $('select#phone_code').on('change', function() {
      var country_code = $(this).children("option:selected").data('country');
      $('input[name="country_code"]').val(country_code);
    });

    $(document).ready(function () {
      $('#allergy_detail').select2();
      $('#add-customer').validate({
        rules: {
            name: {
                required: true,
            },
            phone: {
                required: true,
                minlength: 7,
                maxlength: 15,
                digits:true
            },
            date_of_birth:{
                required: true,
            },
            gender: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            status: {
                required: true,
            },
            // address: {
            //     required: true,
            // },
            // password: {
            //     required: true,
            //     minlength: 6
            // },
            // password_confirmation: {
            //     required: true,
            //     minlength : 6,
            //     equalTo : "#password"
            // },
        },
        messages: {
            name: {
                required: "Please enter a name",
            },
            phone: {
                required: "Please enter a phone",
                number: "Please enter a valid phone number"
            },
            terms: {
                required: "Please accept terms & conditions",
            },
            date_of_birth: {
                required: "Please enter a date of birth",
            },
            gender: {
                required: "Please select a gender",
            },
            address: {
                required: "Please enter a address",
            },
            email: {
                required: "Please enter a email address",
                email: "Please enter a vaild email address"
            },
            status: {
                required: "Please select status",
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long"
            },
            password_confirmation: {
                required: "Please confirm a password",
                minlength: "Your password must be at least 6 characters long",
                equalTo: "Password does not match"
            }
        },
        invalidHandler: function(form, validator) {
          var btn = $('#add-customer').find('button[type="submit"]');
          if (btn) {
            btn.addClass('animate__animated animate__shakeX animate__fast')
            setTimeout(function () {
                btn.removeClass('animate__animated animate__shakeX animate__fast')
            }, 500);
          }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    });
</script>
@endsection
