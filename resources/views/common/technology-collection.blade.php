		<title>{{$tech->Technology_Strategy}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
<meta name="csrf-token" id="token" content="{{ csrf_token() }}">
		

<div class="popdown-content" id="app">
	<header><h2>{{$tech->Technology_Strategy}}</h2></header>
	<section class="body">
	<p>Treatment: {{$treatment['TreatmentId']}}</p>
		
	<p>Nitrogen is being collected from a source polygon and moved to a destination point.</p>

			<div class="technology">
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
					<img src="http://www.cch2o.org/Matrix/icons/{{$tech->Icon}}" width="75">
				<br />{{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
				</a>			
			</div>
					<p class="select"><button id="select_polygon">Select a polygon</button> <span>@{{subembayment}}</span></p>

					<p class="select_point">
						<button id="select_destination" style="display:none;">
							Select a destination point
						</button> 
						<span>@{{subembayment}}</span>
					</p>
					
			</div>
		<table>
			<thead>
				<tr>
					<th colspan="2">Starting Values</th>
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
				<!-- 
						need to change this so it shows & updates the relevant N load (storm, fert, septic, etc.) that each technology is acting on. 
						also, the "fert_percent" slider below needs to change based on which N load is being treated for this particular technology
				 -->
{{-- 					<td>@{{ storm_unatt | round }}kg</td>
					<td>@{{storm_att | round }}kg</td>
					<td>@{{fert_unatt_treated |round}}kg</td>
					<td>@{{fert_treated | round}}kg</td>
					<td>@{{fert_difference | round}}kg</td>  --}}
				</tr>
				
			</tbody>
		</table>
			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.<br />
				<input type="range" id="effective" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="Treatment.Treatment_PerReduce">
			</p>


	</section>
</div>


<script src="{{url('/js/main.js')}}"></script>
<script src="{{url('/js/app.js')}}"></script>


<script>
	$(document).ready(function(){
	 treatment = {{$treatment['TreatmentId']}};
		$('#select_destination').on('click', function(f){
			f.preventDefault();
			// console.log('button clicked');
				$('#popdown-opacity').hide();
				map.on('click', function(e){

					console.log(e.mapPoint.x, e.mapPoint.y);
				
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

		$('#select_polygon').on('click', function(f){
			f.preventDefault();
			$('#popdown-opacity').hide();
			// $( "#info" ).trigger( "click" );
			// dom.byId("info")

			map.disableMapNavigation();
			tb.activate('polygon');
			// console.log('polygon clicked');
			// $('#popdown-opacity').show();
			$('#select_destination').show();

		});



	});
</script>