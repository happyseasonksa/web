{{-- alert messages --}}
@if(Session::has('success') || Session::has('error'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			@if(Session::has('success'))
			<div class="alert alert-success alert-dismissible">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			  {{Session::get('success')}}
			</div>
			@endif
			@if(Session::has('error'))
			<div class="alert alert-danger alert-dismissible">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			  {{Session::get('error')}}
			</div>
			@endif
		</div>
	</div>
</div>
@endif
{{-- alert messages ENDS --}}   