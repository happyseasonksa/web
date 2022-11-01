@extends('layouts.app')

@section('header')
{{-- fancy box --}}
<link rel="stylesheet" type="text/css" href="{{ url('/FancyBox/jquery.fancybox.min.css')}}">
<script src="{{ asset('FancyBox/jquery.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('FancyBox/jquery.fancybox.min.js')}}"></script>
{{-- fancy box ENDS --}}
@endsection

@section('content')
{{-- get labors nationalities END --}}
<div class="container-fluid">
    <div class="row mt-3">
        <!-- left column -->
        <div class="col-md-12" style="text-align: right;">
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
                        <th>{{__('Title')}} :</th>
                        <td colspan="2">
                           {{$ads->title_en}}
                        </td>
                        <th>{{__('Arabic Title')}} :</th>
                        <td colspan="2">
                           {{$ads->title}}
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Admin')}} :</th>
                        <td colspan="2">
                           {{$ads->admin->name}}
                        </td>
                        <th>{{__('Status')}} :</th>
                        <td colspan="2">
                            @switch($ads->status)
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
                        <th>{{__('Starts At')}} :</th>
                        <td colspan="2">
                            {{$ads->start_at}}
                        </td>
                        <th>{{__('Ends At')}} :</th>
                        <td colspan="2">
                            {{$ads->end_at}}
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Image')}} :</th>
                        <td colspan="5">
                            <a href="{{($ads->image)?asset($ads->image):asset('dist/img/default_product.jpeg')}}" data-fancybox="image">
                                <img class="img-thumbnail" width="auto" height="200" style="height: 200px;" src="{{($ads->image)?asset($ads->image):asset('dist/img/default_product.jpeg')}}" />
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

