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
        <div class="col-md-12" style="text-align: right">
        <!-- jquery validation -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title" style="float:right;">{{__('Details')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <table class="table table-striped show-table">
                  <tbody>
                    <tr>
                        <th>{{__('Image Category')}} :</th>
                        <td>
                           {{$category->name}}
                        </td>
                        <th>{{__('Status')}} :</th>
                        <td>
                            @switch($category->status)
                                @case(0)
                                    <span class="badge badge-danger">{{__('InActive')}}</span>
                                    @break
                                @case(1)
                                    <span class="badge badge-success">{{__('Active')}}</span>
                                    @break
                                @default
                                    {{__('None')}}}
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

