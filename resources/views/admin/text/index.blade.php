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
                <h3 class="card-title" style="float: right">{{__('List Texts')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-hover">
                <thead>
                    <tr>
                      <th>{{__('SR')}}</th>
                      <th>{{__('Text')}}</th>
                      <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($texts as $key => $text)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$text->stext}}</td>

                        <td> <a class="btn btn-info btn-sm" title="EDIT" href="{{route('admin.text.edit', ['text' => $text])}}"><i class="fa fa-edit"></i> {{__('Edit')}} </a> <button class="btn btn-danger btn-sm" title="DELETE" onclick="confirmAlert(`{{route('admin.text.destroy', ['text' => $text])}}`)"><i class="fa fa-trash"></i> {{__('Delete')}} </button>
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
                    "next": '&#8594;', // or '???'
                    "previous": '&#8592;' // or '???'
                }

            }
        });
    });
</script>
@endsection
