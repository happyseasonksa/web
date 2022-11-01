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
                    <h3 class="card-title" style="float: right">{{__('Update')}} {{$text->name}} </h3>
                </div>
                <input type="hidden" name="today_date" id="today_date">
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{route('admin.text.update', ['id' => $text->id])}}" enctype="multipart/form-data" role="form" id="add-text">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="stext">{{__('Text')}}</label>
                            <textarea type="text" name="stext" class="form-control @error('stext'){{'is-invalid'}}@enderror" id="stext">{{$text->stext}}</textarea>
                            @error('stext')
                                <span id="stext-error" class="error invalid-feedback">{{ $errors->first('stext') }}</span>
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
      $('#add-text').validate({
        rules: {
            stext: {
                required: true,
                maxlength: 255,
            },
        },
        messages: {
            stext: {
                required: "Please enter a text",
            },

        },
        invalidHandler: function(form, validator) {
          var btn = $('#add-text').find('button[type="submit"]');
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
