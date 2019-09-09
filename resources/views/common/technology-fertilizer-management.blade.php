<!-- Set the title to 'Technology_Strategy' from the dbo.v_Technology_Matrix obtained by 'TechnologyController.php' -->
<!-- Set the popdown up with a header, a body with the technology, a table and reduction rate selection -->
	<div class="blade_container">
		<h4 class="blade_title" title="{{$tech->Technology_Strategy}}">
			{{$tech->Technology_Strategy}}
		</h4>
		<a title="{{$tech->Technology_Strategy}} - Technology Matrix" class="blade_image" href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
			<img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}">
		</a>
		<div class="blade_slider" title="Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.">
			<label>Nutrient Reduction Rate</label>
			<label>@{{fert_percent}}%</label>
			<input type="range" id="{{$type}}-percent"
			  min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="fert_percent" value="{{$tech->Nutri_Reduc_N_High}}" step="1">
		</div>
		<button title="Apply Strategy" class="blade_button" id="applytreatment">Apply</button>
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
			var url = "{{url('/apply_percent')}}" + '/' + percent + '/fert';
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
		});
 	});
</script>