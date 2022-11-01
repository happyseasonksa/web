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
            <div class="card-header" style="float: right;">
                <h3 class="card-title" style="float: right;">{{__('List SubCategories')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              @include('admin.subcategory.table')
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    function getCategory(id=null, selected = null) {
        $.ajax({
            type:'POST',
            url:`{{route('admin.sub-category.getCategory')}}`,
            data:{id},
            success:function(data){
                if(data.status)
                    storeCategory(data.data,selected);
                else errorResponse(data.message);
            },
            error: function(data){
                errorResponse();
            }
        });
    }

    function storeCategory(data,selected) {
        $.each(data, function( index, value ) {
          categoryOptions[value.id] = value.name;
        });
    }

    function importCsv() {
      Swal.fire({
        title: '{{__('Select category')}}',
        input: 'select',
        inputOptions: categoryOptions,
        showCancelButton: false,
        confirmButtonText: '{{__('Import')}}',
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
      }).then((result) => {
        if (result.value) {
          $('#category_id').val(result.value);
          event.preventDefault();
          document.getElementById('csv-upload-form').submit();
        }
      })
    }
    $('select#restaurant_id').on('change', function() {
      if (this.value != '') {
        window.location = "{{url('/admin/sub-category')}}?restaurant_id="+this.value;
      }
    });
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
