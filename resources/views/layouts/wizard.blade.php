<!DOCTYPE html>

<html>
<head>
	<title>WatershedMVP 3.0 Wizard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php if( env('APP_ENV') == 'production' ) : ?>
		<link rel="stylesheet" href="{{secure_url('/css/app.css')}}">
	<?php else :?>
		<link rel="stylesheet" href="{{url('/css/app.css')}}">
	<?php endif; ?>
	<!-- <link href="{{secure_url('/css/app.css')}}" rel="stylesheet" type="text/css"> -->
	<link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css">
	<script src="https://js.arcgis.com/3.16/"></script>
  	<link rel="stylesheet" href="https://js.arcgis.com/3.16/dijit/themes/claro/claro.css">  
  	<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>  
</head>
<body class="claro">
	<!-- <div id="modal">
		<div class="claro"> -->

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
	@include('common/modal')
<!-- </div>
</div> -->
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

			$('.modal-wrapper').hide();
			
			$('#fertMan')
				.css({'cursor': 'pointer'});

			$('#stormMan')
				.css({'cursor': 'pointer'});

			// TODO: Handle spinner icon on-click. There should be only a single spinner on an icon at one time.
			// $('div.technology').on('click', function(e) {	
			// 	$(this).append("<div class = 'fa fa-spinner fa-spin'></div>")	
			// })

			// Disable during testing
			$('.popdown').popdown();
			$('#closeACC').on('click', function(event){
				$('.state').prop('checked', false);
			});
			$('.sliding-panel-button').on('click', function(e){
				$('.sliding-panel-content').toggleClass('is-visible');
				// console.log('button clicked');
			});

			// TODO: Is this real?
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

			// Retrieve static session variables for fert/storm application
			fertApplied = {{session('fert_applied')}};
			stormApplied = {{session('storm_applied')}};

			// If fert management applied, disable clickability for icon
			if (fertApplied) {
				$('#fertMan')
					.css({'pointer-events': 'none'});
			} else {
				$('#fertMan')
					.css({'pointer-events': 'auto'});
			}

			// If storm management applied, disable clickability for icon
			if (stormApplied) {
				$('#stormMan')
					.css({'pointer-events': 'none'});
			} else {
				$('#stormMan')
					.css({'pointer-events': 'auto'});
			}

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

							if(progress > 100)
							{
								progress = 100;
							}

							$('div.progress h3').text(progress + '%');
							$('.remaining span').text(remaining);
							$('div.progress').animate({'height': progress+'%'}, 500);

							subembayments = msg.subembayments;
							$.each(subembayments, function(key, value)
							{
								var sub_progress = value.n_load_target / (value.n_load_att - value.n_load_att_removed);
								if (sub_progress < 1 & sub_progress > 0) {

									sub_progress = sub_progress * 100;
								} else {
									sub_progress = 100;
								}
								$('#progress_'+value.SUBEM_ID).text(Math.round(sub_progress));
								$('#subem_'+value.SUBEM_ID + ' .sub-progress').animate({'width': sub_progress+'%'}, 500);
								$('#subem_'+value.SUBEM_ID + ' .stats .stat-data.scenario-progress').text(Math.round(value.n_load_scenario)+'kg');
							});
							
							// If fert management applied, disable clickability on fert icon
							if (msg.fertapplied) {
								$('#fertMan')
									.css({'pointer-events': 'none'});
							} else {
								$('#fertMan')
									.css({'pointer-events': 'auto'});
							}

							// If storm management applied, disable clickability on storm icon
							if (msg.stormapplied) {
								$('#stormMan')
									.css({'pointer-events': 'none'});
							} else {
								$('#stormMan')
									.css({'pointer-events': 'auto'});
							}
						})
			});
		</script>
	
</body>
</html>
