@extends('layouts.app')
{{-- @section('title','Company') --}}

@section('header')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
      <!-- left column -->
      <div class="col-md-12">
        <!-- jquery validation -->
        <div class="card card-primary" style="text-align: right;">
            <div class="card-header">
                <h3 class="card-title" style="float: right">{{__('List Cards')}}</h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-hover">
                <thead>
                    <tr>
                      <th>{{__('SR')}}</th>
                      <th>{{__('Name')}}</th>
                      <th>{{__('Image')}}</th>
                      <th>{{__('Status')}}</th>
                      <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cards as $key => $card)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$card->category?$card->category->name:'Deleted'}}</td>
                      <td>
                          <a href="{{($card->image)?asset($card->image):asset('dist/img/default_product.jpeg')}}" data-fancybox="gallery">
                              <img class="img-thumbnail" width="200" height="350" src="{{($card->image)?asset($card->image):asset('dist/img/default_product.jpeg')}}" />
                          </a>
                      </td>
                          <td>
                              @if($card->status)
                                  <span class="badge badge-success">{{__('Active')}}</span>
                              @else
                                  <span class="badge badge-danger">{{__('InActive')}}</span>
                              @endif
                          </td>
                        <td>
                          @if(Auth::user()->checkAdminAccess('card', null, 'update'))
                          <a class="btn @if($card->status) btn-danger @else btn-success @endif btn-sm" title="{{($card->status)?__("InActive"):__("Active")}}" href="{{route('admin.card.status.toggle', ['card' => $card])}}">{{($card->status)?__('InActive'):__('Active')}} </a>
                          @endif
{{--                          <a class="btn btn-secondary btn-sm" title="VIEW" href="{{route('admin.card.show', ['card' => $card])}}"><i class="fa fa-info-circle"></i> {{__("View")}} </a>--}}
                          @if(Auth::user()->checkAdminAccess('card', null, 'update'))
                          <a class="btn btn-info btn-sm" title="EDIT" href="{{route('admin.card.edit', ['card' => $card])}}"><i class="fa fa-edit"></i> {{__("Edit")}} </a>
                          @endif
                          @if(Auth::user()->checkAdminAccess('card', null, 'delete'))
                          <button class="btn btn-danger btn-sm" title="DELETE" onclick="confirmAlert(`{{route('admin.card.destroy', ['card' => $card])}}`)"><i class="fa fa-trash"></i> {{__("Delete")}} </button>
                          @endif
                      </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
        </div>
        <!-- /.card -->
        </div>
      <!--/.col (left) -->
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('js-body')
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script>

    $(function () {
        $('#example2').DataTable({
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
