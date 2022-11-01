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
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title" style="float: right;">{{__('Details')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <table class="table table-striped show-table" style="table-layout: fixed;">
                  <tbody>
                    <tr>
                        <th>{{__('Item')}} :</th>
                        <td colspan="3">
                           @if(isset($review->item))
                          <a class="text-primary" title="{{$review->item->name}}" href="{{route('admin.item.show', ['item' => $review->item])}}"> {{$review->item->name}} </a>
                          @else {{__('Deleted')}} @endif
                        </td>
                        <th>{{__('Customer')}} :</th>
                        <td>
                           @if(isset($review->user))
                          <a class="text-primary" title="{{$review->user->name}}" href="{{route('admin.customer.show', ['customer' => $review->user])}}"> {{$review->user->name}} </a>
                          @else {{__('Deleted')}} @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Rating')}} :</th>
                        <td>
                            @for($i=1;$i<=$review->star;$i++)
                              <span class="fa fa-star text-yellow"></span>
                            @endfor
                            @for($i=1;$i<=(5-$review->star);$i++)
                              <span class="fa fa-star"></span>
                            @endfor
                        </td>
                        <th>{{__('Comment')}} :</th>
                        <td>
                           {{$review->comment}}
                        </td>
                        <th>{{__('Status')}} :</th>
                        <td>
                           {{$review->status== 1?__('Active'):__('InActive')}}
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
@section('js-body')
<script>

</script>
@endsection

