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
                <h3 class="card-title" style="float: right">{{__('List Group Notifications')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-hover">
                <thead>
                    <tr>
                      <th>{{__('SR')}}</th>
                      <th>{{__('Message')}}</th>
                      <th>{{__('Created At')}}</th>
                      <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifys as $key => $notify)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$notify->body}}</td>
                        <td>{{date('H:i Y-m-d',strtotime($notify->created_at))}}</td>
                        <td> <button class="btn btn-danger btn-sm" title="DELETE" onclick="confirmAlert(`{{route('admin.notification.destroy', ['notification' => $notify->id])}}`)"><i class="fa fa-trash"></i> {{__('Delete')}} </button>
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
