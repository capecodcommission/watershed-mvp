		<title>{{$tech->Technology_Strategy}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
<meta name="csrf-token" id="token" content="{{ csrf_token() }}">
		

<div class="popdown-content" id="app">
	<header><h2>{{$tech->Technology_Strategy}}</h2></header>
	<section class="body">
	<treatment
			Total_Orig_Nitrogen = 0
			TreatmentID="{{$treatment->TreatmentID}}"
			Polygon = ''
			>
	</treatment>


			<div class="technology">
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
					<img src="http://www.cch2o.org/Matrix/icons/{{$tech->Icon}}" width="75">
				<br />{{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
				</a>			
			</div>
					<p class="select"><button id="select_polygon" v-on:click="drawPolygon">Select a polygon</button> <span>@{{subembayment}}</span></p>

					<p class="select_point">
						<button id="select_destination" style="display:none;">
							Select a destination
						</button> 
						<span>@{{subembayment}}</span>
					</p>
					
			</div>

			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.<br />
				<input type="range" id="septic-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="septic_rate">@{{septic_rate}}
			</p>
			<p>
				<button id="applytreatment">Apply</button>
			</p>
	</section>
</div>


	<template id="treatment-template">
		<div class="treatment" id="@{{TreatmentID}}">
			<p>Total Unattenuated Nitrogen: <span id="total_nitrogen_polygon">@{{Total_Orig_Nitrogen}}</span>; Nitrogen Removed by Treatment: 
			<span id="Nitrogen_Removed">@{{Nitrogen_Removed}}</span></p>
		</div>
	</template>




 <script src="{{url('/js/main.js')}}"></script> 



<script>
	$(document).ready(function(){
	 treatment = {{$treatment->TreatmentID}};
	 func = 'collect';

		$('#select_polygon').on('click', function(f){
			f.preventDefault();
			$('#popdown-opacity').hide();
			map.disableMapNavigation();
			tb.activate('polygon');
			// console.log(tb);
			$('#select_polygon').hide();
			$('#select_destination').show();
			// console.log(msg);
		});
		$('#select_destination').on('click', function(f){
			f.preventDefault();
			// console.log('button clicked');
				$('#popdown-opacity').hide();
				map.on('click', function(e){
					console.log(e);
				
					var url = "{{url('/map/move/')}}"+'/'+e.mapPoint.x+'/'+ e.mapPoint.y +'/' + treatment;
					$.ajax({
						method: 'GET',
						url: url
					})
						.done(function(msg){
							msg = $.parseJSON(msg);
							console.log(msg.SUBEM_DISP);
							// console.log(msg);
							$('#'+msg.SUBEM_NAME+'> .stats').show();
							// $('.notification_count').remove();
							$('#popdown-opacity').show();
							$('.select > span').text('Selected: '+msg.SUBEM_DISP);
							$('.select > span').show();
							$('#select_destination').hide();
						})

			});
		});
	$('#applytreatment').on('click', function(e){
			e.preventDefault();
			var rate = $('#septic-rate').val();
			var url = "{{url('/apply_septic')}}" + '/' +  treatment + '/' + rate + '/septic';
			// console.log(url);
			$.ajax({
				method: 'GET',
				url: url
			})
				.done(function(msg){
					// console.log(msg);
					msg = Math.round(msg);
					$('#n_removed').text(msg);
					$('#popdown-opacity').hide();
				});

		});

	});
</script>