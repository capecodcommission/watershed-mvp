<meta name="csrf-token" id="token" content="{{ csrf_token() }}">

<div class="modal-wrapper" style = "display: none;">
	<div class = 'modal-loading'></div>
	<div class="modal-content">
		<button style = 'display: none;' class = 'modal-close' id = "closeModal">
			<i class = 'fa fa-times'></i>
		</button>
		<div id = 'techView'></div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {	

		// Handle click event for closing modal
		$('#closeModal').on('click', function(e) {
			e.preventDefault()
			localCloseModalContent()
		})
    })
</script>