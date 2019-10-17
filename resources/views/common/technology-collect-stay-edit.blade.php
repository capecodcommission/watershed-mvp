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
		<div class="blade_slider" title="Update the amount to be treated.">
			<!-- <button title="Draw Collection" class="blade_button" id="draw_collection">Draw Collection</button>
			<label id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label> -->
			<label v-show="{{$tech->technology_id == 101}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-show="{{$tech->technology_id == 102}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-show="{{$tech->technology_id == 103}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-show="{{$tech->technology_id == 104}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-show="{{$tech->technology_id == 105}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-show="{{$tech->technology_id == 204}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-show="{{$tech->technology_id == 207}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-show="{{$tech->technology_id == 208}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-show="{{$tech->technology_id == 300}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<label v-show="{{$tech->technology_id == 301}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<label v-show="{{$tech->technology_id == 302}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<label v-show="{{$tech->technology_id == 303}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<label v-show="{{$tech->technology_id == 601}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<label v-show="{{$tech->technology_id == 602}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>	
			<input v-if="{{$tech->technology_id == 101}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="1">
			<input v-if="{{$tech->technology_id == 102}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="1">
			<input v-if="{{$tech->technology_id == 103}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="1">
			<input v-if="{{$tech->technology_id == 104}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="1">
			<input v-if="{{$tech->technology_id == 105}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="1">
			<input v-if="{{$tech->technology_id == 204}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="1">
			<input v-if="{{$tech->technology_id == 207}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="1">
			<input v-if="{{$tech->technology_id == 208}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="1">
			<input v-if="{{$tech->technology_id == 300}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="0.01">
			<input v-if="{{$tech->technology_id == 301}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="0.01">
			<input v-if="{{$tech->technology_id == 302}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="0.01">
			<input v-if="{{$tech->technology_id == 303}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="0.01">
			<input v-if="{{$tech->technology_id == 601}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="0.25">
			<input v-if="{{$tech->technology_id == 602}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="0.25">
			<label v-if="{{$tech->technology_id == 101}}" id = "collect-label-rate">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 102}}" id = "collect-label-rate">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 103}}" id = "collect-label-rate">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 104}}" id = "collect-label-rate">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 105}}" id = "collect-label-rate">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 204}}" id = "collect-label-rate">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 207}}" id = "collect-label-rate">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 208}}" id = "collect-label-rate">@{{collect_rate}}%</label>
			<label v-if="{{$tech->technology_id == 300}}" id = "collect-label-rate">@{{collect_rate}} ppm</label>
			<label v-if="{{$tech->technology_id == 301}}" id = "collect-label-rate">@{{collect_rate}} ppm</label>
			<label v-if="{{$tech->technology_id == 302}}" id = "collect-label-rate">@{{collect_rate}} ppm</label>
			<label v-if="{{$tech->technology_id == 303}}" id = "collect-label-rate">@{{collect_rate}} ppm</label>
			<label v-if="{{$tech->technology_id == 601}}" id = "collect-label-rate">@{{collect_rate}} ppm</label>
			<label v-if="{{$tech->technology_id == 602}}" id = "collect-label-rate">@{{collect_rate}} ppm</label>
		</div>
		<button title="Update Strategy" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" v-show="{{$treatment->Treatment_Value}} != collect_rate" id="updateCollectStay">Update</button>
		<button title="Delete Strategy" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" v-show="{{$treatment->Treatment_Value}} == collect_rate" id="deletetreatment">Delete</button>
</div>

<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {

		// Handle click event for updating collect-stay technologies utilizing the 'update/toilets' API route,
		// reseting the map graphic properties and updating the scenario data
		$('#updateCollectStay').on('click', function(e) {
			e.preventDefault();
			let updateTreatmentButton = document.getElementById("updateCollectStay");
			let setUpdateTreatmentButtonStyling = updateTreatmentButton.setAttribute("style", "display:none;");
			let treatmentValue = $('#collect-rate').val();
			let url = "{{url('/update', $treatment->TreatmentID)}}"  + '/' + treatmentValue;
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(msg){
				destroyModalContents();
				resetGraphicPropsAfterUpdate(msg);
				$( "#update" ).trigger( "click" );
			});
		});

		// Handle click event for deleting selected technology utilizing the 'delete_treatment'
		// API route, deleting the treatment from the treatment sack, deleting the treatment graphic
		// from the map and updating the scenario data
		$('#deletetreatment').on('click', function(e) {
			e.preventDefault();
			let deleteTreatmentButton = document.getElementById("deletetreatment");
		    let setDeleteTreatmentButtonStyling = deleteTreatmentButton.setAttribute("style", "display:none;");
			let treat = $(this).data('treatment');
			let url = "{{url('delete_treatment')}}" + '/' + treat;
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(msg){
				destroyModalContents();
				$("li[data-treatment='{{$treatment->TreatmentID}}']").remove();
				deleteGraphic(treat);
				$( "#update" ).trigger( "click" );
			});
		});
	});
</script>