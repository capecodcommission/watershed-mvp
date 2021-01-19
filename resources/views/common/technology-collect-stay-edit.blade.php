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
			<button title="Edit Polygon" class="blade_button" id="edit_geometry" data-treatment="{{$treatment->TreatmentID}}">Edit Polygon</button>
		</h4>
		<a title="{{$tech->technology_strategy}} - Technology Matrix" class="blade_image" href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->TM_ID}}" target="_blank">
			<img src="{{$_ENV['CCC_ICONS_SVG'].$tech->icon}}"> 
			<span>Click icon for more info.</span>
		</a>
		<div class="blade_slider" title="Update the amount to be treated.">
			<label v-if = "{{in_array($tech->technology_type,['Innovative and Resource-Management Technologies']) && $tech->unit_metric=='Linear Foot'}}" id="unit_metric_label">Enter length (Linear Feet) of PRB to be treated:</label>
			<input v-if = "{{in_array($tech->technology_type,['Innovative and Resource-Management Technologies']) && $tech->unit_metric=='Linear Foot'}}" id="unit_metric" v-model="uMetric" type="number" name="unit_metric" value='{{$treatment->Treatment_MetricValue}}'>
			<label v-if = "{{in_array($tech->technology_type,['Green Infrastructure', 'Innovative and Resource-Management Technologies', 'System Alterations'])}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}}%.</label>
			<label v-if = "{{in_array($tech->technology_type,['Waste Reduction Toilets','On-Site Treatment Systems'])}}" id = "collect-label-reduc">Update the reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} ppm and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<input v-if = "{{in_array($tech->technology_type,['Green Infrastructure', 'Innovative and Resource-Management Technologies', 'System Alterations'])}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step="1">
			<input v-if = "{{in_array($tech->technology_type,['Waste Reduction Toilets','On-Site Treatment Systems'])}}" type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$treatment->Treatment_Value}}" step=".25">
			<label v-if = "{{in_array($tech->technology_type,['Green Infrastructure', 'Innovative and Resource-Management Technologies', 'System Alterations'])}}" id = "collect-label-rate">@{{collect_rate}}%</label>
			<label v-if = "{{in_array($tech->technology_type,['Waste Reduction Toilets','On-Site Treatment Systems'])}}" id = "collect-label-rate">@{{collect_rate}} ppm</label>
		</div>
		<div class="blade_buttons_group">
			<button title="Update Strategy" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" style = 'display:none;' v-show="{{$treatment->Treatment_Value}} != collect_rate || {{$treatment->Treatment_MetricValue}} != uMetric" id="updateCollectStay">Update</button>
			<button v-if = "{{in_array($tech->technology_type,['Green Infrastructure', 'System Alterations', 'Waste Reduction Toilets','On-Site Treatment Systems'])}}" title="Delete Strategy" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" v-show="{{$treatment->Treatment_Value}} == collect_rate" id="deletetreatment">Delete</button>
			<!-- <button v-if = "{{in_array($tech->technology_type,['Innovative and Resource-Management Technologies']) && $tech->unit_metric=='Linear Foot'}}" title="Delete Strategy" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" v-show="{{$treatment->Treatment_Value}} == collect_rate && {{$treatment->Treatment_MetricValue}} == uMetric" id="deletetreatment">Delete</button> -->
			<button v-if = "{{in_array($tech->technology_type,['Innovative and Resource-Management Technologies'])}}" title="Delete Strategy" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" v-show="{{$treatment->Treatment_Value}} == collect_rate" id="deletetreatment">Delete</button>
		</div>
</div>

<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {

		// Handle click event for updating collect-stay technologies utilizing the 'update' API route,
		// reseting the map graphic properties and updating the scenario data
		$('#updateCollectStay').on('click', function(e) {
			e.preventDefault();
			let updateTreatmentButton = document.getElementById("updateCollectStay");
			let setUpdateTreatmentButtonStyling = updateTreatmentButton.setAttribute("style", "display:none;");
			let treatmentValue = $('#collect-rate').val();
			let linearFeet = $('#unit_metric').val() || null;
			let url = "{{url('/update', $treatment->TreatmentID)}}"  + '/' + treatmentValue  + '/' + linearFeet;

			destroyModalContents();
			$(".modal-loading").toggle();
			$('.modal-wrapper').toggle();

			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(msg){
				$(".modal-loading").toggle();
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