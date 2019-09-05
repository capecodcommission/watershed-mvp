<meta name="csrf-token" id="token" content="{{ csrf_token() }}">

<div class="modal-wrapper">
	<div class="modal-content">
		<button id = "closeModal"><i class = 'fa fa-times'></i></button>
		<p>Hello World</p>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$('#closeModal').on('click', function(e) {
			e.preventDefault()
			$('.modal-wrapper').hide()
		})
    })
</script>