@extends('layouts.app')
{{-- @section('title','Company') --}}

@section('header')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="modal fade" id="modal-xl">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{__('Preview')}}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row mt-3">
              <div class="col-sm-12" id="preview">

              </div>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="row mt-3">
      <!-- left column -->
      <div class="col-md-12">
        <!-- jquery validation -->
        <div class="card card-primary" style="text-align: right;">
            <div class="card-header">
                <h3 class="card-title" style="float: right">{{__('List Page')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-hover dt-responsive nowrap">
                <thead>
                    <tr>
                      <th>{{__('SR')}}</th>
                      <th>{{__('Title')}}</th>
                      <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $key => $page)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$page->title}}</td>
                        <td>
                          <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modal-xl" onclick="showIframe('{{$page->name}}')">{{__('Preview')}}</button>
                          <a class="btn btn-primary btn-sm" title="Go To" target="_blank" href="{{url('page').'/'.$page->name}}"><i class="fa fa-eye"></i> {{__('Go To')}} </a>
                          <a class="btn btn-info btn-sm" title="EDIT" href="{{route('admin.page.edit', ['page' => $page])}}"><i class="fa fa-edit"></i> {{__('Edit')}} </a>
                        </td>
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
    function showIframe(name) {
      var frame = `<iframe width="100%" height="400px" frameborder="0" src="{{url('page')}}/${name}"></iframe>`
      $('#preview').html(frame);
    }
    $(function () {
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": false,
          "autoWidth": false,
          "responsive": true,
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
        });
    });
</script>
@endsection
