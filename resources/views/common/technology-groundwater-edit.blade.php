		<title>{{$tech->Technology_Strategy}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">

		
<?php 
	setlocale(LC_MONETARY, 'en_US');

?>
<div class="popdown-content" id="app">
	<header><h2>{{$tech->Technology_Strategy}}</h2></header>
	<section class="body">
<p>{{$treatment->Treatment_Class}}</p>
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
			 <fieldset>
				<h3>Treatment Stats</h3>
				<ul>
					<li>Treatment reduction rate: <strong>{{$treatment->Treatment_Value}}ppm</strong></li>
					<li>Nitrogen removed by this treatment: <strong>{{round($treatment->Nload_Reduction)}}kg</strong></li>
					<li>Parcels affected: <strong>{{$treatment->Treatment_Parcels}}</strong></li>
					<li>Total Treatment Cost: <strong>{{money_format('%10.0n', $treatment->Cost_Total)}}</strong></li>
				</ul>
			</fieldset>	
					
				
 				@if($tech->Show_In_wMVP == 1)
					
					@if($treatment->Treatment_MetricValue > 0)
						<p>{{$treatment->Treatment_UnitMetric}} treated: {{$treatment->Treatment_MetricValue}}</p>
					@endif
					<p>
						<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated: 
						<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;" value="{{$treatment->Treatment_MetricValue}}"></label>
					</p>
				@elseif($tech->Show_In_wMVP == 2)

					<p>
						Acreage of treatment area: {{round($treatment->Treatment_Acreage,2)}}
					</p>


				@elseif($tech->Show_In_wMVP == 3)
					<p>
						Acreage of treatment area: {{round($treatment->Treatment_Acreage,2)}}
					</p>
					<p>
						<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} (for cost calculation): 
						<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;" value="{{$treatment->Treatment_MetricValue}}"></label>
					</p>										
				
				@endif 
			
			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.<br />
				
				<input type="range" id="ground-percent" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="ground_percent" value="{{$treatment->Treatment_Value}}" style="display:inline;"> @{{ground_percent}}%
			</p>
			<p>
				<button id="updatetreatment">Update</button>
				<button id="deletetreatment" class='button--cta right'><i class="fa fa-trash-o"></i> Delete</button>
			</p>


	</section>
</div>


<script src="{{url('/js/main.js')}}"></script>
{{-- <script src="{{url('/js/app.js')}}"></script> --}}


<script>
	$(document).ready(function(){
	 treatment = {{$treatment->TreatmentID}};
	 typeid = {{$treatment->TreatmentType_ID}};
		$('#select_area').on('click', function(f){
			f.preventDefault();
			// console.log('button clicked');
				$('#popdown-opacity').hide();
				map.on('click', function(e){

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
			map.disableMapNavigation();
			tb.activate('polygon');


		});

				$('#updatetreatment').on('click', function(e)
				{
					e.preventDefault();
					var rate = $('#ground-percent').val();
					var units = 1;
					if ('{{$tech->Show_In_wMVP}}' != '2')
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
					var url = "{{url('/update/groundwater', $treatment->TreatmentID)}}"  + '/' + rate + '/' + units;
					$.ajax({
						method: 'GET',
						url: url
					})
						.done(function(msg){
							$('#popdown-opacity').hide();
							$( "#update" ).trigger( "click" );
						});

				});

		
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