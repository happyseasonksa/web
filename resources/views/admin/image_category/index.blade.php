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
      <div class="col-md-12" style="text-align: right;">
        <!-- jquery validation -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title" style="float: right">{{__('List Image Category')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                    <tr>
                      <th>{{__('SR')}}</th>
                      <th>{{__('Name')}}</th>
                      <th>{{__('Status')}}</th>
                      <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorys as $key => $category)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$category->name}}</td>
                        <td>
                          @if($category->status)
                            <span class="badge badge-success">{{__('Active')}}</span>
                          @else
                            <span class="badge badge-danger">{{__('InActive')}}</span>
                          @endif
                        </td>
                        <td><a class="btn @if($category->status) btn-danger @else btn-success @endif btn-sm" title="{{($category->status)?__('InActive'):__('Active')}}" href="{{route('admin.image-category.status.toggle', ['category' => $category])}}">{{($category->status)?__('InActive'):__('Active')}} </a> <a class="btn btn-secondary btn-sm" title="VIEW" href="{{route('admin.image-category.show', ['category' => $category])}}"><i class="fa fa-info-circle"></i> {{__('View')}} </a> <a class="btn btn-info btn-sm" title="EDIT" href="{{route('admin.image-category.edit', ['category' => $category])}}"><i class="fa fa-edit"></i> {{__('Edit')}} </a> <button class="btn btn-danger btn-sm" title="DELETE" onclick="confirmAlert(`{{route('admin.image-category.destroy', ['category' => $category])}}`)"><i class="fa fa-trash"></i> {{__('Delete')}} </button>
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
