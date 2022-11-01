@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary" style="text-align: right;">
                <div class="card-header">
                    <h3 class="card-title" style="float: right">{{__('Update')}} {{ucfirst($card->name)}} </h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{route('admin.card.update', ['id' => $card->id])}}" role="form" id="add-category" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            @php
                                $categories = getImageCategories();
                            @endphp
                            <label for="image_category_id">{{__('Type of Category')}} <span class="text-red">*</span></label>
                            <select name="image_category_id" class="select2 @error('image_category_id'){{'is-invalid'}}@enderror" data-placeholder="{{__('Select Category')}}" id="image_category_id" style="width: 100%;">
                                @foreach($categories as $category_id)
                                    <option value=""></option>
                                    <option @if($card->image_category_id && $category_id->id == $card->image_category_id) selected @endif value="{{$category_id->id}}">{{$category_id->name}}</option>
                                @endforeach
                            </select>
                            @error('image_category_id')
                            <span id="name-error" class="error invalid-feedback">{{ $errors->first('image_category_id') }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">{{__('Status')}}</label>
                            <select name="status" class="custom-select @error('status'){{'is-invalid'}}@enderror" id="status">
                              <option @if($card->status == true) selected @endif value="true">{{__('Active')}}</option>
                              <option @if($card->status == false) selected @endif value="false">{{__('InActive')}}</option>
                            </select>
                            @error('status')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('status') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image">{{__('Image')}} <span class="text-red">*</span></label>
                            <div class="entry col-md-6 input-group form-group">
                                <img class="img-thumbnail col-md-3" src="{{($card->image)?asset($card->image):asset('dist/img/default_product.jpeg')}}" width="100" height="100">
                                <div class="col-md-4">
                                    <p><span class="btn btn-info btn-file">
                                    {{__('Choose File')}} <input name="image" class="@error('image'){{'is-invalid'}}@enderror" type="file" accept=".png, .jpg, .jpeg">
                                    </span></p>
                                </div>
                            </div>
                            @error('image')
                            <span id="image-error" class="error invalid-feedback">{{ $errors->first('image') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="x_axis">{{__('X-Axis')}}</label>
                            <input type="text" name="x_axis" class="form-control @error('x_axis'){{'is-invalid'}}@enderror" value="{{$card->x_axis}}" id="x_axis" placeholder="{{__('X-Axis')}}">
                            @error('x_axis')
                            <span id="x_axis-error" class="error invalid-feedback">{{ $errors->first('x_axis') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="y_axis">{{__('Y-Axis')}}</label>
                            <input type="text" name="y_axis" class="form-control @error('y_axis'){{'is-invalid'}}@enderror" value="{{$card->y_axis}}" id="y_axis" placeholder="{{__('Y-Axis')}}">
                            @error('y_axis')
                            <span id="y_axis-error" class="error invalid-feedback">{{ $errors->first('y_axis') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="color">{{__('Color')}}</label>
                            <input type="text" name="color" class="form-control @error('color'){{'is-invalid'}}@enderror" value="{{$card->color}}" id="color" placeholder="{{__('Color')}}">
                            @error('color')
                            <span id="color-error" class="error invalid-feedback">{{ $errors->first('color') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="font">{{__('Text Font')}}</label>
                            <input type="text" name="font" class="form-control @error('font'){{'is-invalid'}}@enderror" value="{{$card->font}}" id="font" placeholder="{{__('Text Font')}}">
                            @error('font')
                            <span id="font-error" class="error invalid-feedback">{{ $errors->first('font') }}</span>
                            @enderror
                        </div>
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

    $(document).ready(function () {
        $('#image_category_id').select2();
      $('#add-category').validate({
        rules: {
            image_category_id: {
                required: true,
            },
            // ar_name: {
            //     required: true,
            // },
            status: {
                required: true,
            },
            image: {
          //      required: true,
                accept: "image/jpeg, image/pjpeg, image/png"
            },
        },
        messages: {
            image_category_id: {
                required: "Please select a category",
            },
            ar_name: {
                required: "Please enter a arabic name",
            },
            status: {
                required: "Please select a status",
            },
            image: {
                required: "Please select an image",
            },
        },
        invalidHandler: function(form, validator) {
          var btn = $('#add-category').find('button[type="submit"]');
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
