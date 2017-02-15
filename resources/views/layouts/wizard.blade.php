<!DOCTYPE html>

<html>
<head>
	<title>WatershedMVP 3.0 Wizard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="{{url('/css/app.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css">
	<script src="https://js.arcgis.com/3.16/"></script>
  	<link rel="stylesheet" href="https://js.arcgis.com/3.16/dijit/themes/claro/claro.css">  
  	<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>  
</head>
<body class="claro">

<div data-dojo-type="dijit/layout/BorderContainer" 
	   data-dojo-props="design:'headline', gutters:false" 
	   style="width:100%;height:100%;margin:0;">

	<div id="map">

	</div>
  </div>
	<!-- <div id="map" class="map"></div> -->
	<div class="wrapper">
		<div class="content">
			<nav class="toolbar">
				@include('common/map-tools')
				@include('common/selected-treatments')
			</nav>
			
			@include('common/subembayment-progress')
		{{--	@include('common/embayment-progress')	--}}
			@include('common/progress-svg')
			@include('common/wizard-steps')
		</div>
	</div>
	
	<script>
		var selectlayer = {{$embayment->EMBAY_ID}};
		var center_x = {{$embayment->longitude}};
		var center_y = {{$embayment->latitude}};
		window.name = 'wmvp_scenario_{{session('scenarioid')}}';
	</script>

<!-- <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script> -->
<script src="{{url('/js/map.js')}}"></script>
<script src="{{url('/js/main.js')}}"></script>
<script src="{{url('/js/jquery.popdown.js')}}"></script>

<script type="text/javascript">
		$(document).ready(function(){
			$('.popdown').popdown();
			$('#closeACC').on('click', function(event){
				$('.state').prop('checked', false);
			});
			$('.sliding-panel-button').on('click', function(e){
				$('.sliding-panel-content').toggleClass('is-visible');
				// console.log('button clicked');
			});
			$('#getNitrogen').on('click', function(e){
				e.preventDefault();
				var url = "{{url('/getScenarioNitrogen')}}";
				$.ajax({
						method: 'GET',
						url: url
					})
						.done(function(msg){
							// console.log(msg);
							var nitrogen = Math.round(msg[0].N_Original - msg[0].N_Removed);
							$('#getNitrogen').text(nitrogen + 'kg');
						})
			});
			// $('.disable-popups-button').on('click', function(e){
			// 	var layers = [NitrogenLayer, Subembayments, Subwatersheds, WasteWater, Towns, TreatmentType, TreatmentFacilities, EcologicalIndicators, ShallowGroundwater, LandUse, FlowThrough]

			// 	if (e.hasClass('enabled')) {
			// 		for (var i = 0; i < layers.length; i++) {

			// 			if (layers[i].visible) {

			// 				layers[i].disableMouseEvents()
			// 			}
			// 		}
			// 		e.toggleClass('enabled')
			// 	} else {

			// 		for (var i = 0; i < layers.length; i++) {

			// 			if (layers[i].visible) {

			// 				layers[i].enableMouseEvents()
			// 			}
			// 		}
			// 		e.toggleClass('enabled')
			// 	}
			// });

		});
	</script>
	<script>
			var progress;
			progress = {{$progress}};
			remaining = Math.round({{$remaining}});
			$('div.progress h3').text(progress + '%');
			$('.remaining span').text(remaining);

			if(progress >= 100)
			{
				progress = 100;
			}
			
			$('div.progress').css('height', progress+'%');

			$('#update').on('click', function(e){
				var url= '/getScenarioProgress';

				$.ajax({
						method: 'GET',
						url: url
					})
						.done(function(msg){
							progress = msg.embayment;
							remaining = Math.round(msg.remaining);

							$('div.progress h3').text(progress + '%');
							$('.remaining span').text(remaining);
							if(progress > 100)
							{
								progress = 100;
							}	

							$('div.progress').animate({'height': progress+'%'}, 500);

							subembayments = msg.subembayments;
							$.each(subembayments, function(key, value)
							{
								// console.log(value);
								var sub_progress = Math.round((value.n_load_target/value.n_load_scenario) * 100);
								$('#progress_'+value.subem_id).text(sub_progress);
								if (sub_progress > 100) 
								{
									sub_progress = 100;
								}
								$('#subem_'+value.subem_id + ' .sub-progress').animate({'width': sub_progress+'%'}, 500);
								$('#subem_'+value.subem_id + ' .stats .stat-data.scenario-progress').text(Math.round(value.n_load_scenario)+'kg');
							});
							
						})
			});
		</script>
	
</body>
</html>
