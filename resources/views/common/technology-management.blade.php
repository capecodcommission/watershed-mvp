<!-- Set the title to 'Technology_Strategy' from the dbo.v_Technology_Matrix obtained by 'TechnologyController.php' -->
<!-- Set up the HTML for the grid layout as specified in the css -->
	<div class="blade_container">
		<h4 class="blade_title" title="{{$tech->Technology_Strategy}}">
			{{$tech->Technology_Strategy}}
		</h4>
		<a title="{{$tech->Technology_Strategy}} - Technology Matrix" class="blade_image" href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
			<img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}">
		</a>
		<div class="blade_slider" title="Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.">
			<label>Nutrient Reduction Rate</label>
			<label v-if="{{$tech->id == 25}}">@{{fert_percent}}%</label>
			<label v-else="{{$tech->id == 26}}">@{{storm_percent}}%</label>
			<input type="range" id="fert-percent"
			v-if="{{$tech->id == 25}}" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="fert_percent" value="{{$tech->Nutri_Reduc_N_High}}" step="1">
			<input type="range" id="storm-percent"
			v-else="{{$tech->id == 26}}" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="storm_percent" value="{{$tech->Nutri_Reduc_N_High}}" step="1">
		</div>
		<button title="Apply Strategy" class="blade_button" id="applytreatment">Apply</button>
	</div>

<!-- Import the vue data and computed properties -->
<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {
		// Check the state of readyness for applyTreatment, closeWindow & canceltreatment - remove the spinner once ready
		$('div.fa.fa-spinner.fa-spin').remove()
		
		// On click of the 'Apply' button, wrap the logic in a fert/storm conditional, set the percent variable for
		// reduction selection by user, set the url to use to send an ajax GET method to route the user input slider
		// value for the technology percent
		$('#applytreatment').on('click', function(e) {
			e.preventDefault();
			if ("{{$tech->id == 25}}") {
				let percent = $('#fert-percent').val();
				let url = "{{url('/apply_percent')}}" + '/' + percent + '/fert';
				$.ajax({
					method: 'GET',
					url: url
				})
				// Once the GET method is complete, hide the modal, update the subembayments and embayment progresses,
				// set the newtreatment variable and add it to the treatment stack using the popdown generator
				.done(function(msg) {
					$( "#update" ).trigger( "click" );
					let newtreatment = '<li class="technology" data-treatment="' + msg + '">' + '<a href=/edit/' + msg + ' class="popdown">' + '<img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}" alt=""></a></li>';
					$('ul.selected-treatments').append(newtreatment);
					$('ul.selected-treatments li[data-treatment="' + msg + '"] a').popdown();
				});
			}

			else {
				let percent = $('#storm-percent').val();
				let url = "{{url('/apply_percent')}}" + '/' + percent + '/storm';
				$.ajax({
					method: 'GET',
					url: url
				})
				// Once the GET method is complete, hide the modal, update the subembayments and embayment progresses,
				// set the newtreatment variable and add it to the treatment stack using the popdown generator
				.done(function(msg) {
					$( "#update" ).trigger( "click" );
					let newtreatment = '<li class="technology" data-treatment="' + msg + '">' + '<a href=/edit/' + msg + ' class="popdown">' + '<img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}" alt=""></a></li>';
					$('ul.selected-treatments').append(newtreatment);
					$('ul.selected-treatments li[data-treatment="' + msg + '"] a').popdown();
				});
			}
		});
		
		// Clicking the close button: wrap the logic in a fert/storm conditional, set the fert or storm percent values to 0
		$('#closeWindow').on('click', function (e) {
			e.preventDefault();
			if ("{{$tech->id == 25}}") {
				$('#fert-percent').val(0);
			}
			else {
				$('#storm-percent').val(0);
			}
		});
 	});
</script>