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
		<div class="blade_slider" title="Update number of {{$tech->unit_metric}} to be treated.">
			<label title="Update number of {{$tech->unit_metric}} to be treated." id="unit_metric_label">Enter number of {{$tech->unit_metric}} to be treated:</label>
			<input title="Enter a number of {{$tech->unit_metric}} to be treated." v-model="uMetric" type="number" id="unit_metric" name="unit_metric" value="{{$treatment->Treatment_MetricValue}}">
			<button title="Edit Point" class="blade_button" id="edit_geometry" data-treatment="{{$treatment->TreatmentID}}">Edit Point</button>
		</div>
	    <button title="Apply technology updates" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" v-show="{{$treatment->Treatment_MetricValue}} != uMetric" id="updateStormwaterNonManangement">Update</button>
		<button title="Delete treatment" id="deletetreatment" class="blade_button" data-treatment="{{$treatment->TreatmentID}}" v-show="{{$treatment->Treatment_MetricValue}} == uMetric"><i class="fa fa-trash-o"></i> Delete</button>
	</div>

<!-- Import the vue data and computed properties -->
<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {

		// Handle click event for updating stormwater non-management technologies utilizing the 'update/storm' API route,
		// reseting the map graphic properties and updating the scenario data
		$('#updateStormwaterNonManangement').on('click', function(e) {
			e.preventDefault();
			let updateTreatmentButton = document.getElementById("updateStormwaterNonManangement");
			let setDeleteTreatmentButtonStyling = updateTreatmentButton.setAttribute("style", "display:none;");
			let treatmentValue = $('#unit_metric').val();
			let url = "{{url('/update', $treatment->TreatmentID)}}" + '/' + treatmentValue;
			destroyModalContents();
			$(".modal-loading").toggle();
			$('.modal-wrapper').toggle();

			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(msg) {
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