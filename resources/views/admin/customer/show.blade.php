@extends('layouts.app')

@section('header')
    {{-- fancy box --}}
    <link rel="stylesheet" type="text/css" href="{{ url('/FancyBox/jquery.fancybox.min.css')}}">
    <script src="{{ asset('FancyBox/jquery.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('FancyBox/jquery.fancybox.min.js')}}"></script>
    {{-- fancy box ENDS --}}
@endsection

@section('content')
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
                                    {{($customer)?$customer->name:''}}
                                    @if($customer && $customer->email_verified_at != null)
                                        <i title="Verified Email" class="lead fa fa-check-circle text-success"></i>
                                    @endif
                                </td>
                                <th>{{__('Phone')}} :</th>
                                <td>
                                    {{($customer)?$customer->phone:''}}
                                </td>
                            </tr>
                            <tr>
                                <th>{{__('Email')}} :</th>
                                <td>
                                    {{($customer)?$customer->email:''}}
                                </td>
                                <th>{{__('City')}} :</th>
                                <td>
                                    {{($customer->city_id)?$customer->city->name:''}}
                                </td>
                            </tr>
                            <tr>
                                <th>{{__('Country')}} :</th>
                                <td>
                                    {{($customer->country_id)?$customer->country->name:''}}
                                </td>
                                <th>{{__('Status')}} :</th>
                                <td>
                                    @switch($customer->status)
                                        @case(0)
                                        <span class="badge badge-danger">{{__('InActive')}}</span>
                                        @break
                                        @case(1)
                                        <span class="badge badge-success">{{__('Active')}}</span>
                                        @break
                                        @default
                                        {{__('None')}}
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th>{{__('Profile Image')}} :</th>
                                <td colspan="3">
                                    <a href="{{($customer->profile_image)?asset($customer->profile_image):asset('dist/img/default_product.jpeg')}}" data-fancybox="gallery">
                                        <img class="img-thumbnail" width="200" height="350" src="{{($customer->profile_image)?asset($customer->profile_image):asset('dist/img/default_product.jpeg')}}" />
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div><!-- /.container-fluid -->
@endsection


