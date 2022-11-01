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
                <h3 class="card-title" style="float: right">{{__('List Contact History')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-hover" style="width:100%;">
                <thead>
                    <tr>
                      <th>{{__('SR')}}</th>
                      <th>{{__('User')}}</th>
                      <th>{{__('Provider')}}</th>
                      <th>{{__('Item')}}</th>
                      <th>{{__('Contact Type')}}</th>
                      <th>{{__('Created At')}} </th>
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

    $( document ).ajaxComplete(function(event,xhr,settings) {
      if (settings.url) {
          var url = settings.url.split('?')[0];
          if (url !== "{{route('admin.getNotificationData')}}") {
              runShowMore();
          }
      }
    });

    function runShowMore() {
      var maxLength = 30;
      $(".showmoreText").each(function(i,v){
          var myStr = $(v).text();
          if(myStr.length > maxLength){
              var newStr = myStr.substring(0, maxLength);
              var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
              $(v).empty().html(newStr);
              $(v).append(' <a href="javascript:void(0);" class="text-primary read-more">read more...</a>');
              $(v).append('<span class="more-text">' + removedStr + '</span>');
          }
      });
      $(".read-more").click(function(){
          var total = $(this).parent(".showmoreText").text();
          total = total.replace("read more...", "");
          simpleTextAlert("",total);
      });
    }

    $(function () {
        $('#example2').DataTable({
          processing: false,
          serverSide: true,
          info : false,
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
          "autoWidth": false,
          ajax: "{{ route('admin.contact-history.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'name', name: 'name'},
              {data: 'admin', name: 'admin'},
              {data: 'item', name: 'item'},
              // {data: 'address', name: 'address'},
              // {
              //   data: "message",
              //   width: "300px",
              //   "render": function (row) {
              //       return (row)?`<span class="showmoreText">${row}</span>`:'';
              //   }
              // },
              {data: 'contact', name: 'contact'},
              {data: 'creation_date', name: 'creation_date'},
              {data: 'action', name: 'action'},

          ]
        });
    });
</script>
@endsection
