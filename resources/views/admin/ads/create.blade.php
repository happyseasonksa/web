@extends('layouts.app')
@section('header')
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row mt-3">
          <!-- left column -->
          <div class="col-md-12" style="text-align: right;">
            <!-- jquery validation -->
            <div class="card card-primary" style="text-align: right;">
                <div class="card-header">
                    <h3 class="card-title" style="float: right">{{__('sidebar.Add New Ads')}}</h3>
                </div>
                <input type="hidden" name="today_date" id="today_date">
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{route('admin.ads.store')}}" enctype="multipart/form-data" role="form" id="add-ads">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">{{__('Title')}}</label>
                            <input type="text" name="title" class="form-control @error('title'){{'is-invalid'}}@enderror" value="{{old('title')}}" id="name" placeholder="{{__('Title')}}">
                            @error('title')
                                <span id="title-en-error" class="error invalid-feedback">{{ $errors->first('title') }}</span>
                            @enderror
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label for="title">{{__('Arabic Title')}}</label>--}}
{{--                            <input type="text" name="title" class="form-control @error('title'){{'is-invalid'}}@enderror" value="{{old('title')}}" id="title" placeholder="{{__('Arabic Title')}}">--}}
{{--                            @error('title')--}}
{{--                                <span id="title-error" class="error invalid-feedback">{{ $errors->first('title') }}</span>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
                        <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('Start Date')}}:</label>
                                <div class="input-group">
                                    <input type="text" name="start_at" class="form-control float-right" required id="start-date">
                                    <div class="input-group-prepend">
				                      <span class="input-group-text">
				                        <i class="far fa-calendar-alt"></i>
				                      </span>
                                    </div>
                                </div>
                            </div>
                            @error('start_at')
                            <span id="name-error" class="error invalid-feedback">{{ $errors->first('start_at') }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('End Date')}}:</label>
                                <div class="input-group">
                                    <input type="text" name="end_at" class="form-control float-right" required id="end-date">
                                    <div class="input-group-prepend">
				                      <span class="input-group-text">
				                        <i class="far fa-calendar-alt"></i>
				                      </span>
                                    </div>
                                </div>
                            </div>
                            @error('end_at')
                            <span id="name-error" class="error invalid-feedback">{{ $errors->first('end_at') }}</span>
                            @enderror
                        </div>
                        </div>
                        <div class="form-group">
                            <label for="description">{{__('Description')}}</label>
                            <textarea name="description" class="form-control @error('description'){{'is-invalid'}}@enderror" id="description" placeholder="{{__('Enter Description')}}">{{old('description')}}</textarea>
                            @error('description')
                            <span id="description-error" class="error invalid-feedback">{{ $errors->first('description') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="target">{{__('Target')}}</label>
                            <select name="target" class="custom-select @error('target'){{'is-invalid'}}@enderror" id="target">
                                <option @if(old('target') == 'in') selected @endif value="in">{{__('In App')}}</option>
                                <option @if(old('target') == 'out') selected @endif value="out">{{__('Out App')}}</option>
                            </select>
                            @error('target')
                            <span id="name-error" class="error invalid-feedback">{{ $errors->first('target') }}</span>
                            @enderror
                        </div>
                        <div class="form-group" id="link_cont" style="display: none;">
                            <label for="link">{{__('Link')}}</label>
                            <input type="text" name="link" class="form-control @error('link'){{'is-invalid'}}@enderror" value="{{old('link')}}" id="link" placeholder="{{__('Enter Link')}}">
                            @error('link')
                            <span id="link-error" class="error invalid-feedback">{{ $errors->first('link') }}</span>
                            @enderror
                        </div>
                        <div class="form-group" id="item_cont">
                            <label for="item_id">{{__('Item')}}</label>
                            <select name="item_id" class="custom-select @error('item_id'){{'is-invalid'}}@enderror" id="item_id">
                                <option value="">{{__('Select Item')}}</option>
                                @foreach($items as $item)
                                    <option @if(old('item_id') && $item->id == old('item_id')) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('item_id')
                            <span id="name-error" class="error invalid-feedback">{{ $errors->first('item_id') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image">{{__('Upload Image')}}</label>
                            <div class="entry col-md-6 input-group form-group">
                            <img class="img-thumbnail col-md-3" src="{{asset('dist/img/default_product.jpeg')}}" width="100" height="100">
                                <div class="col-md-4">
                                    <p><span class="btn btn-info btn-file">
                                    {{__('Choose File')}} <input name="image" class="@error('image'){{'is-invalid'}}@enderror" type="file" accept=".png, .jpg, .jpeg">
                                    </span></p>
                                </div>
                            </div>
                            @error('image')
                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('image') }}</span>
                            @enderror
                        </div>
{{--                        --}}
{{--                        <div class="form-group">--}}
{{--                            <label for="status">{{__('Status')}}</label>--}}
{{--                            <select name="status" class="custom-select @error('status'){{'is-invalid'}}@enderror" id="status">--}}
{{--                              <option @if(old('status') == 1) selected @endif value="1">{{__('Active')}}</option>--}}
{{--                              <option @if(old('status') == 0) selected @endif value="0">{{__('InActive')}}</option>--}}
{{--                            </select>--}}
{{--                            @error('status')--}}
{{--                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('status') }}</span>--}}
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
    var datetime = moment().format('YYYY-MM-DD');
    //datetime = datetime+' AM';
    $('#today_date').val(datetime);
    $(document).ready(function () {
        $('#end-date').daterangepicker({
            startDate: '{{$endDate}}',
            singleDatePicker: true,
            minDate: datetime,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $('#start-date').daterangepicker({
            startDate: '{{$startDate}}',
            singleDatePicker: true,
            minDate: datetime,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $('select#target').on('change', function() {
                if (this.value == 'in'){
                    $('#item_cont').show();
                    $('#link_cont').hide();
                }else {
                    $('#link_cont').show();
                    $('#item_cont').hide();
                }
        });
      $('#add-ads').validate({
        rules: {
            title: {
                required: true,
            },
            // title_en: {
            //     required: true,
            // },
            start_at: {
                required: true,
                //greaterThan: "#today_date"
            },
            end_at: {
                required: true,
                greaterThan: "#start-date"
            },
            target: {
                required: true,
            },
            link: {
                required: function(element){
                    return $("#target").val()=="out";
                },
                minlength:3
            },
            item_id: {
                required: function(element){
                    return $("#target").val()=="in";
                },
                number:true
            },
            image: {
                required: true,
                accept: "image/jpeg, image/pjpeg, image/png"
            },
            // status: {
            //     required: true,
            // },
        },
        messages: {

            title: {
                required: "Please enter a arabic name",
            },
            title_en: {
                required: "Please enter a english name",
            },
            link: {
                required: "Please enter a link",
            },
            target: {
                required: "Please select a target",
            },
            item_id: {
                required: "Please select an item",
            },
            image: {
                required: "Please select a icon image",
                accept: "Please upload valid image"
            },
            status: {
                required: "Please select a status",
            },
            restaurant_id: {
                required: "Please select a restaurant",
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
