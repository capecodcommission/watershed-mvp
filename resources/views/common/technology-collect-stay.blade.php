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
		<div class="blade_slider" title="Enter number of {{$tech->unit_metric}} to be treated.">
			<button title="Draw Collection" class="blade_button" id="draw_collection">Draw Collection</button>
			<label id = "collect-label-reduc" style="display:none;">Select a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.</label>
			<input type="range" id="collect-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="collect_rate" value="{{$tech->Nutri_Reduc_N_Low}}" step="1" style="display:none;">
			<label id = "collect-label-rate" style="display:none;">@{{collect_rate}} ppm</label>
		</div>
		<button title="Apply Strategy" class="blade_button" id="applytreatment" style="display:none;">Apply</button>
</div>

<!-- TODO: Add warning that sewered parcels will not be affected -->

<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function(){

		// Append technology id to div to be parsed for polygon creation
		// Obtain icon filename and technology id from props
		$('#draw_collection').data('techId','{{$tech->technology_id}}')
		icon = '{{$tech->icon}}'
		techId = '{{$tech->technology_id}}'

		$('#draw_collection').on('click', function(f){
			f.preventDefault();
			map.disableMapNavigation();
			deleteGraphic();
			$('#popdown-opacity').hide()
			$('.modal-wrapper').hide()
			map.setInfoWindowOnClick(false);
			tb.activate('polygon');
		});

		$('#applytreatment').on('click', function(e){
			let applyTreatmentButton = document.getElementById("applytreatment");
			let setapplyTreatmentButtonStyling = applyTreatmentButton.setAttribute("style", "display:none;");
			e.preventDefault();
			var rate = $('#collect-rate').val();
			var url = "{{url('/apply_collectStay')}}" + '/' + rate + '/' + techId;
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

		// $('#closeWindow').on('click', function (e) {
		// 	e.preventDefault();
		// 	destroyModalContents();
		// 	deleteGraphic();
		// })
	});
</script>