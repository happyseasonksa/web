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
                <h3 class="card-title" style="float: right">{{__('List Customers')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-hover dt-responsive nowrap">
                <thead>
                    <tr>
                      <th>{{__('SR')}}</th>
                      <th>{{__('Name')}}</th>
{{--                      <th>Email</th>--}}
                      <th>{{__('Phone')}}</th>
                      <th>{{__('Status')}}</th>
                      <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>

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
            "lengthChange": false,
            "info":false,
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
          processing: false,
          serverSide: true,
          ajax: "{{ route('admin.customer.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'name', name: 'name'},
              //{data: 'email', name: 'email'},
              {data: 'phone', name: 'phone'},
              {data: 'status_show', name: 'status_show'},
              {data: 'action', name: 'action'},

          ]
        });
    });
</script>
@endsection
