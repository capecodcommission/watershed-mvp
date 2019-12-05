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
			<button title="Change Selected Subembayment" class="blade_button" id="select_area">Change Subembayment</button>
			<label id="selected-subembayment">@{{subembayment}}</label>
			<label id="unit_metric_label">Update the number of {{$tech->unit_metric}} to be treated:</label>
			<input id="unit_metric" v-model="uMetric" type="number" name="unit_metric" value='{{$treatment->Treatment_MetricValue}}'>
			<label id="subembayment-rate-label" >Update the selected reduction rate between {{round($tech->Absolu_Reduc_perMetric_Low, 2)}} and {{round($tech->Absolu_Reduc_perMetric_High, 2)}}kg per {{$tech->Unit_Metric}}.</label>
			<input id="subembayment-rate" type="range" min="{{round($tech->Absolu_Reduc_perMetric_Low, 2)}}" max="{{round($tech->Absolu_Reduc_perMetric_High, 2)}}" v-model="subembayment_amount" value="{{$treatment->Treatment_Value}}" step=".1">
			<label id="subembayment-rate-selected">@{{subembayment_amount}}</label>
		</div>
		<button title="Update Strategy" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" style = 'display:none;' v-show="{{$treatment->Treatment_Value}} != subembayment_amount || {{$treatment->Treatment_MetricValue}} != uMetric" id="updateTreatmentInEmbayment">Update</button>
		<button title="Delete Strategy" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" v-show="{{$treatment->Treatment_Value}} == subembayment_amount && {{$treatment->Treatment_MetricValue}} == uMetric" id="deletetreatment">Delete</button>
</div>

<!-- TODO: Add warning that sewered parcels will not be affected -->

<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {

		// Set global variables to use in functionality below
		treatment = {{$treatment->TreatmentID}};
		icon = '{{$tech->icon}}';
		techId = '{{$tech->technology_id}}';
		$('#select_area').data('icon', icon.toString());
		pointCoords = '{{$treatment->POLY_STRING}}';
		let subembaymentUrl = "{{url('/get_subembayment')}}"  + '/' + pointCoords;

		// Display the subembayment name and ID on modal load
		$.ajax({
				method: 'GET',
				url: subembaymentUrl
			})
			.done(function(subembayment){
				$('#selected-subembayment').show();
				$('#selected-subembayment').text('Selected Subembayment: ' + subembayment[0].SUBEM_DISP + ' | ID:' + subembayment[0].SUBEM_ID);
			});

		// Handle on-click event for selecting a location
		$('#select_area').on('click', function(f) {
			f.preventDefault();
			toggleUI();
			deleteGraphic(treatment);
			map.setInfoWindowOnClick(false);
			tb.activate('point');
		});
		
		// Apply the treatment getting the subembayment amount and using that and the technology matrix's technology ID
		// using the 'apply_septic' API route. Once done, destroy the modal contents, update scenario data, add the
		// treatment graphic to the map and add the treatment graphic to the treatment stack
		$('#updateTreatmentInEmbayment').on('click', function(e) {
			e.preventDefault();
			let applyTreatmentButton = document.getElementById("updateTreatmentInEmbayment");
			applyTreatmentButton.setAttribute("style", "display:none;");
			let rate = $('#subembayment-rate').val();
			let units = $('#unit_metric').val();
			let url = "{{url('/update', $treatment->TreatmentID)}}"  + '/' + rate + '/' + units;
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(treatment_id){
				destroyModalContents();
				resetGraphicPropsAfterUpdate(treatment_id);
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