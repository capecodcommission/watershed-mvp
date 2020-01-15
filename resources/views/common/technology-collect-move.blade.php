<head>
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
</head>
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
		</a>
		<div class="blade_slider" title="Select the amount to be treated.">
			<button title="Draw Collection" class="blade_button" id="draw_collection">Draw Collection</button>
			<button title="Select Move Site" class="blade_button" id="select_area" style="display:none;">Select Move Site</button>
			<label id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<input type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Default_ppm}}" step="1" style="display:none;">
			<label id = "collect-label-rate" style="display:none;">@{{collect_rate}} ppm</label>
		</div>
		<!-- TODO: Switch style="display:none;" on the API response after selecting the dump site OR add a v-if="{{$tech->move_site}}" if we need to save to db -->
		<button title="Apply Strategy" class="blade_button" id="applytreatment" style="display:none;">Apply</button>
</div>

<!-- TODO: Add warning that sewered parcels will not be affected -->

<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {
		
		// Append technology id to div to be parsed for polygon creation
		// Obtain the technology ID from the Technology Matrix
		icon = '{{$tech->icon}}'
		techId = '{{$tech->technology_id}}'
		$('#draw_collection').data('techId', techId)
		$('#select_area').data('icon', icon.toString());

		// Disable the map navigation, delete the graphic if re-doing collection drawn, hide the modal,
		// disable the showing of the map's infoWindow when a map click event occurs, activate the map
		// Draw function with a polygon map object
		$('#draw_collection').on('click', function(f) {
			f.preventDefault();
			map.disableMapNavigation();
			deleteGraphic();
			toggleUI();
			map.setInfoWindowOnClick(false);
			editGeoClicked = 1;
			tb.activate('polygon');
		});

		// Handle on-click event for selecting a location for the move site
		$('#select_area').on('click', function(f) {
			f.preventDefault();
			toggleUI();
			deleteGraphic('dump');
			map.setInfoWindowOnClick(false);
			tb.activate('point');
		});

		// Apply the treatment getting the collection rate and using that and the technology matrix's technology ID
		// using the 'apply_septic' API route. Once done, destroy the modal contents, update scenario data, add the
		// treatment graphic to the map and add the treatment graphic to the treatment stack
		$('#applytreatment').on('click', function(e) {
			e.preventDefault();
			let applyTreatmentButton = document.getElementById("applytreatment");
			let setApplyTreatmentButtonStyling = applyTreatmentButton.setAttribute("style", "display:none;");
			let rate = $('#collect-rate').val();
			let url = "{{url('/apply_collectStay')}}" + '/' + rate + '/' + techId;
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(treatment_id){
				destroyModalContents();
				$( "#update" ).trigger( "click" );
				addTreatmentIdToGraphic(treatment_id);
				addToStack(treatment_id, icon, techId);
			});
		});
	});
</script>