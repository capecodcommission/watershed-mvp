<meta name="csrf-token" id="token" content="{{ csrf_token() }}">

<div class="modal-wrapper">
	<div class="modal-content"></div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$('#closeModal').on('click', function(e) {
			e.preventDefault()
			$('.modal-wrapper').hide();
			$('#fertMan')
				.css({'pointer-events': 'auto'})
				.css({'cursor': 'pointer'});
		})
    })
</script>