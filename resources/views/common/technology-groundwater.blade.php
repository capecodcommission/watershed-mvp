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

				@if($tech->Show_In_wMVP == 1)

					<p class="select"><button id="select_area">Select a location</button> <span>@{{subembayment}}</span></p>
					<p>
						<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated: 
						<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;"></label>
					</p>
				@elseif($tech->Show_In_wMVP == 2)

						<button id="select_polygon">Draw Polygon</button>

				@elseif($tech->Show_In_wMVP == 3)
					<p class="select"><button id="select_polygon">Draw Polygon</button></p>
					<p>
						<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} (for cost calculations): 
						<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;"></label>
					</p>
				@endif
			

			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.<br />
				
				<input type="range" id="ground-percent" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" value="{{$tech->Nutri_Reduc_N_Low}}" v-model="ground_percent"> @{{ground_percent}}%
			</p>
			<p>
				<button id="applytreatment">Apply</button>
				<button id="canceltreatment" class='button--cta right'><i class="fa fa-ban"></i> Cancel</button>
			</p>


	</section>
</div>


<script src="{{url('/js/main.js')}}"></script>



<script>
	$(document).ready(function(){
	 treatment = {{$treatment['TreatmentID']}};
		$('#select_area').on('click', function(f){

			f.preventDefault();
			destination_active = 1;
			$('#popdown-opacity').hide();

			map.on('click', function(e)
			{		
				if (destination_active > 0) 
				{
				
					var url = "{{url('/map/point/')}}"+'/'+e.mapPoint.x+'/'+ e.mapPoint.y + '/' + treatment;
					$.ajax({
						method: 'GET',
						url: url
					})
						.done(function(msg){
							msg = $.parseJSON(msg);
							$('#'+msg.SUBEM_NAME+'> .stats').show();
							$('#popdown-opacity').show();
							$('.select > span').text('Selected: '+msg.SUBEM_DISP);
							$('.select > span').show();
							destination_active = 0;
						})
				}
			});
		});

		$('#select_polygon').on('click', function(f){
			f.preventDefault();
			$('#popdown-opacity').hide();
			map.disableMapNavigation();
			tb.activate('polygon');
			$('#select_polygon').hide();


		});

		$('#applytreatment').on('click', function(e){
			// need to save the treated N values and update the subembayment progress
			e.preventDefault();

			var percent = $('#ground-percent').val();
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
			
				
			// console.log(units);
			var url = "{{url('/apply_groundwater')}}" + '/' +  treatment + '/' + percent + '/' + units;

			$.ajax({
				method: 'GET',
				url: url
			})
				.done(function(msg){
					msg = Math.round(msg);
					$('#n_removed').text(msg);
					$('#popdown-opacity').hide();
					$( "#update" ).trigger( "click" );
					var newtreatment = '<li class="technology" data-treatment="{{$treatment->TreatmentID}}"><a href="{{url('/edit', $treatment->TreatmentID)}}" class="popdown"><img src="http://2016.watershedmvp.org/images/SVG/{{$tech->Icon}}" alt=""></a></li>';
					$('ul.selected-treatments').append(newtreatment);
					$('ul.selected-treatments li[data-treatment="{{$treatment->TreatmentID}}"] a').popdown();	
				});

			map.destroy()
		});

		$('#canceltreatment').on('click', function(e){
		var url = "{{url('cancel', $treatment->TreatmentID)}}";
		$.ajax({
			method: 'GET',
			url: url
		})
			.done(function(msg){
				$('#popdown-opacity').hide();
			});
		});
	});
</script>