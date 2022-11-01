@extends('layouts.app')

@section('header')
{{-- fancy box --}}
<link rel="stylesheet" type="text/css" href="{{ url('/FancyBox/jquery.fancybox.min.css')}}">
<script src="{{ asset('FancyBox/jquery.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('FancyBox/jquery.fancybox.min.js')}}"></script>
{{-- fancy box ENDS --}}
@endsection

@section('content')
@php
$typesOfAdmins = typeOfAdmins();
@endphp
<div class="container-fluid">
    <div class="row mt-3">
        <!-- left column -->
        <div class="col-md-12">
        <!-- jquery validation -->
        <div class="card card-primary" style="text-align: right;">
            <div class="card-header">
                <h3 class="card-title" style="float: right">{{__('Access Details')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <table class="table table-striped show-table">
                  <tbody>
                    <tr>
                        <th>{{__('Name')}} :</th>
                        <td>
                           {{$admin->name}}
                        </td>
                        <th>{{__('Email')}} :</th>
                        <td>
                            {{$admin->email}}
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Phone')}} :</th>
                        <td>
                            {{$admin->phone}}
                        </td>
                        <th>{{__('Status')}} :</th>
                        <td>
                            @switch($admin->status)
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
                  </tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->
        </div>
    </div>
</div><!-- /.container-fluid -->
@endsection


