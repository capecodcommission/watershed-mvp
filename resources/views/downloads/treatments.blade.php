<html>
	<head>
		<title>WatershedMVP Scenario Results</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{{-- {{ HTML::style('css/download.css') }} --}}
	</head>
	<body>

		<div class="wrapper results_download">
		<div class="content">
			<h1>Scenario: {{$scenario->ScenarioID}} for {{$scenario->AreaName}}</h1>
			<p>Link to scenario: {{url('map', [$scenario->AreaID, $scenario->ScenarioID])}}</p>
			<div id="app">
			<h2>Technology Stack</h2>
		
			<table>
				<thead>
					<tr>
						<th>Technology</th>
						<th>ID</th>
						<th>Parcels Affected</th>
						<th>Nitrogen Removed (kg)</th>
						<th>Treatment Total Cost</th>
						<th>Cost per kg Nitrogen Removed</th>
						<th>Unit Metric</th>
						<th>Num Units</th>
					</tr>
				</thead>
				<tbody>
				<?php $row = 7; ?>
					@foreach($results as $result)
					<tr>
						<td>{{$result->Technology_Strategy}}</td>
						<td>{{$result->TreatmentID}}</td>
						<td>{{$result->Treatment_Parcels}}</td>
						<td>{{round($result->Nload_Reduction)}}</td>
						<td>{{money_format('%10.0n', $result->Cost_Total)}}</td>
						<td>@if($result->Nload_Reduction > 0) {{$result->Cost_Total/$result->Nload_Reduction}} @endif <?php $row++; ?></td>
						<td>{{$result->Treatment_UnitMetric}}</td>
						<td>{{$result->Treatment_MetricValue}}</td>
					</tr>
					@endforeach
					<tr style="border-top: 2px double #000000;">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>						
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><strong>Total Nitrogen Removed</strong></td>
						<td><strong>Total Scenario Cost</strong></td>
						<td><strong>Avg Cost per kg N removed</strong></td>
						<td></td>
						<td></td>						
					</tr>
					<tr class="summary">
						<td><strong>Scenario Totals:</strong></td>
						<td></td>
						<td></td>
						<td class="total_nitrogen">=SUM(C8:C{{$row}})</td>
						<td class="total_cost">=SUM(D8:D{{$row}})</td>
						<td class="avg_cost_per_kg">=(SUM(D8:D{{$row}})/SUM(C8:C{{$row}}))</td>
					</tr>
				</tbody>
			</table>
			<h2>Towns Affected</h2>
			<table>
				<thead>
					<tr>
						<th>Town</th>
						<th>Treatment_Wiz ID</th>
						<th>Parcels Affected</th>
						<th>Nitrogen Removed (unattenuated kg)</th>
					</tr>
				</thead>
				<tbody>
					@foreach($towns as $town)
						<tr>
							<td>{{$town->town}}</td>
							<td>{{$town->wtt_treatment_id}}</td>
							<td>{{$town->wtt_tot_parcels}}</td>
							<td>{{round($town->wtt_unatt_n_removed)}}</td>
						</tr>
	
					@endforeach
				</tbody>
			</table>	
			<p></p>		
			<p></p>
			{{-- <p>Scenario Created: {{$scenario->CreateDate}}</p> --}}
			<p>Created by: {{$scenario->user->name}} ({{$scenario->user->email}}) on {{date('Y-m-d', strtotime($scenario->CreateDate))}}</p>
			{{-- <p>Scenario Updated: {{$scenario->UpdateDate}}</p> --}}
			<p>Downloaded on: <?php echo date('Y-m-d H:i:s');?></p>

		</div>
		</div>
	</div>
	</body>
</html>