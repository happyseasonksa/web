@extends('auth.app')

@section('content')
<div class="register-box">
  <div class="register-logo">
    <a href="#"><b>Register</b></a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new membership</p>
      <form id="user-register-form" action="{{ route('register') }}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control @error('name'){{'is-invalid'}}@enderror" name="name" value="{{ old('name') }}" placeholder="Full name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          @error('name')
              <span id="name-error" class="error invalid-feedback">{{ $errors->first('name') }}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email'){{'is-invalid'}}@enderror" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          @error('email')
              <span id="name-error" class="error invalid-feedback">{{ $errors->first('email') }}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone'){{'is-invalid'}}@enderror" placeholder="Phone">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone-alt"></span>
            </div>
          </div>
          @error('phone')
              <span id="name-error" class="error invalid-feedback">{{ $errors->first('phone') }}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="text" name="address" value="{{ old('address') }}" class="form-control @error('address'){{'is-invalid'}}@enderror" placeholder="Address">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-thumbtack"></span>
            </div>
          </div>
          @error('address')
              <span id="name-error" class="error invalid-feedback">{{ $errors->first('address') }}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <select name="gender" class="custom-select @error('gender'){{'is-invalid'}}@enderror">
            <option value="">Select gender</option>
            <option @if(old('gender') === 'male') selected @endif value="male">Male</option>
            <option @if(old('gender') === 'female') selected @endif value="female">Female</option>
          </select>
          @error('gender')
              <span id="name-error" class="error invalid-feedback">{{ $errors->first('gender') }}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <div class="custom-file">
            <input type="file" value="{{ old('id_proof_1') }}" name="id_proof_1" class="custom-file-input @error('id_proof_1'){{'is-invalid'}}@enderror">
            <label class="custom-file-label" for="exampleInputFile">Choose ID Proof 1st</label>
          </div>
          @error('id_proof_1')
              <span id="name-error" class="error invalid-feedback">{{ $errors->first('id_proof_1') }}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <div class="custom-file">
            <input type="file" value="{{ old('id_proof_2') }}" name="id_proof_2" class="custom-file-input @error('id_proof_2'){{'is-invalid'}}@enderror">
            <label class="custom-file-label" for="exampleInputFile">Choose ID Proof 2nd</label>
          </div>
          @error('id_proof_2')
              <span id="name-error" class="error invalid-feedback">{{ $errors->first('id_proof_2') }}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" required autocomplete="new-password" class="form-control @error('password'){{'is-invalid'}}@enderror" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          @error('password')
              <span id="name-error" class="error invalid-feedback">{{ $errors->first('password') }}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control @error('password_confirmation'){{'is-invalid'}}@enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          @error('password_confirmation')
              <span id="name-error" class="error invalid-feedback">{{ $errors->first('password_confirmation') }}</span>
          @enderror
        </div>
        <div class="row">
          <div class="col-8">
            <div class="input-group form-check">
              <input type="checkbox" name="terms" class="form-check-input @error('terms'){{'is-invalid'}}@enderror">
              <label class="form-check-label" 
              >I agree terms & conditions</label>
              @error('terms')
                  <span id="name-error" class="error invalid-feedback">{{ $errors->first('terms') }}</span>
              @enderror
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <a href="{{ route('login') }}" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->
@endsection
@section('js-body')
<!-- jquery-validation -->
  <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
<!-- jquery-validation END -->

<script>
  $(document).ready(function () {
    $('#user-register-form').validate({
        rules: {
            name: {
                required: true,
            },
            phone: {
                required: true,
                minlength: 10,
                number:true
            },
            terms: {
                required: true,
            },
            gender: {
                required: true,
            },
            address: {
                required: true,
            },
            id_proof_1: {
                required: true,
                accept: "image/jpeg, image/pjpeg"
            },
            id_proof_2: {
                required: true,
                accept: "image/jpeg, image/pjpeg"
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 6 
            },
            password_confirmation: {
                required: true,
                minlength : 6,
                equalTo : "#password" 
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
            },
            phone: {
                required: "Please enter a phone",
                minlength: "Please enter a valid phone number",
                number: "Please enter a valid phone number"
            },
            terms: {
                required: "Please accept terms & conditions",
            },
            gender: {
                required: "Please select a gender",
            },
            address: {
                required: "Please enter a address",
            },
            id_proof_1: {
                required: "Please select a ID proof image",
                accept: "Please upload valid image"
            },
            id_proof_2: {
                required: "Please select a ID proof image",
                accept: "Please upload valid image"
            },
            email: {
                required: "Please enter a email address",
                email: "Please enter a vaild email address"
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
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.input-group').append(error);
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
