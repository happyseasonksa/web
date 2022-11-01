@extends('layouts.app')
{{-- @section('title','Company') --}}

@section('header')
{{-- fancy box --}}
<link rel="stylesheet" type="text/css" href="{{ url('/FancyBox/jquery.fancybox.min.css')}}">

{{-- fancy box ENDS --}}
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
                <h3 class="card-title" style="float: right">{{__('List Notification')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-hover">
                <thead>
                    <tr>
                      <th>{{__('SR')}}</th>
                      <th>{{__('Title')}}</th>
                      <th>{{__('Body')}}</th>
                      <th>{{__('Created At')}}</th>
                      <th>{{__('Action')}}</th>
                    </tr>
                </thead>
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
<script type="text/javascript" src="{{ asset('FancyBox/jquery.fancybox.min.js')}}"></script>
{{-- moment js --}}
<script src="{{ asset('plugins/moment/moment.min.js')}}"></script>

<script>
	$(function () {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $('#example2').DataTable({
              processing: false,
              serverSide: true,
              "paging": true,
              "searching": true,
              "ordering": true,
              "info": false,
              "autoWidth": false,
              "scrollX": true,
              "lengthChange": false,
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
              sScrollX: "100%",
              ajax: {
                url: `{{route('admin.getNotificationList')}}`,
                type: 'GET',
              },
              "createdRow": function (row, data, dataIndex) {
                  if (data.is_read == 1) {
                    $(row).addClass('table-highlight');
                  }
              },
              columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: "title"},
                {data: "body"},
                {data: "recieved_at"},
                {
                  data: "action",
                  name: "action",
                  "orderable": false,
                  "seachable": false,
                },
              ]
          });
    });
</script>
@endsection
