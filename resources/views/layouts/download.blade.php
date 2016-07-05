<html>
	<head>
		<title>WatershedMVP Scenario Results</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
						<th>Nitrogen Removed (kg)</th>
						<th>Treatment Cost</th>
						<th>Cost per kg Nitrogen Removed</th>
					</tr>
				</thead>
				<tbody>
				<?php $row = 7; ?>

					@foreach($results as $result)
					<tr>
						<td>{{$result->Technology_Strategy}} ({{$result->TreatmentID}})</td>
						<td>{{$result->Treatment_Parcels}}</td>
						<td>{{round($result->Nload_Reduction)}}</td>
						<td>{{money_format('%10.0n', $result->Cost_Total)}}</td>
						<td>@if($result->Nload_Reduction > 0) {{$result->Cost_Total/$result->Nload_Reduction}} @endif</td>
						<?php $row++; ?>
					</tr>
					@endforeach
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>Total Nitrogen Removed</td>
						<td>Total Scenario Cost</td>
						<td>Avg Cost per kg N removed</td>
					</tr>
					<tr>
						<td><strong>Scenario Totals:</strong></td>
						<td></td>
						<td>=SUM(C8:C{{$row}})</td>
						<td>=SUM(D8:D{{$row}})</td>
						<td>=(SUM(D8:D{{$row}})/SUM(C8:C{{$row}}))</td>
					</tr>
				</tbody>
			</table>
			
		

		</div>
		</div>
	</div>
	</body>
</html>