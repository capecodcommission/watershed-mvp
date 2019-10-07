<!-- Set the title to 'technology_strategy' from the dbo.v_Technology_Matrix obtained by 'TechnologyController.php' -->
<!-- Set up the HTML for the grid layout as specified in the css -->
<div class="blade_container">
	<button class="modal-close" id ="closeModal">
		<i class="fa fa-times"></i>
	</button>
	<h4 class="blade_title" title="{{$tech->technology_strategy}}">
		{{$tech->technology_strategy}}
	</h4>
	<a title="{{$tech->technology_strategy}} - Technology Matrix" class="blade_image" href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->TM_ID}}" target="_blank">
		<!-- <img v-show="{{$tech->technology_id == 400}}" src="{{$_ENV['CCC_ICONS_SVG'].'$tech->icon'}}">  TODO: FUTURE SYNTAX -->
		<img v-show="{{$tech->technology_id == 400}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FertilizerManagement.svg'}}">
		<img v-show="{{$tech->technology_id == 401}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterManagement.svg'}}">
	</a>
	<div class="blade_slider" title="Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.">
		<label>Nutrient Reduction Rate</label>
		<label v-if="{{$tech->technology_id == 400}}">@{{fert_percent}}%</label>
		<label v-else="{{$tech->technology_id == 401}}">@{{storm_percent}}%</label>
		<input type="range" id="fert-percent"
		v-show="{{$tech->technology_id == 400}}" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="fert_percent" value="{{$tech->Nutri_Reduc_N_Low}}" step="1">
		<input type="range" id="storm-percent"
		v-show="{{$tech->technology_id == 401}}" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="storm_percent" value="{{$tech->Nutri_Reduc_N_Low}}" step="1">
	</div>
	<button title="Apply Strategy" class="blade_button" id="applytreatment">Apply</button>
</div>

<!-- Import the vue data and computed properties and helpers -->
<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {
		// Check the state of readyness for applyTreatment, closeWindow & canceltreatment - remove the spinner once ready
		$('div.fa.fa-spinner.fa-spin').remove()

		techId = "{{$tech->technology_id}}";
		
		// On click of the 'Apply' button, wrap the logic in a fert/storm conditional, set the percent variable for
		// reduction selection by user, set the url to use to send an ajax GET method to route the user input slider
		// value for the technology percent
		$('#applytreatment').on('click', function(e) {
			let applyTreatmentButton = document.getElementById("applytreatment");
			let setapplyTreatmentButtonStyling = applyTreatmentButton.setAttribute("style", "display:none;");
			e.preventDefault();
			setapplyTreatmentButtonStyling;
			if ("{{$tech->technology_id == 400}}") {
				let percent = $('#fert-percent').val();
				let url = "{{url('/apply_percent')}}" + '/' + percent + '/' + 'fert' + '/' + techId;
				$.ajax({
					method: 'GET',
					url: url
				})
				// Once the GET method is complete, hide the modal, update the subembayments and embayment progresses,
				// set the newtreatment variable and add it to the treatment stack using the popdown generator
				.done(function(treatment_id) {
					destroyModalContents();
					$( "#update" ).trigger( "click" );
					addToStack(treatment_id, '{{$tech->icon}}');
				});
			}

			else {
				let percent = $('#storm-percent').val();
				let url = "{{url('/apply_percent')}}" + '/' + percent + '/' + 'storm' + '/' + techId;
				$.ajax({
					method: 'GET',
					url: url
				})
				// Once the GET method is complete, hide the modal, update the subembayments and embayment progresses,
				// set the newtreatment variable and add it to the treatment stack using the popdown generator
				.done(function(treatment_id) {
					destroyModalContents();
					$( "#update" ).trigger( "click" );
					addToStack(treatment_id, '{{$tech->icon}}');
				});
			}
		});
 	});
</script>