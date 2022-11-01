@extends('layouts.app')

@section('header')
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css')}}">

@endsection

@section('content')
    @php
        $daysName = '';
        foreach (getAllDays() as $day) {
            if ($daysName == '')
                $daysName = '#'.$day.'_from'.',#'.$day.'_to';
            else
                $daysName = $daysName.',#'.$day.'_from'.',#'.$day.'_to';
        }
    @endphp
    <div class="container-fluid">
        <div class="row mt-3">
            <!-- left column -->
            <div class="col-md-12" style="text-align: right;">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title" style="float: right;">{{__('Add New Item')}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="POST" action="{{route('admin.item.store')}}" role="form" id="add-table-category" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="lat" value="" id="latInput">
                        <input type="hidden" name="lng" value="" id="lngInput">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" name="name" class="form-control @error('name'){{'is-invalid'}}@enderror" value="{{old('name')}}" id="name" placeholder="{{__('Name')}}">
                                @error('name')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('name') }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone">{{__('Phone')}}</label>
                                <input type="text" name="phone" class="form-control @error('phone'){{'is-invalid'}}@enderror" value="{{old('phone')}}" id="phone" placeholder="{{__('Phone')}}">
                                @error('phone')
                                <span id="phone-error" class="error invalid-feedback">{{ $errors->first('phone') }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                @php
                                    $categories = getCategories();
                                @endphp
                                <label for="category_id">{{__('Type of Category')}} <span class="text-red">*</span></label>
                                <select name="category_id" class="select2 @error('category_id'){{'is-invalid'}}@enderror" data-placeholder="{{__('Select Category')}}" id="category_id" style="width: 100%;">
                                    @foreach($categories as $category_id)
                                        <option value=""></option>
                                        <option @if(old('category_id') && $category_id->id == old('category_id')) selected @endif value="{{$category_id->id}}">{{$category_id->name}}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('category_id') }}</span>
                                @enderror
                            </div>
                            <div class="form-group">

                                <label for="subcategory_id">{{__('Type of SubCategory')}} <span class="text-red">*</span></label>
                                <select name="subcategory_id" class="select2 @error('subcategory_id'){{'is-invalid'}}@enderror" data-placeholder="{{__('Select Category')}}" id="subcategory_id" style="width: 100%;">

                                </select>
                                @error('subcategory_id')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('subcategory_id') }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                @php
                                    $cities = getCities();
                                @endphp
                                <label for="city_id">{{__('City')}} <span class="text-red">*</span></label>
                                <select name="city_id" class="select2 @error('city_id'){{'is-invalid'}}@enderror" data-placeholder="{{__('Select City')}}"   id="city_id" style="width: 100%;">
                                    @foreach($cities as $city)
                                        <option value=""></option>
                                        <option @if(old('city_id') && $city->id == old('city_id')) selected @endif value="{{$city->id}}" data-name="{{$city->name}}" data-arname="{{$city->ar_name}}" >{{$city->name}}</option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('city_id') }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">{{__('Description')}}</label>
                                <textarea name="description" class="form-control @error('description'){{'is-invalid'}}@enderror" id="description" placeholder="{{__('Description')}}">{{old('description')}}</textarea>
                                @error('description')
                                <span id="description-error" class="error invalid-feedback">{{ $errors->first('description') }}</span>
                                @enderror
                            </div>
                            <div class="form-group services-container row">
                                <label for="services" class="col-md-12">{{__('Services')}}</label>
                                <div class="services-entry col-md-12 row mt-2">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="services[]" value=""
                                               placeholder="{{__('Services')}}">
                                    </div>
                                    <div class="col-md-2">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success btn-add" type="button">
                                            <span class="fa fa-plus"></span>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group staff-container row">
                                <label for="staff" class="col-md-12">{{__('Staff')}}</label>
                                <div class="staff-entry col-md-12 row mt-2">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="staff[]" value=""
                                               placeholder="{{__('Staff')}}">
                                    </div>
                                    <div class="col-md-2">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success btn-add" type="button">
                                            <span class="fa fa-plus"></span>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group facilities-container row">
                                <label for="facilities" class="col-md-12">{{__('Facilities')}}</label>
                                <div class="facilities-entry col-md-12 row mt-2">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="facilities[]" value=""
                                               placeholder="{{__('Facilities')}}">
                                    </div>
                                    <div class="col-md-2">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success btn-add" type="button">
                                            <span class="fa fa-plus"></span>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group offers-container row">
                                <label for="offers" class="col-md-12">{{__('Offers')}}</label>
                                <div class="offers-entry col-md-12 row mt-2">
                                    <div class="col-md-10">
                                        <textarea type="text" name="offers[]" class="form-control editor @error('offers'){{'is-invalid'}}@enderror"  id="offers" placeholder="{{__('Offers')}}"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success btn-add" type="button">
                                            <span class="fa fa-plus"></span>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="openTimes">{{__('Open Times')}}</label>
                                <textarea name="openTimes" class="form-control  @error('openTimes'){{'is-invalid'}}@enderror" id="openTimes" placeholder="{{__('openTimes')}}">{{old('openTimes')}}</textarea>
                                @error('openTimes')
                                <span id="openTimes-error" class="error invalid-feedback">{{ $errors->first('openTimes') }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="website">{{__('Website')}}</label>
                                <input type="text" name="website" class="form-control @error('website'){{'is-invalid'}}@enderror" value="{{old('website')}}" id="website" placeholder="{{__('website')}}">
                                @error('website')
                                <span id="website-error" class="error invalid-feedback">{{ $errors->first('website') }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="twitter">{{__('Twitter Link')}}</label>
                                <input type="text" name="twitter" class="form-control @error('twitter'){{'is-invalid'}}@enderror" value="{{old('twitter')}}" id="twitter" placeholder="{{__('twitter link')}}">
                                @error('twitter')
                                <span id="twitter-error" class="error invalid-feedback">{{ $errors->first('twitter') }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="whatsapp">{{__('Whatsapp Link')}}</label>
                                <input type="text" name="whatsapp" class="form-control @error('whatsapp'){{'is-invalid'}}@enderror" value="{{old('whatsapp')}}" id="whatsapp" placeholder="{{__('Whatsapp Link')}}">
                                @error('whatsapp')
                                <span id="whatsapp-error" class="error invalid-feedback">{{ $errors->first('whatsapp') }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="facebook">{{__('Facebook Link')}}</label>
                                <input type="text" name="facebook" class="form-control @error('facebook'){{'is-invalid'}}@enderror" value="{{old('facebook')}}" id="facebook" placeholder="{{__('Facebook Link')}}">
                                @error('facebook')
                                <span id="facebook-error" class="error invalid-feedback">{{ $errors->first('facebook') }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="instagram">{{__('Instagram Link')}}</label>
                                <input type="text" name="instagram" class="form-control @error('instagram'){{'is-invalid'}}@enderror" value="{{old('instagram')}}" id="instagram" placeholder="{{__('Instagram Link')}}">
                                @error('instagram')
                                <span id="instagram-error" class="error invalid-feedback">{{ $errors->first('instagram') }}</span>
                                @enderror
                            </div>


                            <div class="row image-container @error('images'){{'is-invalid'}}@enderror">
                                <label class="col-md-12" for="images">{{__('Add Images')}}</label>
                                <div class="entry col-md-6 input-group form-group">
                                    <img src="{{asset('dist/img/default_product.jpeg')}}" width="100" height="100">
                                    <div class="mx-1">
                                        <p><span class="btn btn-info btn-file">
                                    {{__('Choose File')}} <input name="images[]" type="file">
                                    </span></p>
                                    </div>
                                    <span class="input-group-btn">
                                    <button class="btn btn-success btn-add" type="button">
                                        <span class="fa fa-plus"></span>
                                    </button>
                                </span>
                                </div>
                                @error('images')
                                <span id="images-error" class="error text-danger">{{ $errors->first('images') }}</span>
                                @enderror
                                @error('images.*')
                                <span id="images-error" class="error text-danger">{{__('File should be a valid image like jpg, png or jpeg')}}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">{{__('Status')}}</label>
                                <select name="status" class="custom-select @error('status'){{'is-invalid'}}@enderror" id="status">
                                    <option @if(old('status') == 1) selected @endif value="1">{{__('Active')}}</option>
                                    <option @if(old('status') == 0) selected @endif value="0">{{__('InActive')}}</option>
                                </select>
                                @error('status')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('status') }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="address">{{__('Address')}}</label>
                                <input type="text" name="address" class="form-control @error('address'){{'is-invalid'}}@enderror" value="{{old('address')}}" id="address" placeholder="{{__('Address')}}">
                                @error('address')
                                <span id="address-error" class="error invalid-feedback">{{ $errors->first('address') }}</span>
                                @enderror
                            </div>
                            <div class="map-container">
                                <div id="map" style="width:100%;height:300px;"></div>
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

    {{-- ck editor --}}
    <script type="text/javascript" src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('ckeditor/adapters/jquery.js') }}"></script>
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $(document).on('change', "input[class='check_daily']", function() {
            if ($(this).is(':checked')) {
                var day = $(this).attr('data-day');
                $('#'+day+'_from').data('daterangepicker').setStartDate('00:00');
                $('#'+day+'_to').data('daterangepicker').setStartDate('23:59');
                $('#'+day+'_action').val(1).change();
            }else {
                var day = $(this).attr('data-day');
                $('#'+day+'_from').data('daterangepicker').setStartDate('00:00');
                $('#'+day+'_to').data('daterangepicker').setStartDate('00:00');
                $('#'+day+'_action').val(0).change();
            }
        });
        {{-- disable enter submit on search input --}}
        $(document).on('keyup keypress', 'form input#address', function(e) {
            if(e.which == 13) {
                e.preventDefault();
                return false;
            }
        });
        $(`.staff-container`).on('click', '.btn-add', function(e)
        {
            e.preventDefault();
            var controlForm = $('.staff-container:first'),
                currentEntry = $(this).parents('.staff-entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);
            newEntry.find('input[name="staff[]"]').val('');
            controlForm.find('.staff-entry:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="fa fa-minus"></span>');
        }).on('click', '.btn-remove', function(e)
        {
            $(this).parents('.staff-entry:first').remove();

            e.preventDefault();
            return false;
        });

        $(`.offers-container`).on('click', '.btn-add', function(e)
        {
            e.preventDefault();
            var controlForm = $('.offers-container:first'),
                currentEntry = $(this).parents('.offers-entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);
            newEntry.find('input[name="offers[]"]').val('');
            controlForm.find('.offers-entry:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="fa fa-minus"></span>');
        }).on('click', '.btn-remove', function(e)
        {
            $(this).parents('.offers-entry:first').remove();

            e.preventDefault();
            return false;
        });

        $(`.services-container`).on('click', '.btn-add', function(e)
        {
            e.preventDefault();
            var controlForm = $('.services-container:first'),
                currentEntry = $(this).parents('.services-entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);
            newEntry.find('input[name="services[]"]').val('');
            controlForm.find('.services-entry:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="fa fa-minus"></span>');
        }).on('click', '.btn-remove', function(e)
        {
            $(this).parents('.services-entry:first').remove();

            e.preventDefault();
            return false;
        });

        $(`.facilities-container`).on('click', '.btn-add', function(e)
        {
            e.preventDefault();
            var controlForm = $('.facilities-container:first'),
                currentEntry = $(this).parents('.facilities-entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);
            newEntry.find('input[name="facilities[]"]').val('');
            controlForm.find('.facilities-entry:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="fa fa-minus"></span>');
        }).on('click', '.btn-remove', function(e)
        {
            $(this).parents('.facilities-entry:first').remove();

            e.preventDefault();
            return false;
        });



        // add images
        $(`.image-container`).on('click', '.btn-add', function(e)
        {
            e.preventDefault();
            var controlForm = $('.image-container:first'),
                currentEntry = $(this).parents('.entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);
            newEntry.find('input').val('');
            newEntry.find('img').attr("src","{{asset('dist/img/default_product.jpeg')}}");
            controlForm.find('.entry:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="fa fa-minus"></span>');
        }).on('click', '.btn-remove', function(e)
        {
            $(this).parents('.entry:first').remove();

            e.preventDefault();
            return false;
        });

        $('.image-container').on('change', "input[type='file']", function () {
            readURL(this);
        });
        // add images ENDS

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(input).closest("div.entry").find('img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // get sub-categories
        $('select#category_id').on('change', function() {
            if (this.value == '') {
                getSubCategory();
            }else{
                getSubCategory(this.value);
            }
        });

        function getSubCategory(id=null, selected = null) {
            $.ajax({
                type:'POST',
                url:`{{route('admin.item.getSubCategory')}}`,
                data:{id},
                success:function(data){
                    if(data.status)
                        storeSubCategory(data.data,selected);
                    else errorResponse(data.message);
                },
                error: function(data){
                    errorResponse();
                }
            });
        }

        function storeSubCategory(data,selected) {
            $('#subcategory_id').empty();
            var options = `<option value="">{{__('Select Sub Category')}}</option>`;
            $.each(data, function( index, value ) {
                options = options+`<option ${selected == value.id? 'selected':''} value="${value.id}">${value.name}</option>`;
            });
            $('#subcategory_id').append(options);
        }

        $(document).ready(function () {
            $( 'textarea.editor' ).ckeditor();
            CKEDITOR.config.allowedContent = true;
            // CKEDITOR.config.fullPage = true;
            $('#subcategory_id').select2();
            $('#category_id').select2();
            $('#city_id').select2();
            jQuery.validator.addMethod("greaterThan", function(value, element, params) {
                    if (!/Invalid|NaN/.test(moment(value, 'DD-MM-YYYY').toDate())) {
                        return moment(value, 'DD-MM-YYYY').toDate() > moment($(params).val(), 'DD-MM-YYYY').toDate();
                    }
                    return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val()));
                },'Must be greater than {1}.'
            );
            jQuery.validator.addMethod("alphanumeric", function(value, element) {
                    var strng = new RegExp("^\s*([0-9a-zA-Z]*)\s*$");
                    return strng.test(value);
                }, "Please enter alphanumeric characters."
            );
            $('#add-table-category').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    phone: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    city_id: {
                        required: true,
                    },
                    category_id: {
                        required: true,
                    },
                    subcategory_id: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    status: {
                        required: true,
                    },
                    'images[]': {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "{{__('Please enter a name')}}",
                    },
                    address: {
                        required: "{{__('Please enter a address')}}",
                    },
                    phone: {
                        required: "{{__('Please enter a phone')}}",
                    },
                    description: {
                        required: "{{__('Please enter a description')}}",
                    },
                    category_id: {
                        required: "{{__('Please select a category')}}",
                    },
                    subcategory_id: {
                        required: "{{__('Please select a category')}}",
                    },
                    status: {
                        required: "{{__('Please select a status')}}",
                    },
                    city_id: {
                        required: "{{__('Please select a city')}}",
                    },
                    'images[]': {
                        required: "Please select a image",
                    }

                },
                invalidHandler: function(form, validator) {
                    var btn = $('#add-table-category').find('button[type="submit"]');
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
    <script>
        var latInput = $('#latInput');
        var lngInput = $('#lngInput');
        var addressInput = $('#address');
        var cityInput = $('#city');
        var marker;

        {{-- disable enter submit on search input --}}
        $(document).on('keyup keypress', 'form input#address', function(e) {
            if(e.which == 13) {
                e.preventDefault();
                return false;
            }
        });

        var geocoder;
        function initAutocomplete() {
            geocoder = new google.maps.Geocoder();
            var map = new google.maps.Map(document.getElementById('map'), {
                center: currentPos,
                zoom: 8,
                mapTypeId: 'roadmap'
            });

            var icon = {
                url: "https://maps.gstatic.com/mapfiles/place_api/icons/geocode-71.png",
                size: new google.maps.Size(200, 200),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(25, 50),
                scaledSize: new google.maps.Size(50, 50)
            };

            marker = new google.maps.Marker({
                map: map,
                icon: icon,
                position: currentPos,
            });

            // Create the search box and link it to the UI element.
            var input = document.getElementById('address');
            var searchBox = new google.maps.places.SearchBox(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });
            // click event on map
            google.maps.event.addListener(map, 'click', function(e) {
                if (marker) {
                    marker.setMap(null);
                }

                marker = new google.maps.Marker({
                    map: map,
                    icon: icon,
                    position: e.latLng,
                    draggable:true,
                });
                updateAddressInput(e.latLng);
                google.maps.event.addListener(marker, 'dragend', function(marker){
                    var latLng = marker.latLng;
                    updateAddressInput(latLng);
                });
            });
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    latInput.val('');
                    lngInput.val('');
                    return;
                }
                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        errorResponse('Unable to get location');
                        latInput.val('');
                        lngInput.val('');
                        console.log("Returned place contains no geometry");
                        return;
                    }

                    if (marker) {
                        marker.setMap(null);
                    }
                    marker = new google.maps.Marker({
                        map: map,
                        icon: icon,
                        title: place.name,
                        position: place.geometry.location,
                        draggable:true,
                    });

                    google.maps.event.addListener(marker, 'dragend', function(marker){
                        var latLng = marker.latLng;
                        updateAddressInput(latLng);
                    });

                    // save lat and lng
                    latInput.val(place.geometry.location.lat());
                    lngInput.val(place.geometry.location.lng());

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }
        function updateAddressInput(latLng) {
            currentLatitude = latLng.lat();
            currentLongitude = latLng.lng();
            latInput.val(currentLatitude);
            lngInput.val(currentLongitude);
            geocodePosition(latLng);
        }

        function geocodePosition(pos) {
            geocoder.geocode({
                latLng: pos
            }, function(responses) {
                if (responses && responses.length > 0) {
                    if (responses[0] && responses[0].formatted_address) {
                        var formatted_address = responses[0].formatted_address;
                        addressInput.val(formatted_address);
                    }
                } else {
                    errorResponse('Cannot determine address at this location.');
                }
            });
        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{\Config::get('constant.google_map_key')}}&libraries=places&callback=initAutocomplete" async defer></script>
@endsection
