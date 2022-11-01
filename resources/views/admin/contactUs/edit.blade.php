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
                        <h3 class="card-title" style="float: right">{{__('Reply')}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="POST" action="{{route('admin.contactUs.update',['id'=> $contactUs->id])}}" role="form" enctype="multipart/form-data" id="add-workshop">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="reply">{{__('Reply')}} <span class="text-red">*</span></label>
                                <input type="text" class="form-control @error('name'){{'is-invalid'}}@enderror" name="reply" value="{{ old('reply')}}" placeholder="{{__('Enter Reply')}}">
                                @error('reply')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('reply') }}</span>
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

        //Date range picker
        $("#start_at").daterangepicker({
            timePicker : true,
            timePicker24Hour : true,
            timePickerIncrement : 1,
            singleDatePicker:true,
            timePickerSeconds : false,
            locale : {
                format : 'HH:mm'
            }
        }).on('show.daterangepicker', function(ev, picker) {
            picker.container.find(".calendar-table").hide();
        });
        $("#end_at").daterangepicker({
            timePicker : true,
            timePicker24Hour : true,
            timePickerIncrement : 1,
            singleDatePicker:true,
            timePickerSeconds : false,
            locale : {
                format : 'HH:mm'
            }
        }).on('show.daterangepicker', function(ev, picker) {
            picker.container.find(".calendar-table").hide();
        });

        $(document).ready(function () {
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
            $('#add-workshop').validate({
                rules: {
                    reply: {
                        required: true,
                    },
                },
                messages: {
                    reply: {
                        required: "Please enter a reply",
                    },
                },
                invalidHandler: function(form, validator) {
                    var btn = $('#add-workshop').find('button[type="submit"]');
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
                        if(responses[0].address_components.length == 4){
                            selcted=responses[0].address_components[1].long_name;
                            //console.log(responses[0].address_components)
                            cityInput.val(selcted)
                            //console.log(selcted)
                        }
                        if(responses[0].address_components.length == 5){
                            selcted=responses[0].address_components[2].long_name;
                            //console.log(responses[0].address_components)
                            cityInput.val(selcted)
                            //console.log(selcted)
                        }
                        if(responses[0].address_components.length == 6){
                            selcted=responses[0].address_components[2].long_name;
                            //console.log(responses[0].address_components)
                            cityInput.val(selcted)
                            //console.log(selcted)
                        }
                        if(responses[0].address_components.length == 7){
                            selcted=responses[0].address_components[2].long_name;
                            //console.log(responses[0].address_components)
                            cityInput.val(selcted)
                            //console.log(selcted)
                        }
                        if(responses[0].address_components.length == 8){
                            selcted=responses[0].address_components[3].long_name;
                            console.log(responses[0].address_components)
                            cityInput.val(selcted)
                            console.log(selcted)

                        }
                    }
                } else {
                    errorResponse('Cannot determine address at this location.');
                }
            });
        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{\Config::get('constant.google_map_key')}}&libraries=places&callback=initAutocomplete" async defer></script>
@endsection
