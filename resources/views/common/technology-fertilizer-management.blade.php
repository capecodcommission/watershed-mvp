<!-- Set the title to 'Technology_Strategy' from the dbo.v_Technology_Matrix obtained by 'TechnologyController.php' -->
<!-- Set the popdown up with a header, a body with the technology, a table and reduction rate selection -->
<div class="column fertilizer_management_modal">
	<div class="row fertilizer_management_modal_content">
		Shawn@theTop
	</div>
	<div class="row fertilizer_management_modal_content">
		<div class="column=4">Shawn1</div>
		<div class="column=4">Shawn2</div>
		<div class="column=4">Shawn3</div>
	</div>
	<div class="row fertilizer_management_modal_content">
		Shawn@theBottom
	</div>
</div>

<!-- Import the vue data and computed properties -->
<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {
		// Check the state of readyness for applyTreatment, closeWindow & canceltreatment - remove the spinner once ready
		$('div.fa.fa-spinner.fa-spin').remove()
		
		// On Click of treatment icon set the percent variable for the fertilization percent for reduction selection by user
		// and set the url to use to send an ajax GET method to route the user input slider value for the fertilzation percent
		$('#applytreatment').on('click', function(e) {
			e.preventDefault();
			var percent = $('#fert-percent').val();
			var url = "{{url('/apply_percent')}}" + '/' + null + '/' + percent + '/fert';
			$.ajax({
				method: 'GET',
				url: url
			})
			// Once the GET method is complete, format the returned message, route that to the text, hide the popdown, click
			// the update subemebayments progress and embayment progress, set the newtreatment variable and add it to the
			// selected treatments popdown tray at top
			.done(function(msg) {
				$('.modal-wrapper').hide();
				$( "#update" ).trigger( "click" );
				var newtreatment = '<li class="technology" data-treatment="' + msg + '">' + '<a href=/edit/' + msg + ' class="popdown">' + '<img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}" alt=""></a></li>';
				$('ul.selected-treatments').append(newtreatment);
				$('ul.selected-treatments li[data-treatment="' + msg + '"] a').popdown();
			});
		});
		
		// Clicking the close window button: hide the popdown, set the url to cancel the treatment which runs the DELtreatment
		// stored procedure, send an ajax GET method to the url to disassociate the treatment with the parcels and return nothing
		$('#closeWindow').on('click', function (e) {
			e.preventDefault();
			// $('#popdown-opacity').hide();
			$('#fert-percent').val(0);
			$('#fertMan').css({'pointer-events': 'auto'});
		});

		// On clicking cancel treatment, hide the popdown, reset the fertilizer percent back to 0, cancel the treatment in the
		// same fashion as closeWindow and return an empty message
		$('#canceltreatment').on('click', function(e) {
			e.preventDefault();
			// $('#popdown-opacity').hide();
			$('#fert-percent').val(0);
			$('#fertMan').css({'pointer-events': 'auto'});
		});
 	});
</script>