		<title>{{$tech->Technology_Strategy}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
<meta name="csrf-token" id="token" content="{{ csrf_token() }}">
		

<div class="popdown-content" id="app">
	<header><h2>{{$tech->Technology_Strategy}}</h2></header>
	<section class="body">

			<div class="technology">
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
					<img src="http://2016.watershedmvp.org/images/SVG/{{$tech->Icon}}" width="75">
				 {{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
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
					<p>Nitrogen removed by this treatment: {{round($treatment->Nload_Reduction)}}kg</p>
					<p>Treatment reduction rate: {{$treatment->Treatment_Value}}%</p>
					<p>Total Treatment Cost: ${{money_format('%10.0n', $treatment->Cost_Total)}}</p>
					@if($treatment->Treatment_UnitMetric == 'Acres' && $treatment->Treatment_MetricValue > 0)
						<p>{{$treatment->Treatment_UnitMetric}} treated: {{$treatment->Treatment_MetricValue}}</p>
					@endif

				@if($tech->Show_In_wMVP == 1)
					<!-- <p class="select"><button id="select_area">Select a location</button> <span>@{{subembayment}}</span></p> -->
					{{-- <p class="select"><button id="select_area">Select a location</button> <span>@{{subembayment}}</span></p> --}}
					<p>
						<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated: 
						<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;" value="{{$treatment->Treatment_MetricValue}}"></label>
					</p>
				@elseif($tech->Show_In_wMVP == 2)
					<!-- <div id="info">Select a polygon for the treatment area:  -->
						<button id="select_polygon">Draw Polygon</button>
					<!-- </div> -->
					<!-- <p class="select"><button id="select_area">Select a polygon</button> <span>@{{subembayment}}</span></p> -->
				@elseif($tech->Show_In_wMVP == 3)
					{{-- <p class="select"><button id="select_area">Select a polygon</button> <span>@{{subembayment}}</span></p> --}}
					<p>
						<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated: 
						<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;" value="{{$treatment->Treatment_MetricValue}}"></label>
					</p>

				@elseif($tech->Show_In_wMVP == 4)
{{-- 					<table>
						<thead>
							<tr>
								<th colspan="2">Stormwater Nitrogen</th>
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
							
							 		<td>@{{storm_unatt | round}}kg</td>
									<td>@{{storm_att | round }}kg</td>
									<td>@{{storm_unatt_treated | round }}kg</td>
									<td>@{{storm_att_treated | round }}kg</td>
									<td>@{{storm_difference | round }}kg</td>
							</tr>
							
						</tbody>
					</table> --}}
				@endif
		

			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.<br />
				
				<input type="range" id="storm-percent" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="storm_percent" value="{{$treatment->Treatment_Value}}"> @{{storm_percent}}%
			</p>
			<p>
				<button id="updatetreatment">Update</button>
				<button id="deletetreatment" class='button--cta right'><i class="fa fa-trash-o"></i> Delete</button>
			</p>


	</section>
</div>


<script src="{{url('/js/main.js')}}"></script>
<!-- <script src="{{url('/js/app.js')}}"></script> -->


<script>
	$(document).ready(function(){
	 treatment = {{$treatment->TreatmentID}};
	 @if($tech->Show_In_wMVP < 4)
		 // var location;
			$('#select_area').on('click', function(f){
				f.preventDefault();
				// console.log('button clicked');
					$('#popdown-opacity').hide();
					map.on('click', function(e){
						// console.log('map clicked');
						// console.log(e.mapPoint.x, e.mapPoint.y);
					
						var url = "{{url('/map/point/')}}"+'/'+e.mapPoint.x+'/'+ e.mapPoint.y + '/' + treatment;
						$.ajax({
							method: 'GET',
							url: url
						})
							.done(function(msg){
								msg = $.parseJSON(msg);
								// console.log(msg.SUBEM_DISP);
								// console.log(msg);
								// location = msg.SUBEM_ID;
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

			});
			$('#updatetreatment').on('click', function(e){
				// need to save the treated N values and update the subembayment progress
				e.preventDefault();
				// console.log('clicked');
				var percent = $('#storm-percent').val();
				var units = 1;
				if ('{{$tech->Show_In_wMVP}}' != '2' )
				{
					units = $('#unit_metric').val();
				}
				else if ('{{$tech->Unit_Metric}}' == 'Each')
				{
					units = 1;
				}
				else
				{
					units = 0.00000000;
				}
				var url = "{{url('/update/storm', $treatment->TreatmentID)}}" + '/' + percent + '/' + units;
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
						$( "#update" ).trigger( "click" );
					});
			});
			@else
				$('#updatetreatment').on('click', function(e)
				{
					e.preventDefault();
					var percent = $('#storm-percent').val();
					var url = "{{url('/update/storm-percent', $treatment->TreatmentID)}}"  + '/' + percent;
					$.ajax({
						method: 'GET',
						url: url
					})
						.done(function(msg){
							$('#popdown-opacity').hide();
							$( "#update" ).trigger( "click" );
						});

				});

			@endif



		$('#deletetreatment').on('click', function(e){
		var url = "{{url('delete_treatment', $treatment->TreatmentID)}}";
		$.ajax({
			method: 'GET',
			url: url
		})
			.done(function(msg){
				$('#popdown-opacity').hide();
				$("li[data-treatment='{{$treatment->TreatmentID}}']").remove();
				
				for (var i = map.graphics.graphics.length - 1; i >= 0; i--) {
                
	                if (map.graphics.graphics[i].attributes) {

	                    if (map.graphics.graphics[i].attributes.treatment_id == treatment) {

	                    	map.graphics.remove(map.graphics.graphics[i])
	                    }
	                }
           		}

           		$( "#update" ).trigger( "click" );
			});
		});


		});
</script>