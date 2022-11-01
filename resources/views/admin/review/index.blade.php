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
                <h3 class="card-title" style="float: right;">{{__('List Reviews')}}</h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-bordered table-hover dt-responsive nowrap">
                <thead>
                    <tr>
                      <th>{{__('SR')}}</th>
                      <th>{{__('Item')}}</th>
                      <th>{{__('Customer')}}</th>
                      <th>{{__('Rating')}}</th>
                      <th>{{__('Comment')}}</th>
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


  function starRating(sno){
    var star = '';
    for (var i=1;i<=sno;i++){
      star = star + `<span class="fa fa-star text-yellow"></span>`;
    }
    for (var i=1;i<=(5-sno);i++){
      star = star + `<span class="fa fa-star"></span>`;
    }
    return star;
  }

  text_truncate = function(str, length, ending) {
    if (length == null) {
      length = 100;
    }
    if (ending == null) {
      ending = '...';
    }
    if (str.length > length) {
      return str.substring(0, length - ending.length) + ending;
    } else {
      return str;
    }
  };
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  $(function () {
      var table = $('#example2').DataTable({
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
          processing: false,
          serverSide: true,
          ajax: "{{ route('admin.review.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'item', name: 'item'},
              {data: 'user', name: 'user'},
              {
                data: "star",
                "render": function (row) {
                    var ratingS = Math.ceil(row)*20;
                    return `<div><span class="stars-container stars-${ratingS}">★★★★★</span></div>`;
                    // return starRating(parseInt(row));
                }
              },
              {
                data: "comment",
                "render": function (row) {
                    return (row)?text_truncate(row,50):''
                }
              },
              {data: 'status', name: 'status'},
              {data: 'action', name: 'action'},

          ]
      });
  });
</script>
@endsection
