<table id="example2" class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>{{__('SR')}}</th>
      <th>{{__('Name')}}</th>
      @if(!isset($category))
      <th>{{__('Category Name')}}</th>
      @endif
{{--      <th>{{__('Image')}}</th>--}}
      <th>{{__('Status')}}</th>
      <th>{{__('Action')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($subcategorys as $key => $subcategory)
    <tr>
      <td>{{$key+1}}</td>
      <td>{{$subcategory->name}}</td>
      @if(!isset($category))
      <td>
        @if($subcategory->category)
        <a class="text-primary" title="{{$subcategory->category->name}}" href="{{route('admin.category.show', ['category' => $subcategory->category])}}"> {{$subcategory->category->name}} </a>
        @else
          {{__('Deleted')}}
        @endif
{{--      </td>--}}
{{--            <td>--}}
{{--                <a href="{{($subcategory->image)?asset($subcategory->image):asset('dist/img/default_product.jpeg')}}" data-fancybox="gallery">--}}
{{--                    <img class="img-thumbnail" width="200" height="350" src="{{($subcategory->image)?asset($subcategory->image):asset('dist/img/default_product.jpeg')}}" />--}}
{{--                </a>--}}
{{--            </td>--}}
      @endif
      <td>
        @if($subcategory->status)
        <span class="badge badge-success">{{__('Active')}}</span>
        @else
        <span class="badge badge-danger">{{__('InActive')}}</span>
        @endif
      </td>
      <td>
        @if(Auth::user()->checkAdminAccess('subcategory', null, 'update'))
        <a class="btn @if($subcategory->status) btn-danger @else btn-success @endif btn-sm" title="{{($subcategory->status)?__('InActive'):__('Active')}}" href="{{route('admin.sub-category.status.toggle', ['subcategory' => $subcategory])}}">{{($subcategory->status)?__('InActive'):__('Active')}} </a>
        <a class="btn btn-info btn-sm" title="EDIT" href="{{route('admin.sub-category.edit', ['subcategory' => $subcategory])}}"><i class="fa fa-edit"></i> {{__('Edit')}} </a>
        @endif
        @if(Auth::user()->checkAdminAccess('subcategory', null, 'delete'))
        <button class="btn btn-danger btn-sm" title="DELETE" onclick="confirmAlert(`{{route('admin.sub-category.destroy', ['subcategory' => $subcategory])}}`)"><i class="fa fa-trash"></i> {{__('Delete')}} </button>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>
