<html>
	<head>
		<title>WatershedMVP Scenario Results</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
			<h1>Scenario: {{$scenario->ScenarioID}} for {{$scenario->AreaName}}</h1>
			<p>Link to scenario: {{url('map', [$scenario->AreaID, $scenario->ScenarioID])}}</p>

			<h2>Embayment Stats</h2>
			<table>
				<tr>
					<td>Unsewered Parcels</td>
					<td>{{$scenario->parcels_septic + $scenario->parcels_gwdp}}</td>
				</tr>
				<tr>
					<td>Sewered Parcels</td>
					<td>{{$scenario->parcels_sewer}}</td>
				</tr>
				<tr>
					<td><strong>Total Parcels</strong></td>
					<td>{{$scenario->Total_Parcels}}</td>
				</tr>
				<tr>
					<td>Total Water Use</td>
					<td>{{$scenario->Total_WaterUse}}</td>
				</tr>
				<tr>
					<td>Total WWFlow</td>
					<td>{{$scenario->Total_WaterFlow}}</td>
				</tr>
				<tr>
					<td>Existing Total Nitrogen (kg)</td>
					<td>{{$scenario->Nload_Existing}}</td>
				</tr>
				<tr>
					<td>Existing Nitrogen - Fertilizer</td>
					<td>{{$scenario->Nload_Fert}}</td>
				</tr>
				<tr>
					<td>Existing Nitrogen - Septic</td>
					<td>{{$scenario->Nload_Sept}}</td>
				</tr>
				<tr>
					<td>Existing Nitrogen - Stormwater</td>
					<td>{{$scenario->Nload_Storm}}</td>
				</tr>
			</table>

			<h2>Technology Stack</h2>
			<table>
				<thead>
					<tr>
						<th>Technology</th>
						<th>ID</th>
						<th>Parcels Affected</th>
						<th>Nitrogen Removed (kg)</th>
						<th>Total Present Worth</th>
						<th>Cost per kg Nitrogen Removed</th>
						<th>Unit Metric</th>
						<th>Num Units</th>
						<th>Wastewater Flow</th>
						<th>Water Use</th>
						<th>Road Length (ft)</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$start_row = 20;
					$row = $start_row - 1;
				?>
					@foreach($scenario->treatments as $result)
					<tr>
						<td>{{$result->Technology_Strategy}}</td>
						<td>{{$result->TreatmentID}}</td>
						<td>{{$result->Treatment_Parcels}}</td>
						<td>{{round($result->Nload_Reduction)}}</td>
						<td>{{money_format('%10.0n', $result->Cost_Total)}}</td>
						<td>@if($result->Nload_Reduction > 0) {{($result->Cost_Total/$result->Nload_Reduction)/12.46}} @endif <?php $row++; ?></td>
						<td>{{$result->Treatment_UnitMetric}}</td>
						<td>{{$result->Treatment_MetricValue}}</td>
						<td>{{$result->Treatment_Wastewater_Flow}}</td>
						<td>{{$result->Treatment_WaterUse}}</td>
						<td>{{$result->Clipped_Rds_LinFeet}}</td>
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
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr class="summary">
						<td><strong>Scenario Totals:</strong></td>
						<td></td>
						<td></td>
						<td class="total_nitrogen">=SUM(D{{$start_row}}:D{{$row}})</td>
						<td class="total_cost">=SUM(E{{$start_row}}:E{{$row}})</td>
						<td class="avg_cost_per_kg">=((SUM(E{{$start_row}}:E{{$row}})/SUM(D{{$start_row}}:D{{$row}}))/12.46)</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="total_road_length">=SUM(K8:K{{$row}})</td>
					</tr>
				</tbody>
			</table>
			<h2>Towns Affected</h2>
			<!-- <table>
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
			</table> -->

			<p>Scenario Created: {{$scenario->CreateDate}}</p>
			<p>Created by: {{$scenario->user->name}} ({{$scenario->user->email}}) </p>
			{{-- <p>Scenario Updated: {{$scenario->UpdateDate}}</p> --}}
			<p>Downloaded on: <?php echo date('Y-m-d H:i:s');?></p>



	</body>
</html>
