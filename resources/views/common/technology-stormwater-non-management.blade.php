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
			<img src="{{$_ENV['CCC_ICONS_SVG'].$tech->icon}}">
			<span>Click icon for more info.</span>
		</a>
		<div class="blade_slider" title="Enter number of {{$tech->unit_metric}} to be treated.">
			<button title="Select Location" class="blade_button" id="select_area">Select Location</button>
			<label id="unit_metric_label" style="display:none;">Enter number of {{$tech->unit_metric}} to be treated:</label>
			<input v-model="uMetric" type="number" id="unit_metric" name="unit_metric" style="display:none;">
		</div>
		<button title="Apply Strategy" class="blade_button" id="applytreatment" v-show="uMetric > 0" >Apply</button>
	</div>

<!-- Import the vue data and computed properties -->
<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {

		// Retrieve treatment id, icon from props
		icon = '{{$tech->icon}}';
		techId = '{{$tech->technology_id}}';
		$('#select_area').data('icon', icon.toString());
		$('#select_area').data('techId', techId);
		
		// On click of the 'Apply' button, wrap the logic in a fert/storm conditional, set the percent variable for
		// reduction selection by user, set the url to use to send an ajax GET method to route the user input slider
		// value for the technology percent
		$('#applytreatment').on('click', function(e) {
			e.preventDefault();
			let applyTreatmentButton = document.getElementById("applytreatment");
			let setapplyTreatmentButtonStyling = applyTreatmentButton.setAttribute("style", "display:none;");
			var units = $('#unit_metric').val();

			// Create and trigger API route url from parsed properties
			var url = "{{url('/apply_storm')}}" + '/' + units + '/' + techId;
			destroyModalContents();
			$(".modal-loading").toggle();
			$('.modal-wrapper').toggle();

			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(treatment_id) {
				$(".modal-loading").toggle();
				destroyModalContents();
				$( "#update" ).trigger( "click" );
				addTreatmentIdToGraphic(treatment_id);
				addToStack(treatment_id, icon, techId);
			});
		});

		// Handle on-click event for selecting a location
		$('#select_area').on('click', function(f) {
			f.preventDefault();
			toggleUI();
			deleteGraphic();
			map.setInfoWindowOnClick(false);
			editGeoClicked = 1;
			tb.activate('point');
		});
 	});
</script>