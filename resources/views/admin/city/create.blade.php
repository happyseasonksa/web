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
                    <h3 class="card-title" style="float: right">{{__('Add New City')}}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{route('admin.city.store')}}" enctype="multipart/form-data" role="form" id="add-city">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <?php
                            $countries=getCountries();
                            ?>
                            <label for="country_id">{{__('Country')}}</label>
                            <select name="country_id" class="select2 @error('country_id'){{'is-invalid'}}@enderror" id="country_id" data-placeholder="{{__('Select Country')}}" style="width: 100%">
                                @foreach($countries as $category)
                                    <option @if(old('country_id') && $category->id == old('country_id')) selected @endif value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                            @error('country_id')
                            <span id="name-error" class="error invalid-feedback">{{ $errors->first('country_id') }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">{{__('Name')}}</label>
                            <input type="text" name="name" class="form-control @error('name'){{'is-invalid'}}@enderror" value="{{old('name')}}" id="name" placeholder="{{__('Name')}}">
                            @error('name')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('name') }}</span>
                            @enderror
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label for="ar_name">{{__('Arabic Name')}}</label>--}}
{{--                            <input type="text" name="ar_name" class="form-control @error('ar_name'){{'is-invalid'}}@enderror" value="{{old('ar_name')}}" id="ar_name" placeholder="{{__('Arabic Name')}}">--}}
{{--                            @error('ar_name')--}}
{{--                                <span id="ar_name-error" class="error invalid-feedback">{{ $errors->first('ar_name') }}</span>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
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
    $(document).ready(function () {
        $('#country_id').select2();
      $('#add-city').validate({
        rules: {
            name: {
                required: true,
                maxlength: 255,
            },
            country_id: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
            },
            country_id: {
                required: "Please select a country",
            },
        },
        invalidHandler: function(form, validator) {
          var btn = $('#add-city').find('button[type="submit"]');
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
