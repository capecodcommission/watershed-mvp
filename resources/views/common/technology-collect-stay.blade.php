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
			<span>Click icon for more info.</span>
		</a>
		<div class="blade_slider" title="Select the amount to be treated.">
			<button title="Draw Treatment" class="blade_button" id="draw_collection">Draw Collection</button>
			<label v-if = "{{in_array($tech->technology_type,['Innovative and Resource-Management Technologies']) && $tech->unit_metric=='Linear Foot'}}" id="unit_metric_label" style="display:none;">Enter length (Linear Feet) of PRB to be treated:</label>
			<input v-if = "{{in_array($tech->technology_type,['Innovative and Resource-Management Technologies']) && $tech->unit_metric=='Linear Foot'}}" id="unit_metric" style="display:none;" v-model="uMetric" type="number" name="unit_metric" value='1'>
			<label v-if = "{{in_array($tech->technology_type,['Green Infrastructure', 'Innovative and Resource-Management Technologies', 'System Alterations'])}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}}% and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-if = "{{in_array($tech->technology_type,['Waste Reduction Toilets','On-Site Treatment Systems'])}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} ppm and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<input v-if = "{{in_array($tech->technology_type,['Green Infrastructure', 'Innovative and Resource-Management Technologies', 'System Alterations'])}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low}}" step="1" style="display:none;">
			<input v-if = "{{in_array($tech->technology_type,['Waste Reduction Toilets','On-Site Treatment Systems'])}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low_ppm}}" step=".05" style="display:none;">
			<label v-if = "{{in_array($tech->technology_type,['Green Infrastructure', 'Innovative and Resource-Management Technologies', 'System Alterations'])}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}}%</label>
			<label v-if = "{{in_array($tech->technology_type,['Waste Reduction Toilets','On-Site Treatment Systems'])}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}} ppm</label>
		</div>
		<button title="Apply Strategy" class="blade_button" id="applytreatment" style="display:none;">Apply</button>
</div>

<!-- TODO: Add warning that sewered parcels will not be affected -->

<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {
		
		// Append technology id to div to be parsed for polygon creation
		// Obtain the technology ID from the Technology Matrix
		$('#draw_collection').data('techId','{{$tech->technology_id}}')
		icon = '{{$tech->icon}}'
		techId = '{{$tech->technology_id}}'

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

		// Apply the treatment getting the collection rate and using that and the technology matrix's technology ID
		// using the 'apply_septic' API route. Once done, destroy the modal contents, update scenario data, add the
		// treatment graphic to the map and add the treatment graphic to the treatment stack
		$('#applytreatment').on('click', function(e) {
			e.preventDefault();
			let applyTreatmentButton = document.getElementById("applytreatment");
			let setApplyTreatmentButtonStyling = applyTreatmentButton.setAttribute("style", "display:none;");
			let rate = $('#collect-rate').val();
			let linearFeet = $('#unit_metric').val() || null;
			let url = "{{url('/apply_collectStay')}}" + '/' + rate + '/' + techId + '/' + linearFeet;

			destroyModalContents();
			$(".modal-loading").toggle();
			$('.modal-wrapper').toggle();

			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(treatment_id){
				$(".modal-loading").toggle();
				destroyModalContents();
				$( "#update" ).trigger( "click" );
				addTreatmentIdToGraphic(treatment_id);
				addToStack(treatment_id, icon, techId);
			});
		});
	});
</script>