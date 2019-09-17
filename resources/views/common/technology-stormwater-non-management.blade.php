<!-- Set the title to 'Technology_Strategy' from the dbo.v_Technology_Matrix obtained by 'TechnologyController.php' -->
<!-- Set up the HTML for the grid layout as specified in the css -->
	<div class="blade_container">
		<h4 class="blade_title" title="{{$tech->Technology_Strategy}}">
			{{$tech->Technology_Strategy}}
		</h4>
		<a title="{{$tech->Technology_Strategy}} - Technology Matrix" class="blade_image" href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->TM_ID}}" target="_blank">
			<img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}">
		</a>
		<div class="blade_slider" title="Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.">
			<button title="Select Location" class="blade_button" id="select_area">Select Location</button>
			<label  style="display: none" for="unit_metric"  id="unit_metric_label">Enter number of {{$tech->Unit_Metric}} to be treated:</label>
			<input style="display: none" type="text" id="unit_metric" name="unit_metric">
		</div>
		<button title="Apply Strategy" class="blade_button" id="applytreatment">Apply</button>
	</div>

<!-- Import the vue data and computed properties -->
<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {

		// Retrieve treatment id, icon from props
		icon = '{{$tech->Icon}}';
		techId = '{{$tech->Technology_ID}}';
		$('#select_area').data('icon', icon.toString());
		
		// On click of the 'Apply' button, wrap the logic in a fert/storm conditional, set the percent variable for
		// reduction selection by user, set the url to use to send an ajax GET method to route the user input slider
		// value for the technology percent
		$('#applytreatment').on('click', function(e) {
			e.preventDefault();
				var percent = 0;
				var units = $('#unit_metric').val();
				var subemID =  $('.select > span').data('subemid');
				

				// Create and trigger API route url from parsed properties
				var url = "{{url('/apply_storm')}}" + '/' +  0 + '/' + percent + '/' + units + '/' + subemID + '/' + techId;
				$.ajax({
					method: 'GET',
					url: url
				})
				.done(function(treatment_id) {
					$( "#update" ).trigger( "click" );
					addToStack(treatment_id, '{{$tech->Icon}}');
				});
		});

		// Handle on-click event for selecting a location
		$('#select_area').on('click', function(f) {
			f.preventDefault();
			// destroyModalContents();
			$('.modal-wrapper').hide();
			tb.activate('point');
		});
 	});
</script>