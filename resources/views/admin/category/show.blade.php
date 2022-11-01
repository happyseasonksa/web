@extends('layouts.app')

@section('header')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')
{{-- get labors nationalities END --}}
<div class="container-fluid">
    <div class="row mt-3">
        <!-- left column -->
        <div class="col-md-12">
        <!-- jquery validation -->
        <div class="card card-primary" style="text-align: right;">
            <div class="card-header">
                <h3 class="card-title" style="float: right">{{__('Car Category Details')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <table class="table table-striped show-table">
                  <tbody>
                    <tr>
                        <th>{{__('Name')}} :</th>
                        <td>
                           {{$category->name}}
                        </td>
                        <th>{{__('Status')}} :</th>
                        <td>
                            @switch($category->status)
                                @case(0)
                                    <span class="badge badge-danger">{{__('InActive')}}</span>
                                    @break
                                @case(1)
                                    <span class="badge badge-success">{{__('Active')}}</span>
                                    @break
                                @default
                                    None
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Category Icon')}} :</th>
                        <td colspan="4">
                            <a href="{{($category->icon)?asset($category->icon):asset('dist/img/default_product.jpeg')}}" data-fancybox="gallery">
                                <img class="img-thumbnail" width="200" height="350" src="{{($category->icon)?asset($category->icon):asset('dist/img/default_product.jpeg')}}" />
                            </a>
                        </td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->
        </div>
        <div class="col-md-12">
        <!-- jquery validation -->
        <div class="card card-primary" style="text-align: right;">
            <div class="card-header">
                <h3 class="card-title" style="float: right">{{__('List SubCategories')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              @include('admin.subcategory.table')
            </div>
        </div>
        <!-- /.card -->
        </div>
    </div>
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

