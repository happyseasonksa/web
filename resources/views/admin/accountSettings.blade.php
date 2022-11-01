@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary" style="text-align: right;">
                <div class="card-header">
                    <h3 class="card-title" style="float: right">{{__('Account Settings')}}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{route('admin.update.settings')}}" role="form" id="admin-update-settings">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">{{__('Name')}}</label>
                            <input type="text" name="name" class="form-control @error('name'){{'is-invalid'}}@enderror" value="{{$user->name}}" id="name" placeholder="{{__('Enter Name')}}">
                            @error('name')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('name') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" id="change_password" value="true">
                              <label for="change_password" class="custom-control-label">{{__('Change Password')}}</label>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="password">{{__('Password')}}</label>
                            <input type="password" name="password" class="form-control @error('password'){{'is-invalid'}}@enderror" value="" autocomplete="new-password" id="password" placeholder="{{__('Password')}}">
                            @error('password')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('password') }}</span>
                            @enderror
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="conform_password">{{__('Confirm Password')}}</label>
                            <input type="password" autocomplete="new-password" class="form-control @error('conform_password'){{'is-invalid'}}@enderror" value="" name="conform_password" id="conform_password" placeholder="{{__('Confirm Password')}}">
                            @error('conform_password')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('conform_password') }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
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
<script>
    $(document).ready(function () {
        $('#change_password').change(function () {
            var checked = $(this).is(":checked");
            visiblePassword(checked);
        });

        function visiblePassword(checked) {
            if (checked) {
                $('#password').val('');
                $('#password').parent().show();
                $('#conform_password').val('');
                $('#conform_password').parent().show();
            }else{
                $('#password').val('');
                $('#password').parent().hide();
                $('#conform_password').val('');
                $('#conform_password').parent().hide();
            }
        }

      $('#admin-update-settings').validate({
        rules: {
            name: {
                required: true,
            },
            password: {
                required: {
                    depends: function(element) {
                      return $("#change_password").is(":checked");
                    }
                },
                minlength: 6
            },
            conform_password: {
                required: {
                    depends: function(element) {
                      return $("#change_password").is(":checked");
                    }
                },
                minlength : 6,
                equalTo : "#password"
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long"
            },
            conform_password: {
                required: "Please confirm a password",
                minlength: "Your password must be at least 6 characters long",
                equalTo: "Password does not match"
            }
        },
        invalidHandler: function(form, validator) {
          var btn = $('#admin-update-settings').find('button[type="submit"]');
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
