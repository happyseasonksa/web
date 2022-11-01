@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary" style="text-align: right;">
                <div class="card-header">
                    <h3 class="card-title" style="float: right;">{{__('Update')}} {{ucfirst($subcategory->name)}} </h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{route('admin.sub-category.update', ['id' => $subcategory->id])}}" role="form" id="add-sub-category" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <?php
                            $categorys=getCategories();
                            ?>
                            <label for="category_id">{{__('Type of Category')}}</label>
                            <select name="category_id" class="custom-select @error('category_id'){{'is-invalid'}}@enderror" id="category_id">
                                <option value="">{{__('Select Category')}}</option>
                                @foreach($categorys as $category)
                                    <option @if($subcategory->category_id && $category->id == $subcategory->category_id) selected @endif value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span id="name-error" class="error invalid-feedback">{{ $errors->first('category_id') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">{{__('Name')}}</label>
                            <input type="text" name="name" class="form-control @error('name'){{'is-invalid'}}@enderror" value="{{$subcategory->name}}" id="name" placeholder="{{__('Name')}}">
                            @error('name')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('name') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">{{__('Status')}}</label>
                            <select name="status" class="custom-select @error('status'){{'is-invalid'}}@enderror" id="status">
                              <option @if($subcategory->status == true) selected @endif value="true">{{__('Active')}}</option>
                              <option @if($subcategory->status == false) selected @endif value="false">{{__('InActive')}}</option>
                            </select>
                            @error('status')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('status') }}</span>
                            @enderror
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label for="image">{{__('Icon Image')}} <span class="text-red">*</span></label>--}}
{{--                            <div class="entry col-md-6 input-group form-group">--}}
{{--                                <img class="img-thumbnail col-md-3" src="{{($subcategory->image)?asset($subcategory->image):asset('dist/img/default_product.jpeg')}}" width="100" height="100">--}}
{{--                                <div class="col-md-4">--}}
{{--                                    <p><span class="btn btn-info btn-file">--}}
{{--                                    {{__('Choose File')}} <input name="image" class="@error('image'){{'is-invalid'}}@enderror" type="file" accept=".png, .jpg, .jpeg">--}}
{{--                                    </span></p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            @error('image')--}}
{{--                            <span id="image-error" class="error invalid-feedback">{{ $errors->first('image') }}</span>--}}
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
      $('#add-sub-category').validate({
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            category_id: {
                required: true,
            },
            // ar_name: {
            //     required: true,
            // },
            status: {
                required: true,
            },
            // image: {
            //     //required: true,
            //     accept: "image/jpeg, image/pjpeg, image/png"
            // },
        },
        messages: {
            name: {
                required: "Please enter a name",
            },
            ar_name: {
                required: "Please enter a arabic name",
            },
            category_id: {
                required: "Please select a category",
            },
            status: {
                required: "Please select a status",
            },
            image: {
                required: "Please select an image",
            },
        },
        invalidHandler: function(form, validator) {
          var btn = $('#add-sub-category').find('button[type="submit"]');
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
