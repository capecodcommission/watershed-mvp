<html>
	<head>
		<title>WatershedMVP Scenario Results</title>
		<?php if( env('APP_ENV') == 'production' ) : ?>
			<link rel="stylesheet" href="{{secure_url('/css/app.css')}}">
		<?php else :?>
			<link rel="stylesheet" href="{{url('/css/app.css')}}">
		<?php endif; ?>
		<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
		<script>window.name = 'wmvp_results_{{$scenario->ScenarioID}}';</script>
	</head>
	<body class="results-page">
		<div class="wrapper">
		<div align='center' class="content full-width">
			@include('common.navigation')

			<h1>Scenario ID: {{$scenario->ScenarioID}}</h1>
	      	<h1>Embayment: {{$scenario->AreaName}}</h1>
			<h2 class="author">Created by: {{$scenario->user->name}} on {{date('Y-m-d', strtotime($scenario->CreateDate))}}</h2>
			<div id="app">
			<?php
				// TODO: Can we get/set from global variables?
				$scenario_cost = 0;
				$scenario_cost_town = 0;
				$n_removed = 0;
				$n_removed_town = 0;
				$n_att_total = 0;
				$n_att_rem_total = 0;
				$n_scen_total = 0;
				$n_target_total = 0;
				$n_rem_total = 0;
				setlocale(LC_MONETARY, 'en_US');
			?>


			@if(count($scenario->treatments) > 0)


			<h2>Applied Treatments</h2>
			<table class = 'resultsTable'>
				<thead>
					<tr>
						<th>Technology</th>
						<th>Towns</th>
					</tr>
				</thead>
				<tbody>
					@foreach($scenario->treatments as $treatment)
						@if(!$treatment->Parent_TreatmentId)
							<tr>
								<td>
									<img src="{{$_ENV['CCC_ICONS_SVG'].$treatment->treatment_icon}}" alt="">
									<p>{{$treatment->TreatmentType_Name}} ({{$treatment->TreatmentID}}) </p>
								</td>
								<td>
									<table class = 'resultsTable' id = 'townsTable'>
										<thead>
											<tr>
												<th>Town</th>
												<th>Parcels Affected</th>
												<th>Nitrogen Removed (Unattenuated)</th>
												<th>Total Cost</th>
												<th>Cost per kg N removed</th>
											</tr>
										</thead>
										<tbody>
											@foreach($towns as $town)
												@if($town->TreatmentID == $treatment->TreatmentID || $town->Parent_TreatmentId == $treatment->TreatmentID)
													<tr>
														<td>{{$town->TOWN}}</td>
														<td>{{$town->wtt_tot_parcels}}</td>
														<td><?php echo number_format(round($town->wtt_unatt_n_removed)); $n_removed += $town->wtt_unatt_n_removed; $n_removed_town += $town->wtt_unatt_n_removed; ?>kg</td>
														<td><?php echo '$'.number_format($town->townCost,0,'.',','); $scenario_cost += $town->townCost; $scenario_cost_town += $town->townCost ?></td>
														<td><?php echo '$'.number_format($town->costPerKg,0,'.',','); ?></td>
													</tr>
												@endif
											@endforeach
											<tr id="totals">
												<td><strong>Totals:</strong></td>
												<td></td>
												<td>
													<strong><?php echo number_format(round($n_removed_town));?>kg</strong>
												</td>
												<td>
													<strong><?php echo '$'.number_format($scenario_cost_town,0,'.',',');?></strong>
												</td>
												<td>
													<strong><?php echo '$'.number_format(($scenario_cost_town/max($n_removed_town, 1)),0,'.',','); ?></strong>
												</td>
												<?php $n_removed_town = 0; $scenario_cost_town = 0 ?>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						@endif
					@endforeach
					<tr style = 'padding-top: 2%' id = 'totals'>
						<td><strong>Scenario Totals:</strong></td>
						<td>
							<table class = 'resultsTable' id = 'scenarioTotalsTable'>
								<thead>
									<tr>
										<th></th>
										<th></th>
										<th>Nitrogen Removed (Unattenuated)</th>
										<th>Total Cost</th>
										<th>Cost per kg N removed</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td></td>
										<td></td>
										<td>
											<strong><?php echo number_format(round($n_removed));?>kg</strong>
										</td>
										<td>
											<strong><?php echo '$'.number_format($scenario_cost,0,'.',',');?></strong>
										</td>
										<td>
											<strong><?php echo '$'.number_format(($scenario_cost/max($n_removed, 1)),0,'.',','); ?></strong>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<h2>Subembayments</h2>
			<table style = 'margin-bottom: 20px' class = 'resultsTable'>
				<thead>
					<tr>
						<th>Subembayment</th>
						<th>Original N<sup>1</sup></th>
						<th>N Removed (Attenuated)<sup>2</sup></th>
						<th>Scenario N</th>
						<th>Threshold N</th>
						<th>N Remaining to Threshold <sup>3</sup></th>
					</tr>
				</thead>
				<tbody>

					@foreach($subembayments as $sub)
					<tr>
						<td>{{$sub->SUBEM_DISP}}</td>
						<td>{{number_format(round($sub->n_load_att))}}kg</td> <?php $n_att_total += $sub->n_load_att; ?>
						<td>{{number_format(round($sub->n_load_att_removed))}}kg</td> <?php $n_att_rem_total += $sub->n_load_att_removed; ?>
						<td>{{number_format(round($sub->n_load_scenario))}}kg</td> <?php $n_scen_total += $sub->n_load_scenario; ?>
						<td>{{number_format(round($sub->n_load_target))}}kg</td> <?php $n_target_total += $sub->n_load_target; ?>
						<td>{{number_format(round($sub->n_load_scenario - $sub->n_load_target))}}kg</td> <?php $n_rem_total += $sub->n_load_scenario - $sub->n_load_target; ?>
					</tr>
					@endforeach

					<tr>
						<td><strong>Subembayment Totals</strong></td>
						<td><strong><?php echo number_format(round($n_att_total));?>kg</strong></td>
						<td><strong><?php echo number_format(round($n_att_rem_total));?>kg</strong></td>
						<td><strong><?php echo number_format(round($n_scen_total));?>kg</strong></td>
						<td><strong><?php echo number_format(round($n_target_total));?>kg</strong></td>
						<td><strong><?php echo number_format(round($n_rem_total));?>kg</strong></td>
					</tr>
				</tbody>

			</table>
			<p><sup>1</sup> The "Original N" value is calculated (attenuated) total Nitrogen for the subembayment. </p>
			<p><sup>2</sup>A negative number in this column represents Nitrogen added to a subembayment as part of a collection treatment.</p>
			<p><sup>3</sup>A negative number in this column means the user has exceeded the threshold for this subembayment.</p>

			<p>
				<a href="{{url('download', $scenario->ScenarioID)}}" class="button--cta right" target="_blank"><i class="fa fa-download"></i> Download Results (.xls)</a>
			</p>

		@else
		<p>No treatments have been applied to this scenario yet.</p>
		<p><a href="{{url('map', [$scenario->AreaID, $scenario->ScenarioID])}}" class="button" target="wmvp_scenario_{{$scenario->ScenarioID}}">Return to map</a> </p>
		@endif

		</div>
		</div>
	</div>

	<script>
	$(document).ready(function() {
		scenario = {{$scenario->ScenarioID}};

		$('.save').on('click', function(e) {
			e.preventDefault();
			var url = "{{url('save')}}" + '/' + scenario;
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(msg) {
				$('#saved').addClass('button--cta')
			});
		});
	});
	</script>
	</body>
</html>
