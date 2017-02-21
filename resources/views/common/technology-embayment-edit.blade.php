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
				<p>Treatment reduction rate: {{$treatment->Treatment_Value}} per {{$treatment->Treatment_UnitMetric}} for {{$treatment->Treatment_MetricValue}} {{$treatment->Treatment_UnitMetric}} </p>

				@if($tech->Show_In_wMVP == 1)
					<!-- <p class="select"><button id="select_area">Select a location</button> <span>@{{subembayment}}</span></p> -->
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
					<p class="select"><button id="select_area">Select a polygon</button> <span>@{{subembayment}}</span></p>
					<p>
						<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated:
						<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;" value="{{$treatment->Treatment_MetricValue}}"></label>
					</p>
				@endif
			<p>
				Enter a valid reduction rate between {{round($tech->Absolu_Reduc_perMetric_Low)}} and {{round($tech->Absolu_Reduc_perMetric_High)}}kg per {{$tech->Unit_Metric}}.<br />

				<input type="range" id="embayment-percent" min="{{round($tech->Absolu_Reduc_perMetric_Low, 2)}}" max="{{round($tech->Absolu_Reduc_perMetric_High, 2)}}" v-model="embayment_percent" value='{{$treatment->Treatment_Value}}' step="0.1"> @{{embayment_percent}}
			</p>
			<p>
				<button id="updatetreatment">Update</button>
				<button id="deletetreatment" class='button--cta right'><i class="fa fa-trash-o"></i> Delete</button>
			</p>


	</section>
</div>


<script src="{{url('/js/main.js')}}"></script>


<script>
	$(document).ready(function(){
	 treatment = {{$treatment->TreatmentID}};
	 var subemid = '';
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
							subemid = msg.SUBEM_ID;
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

		});

		$('#updatetreatment').on('click', function(e)
				{
					e.preventDefault();
					var rate = $('#embayment-percent').val();
					var units = $('#unit_metric').val();
					var url = "{{url('/update/embay', $treatment->TreatmentID)}}"  + '/' + rate + '/' + units + '/' + subemid;
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
			});
		});


	});
</script>
