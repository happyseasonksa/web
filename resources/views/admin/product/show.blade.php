@extends('layouts.app')
@section('header')
{{-- fancy box --}}
<link rel="stylesheet" type="text/css" href="{{ url('/FancyBox/jquery.fancybox.min.css')}}">
<script src="{{ asset('FancyBox/jquery.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('FancyBox/jquery.fancybox.min.js')}}"></script>
{{-- fancy box ENDS --}}
@endsection
@section('content')
{{-- get allergy details --}}

{{-- get allergy details END --}}
<div class="container-fluid">
    <div class="row mt-3">
        <!-- left column -->
        <div class="col-md-12">
        <!-- jquery validation -->
        <div class="card card-primary" style="text-align: right;">
            <div class="card-header">
                <h3 class="card-title" style="float: right">{{__('Details')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <table class="table table-striped show-table">
                  <tbody>
                    <tr>
                        <th>{{__('Name')}} :</th>
                        <td>
                           {{$item->name}}
                        </td>
                        <th>{{__('Category')}} :</th>
                        <td>
                            {{($item->category)?$item->category->name:'Deleted'}}
                        </td>
                        <th>{{__('SubCategory')}} :</th>
                        <td>
                            {{($item->subcategory)?$item->subcategory->name:'Deleted'}}
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Address')}} :</th>
                        <td>
                           {{$item->address}}
                        </td>
                        <th>{{__('Open Times')}} :</th>
                        <td>
                            {{$item->openTimes}}
                        </td>
                        <th>{{__('Website')}} :</th>
                        <td>
                            {{$item->website}}
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Phone')}} :</th>
                        <td>
                           {{$item->phone}}
                        </td>
                        <th>{{__('Facebook Link')}} :</th>
                        <td>
                            {{$item->facebook}}
                        </td>
                        <th>{{__('Twitter Link')}} :</th>
                        <td>
                            {{$item->twitter}}
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Instagram Link')}} :</th>
                        <td>
                           {{$item->instagram}}
                        </td>
                        <th>{{__('Whatsapp Link')}} :</th>
                        <td>
                            {{$item->whatsapp}}
                        </td>
                        <th>{{__('Status')}} :</th>
                        <td>
                            @switch($item->status)
                                @case(0)
                                    <span class="badge badge-danger">{{__('InActive')}}</span>
                                    @break
                                @case(1)
                                    <span class="badge badge-success">{{__('Active')}}</span>
                                    @break
                                @default
                                    None
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <strong>{{__('Description')}} : </strong> {{$item->description}}
                        </td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-md-12" style="text-align: right;">
            <!-- jquery validation -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title" style="float: right;">{{__('Services')}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example4" class="table table-bordered table-hover dt-responsive nowrap">
                        <thead>
                        <tr>
                            <th>{{__('SR')}}</th>
                            <th>{{__('Service')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($item->services())
                            @foreach($item->services() as $key => $service)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        {{$service->service}}
                                    </td>
                                 </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12" style="text-align: right;">
            <!-- jquery validation -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title" style="float: right;">{{__('Staff')}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example4" class="table table-bordered table-hover dt-responsive nowrap">
                        <thead>
                        <tr>
                            <th>{{__('SR')}}</th>
                            <th>{{__('Service')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($item->staff())
                            @foreach($item->staff() as $key => $service)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        {{$service->service}}
                                    </td>
                                 </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12" style="text-align: right;">
            <!-- jquery validation -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title" style="float: right;">{{__('Facilities')}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example4" class="table table-bordered table-hover dt-responsive nowrap">
                        <thead>
                        <tr>
                            <th>{{__('SR')}}</th>
                            <th>{{__('Service')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($item->facilities())
                            @foreach($item->facilities() as $key => $service)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        {{$service->service}}
                                    </td>
                                 </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12" style="text-align: right;">
            <!-- jquery validation -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title" style="float: right;">{{__('Offers')}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example4" class="table table-bordered table-hover dt-responsive nowrap">
                        <thead>
                        <tr>
                            <th>{{__('SR')}}</th>
                            <th>{{__('Service')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($item->offers())
                            @foreach($item->offers() as $key => $service)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        {{$service->service}}
                                    </td>
                                 </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>



        <div class="row mt-3">
        <!-- left column -->
        <div class="col-md-12">
        <!-- jquery validation -->
        <div class="card card-primary" style="text-align: right;">
            <div class="card-header">
                <h3 class="card-title" style="float: right">{{__('Images')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="row mt-1">
                @if(count($item->images) > 0)
                    @foreach($item->images as $image)
                        <div class="col-md-3 input-group form-group">
                            <a href="{{asset($image->source)}}" data-fancybox="gallery">
                              <img class="img-thumbnail" width="200" height="350" src="{{asset(getResizeImage($image->source))}}" />
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12 text-center">{{__('No image available')}}</div>
                @endif
              </div>
            </div>
        </div>
        <!-- /.card -->
        </div>
    </div>
    <label style="float: right;">{{__('Location')}}</label>
    <div class="map-container">
        <div id="map" style="width:100%;height:300px;"></div>
    </div>
    <div class="row mt-3">
    <div class="col-md-12" style="text-align: right;">
        <!-- jquery validation -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title" style="float: right;">{{__('List Comments')}}</h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example4" class="table table-bordered table-hover dt-responsive nowrap">
                    <thead>
                    <tr>
                        <th>{{__('SR')}}</th>
                        <th>{{__('Customer')}}</th>
                        <th>{{__('Rating')}}</th>
                        <th>{{__('Comment')}}</th>
{{--                        <th>{{__('Status')}}</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @if($item->reviews)
                        @foreach($item->reviews as $key => $review)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>
                            @if($review->user)
                             <a href="{{route('admin.customer.show',['customer'=>$review->user])}}" class="text-primary">{{$review->user->name}}</a>
                            @else
                            {{__('Deleted')}}
                            @endif
                        </td>
                        <td>
                            @for ($i=1;$i<=(5-$review->star);$i++)
                                <span class="fa fa-star"></span>
                            @endfor
                            @for ($i=1;$i<=$review->star;$i++)
                            <span class="fa fa-star text-yellow"></span>
                            @endfor

                        </td>
                        <td>
                            {{$review->comment}}
                        </td>
{{--                        <td>--}}
{{--                            @if(isset($review->status) && $review->status == true)--}}
{{--                            {{__('Active')}}--}}
{{--                            @else--}}
{{--                            {{__('InActive')}}--}}
{{--                            @endif--}}
{{--                        </td>--}}
                    </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->
    </div>
    </div>

</div><!-- /.container-fluid -->
@endsection
@section('js-body')
    <script>
        @if ($item->lat && $item->lng)
        var myLatLng = {lat: parseFloat("{{$item->lat}}"), lng: parseFloat("{{$item->lng}}")};
        @else
        var myLatLng = {lat: -33.8688, lng: 151.2195};
        @endif
        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: myLatLng,
                zoom: 14,
                mapTypeId: 'roadmap'
            });

            var icon = {
                url: "https://maps.gstatic.com/mapfiles/place_api/icons/geocode-71.png",
                size: new google.maps.Size(200, 200),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(25, 50),
                scaledSize: new google.maps.Size(50, 50)
            };

            @if ($item->lat && $item->lng)

                marker = new google.maps.Marker({
                map: map,
                icon: icon,
                title: "{{$item->address}}",
                position: myLatLng,
            });
            @endif
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{\Config::get('constant.google_map_key')}}&libraries=places&callback=initAutocomplete" async defer></script>
@endsection

@section('js-body')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script>
        $(function () {
            $('#example4').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info" : false,
                "oLanguage": {
                    "sSearch": "{{__('Search')}}"
                },
                "language": {
                    "emptyTable": "{{__('No data available in table')}}",
                    "paginate": {
                        "next": '&#8594;', // or '→'
                        "previous": '&#8592;' // or '←'
                    }

                },
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endsection
