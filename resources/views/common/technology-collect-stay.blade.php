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
			<!-- <img v-show="{{$tech->technology_id == 400}}" src="{{$_ENV['CCC_ICONS_SVG'].'$tech->icon'}}">  TODO: FUTURE SYNTAX -->
			<img v-show="{{$tech->technology_id == 101}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SurfaceWaterRemediationWetlands.svg'}}">
			<img v-show="{{$tech->technology_id == 102}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SurfaceWaterRemediationWetlands.svg'}}">
			<img v-show="{{$tech->technology_id == 103}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SurfaceWaterRemediationWetlands.svg'}}">
			<img v-show="{{$tech->technology_id == 104}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_HydroponicTreatment.svg'}}">
			<img v-show="{{$tech->technology_id == 105}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_Phytoremediation.svg'}}">
			<img v-show="{{$tech->technology_id == 204}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_Phytoremediation.svg'}}">
			<img v-show="{{$tech->technology_id == 207}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FertigationWellsTurf.svg'}}">
			<img v-show="{{$tech->technology_id == 208}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FertigationWellsTurf.svg'}}">
			<img v-show="{{$tech->technology_id == 300}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_CompostingToilet.svg'}}">
			<img v-show="{{$tech->technology_id == 301}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_IncineratingToilet.svg'}}">
			<img v-show="{{$tech->technology_id == 302}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_PackagingToilet.svg'}}">
			<img v-show="{{$tech->technology_id == 303}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_UrineDivertingToilet.svg'}}">
			<img v-show="{{$tech->technology_id == 601}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_IA.svg'}}">
			<img v-show="{{$tech->technology_id == 602}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_EnhancedIA.svg'}}">
		</a>
		<div class="blade_slider" title="Select the amount to be treated.">
			<button title="Draw Treatment" class="blade_button" id="draw_collection">Draw Collection</button>
			<label v-if="{{$tech->technology_id == 101}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}}% and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-if="{{$tech->technology_id == 102}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}}% and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-if="{{$tech->technology_id == 103}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}}% and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-if="{{$tech->technology_id == 104}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}}% and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-if="{{$tech->technology_id == 105}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}}% and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-if="{{$tech->technology_id == 204}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}}% and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-if="{{$tech->technology_id == 207}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}}% and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-if="{{$tech->technology_id == 208}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}}% and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-if="{{$tech->technology_id == 300}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<label v-if="{{$tech->technology_id == 301}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<label v-if="{{$tech->technology_id == 302}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<label v-if="{{$tech->technology_id == 303}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<label v-if="{{$tech->technology_id == 601}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<label v-if="{{$tech->technology_id == 602}}" id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>			
			<input v-if="{{$tech->technology_id == 101}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low}}" step="1" style="display:none;">
			<input v-if="{{$tech->technology_id == 102}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low}}" step="1" style="display:none;">
			<input v-if="{{$tech->technology_id == 103}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low}}" step="1" style="display:none;">
			<input v-if="{{$tech->technology_id == 104}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low}}" step="1" style="display:none;">
			<input v-if="{{$tech->technology_id == 105}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low}}" step="1" style="display:none;">
			<input v-if="{{$tech->technology_id == 204}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low}}" step="1" style="display:none;">
			<input v-if="{{$tech->technology_id == 207}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low}}" step="1" style="display:none;">
			<input v-if="{{$tech->technology_id == 208}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low}}" step="1" style="display:none;">
			<input v-if="{{$tech->technology_id == 300}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low_ppm}}" step="0.01" style="display:none;">
			<input v-if="{{$tech->technology_id == 301}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low_ppm}}" step="0.01" style="display:none;">
			<input v-if="{{$tech->technology_id == 302}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low_ppm}}" step="0.01" style="display:none;">
			<input v-if="{{$tech->technology_id == 303}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low_ppm}}" step="0.01" style="display:none;">
			<input v-if="{{$tech->technology_id == 601}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low_ppm}}" step="0.25" style="display:none;">
			<input v-if="{{$tech->technology_id == 602}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low_ppm}}" step="0.25" style="display:none;">
			<label v-if="{{$tech->technology_id == 101}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 102}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 103}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 104}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 105}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 204}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 207}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 208}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 300}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}} ppm</label>
			<label v-if="{{$tech->technology_id == 301}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}} ppm</label>
			<label v-if="{{$tech->technology_id == 302}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}} ppm</label>
			<label v-if="{{$tech->technology_id == 303}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}} ppm</label>
			<label v-if="{{$tech->technology_id == 601}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}} ppm</label>
			<label v-if="{{$tech->technology_id == 602}}" id = "collect-label-rate" style="display:none;">@{{collect_rate}} ppm</label>
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
			$('.modal-wrapper').hide();
			map.setInfoWindowOnClick(false);
			tb.activate('polygon');
		});

		// Apply the treatment getting the collection rate and using that and the technology matrix's technology ID
		// using the 'apply_septic' API route. Once done, destroy the modal contents, update scenario data, add the
		// treatment graphic to the map and add the treatment graphic to the treatment stack
		$('#applytreatment').on('click', function(e) {
			e.preventDefault();
<<<<<<< HEAD
			var rate = $('#collect-rate').val();
			var url = "{{url('/apply_collectStay')}}" + '/' + rate + '/' + techId;
=======
			let applyTreatmentButton = document.getElementById("applytreatment");
			let setApplyTreatmentButtonStyling = applyTreatmentButton.setAttribute("style", "display:none;");
			let rate = $('#collect-rate').val();
			let url = "{{url('/apply_septic')}}" + '/' + rate + '/' + techId;
>>>>>>> 7979bfd... Added 'collect-stay-edit' blade and updated 'TechnologyController' to use it, updated 'app.css' with new styling, removed commented computed property from 'main.js', updated comments in 'collect-stay' and 'technology-stormwater-non-management' blades.
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