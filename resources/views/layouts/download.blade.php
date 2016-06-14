<html>
	<head>
		<title>WatershedMVP Scenario Results</title>

	</head>
	<body>
		<div class="wrapper">
		<div class="content">
			<h1>Scenario: {{$scenario->ScenarioID}} for {{$scenario->AreaName}}</h1>
			<p>Link to scenario: {{url('map', $scenario->AreaID, $scenario->ScenarioID)}}</p>
			<div id="app">
			<h2>Technology Stack</h2>
		
			<table>
				<thead>
					<tr>
						<th>Technology</th>
						<th>Parcels Affected</th>
						<th>Nitrogen Removed</th>
					</tr>
				</thead>
				<tbody>

					@foreach($results as $result)
					<tr>
						<td>{{$result->Technology_Strategy}} ({{$result->TreatmentID}})</td>
						<td>{{$result->Treatment_Parcels}}</td>
						<td>{{$result->Nload_Reduction}}kg</td>
	
					</tr>
					@endforeach

				</tbody>
			</table>
			
		

		</div>
		</div>
	</div>
	</body>
</html>