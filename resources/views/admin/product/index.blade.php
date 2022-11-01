@extends('layouts.app')
{{-- @section('title','Company') --}}

@section('header')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')
{{-- image modal --}}
<div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{__('Images')}}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row"></div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
{{-- image modal ENDS --}}
<div class="container-fluid">

    @if($provider_id != null)
    <div class="row mt-3">
      <!-- left column -->
      <div class="col-md-12">
        <!-- jquery validation -->
        <div class="card card-primary" style="text-align: right;">
            <div class="card-header">
                <h3 class="card-title" style="float: right">{{__('List Items')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-hover dt-responsive nowrap">
                <thead>
                    <tr>
                      <th>{{__('SR')}}</th>
                      <th>{{__('Name')}}</th>
                      <th>{{__('Category Name')}}</th>
                      <th>{{__('Address')}}</th>
                      <th>{{__('Images')}}</th>
                      <th>{{__('Status')}}</th>
                      <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $key => $item)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td><a class="text-primary" title="VIEW" href="{{route('admin.item.show', ['item' => $item])}}"> {{$item->name}} </a></td>
                        <td>{{($item->category)?$item->category->name:'None'}}</td>
                        <td>{{$item->address}}</td>
                        <td><button type="button" class="btn btn-sm btn-info" onclick="showImages({{$item->id}})">{{__('Show')}}</button></td>
                        <td>
                          @if($item->status)
                            <span class="badge badge-success">{{__('Active')}}</span>
                          @else
                            <span class="badge badge-danger">{{__('InActive')}}</span>
                          @endif
                        </td>
                        <td>
                          @if(Auth::user()->checkAdminAccess('item', null, 'update'))
                          <a class="btn @if($item->status) btn-danger @else btn-success @endif btn-sm" title="{{($item->status)?__('InActive'):__('Active')}}" href="{{route('admin.item.status.toggle', ['item' => $item])}}">{{($item->status)?__('InActive'):__('Active')}} </a>
                          @endif
                          <a class="btn btn-secondary btn-sm" title="{{__('VIEW')}}" href="{{route('admin.item.show', ['item' => $item])}}"><i class="fa fa-info-circle"></i> {{__('View')}} </a>
                          @if(Auth::user()->checkAdminAccess('item', null, 'update'))
                          <a class="btn btn-info btn-sm" title="{{__('EDIT')}}" href="{{route('admin.item.edit', ['item' => $item])}}"><i class="fa fa-edit"></i> {{__('Edit')}} </a>
                          @endif
                          @if(Auth::user()->checkAdminAccess('item', null, 'delete'))
                          <button class="btn btn-danger btn-sm" title="{{__('DELETE')}}" onclick="confirmAlert(`{{route('admin.item.destroy', ['item' => $item])}}`)"><i class="fa fa-trash"></i> {{__('Delete')}} </button>
                          @endif
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
    @endif
</div><!-- /.container-fluid -->
@endsection

@section('js-body')
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script>


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


     function showImages(id) {
        $.ajax({
            type:'POST',
            url:`{{route('admin.item.showImages')}}`,
            data:{id},
            success:function(data){
                if(data.status)
                    if (data.data.length > 0) {
                      showImageModal(data.data);
                    }else{
                      errorResponse("No image found!")
                    }
                else errorResponse(data.message);
            },
            error: function(data){
                errorResponse();
            }
        });
    }

    function showImageModal(images) {
        var container = $('#modal-lg .row');
        container.empty();
        var options = ``;
        $.each(images, function( index, value ) {
          var image_link = value.source;
            var expression = /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/gi;
            var regex = new RegExp(expression);

            if (image_link.match(regex)) {
                options = options+`<div class="col-sm-4"><img width="100" height="100" src="${image_link}"/></div>`;
            } else {
                options = options+`<div class="col-sm-4"><img width="100" height="100" src="{{asset('')}}${image_link}"/></div>`;
            }
        });
        container.append(options);
        $('#modal-lg').modal('show');
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
