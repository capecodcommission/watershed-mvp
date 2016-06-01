		<title>{{$tech->Technology_Strategy}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
		

<div class="popdown-content" id="app">
	<header><h2>{{$tech->Technology_Strategy}}</h2></header>
	<section class="body">

			<div class="technology">
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
					<img src="http://www.cch2o.org/Matrix/icons/{{$tech->Icon}}" width="75">
				 <i class="fa fa-question-circle"></i>
				</a>			
			</div>

			<!-- 
					This needs to be a case/switch based on the show_in_wmvp field
					0 => (this shouldn't ever appear because this technology shouldn't have been listed)
					1 => user will enter a unit metric to use for calculations (acres, linear feet, etc)
					2 => user will need to select a polygon for the treatment area
					3 => user will select a polygon and enter the unit metric for the treatment area calculation
						unit metric is used to calculate cost
					4 => user does not enter a treatment area (Fertilizer Mgmt or Stormwater BMPs)
			 -->

			</div>
		<table>
			<thead>
				<tr>
					<th colspan="2">Fertilizer Nitrogen</th>
					<th colspan="2">After Treatment</th>
					<th></th>
				</tr>
				<tr>
					<th>Unattenuated</th>
					<th>Attenuated</th>
					<th>Unattenuated</th>
					<th>Attenuated</th>
					<th>N Removed</th>
				</tr>
			</thead>
			<tbody>
				<tr>
				
				 		<td>@{{fert_unatt | round}}kg</td>
						<td>@{{fert_att | round }}kg</td>
						<td>@{{fert_unatt_treated | round }}kg</td>
						<td>@{{fert_treated | round }}kg <sup>1</sup></td>
						<td>@{{fert_difference | round }}kg</td>
				</tr>
				
			</tbody>
		</table>
			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.<br />
				
				<input type="range" id="{{$type}}-percent" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="fert_percent"> @{{fert_percent}}%
			</p>
			<p>
				<button id="applytreatment">Apply</button>
			</p>


	</section>
</div>


<script src="{{url('/js/main.js')}}"></script>
<script src="{{url('/js/app.js')}}"></script>


<script>
	$(document).ready(function(){
	 treatment = {{$treatment['TreatmentId']}};
		$('#select_area').on('click', function(f){
			f.preventDefault();
			// console.log('button clicked');
				$('#popdown-opacity').hide();
				map.on('click', function(e){

					// console.log(e.mapPoint.x, e.mapPoint.y);
				
					var url = "{{url('/map/point/')}}"+'/'+e.mapPoint.x+'/'+ e.mapPoint.y;
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
						})

			});
		});

		$('#select_polygon').on('click', function(e){
			f.preventDefault();
			$('#popdown-opacity').hide();

			map.disableMapNavigation();
			tb.activate('polygon');

		});
		$('#applytreatment').on('click', function(e){
			// need to save the treated N values and update the subembayment progress
			// 
			e.preventDefault();
			// console.log('clicked');
			var percent = $('#fert-percent').val();
			var url = "{{url('/apply_percent')}}" + '/' +  {{$treatment['TreatmentId']}} + '/' + percent + '/fert';
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