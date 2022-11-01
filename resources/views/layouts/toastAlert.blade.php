<script>
	@if(Session::has('toast-success') || Session::has('toast-error'))
	$(document).Toasts('create', {
        title: `@if(Session::has('toast-success')) {{__('Success Alert')}}! @else {{__('Error Alert')}} @endif`,
        class: `@if(Session::has('toast-success')) bg-success @else bg-danger @endif`,
        autohide: true,
        delay: 3000,
        body: `@if(Session::has('toast-success')) {{Session::get('toast-success')}} @else {{Session::get('toast-error')}} @endif`
    })
    @endif
</script>
