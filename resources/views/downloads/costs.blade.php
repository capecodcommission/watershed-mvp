<html>
	<head>
		<title>WatershedMVP Scenario Costs</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>

		<div class="wrapper results_download">
		<div class="content">
			<h1>Scenario: {{$scenario->ScenarioID}} for {{$scenario->AreaName}}</h1>
			<p></p>
			<h2>Cost Breakdown</h2>

			<table>
				<thead>
					<tr>
						<th>Technology</th>
						<th>ID</th>
						<th>Parcels Affected</th>
						<th>Unattenuated Nitrogen Removed (kg)</th>
						<th>Treatment Construction Cost</th>
						<th>OM Cost</th>
						<th>Collection Cost</th>
						<th>Transport/Disposal Cost</th>
						<th>NonConstruction Cost</th>
						<th>Monitoring Cost</th>
						<th>Total Present Worth</th>
						<th>Cost per kg Nitrogen Removed</th>
					</tr>
				</thead>
				<tbody>
				<?php $row = 7; ?>
					@foreach($scenario->treatments as $result)
					<tr>
						<td>{{$result->TreatmentType_Name}}</td>
						<td>{{$result->TreatmentID}}</td>
						<td>{{$result->Treatment_Parcels}}</td>
						<td>{{round($result->Nload_Reduction)}}</td>
						<td>{{round($result->Cost_Capital, 2)}}</td>
						<td>{{round($result->Cost_OM, 2)}}</td>
						<td>{{round($result->Cost_Collection, 2)}}</td>
						<td>{{round($result->Cost_TransportDisposal, 2)}}</td>
						<td>{{round($result->Cost_NonConstruction, 2)}}</td>
						<td>{{round($result->Cost_Monitor, 2)}}</td>
						<td>{{money_format('%10.0n', $result->Cost_Total)}}</td>
						<td>@if($result->Nload_Reduction > 0) {{($result->Cost_Total/$result->Nload_Reduction)/12.46}} @endif <?php $row++; ?></td>
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
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td><strong>Total Parcels Affected</strong></td>
						<td><strong>Unattenuated Total Nitrogen Removed</strong></td>
						<td><strong>Total Treatment Construction Cost</strong></td>
						<td><strong>Total OM Cost</strong></td>
						<td><strong>Total Collection Cost</strong></td>
						<td><strong>Total Transport/Disposal Cost</strong></td>
						<td><strong>Total NonConstruction Cost</strong></td>
						<td><strong>Total Monitoring Cost</strong></td>
						<td><strong>Total Scenario Cost</strong></td>
						<td><strong>Avg Cost per kg N removed</strong></td>
					</tr>
					<tr class="summary">
						<td><strong>Scenario Totals:</strong></td>
						<td></td>
						<td>=SUM(C8:C{{$row}})</td>
						<td class="total_nitrogen">=SUM(D8:D{{$row}})</td>
						<td>=SUM(E8:E{{$row}})</td>
						<td>=SUM(F8:F{{$row}})</td>
						<td>=SUM(G8:G{{$row}})</td>
						<td>=SUM(H8:H{{$row}})</td>
						<td>=SUM(I8:I{{$row}})</td>
						<td>=SUM(J8:J{{$row}})</td>
						<td class="total_cost">=SUM(K8:K{{$row }})</td>
						<td class="avg_cost_per_kg">=((SUM(K8:K{{$row}})/SUM(D8:D{{$row}}))/12.46)</td>
					</tr>
				</tbody>
			</table>

		</div>
		</div>
	</div>
	</body>
</html>
