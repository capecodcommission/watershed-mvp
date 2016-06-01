<html>
	<head>
		<title>WatershedMVP Scenario Results</title>
		<link rel="stylesheet" href="{{url('/css/app.css')}}">

	</head>
	<body>
		<div class="wrapper">
			<h1>Embayment: </h1>

			<div id="app">
			<h2>Technology Stack</h2>
			

		
			<table>
				<thead>
					<tr>
						<th colspan="2">Technology</th>
						<th>Parcels Affected</th>
						<th>Nitrogen Removed</th>
					</tr>
				</thead>
				<tbody>

					@foreach($results as $result)
					<tr>
						<td><div class="technology"><img src="http://www.cch2o.org/Matrix/icons/{{$result->Icon}}" alt=""></div></td>
						<td>{{$result->Technology_Strategy}} ({{$result->TreatmentID}})</td>
						<td>{{$result->Treatment_Parcels}}</td>
						<td>{{$result->Nload_Reduction}}kg</td>
	
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
						<th>Nitrogen Removed (unattenuated)</th>
						<th>Parcels Affected</th>
					</tr>
				</thead>
				<tbody>
					@foreach($towns as $town)
						<tr>
							<td>{{$town->town}}</td>
							<td>{{$town->wtt_treatment_id}}</td>
							<td>{{$town->wtt_unatt_n_removed}}kg</td>
							<td>{{$town->wtt_tot_parcels}}</td>
						</tr>
	
					@endforeach
				</tbody>
			</table>

		
		
<p><a href="{{url('map', [$embay_id, $scenarioid])}}">back to map</a></p>

		</div>
		</div>
	
	</body>
</html>