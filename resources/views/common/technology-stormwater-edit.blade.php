<title>{{$tech->Technology_Strategy}}</title>
<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
<meta name="csrf-token" id="token" content="{{ csrf_token() }}">	

<div class="popdown-content" id="app">
	<header>
		<div class = 'row'>
			<div class = 'col'>
				<h2>{{$tech->Technology_Strategy}}<button style = 'position: absolute; right: 20; top: 10' id = "closeWindow"><i class = 'fa fa-times'></i></button></h2>
			</div>
		</div>
	</header>
	<section class="body">
		<div class="technology">
			<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
				<img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}" width="75">
				{{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
			</a>			
		</div>
		<p>Nitrogen removed by this treatment: {{round($treatment->Nload_Reduction)}}kg</p>
		<p>Treatment reduction rate: {{$treatment->Treatment_Value}}%</p>
		<p>Total Treatment Cost: ${{money_format('%10.0n', $treatment->Cost_Total)}}</p>
		@if($treatment->Treatment_UnitMetric == 'Acres' && $treatment->Treatment_MetricValue > 0)
			<p>{{$treatment->Treatment_UnitMetric}} treated: {{$treatment->Treatment_MetricValue}}</p>
		@endif
		<!-- 
			This needs to be a case/switch based on the show_in_wmvp field
			0 => (this shouldn't ever appear because this technology shouldn't have been listed)
			1 => user will enter a unit metric to use for calculations (acres, linear feet, etc)
			2 => user will need to select a polygon for the treatment area
			3 => user will select a polygon and enter the unit metric for the treatment area calculation
				unit metric is used to calculate cost
			4 => user does not enter a treatment area (Fertilizer Mgmt or Stormwater BMPs)
		-->
		<!-- TODO: Switch if/else to case/switch once Laravel is upgraded. Case statement switches are unavailable in current version (5.2) -->
		@if($tech->Show_In_wMVP == 1)
			<p>
				<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated: 
				<input v-model="uMetric" type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;" value="{{$treatment->Treatment_MetricValue}}"></label>
			</p>
		@elseif($tech->Show_In_wMVP == 2)
			<button id="select_polygon">Draw Polygon</button>
		@elseif($tech->Show_In_wMVP == 3)
			<p>
				<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated: 
				<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;" value="{{$treatment->Treatment_MetricValue}}"></label>
			</p>
		@elseif($tech->Show_In_wMVP == 4)
			<p> Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.
				<br />
				<input 
					type="range" 
					id="storm-percent" 
					min="{{$tech->Nutri_Reduc_N_Low}}" 
					max="{{$tech->Nutri_Reduc_N_High}}" 
					v-model="storm_percent" 
					value="{{$treatment->Treatment_Value}}"
				> 
				@{{storm_percent}}%
			</p> 
		@endif
		<p>
			<button v-show="storm_percent != {{$treatment->Treatment_Value}}" id="updateManagement">Update</button>
			<button v-show="{{$treatment->Treatment_MetricValue}} != uMetric" id="updateNonManangement">Update</button>
			<button id="deletetreatment" data-treatment = "{{$treatment->TreatmentID}}" class='button--cta right'><i class="fa fa-trash-o"></i> Delete</button>
		</p>
	</section>
</div>

<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function(){

		// Remove loading icon from selected technology
		$('div.fa.fa-spinner.fa-spin').remove()
		treatment = {{$treatment->TreatmentID}};
		 
		// If technology is non-management
		 @if($tech->Show_In_wMVP < 4) {

			// Handle click-event for custom polygon creation
			$('#select_polygon').on('click', function(f) {
				f.preventDefault();
				$('#popdown-opacity').hide();
				map.disableMapNavigation();
				tb.activate('polygon');
			});

			// Handle click-event for updating non-management technologies
			$('#updateNonManangement').on('click', function(e) {
				e.preventDefault();
				var percent = 0
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
				$.ajax({
					method: 'GET',
					url: url
				})
				.done(function(msg) {
					msg = Math.round(msg);
					$('#n_removed').text(msg);
					$('#popdown-opacity').hide();
					$( "#update" ).trigger( "click" );
				});
			});
		}

		// ELse if technology is management-related
		@else {
			
			// Handle click-event for management technology
			$('#updateManagement').on('click', function(e) {
				e.preventDefault();
				var percent = $('#storm-percent').val();
				var url = "{{url('/update/storm-percent', $treatment->TreatmentID)}}"  + '/' + percent;
				$.ajax({
					method: 'GET',
					url: url
				})
				.done(function(msg) {
					$('#popdown-opacity').hide();
					$( "#update" ).trigger( "click" );
				});
			});
		}
		@endif

		// Handle click-event for closing popdown
		$('#closeWindow').on('click', function (e) {
			$('#popdown-opacity').hide();
		})

		// Handle click-event for deleting selected technology
		$('#deletetreatment').on('click', function(e){
			var treat = $(this).data('treatment');
			var url = "{{url('delete_treatment')}}" + '/' + treat + '/' + 'storm'
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