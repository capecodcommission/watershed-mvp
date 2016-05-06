		<title>{{$tech->Technology_Strategy}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
<meta name="csrf-token" id="token" content="{{ csrf_token() }}">
		

<div class="popdown-content" id="app">
	<header><h2>{{$tech->Technology_Strategy}}</h2></header>
	<section class="body">
		<form action="" @submit.prevent="AddNewTreatment" method="POST">
		
			<!-- 	<input type="text" name="csrf-token" id="token" value="{{ csrf_token() }}">
				<input type="text" name="TreatmentType_ID" id="TreatmentType_ID" v-model="Treatment.TreatmentType_ID">
				<input type="text" v-model="Treatment.ScenarioID">
		 -->

			<div class="technology">
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
					<img src="http://www.cch2o.org/Matrix/icons/{{$tech->Icon}}" width="75">
				<br />{{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
				</a>			
			</div>
<!-- 			<div>
				<div id="info">
	  <div>Select a shape then draw on map to add graphic</div>

	  <button id="Polygon">Polygon</button>

	</div> -->


			<!-- 
					This needs to be a case/switch based on the show_in_wmvp field
					0 => (this shouldn't ever appear because this technology shouldn't have been listed)
					1 => user will enter a unit metric to use for calculations (acres, linear feet, etc)
					2 => user will need to select a polygon for the treatment area
					3 => user will select a polygon and enter the unit metric for the treatment area calculation
					4 => user does not enter a treatment area (Fertilizer Mgmt or Stormwater BMPs)
			 -->

				@if($tech->Unit_Metric =='Acres')
					<p class="select"><button id="select_area">Select a location</button> <span>@{{subembayment}}</span></p>
					<p>
						<label for="acres">Enter number of acres to be treated: 
						<input type="text" id="acres" name="acres" size="3" style="width: auto;"></label>
					</p>
				@endif
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
					<td>@{{ fert_unatt | round }}kg</td>
					<td>@{{fert_att | round }}kg</td>
					<td>@{{fert_unatt_treated |round}}kg</td>
					<td>@{{fert_treated | round}}kg</td>
					<td>@{{fert_difference | round}}kg</td> 
				</tr>
				
			</tbody>
		</table>
			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.<br />
				<input type="range" id="effective" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="Treatment.Treatment_PerReduce">
			</p>
<!-- 			<p><a href="#" class="button">Apply</a>
				<button type="submit">Apply</button>
			</p> -->
			</form>

	</section>
</div>


<script src="{{url('/js/main.js')}}"></script>
<script src="{{url('/js/app.js')}}"></script>


<script>
	$(document).ready(function(){

		$('#select_area').on('click', function(f){
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
	});
</script>