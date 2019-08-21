<!-- Set the title to 'Technology_Strategy' from the dbo.v_Technology_Matrix obtained by 'TechnologyController.php' -->
<title>{{$tech->Technology_Strategy}}</title>
<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
<meta name="csrf-token" id="token" content="{{ csrf_token() }}">
<!-- Set the popdown up with a header, a body with the technology, a table and reduction rate selection -->
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
				<img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}" width="75">{{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
			</a>
		</div>

		<!--
			Case/switch based on the Show_In_wMVP field
			0 => (this shouldn't ever appear because this technology shouldn't have been listed)
			1 => user will enter a unit metric to use for calculations (acres, linear feet, etc)
			2 => user will need to select a polygon for the treatment area
			3 => user will select a polygon and enter the unit metric for the treatment area calculation
				unit metric is used to calculate cost
			4 => user does not enter a treatment area (Fertilizer Mgmt or Stormwater BMPs)
		-->
		<!-- TODO: Replace ifelse with case/switch once Laravel is upgraded -->
		<!-- 1 => user will enter a unit metric to use for calculations (acres, linear feet, etc) -->
		@if($tech->Show_In_wMVP == 1)
			<p class="select">
				<button id="select_area_{{$treatment->TreatmentID}}">Select a location</button> 
				<span>@{{subembayment}}</span>
			</p>
			<p>
				<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated:
				<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;"></label>
			</p>
		<!-- 2 => user will need to select a polygon for the treatment area -->
		@elseif($tech->Show_In_wMVP == 2)
			<button id="select_polygon_{{$treatment->TreatmentID}}">Draw Polygon</button>
		<!-- 3 => user will select a polygon and enter the unit metric for the treatment area calculation
		unit metric is used to calculate cost -->
		@elseif($tech->Show_In_wMVP == 3)
			<p class="select">
				<button id="select_polygon_{{$treatment->TreatmentID}}">Select a polygon</button> 
				<span>@{{subembayment}}</span>
			</p>
			<p>
				<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated:
				<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;"></label>
			</p>
		<!-- 4 => user does not enter a treatment area (Fertilizer Mgmt or Stormwater BMPs) -->
		@elseif($tech->Show_In_wMVP == 4)
			<table>
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
			</table>
		@endif
		<!-- 4 => user does not enter a treatment area (Fertilizer Mgmt or Stormwater BMPs) -->
		@if($tech->Show_In_wMVP == 4)
			<p> Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.
				<br />
				<input 
					type="range" 
					id="storm-percent" 
					min="{{$tech->Nutri_Reduc_N_Low}}" 
					max="{{$tech->Nutri_Reduc_N_High}}" 
					v-model="storm_percent" 
					value="{{$tech->Nutri_Reduc_N_Low}}"
				> 
				@{{storm_percent}}%
			</p>
		@endif
		<p>
			<button id="apply_treatment_{{$treatment->TreatmentID}}">Apply</button>
			<button id="cancel_treatment_{{$treatment->TreatmentID}}" class="button--cta right"><i class="fa fa-ban"></i> Cancel</button>
		</p>
	</section>
</div>

<!-- Import the vue data and computed properties -->
<script src="{{url('/js/main.js')}}"></script>

