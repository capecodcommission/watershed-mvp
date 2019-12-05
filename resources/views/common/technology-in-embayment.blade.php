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
			<button title="Select Subembayment" class="blade_button" id="select_area">Select Subembayment</button>
			<label id="selected-subembayment" style="display:none;">@{{subembayment}}</label>
			<label id="unit_metric_label" style="display:none;">Enter number of {{$tech->unit_metric}} to be treated:</label>
			<input id="unit_metric" style="display:none;" v-model="uMetric" type="number" name="unit_metric" value='1'>
			<label id="subembayment-rate-label" style="display:none;" >Select a reduction rate between {{round($tech->Absolu_Reduc_perMetric_Low, 2)}} and {{round($tech->Absolu_Reduc_perMetric_High, 2)}}kg per {{$tech->unit_metric}}.</label>
			<input id="subembayment-rate" style="display:none;" type="range" min="{{round($tech->Absolu_Reduc_perMetric_Low, 2)}}" max="{{round($tech->Absolu_Reduc_perMetric_High, 2)}}" v-model="subembayment_amount" value='{{round($tech->Absolu_Reduc_perMetric_Low, 2)}}' step=".1">
			<label id="subembayment-rate-selected" style="display:none;">@{{subembayment_amount}}</label>
		</div>
		<button title="Apply Strategy" class="blade_button" id="applyTreatmentInEmbayment" style="display:none;">Apply</button>
</div>

<!-- TODO: Add warning that sewered parcels will not be affected -->

<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function() {

		icon = '{{$tech->icon}}'
		techId = '{{$tech->technology_id}}'
		$('#select_area').data('icon', icon.toString());

		// Handle on-click event for selecting a location
		$('#select_area').on('click', function(f) {
			f.preventDefault();
			toggleUI();
			deleteGraphic();
			map.setInfoWindowOnClick(false);
			tb.activate('point');
		});
		
		// Apply the treatment getting the collection rate and using that and the technology matrix's technology ID
		// using the 'apply_septic' API route. Once done, destroy the modal contents, update scenario data, add the
		// treatment graphic to the map and add the treatment graphic to the treatment stack
		$('#applyTreatmentInEmbayment').on('click', function(e) {
			e.preventDefault();
			let applyTreatmentButton = document.getElementById("applyTreatmentInEmbayment");
			applyTreatmentButton.setAttribute("style", "display:none;");
			let rate = $('#subembayment-rate').val();
			let units = $('#unit_metric').val();
			let url = "{{url('apply_embayment')}}" + '/' + rate + '/' + units + '/' + techId;
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