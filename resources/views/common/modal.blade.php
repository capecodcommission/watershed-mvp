<meta name="csrf-token" id="token" content="{{ csrf_token() }}">

<div class="modal-wrapper" style = "display: none;">
	<div class="modal-content">
		<button class = 'modal-close' id = "closeModal">
			<i class = 'fa fa-times'></i>
		</button>
		<div id = 'techView'></div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$('#closeModal').on('click', function(e) {
			e.preventDefault()
			$('.modal-wrapper').hide();
			$('#fertMan')
				.css({'pointer-events': 'auto'})
				.css({'cursor': 'pointer'});
			$('#stormMan')
				.css({'pointer-events': 'auto'})
				.css({'cursor': 'pointer'});
		})
    })
</script>