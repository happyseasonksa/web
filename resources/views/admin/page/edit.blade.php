@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary" style="text-align: right;">
                <div class="card-header">
                    <h3 class="card-title" style="float: right">Preview</h3>
                </div>
                <div class="card-body" id="email_preview">
                    <iframe width="100%" height="400px" frameborder="0" src="{{url('page').'/'.$page->name}}"></iframe>
                </div>
            </div>
          </div>
    </div>
    <div class="row mt-3">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary" style="text-align: right;">
                <div class="card-header">
                    <h3 class="card-title" style="float: right">Update {{ucfirst($page->name)}} </h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{route('admin.page.update', ['id' => $page->id])}}" enctype="multipart/form-data" role="form" id="add-page">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control @error('title'){{'is-invalid'}}@enderror" value="{{$page->title}}" id="title" postholder="Enter Title">
                            @error('title')
                                <span id="title-error" class="error invalid-feedback">{{ $errors->first('title') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea type="text" name="content" class="form-control editor @error('content'){{'is-invalid'}}@enderror" id="content" postholder="Enter Content">{!! $page->content !!}</textarea>
                            @error('content')
                                <span id="content-error" class="error invalid-feedback">{{ $errors->first('content') }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
            </div>
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('js-body')
<!-- jquery-validation -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
{{-- ck editor --}}
<script type="text/javascript" src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('ckeditor/adapters/jquery.js') }}"></script>
<script>
    $(document).ready(function () {
      $( 'textarea.editor' ).ckeditor();
        CKEDITOR.config.allowedContent = true;
        // CKEDITOR.config.fullPage = true;
      $('#add-page').validate({
        rules: {
            // name: {
            //     required: true,
            // },
            title: {
                required: true,
            },
            content: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
            },
            title: {
                required: "Please enter a title",
            },
            content: {
                required: "Please enter a content",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    });
</script>
@endsection