<script>
	$(document).ready(function(){

		// Remove loading icon from technology icon
		$('div.fa.fa-spinner.fa-spin').remove()

		// Retrieve treatment id, icon from props
		treatment = {{$treatment->TreatmentID}};
		icon = '{{$tech->Icon}}';
		$('#select_area_'+treatment).data('icon', icon.toString())
		 
		// If technology is not fertilizer or stormwater management
	 	@if($tech->Show_In_wMVP < 4) {

			// Handle on-click event for closing popdown, disassociating, and deleting the selected technology from the user's scenario
			$('#closeWindow').on('click', function(e) {
				$('#popdown-opacity').hide();
				for (var i = map.graphics.graphics.length - 1; i >= 0; i--) {
					if (map.graphics.graphics[i].attributes) {
						if (map.graphics.graphics[i].attributes.treatment_id == treatment) {
							map.graphics.remove(map.graphics.graphics[i])
						}
					}
				}
			})

			// Handle on-click event for selecting a location
			$('#select_area_'+treatment).on('click', function(f) {
				f.preventDefault();

				// Hide modal, activate point geometry on map's draw-toolbar
				$('#popdown-opacity').hide();
				tb.activate('point')
			});

			// Handle on-click event for drawing a custom polygon
			$('#select_polygon_'+treatment).on('click', function(f) {
				f.preventDefault();
				$('#popdown-opacity').hide();
				map.disableMapNavigation();
				tb.activate('polygon');
			});

			// Handle on-click event for applying selected technology, adding to applied technology stack
			$('#apply_treatment_'+treatment).on('click', function(e) {
				e.preventDefault();
				var percent = 0;
				var units = 1;
				var subemID =  $('.select > span').data('subemid')
				if ('{{$tech->Show_In_wMVP}}' == '1' || '{{$tech->Show_In_wMVP}}' == '3' ) {
					units = $('#unit_metric').val();
				}
				else if ('{{$tech->Unit_Metric}}' == 'Each') {
					units = 1;
				}
				else {
					units = 0;
				}

				// Create and trigger API route url from parsed properties
				var url = "{{url('/apply_storm')}}" + '/' +  treatment + '/' + percent + '/' + units + '/' + subemID;
				$.ajax({
					method: 'GET',
					url: url
				})
				.done(function(msg) {
					msg = Math.round(msg);
					$('#n_removed').text(msg);
					$('#popdown-opacity').hide();
					$( "#update" ).trigger( "click" );
					var newtreatment = '<li class="technology" data-treatment="{{$treatment->TreatmentID}}"><a href="{{url('/edit', $treatment->TreatmentID)}}" class="popdown"><img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}" alt=""></a></li>';
					$('ul.selected-treatments').append(newtreatment);
					$('ul.selected-treatments li[data-treatment="{{$treatment->TreatmentID}}"] a').popdown();
				});
			});
		}

		// Else if technology is fertilizer or stormwater management
		@else {
			// Clicking the close window button: hide the popdown, set the url to cancel the treatment which runs the DELtreatment
			// stored procedure, send an ajax GET method to the url to disassociate the treatment with the parcels and handle the
			// stormwater icon clickability
			$('#closeWindow').on('click', function (e) {
				e.preventDefault();
				$('#popdown-opacity').hide();
				$('#storm-percent').val(0);
				let treat = {{$treatment->TreatmentID}};
				let url = "{{url('cancel')}}" + '/' + treat + '/' + 'storm';
				$.ajax({
					method: 'GET',
					url: url
				})
				.done(function(msg) {
					// If storm management applied, disable clickability for icon
					if (msg.stormApplied) {
						$('#stormMan')
							.css({'pointer-events': 'none'})
					} else {
						$('#stormMan')
							.css({'pointer-events': 'auto'})
					}
				});
			});

			// Handle on-click event for applying management technology
			$('#apply_treatment_'+treatment).on('click', function(e) {
				e.preventDefault();
				var percent = $('#storm-percent').val();
				var url = "{{url('/apply_percent')}}" + '/' +  treatment + '/' + percent + '/storm';
				$.ajax({
					method: 'GET',
					url: url
				})
				.done(function(msg) {
					msg = Math.round(msg);
					$('#n_removed').text(msg);
					$('#popdown-opacity').hide();
					$( "#update" ).trigger( "click" );
					var newtreatment = '<li class="technology" data-treatment="{{$treatment->TreatmentID}}"><a href="{{url('/edit', $treatment->TreatmentID)}}" class="popdown"><img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}" alt=""></a></li>';
					$('ul.selected-treatments').append(newtreatment);
					$('ul.selected-treatments li[data-treatment="{{$treatment->TreatmentID}}"] a').popdown();
				});
			});
		}
		@endif

		// Clicking the cancel window button: hide the popdown, set the url to cancel the treatment which runs the DELtreatment
		// stored procedure, send an ajax GET method to the url to disassociate the treatment with the parcels and handle the
		// stormwater icon clickability
		$('#cancel_treatment_'+treatment).on('click', function(e) {
			e.preventDefault();
			$('#popdown-opacity').hide();
			$('#storm-percent').val(0);
			let treat = {{$treatment->TreatmentID}};
			var url = "{{url('cancel')}}" + '/' + treat + '/' + 'storm';
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(msg) {
				// If storm management applied, disable clickability for icon
				if (msg.stormApplied) {
					$('#stormMan')
						.css({'pointer-events': 'none'})
				} else {
					$('#stormMan')
						.css({'pointer-events': 'auto'})
				}
				for (var i = map.graphics.graphics.length - 1; i >= 0; i--) {
					if (map.graphics.graphics[i].attributes) {
						if (map.graphics.graphics[i].attributes.treatment_id == treatment) {
							map.graphics.remove(map.graphics.graphics[i])
						}
					}
				}
			});
		});
	});
</script>
