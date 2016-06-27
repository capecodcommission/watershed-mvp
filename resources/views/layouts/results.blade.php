<html>
	<head>
		<title>WatershedMVP Scenario Results</title>
		<link rel="stylesheet" href="{{url('/css/app.css')}}">
  	<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>  
	</head>
	<body>
		<div class="wrapper">
		<div class="content">
			<h1>Embayment: </h1>

			<div id="app">
			<h2>Technology Stack</h2>
		
			<table>
				<thead>
					<tr>
						<th colspan="2">Technology</th>
						<th>Parcels Affected</th>
						<th>Nitrogen Removed</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>

					@foreach($results as $result)
					<tr id="treat_{{$result->TreatmentID}}">
						<td><div class="technology"><img src="http://www.cch2o.org/Matrix/icons/{{$result->Icon}}" alt=""></div></td>
						<td>{{$result->Technology_Strategy}} ({{$result->TreatmentID}})</td>
						<td>{{$result->Treatment_Parcels}}</td>
						<td>{{round($result->Nload_Reduction)}}kg</td>
						<td><a data-treatment="{{$result->TreatmentID}}" class="deletetreatment button--cta"><i class="fa fa-trash-o"></i> Delete</a></td>
	
					</tr>
					@endforeach

				</tbody>
			</table>
			<h2>Towns Affected</h2>
			<table>
				<thead>
					<tr>
						<th>Town</th>
						<th>Treatment</th>
						<th>Parcels Affected</th>
						<th>Nitrogen Removed (unattenuated)</th>
					</tr>
				</thead>
				<tbody>
					@foreach($towns as $town)
						<tr>
							<td>{{$town->town}}</td>
							<td>{{$town->wtt_treatment_id}}</td>
							<td>{{$town->wtt_tot_parcels}}</td>
							<td>{{round($town->wtt_unatt_n_removed)}}kg</td>
						</tr>
	
					@endforeach
				</tbody>
			</table>
			<h2>Subembayments</h2>
			<table>
				<thead>
					<tr>
						<th>Subembayment</th>
						<th>Original N<sup>1</sup></th>
						<th>N Removed (Attenuated)<sup>2</sup></th>
						<th>Scenario N</th>
						<th>Target N</th>
						<th>N Remaining to Target <sup>3</sup></th>
					</tr>
				</thead>
				<tbody>
					@foreach($subembayments as $sub)
					<tr>
						<td>{{$sub->subem_disp}}</td>
						<td>{{round($sub->n_load_att)}}kg</td>
						<td>{{round($sub->n_load_att_removed)}}kg</td>
						<td>{{round($sub->n_load_scenario)}}kg</td>
						<td>{{round($sub->n_load_target)}}kg</td>
						<td>{{round($sub->n_load_scenario - $sub->n_load_target)}}</td>
					</tr>

					@endforeach
				</tbody>

			</table>
			<p><sup>1</sup> The "Original N" value is calculated (attenuated) total Nitrogen for the subembayment. </p>
			<p><sup>2</sup>A negative number in this column represents Nitrogen added to a subembayment as part of a collection treatment.</p>
			<p><sup>3</sup>A negative number in this column means the user has exceeded the target for this subembayment.</p>
					
					
			<p><a href="{{url('map', [$embay_id, $scenarioid])}}" class="button">back to map</a></p>

		</div>
		</div>
	</div>

	<script>
	$(document).ready(function(){
		$('.deletetreatment').on('click', function(e){

			e.preventDefault();
			var treat = $(this).data('treatment');
			var url = "{{url('delete')}}" + '/' + treat;
			$.ajax({
				method: 'GET',
				url: url
			})
				.done(function(msg){
					$('#treat_'+treat).remove();
				});
			});
	});
	</script>
	</body>
</html>