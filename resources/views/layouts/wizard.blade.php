<!-- resources/views/layouts/wizard.blade -->
<!DOCTYPE html>
<html>
	<head>
		<title>WatershedMVP 4.1 Wizard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php if( env('APP_ENV') == 'production' ) : ?>
			<link rel="stylesheet" href="{{secure_url('/css/app.css')}}">
		<?php else :?>
			<link rel="stylesheet" href="{{url('/css/app.css')}}">
		<?php endif; ?>
		<script type="text/javascript" src="https://cdn.plot.ly/plotly-latest.min.js"></script>
		<link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css">
		<script src="https://js.arcgis.com/3.16/"></script>
		<link rel="stylesheet" href="https://js.arcgis.com/3.16/dijit/themes/claro/claro.css">  
		<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
	</head>
	<body class="claro">
		<div data-dojo-type="dijit/layout/BorderContainer" data-dojo-props="design:'headline', gutters:false" style="width:100%;height:100%;margin:0;">
			<div id="map" class="dark"></div>
		</div>
		<div class="wrapper">
			<div class="content">
				<nav class="toolbar">
					@include('common/map-tools')
					@include('common/selected-treatments')
				</nav>
				@include('common/subembayment-progress')
				@include('common/wizard-steps')
				@include('common/wizard-steps-open-arrow')
			</div>
		</div>
		@include('common/modal')
		<script>
			var selectlayer = {{$embayment->EMBAY_ID}};
			var center_x = {{$embayment->longitude}};
			var center_y = {{$embayment->latitude}};
			window.name = 'wmvp_scenario_{{session('scenarioid')}}';
		</script>
		<script src="{{url('/js/plotly.js')}}"></script>
		<script src="{{url('/js/map.js')}}"></script>
		<script src="{{url('/js/main.js')}}"></script>
		<script src="{{url('/js/jquery.popdown.js')}}"></script>
		<script src="{{url('/js/helpers.js')}}"></script>
		<script type="text/javascript">
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				error: function(x, status, error) {
					//On error, hide modal and display map tools
					alert("An error occurred");
					toggleUI(true);
					destroyModalContents();
				}
			});

			$(document).ready(function() {
				// Remove scrolling from body if routed from login to map
				$('#app-layout').removeClass('scrollable');

				$('#fertMan').css({'cursor': 'pointer'});

				$('#stormMan').css({'cursor': 'pointer'});

				$('#closeACC').on('click', function(event) {
					$('.state').prop('checked', false);
				});
				$('.sliding-panel-content').toggleClass('is-visible');
				$('.sliding-panel-button').on('click', function(e){
					$('.sliding-panel-content').toggleClass('is-visible');
				});

				// TODO: Is this real?
				$('#getNitrogen').on('click', function(e) {
					e.preventDefault();
					var url = "{{url('/getScenarioNitrogen')}}";
					$.ajax({
						method: 'GET',
						url: url
					})
					.done(function(msg) {
						var nitrogen = Math.round(msg[0].N_Original - msg[0].N_Removed);
						$('#getNitrogen').text(nitrogen + 'kg');
					})
				});

				// Retrieve static session variables for fert/storm application
				fertApplied = {{session('fert_applied')}};
				stormApplied = {{session('storm_applied')}};

				// If fert management applied, disable clickability for icon
				if (fertApplied) {
					$('#fertMan').css({'pointer-events': 'none'});
				} else {
					$('#fertMan').css({'pointer-events': 'auto'});
				}

				// If storm management applied, disable clickability for icon
				if (stormApplied) {
					$('#stormMan').css({'pointer-events': 'none'});
				} else {
					$('#stormMan').css({'pointer-events': 'auto'});
				}

			});
		</script>
		<script>
			function colorItWhite(progress) {
				$('div.plotlyDiv p').text('Scenario Progress: ' + progress + '%');
				$('div.plotlyDiv p').css({'color': 'white'});
			};
			
			function colorItBlueLow(progress) {
				$('div.plotlyDiv p').text('Scenario Progress: ' + progress + '%');
				$('div.plotlyDiv p').css({'color': '#eff3ff'});
			};

			function colorItBlueLowMid(progress) {
				$('div.plotlyDiv p').text('Scenario Progress: ' + progress + '%');
				$('div.plotlyDiv p').css({'color': '#bdd7e7'});
			};

			function colorItBlueHighMid(progress) {
				$('div.plotlyDiv p').text('Scenario Progress: ' + progress + '%');
				$('div.plotlyDiv p').css({'color': '#6baed6'});
			};

			function colorItBlueHigh(progress) {
				$('div.plotlyDiv p').text('Scenario Progress: ' + progress + '%');
				$('div.plotlyDiv p').css({'color': '#3182bd'});		
			};

			function colorItBlueFull(progress) {
				$('div.plotlyDiv p').text('Scenario Progress: ' + progress + '%');
				$('div.plotlyDiv p').css({'color': '#08519c'});
			};

			function colorProgress(progress) {
				if (progress < 0) {
					alert("Somehow you ended up with a negative scenario progress percentage. That seems curious!")
				}
				else if (progress == 0) {
					colorItWhite(progress);
				}
				else if (progress > 0 && progress <= 25) {
					colorItBlueLow(progress);
				}
				else if (progress > 25 && progress <= 50) {
					colorItBlueLowMid(progress);
				}
				else if (progress > 50 && progress <= 75) {
					colorItBlueHighMid(progress);
				}
				else if (progress > 75 && progress < 100) {
					colorItBlueHigh(progress);
				}
				else if (progress == 100) {
					colorItBlueFull(progress);
				}
				else {
					alert("Something unexcpected is happening with your scenario progress percentage. Let's try that again!");
				}
			};

							
			var progress;
			progress = {{$progress}};
			remaining = Math.round({{$remaining}});
			$('div.progress h3').text(progress + '%');
			$('.remaining span').text(remaining);

			if(progress >= 100)
			{
				progress = 100;
			}

			colorProgress(progress);
			
			$('div.progress').css('height', progress+'%');

			$('#update').on('click', function(e) {
				var url= '/getScenarioProgress';

				$.ajax({
					method: 'GET',
					url: url
				})
				.done(function(msg) {
					progress = msg.embayment;
					remaining = Math.round(msg.remaining);

					if(progress > 100)
					{
						progress = 100;
					}

					$('div.progress h3').text(progress + '%');
					$('.remaining span').text(remaining);
					$('div.progress').animate({'height': progress+'%'}, 500);

					colorProgress(progress);

					subembayments = msg.subembayments;
					$.each(subembayments, function(key, value) {
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
						$('#fertMan').css({'pointer-events': 'none'});
					} else {
						$('#fertMan').css({'pointer-events': 'auto'});
					}

					// If storm management applied, disable clickability on storm icon
					if (msg.stormapplied) {
						$('#stormMan').css({'pointer-events': 'none'});
					} else {
						$('#stormMan').css({'pointer-events': 'auto'});
					}
				})
			});
		</script>
	</body>
</html>