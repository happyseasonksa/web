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
                <h3 class="card-title" style="float: right">{{__('List Access')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-hover dt-responsive nowrap">
                <thead>
                    <tr>
                      <th>{{__('SR')}}</th>
                      <th>{{__('Name')}}</th>
                      <th>{{__('Email')}}</th>
{{--                      <th>{{__('Type')}}</th>--}}
                      <th>{{__('Status')}}</th>
                      <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $typesOfAdmins = typeOfAdmins();
                    @endphp
                    @foreach($admins as $key => $admin)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$admin->name}}</td>
                        <td>{{$admin->email}}</td>
{{--                        <td>{{(isset($typesOfAdmins[$admin->type])?$typesOfAdmins[$admin->type]:__('None'))}}</td>--}}
                        <td>
                          @if($admin && $admin->status)
                            <span class="badge badge-success">{{__('Active')}}</span>
                          @else
                            <span class="badge badge-danger">{{__('InActive')}}</span>
                          @endif
                        </td>
                        <td>
                          @if(Auth::user()->checkAdminAccess('access', null, 'update'))
                          <a class="btn @if($admin && $admin->status) btn-danger @else btn-success @endif btn-sm" title="{{($admin && $admin->status)?'InActive':'Active'}}" href="{{route('admin.access.status.toggle', ['admin' => $admin])}}">{{($admin && $admin->status)?__('InActive'):__('Active')}} </a>
                          @endif
                          <a class="btn btn-secondary btn-sm" title="VIEW" href="{{route('admin.access.show', ['admin' => $admin])}}"><i class="fa fa-info-circle"></i> {{__('View')}} </a>
                          @if(Auth::user()->checkAdminAccess('access', null, 'update'))
                          <a class="btn btn-info btn-sm" title="EDIT" href="{{route('admin.access.edit', ['admin' => $admin])}}"><i class="fa fa-edit"></i> {{__('Edit')}} </a>
                          @endif
                          @if(Auth::user()->checkAdminAccess('access', null, 'delete'))
                          <button class="btn btn-danger btn-sm" title="DELETE" onclick="confirmAlert(`{{route('admin.access.destroy', ['admin' => $admin])}}`)"><i class="fa fa-trash"></i> {{__('Delete')}} </button>
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
          "autoWidth": false,
          "responsive": true,
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

            }
        });
    });
</script>
@endsection
