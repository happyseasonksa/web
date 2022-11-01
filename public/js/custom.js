function confirmAlert(url,title='هل تريد المتابعة؟',text="") {
	Swal.fire({
	  title,
	  showClass: {
	    popup: 'animate__animated animate__zoomIn animate__faster'
	  },
	  hideClass: {
	    popup: 'animate__animated animate__zoomOut animate__faster'
	  },
	  text,
	  icon: 'warning',
	  showCancelButton: true,
	  cancelButtonText: 'إلغاء',
	  confirmButtonColor: '#e66060',
	  cancelButtonColor: '#585757',
	  confirmButtonText: 'حذف'
	}).then((result) => {
	  if (result.value) {
	    location.href = url;
	  }
	})
}

function simpleTextAlert(title="",text="",btnText="Ok") {
	Swal.fire({
	    title,
	  	text,
	  	confirmButtonText: btnText,
	    showClass: {
	    	popup: 'animate__animated animate__zoomIn animate__faster'
		},
		hideClass: {
		    popup: 'animate__animated animate__zoomOut animate__faster'
		},
	})
}

function simpleToastAlert(title="",body="") {
	$(document).Toasts('create', {
        title: `${title}`,
        class: `bg-success`,
        autohide: true,
        delay: 3000,
        body: `${body}`
    })
}

function errorResponse($msg = 'Something went wrong!') {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: $msg,
    });
}

var currentPos = {lat: 24.064760, lng: 45.869500};
function getCurrentLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(saveCurrentLocation);
  }
}

function saveCurrentLocation(position) {
  currentPos = {lat: position.coords.latitude, lng: position.coords.longitude};
}
getCurrentLocation();
