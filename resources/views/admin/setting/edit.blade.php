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
                    <h3 class="card-title" style="float: right">Update {{ucfirst($setting->name)}} </h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{route('admin.setting.update', ['id' => $setting->id])}}" enctype="multipart/form-data" role="form" id="add-category">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input disabled type="text" name="name" class="form-control @error('name'){{'is-invalid'}}@enderror" value="{{$setting->name}}" id="name" placeholder="Enter Name">
                            @error('name')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('name') }}</span>
                            @enderror
                        </div>
                        @if($setting->name_id =='message_setting')
                            <div class="form-group">
                                <label for="value">{{__('Status')}}</label>
                                <select name="value" class="custom-select @error('status'){{'is-invalid'}}@enderror" id="value">
                                    <option @if($setting->value == 'true') selected @endif value="true">{{__('Active')}}</option>
                                    <option @if($setting->value == 'false') selected @endif value="false">{{__('InActive')}}</option>
                                </select>
                                @error('value')
                                <span id="value-error" class="error invalid-feedback">{{ $errors->first('value') }}</span>
                                @enderror
                            </div>

                        @else
                        <div class="form-group">
                            <label for="title">Value</label>
                            <input type="text" name="value" class="form-control @error('value'){{'is-invalid'}}@enderror" value="{{$setting->value}}" id="title" placeholder="Enter Value">
                            @error('value')
                                <span id="title-error" class="error invalid-feedback">{{ $errors->first('value') }}</span>
                            @enderror
                        </div>
                        @endif
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
            </div>
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('js-body')
    <!-- jquery-validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
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
        $(document).ready(function () {
            $('#add-setting').validate({
                rules: {

                    name: {
                        required: true,
                    },
                    value: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter name",
                    },
                    status: {
                        required: "Please enter value",
                    },
                },
                invalidHandler: function(form, validator) {
                    var btn = $('#add-ads').find('button[type="submit"]');
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
