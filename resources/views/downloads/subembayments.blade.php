<html>
	<head>
		<title>WMVP Results by Subembayment</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	</head>
	<body>

		<div class="wrapper results_download">
		<div class="content">
			<h1>Scenario: {{$scenario->ScenarioID}} for {{$scenario->AreaName}}</h1>
			<p></p>
			<div id="app">

			<?php
				$scenario_cost = 0;
				$n_removed = 0;
				$n_att_total = 0;
				$n_att_rem_total = 0;
				$n_scen_total = 0;
				$n_target_total = 0;
				$n_rem_total = 0;
				$n_rem_septic = 0;
				$n_rem_treated = 0;
			?>
			
			<h2>Subembayments</h2>
			<table>
				<thead>
					<tr>
						<th>Subembayment</th>
						<th>Original N *</th>
						<th>Attenuated N Removed **</th>
						<th>Scenario N</th>
						<th>Threshold N</th>
						<th>N Remaining to Threshold ***</th>
						<th>Unattenuated Septic Load</th>
						<th>Unattenuated NLoad Total</th>
					</tr>
				</thead>
				<tbody>

					@foreach($subembayments as $sub)
					<tr>
						<td>{{$sub->subem_disp}}</td>
						<td>{{round($sub->n_load_att)}}</td> <?php $n_att_total += $sub->n_load_att; ?>
						<td>{{round($sub->n_load_att_removed)}}</td> <?php $n_att_rem_total += $sub->n_load_att_removed; ?>
						<td>{{round($sub->n_load_scenario)}}</td> <?php $n_scen_total += $sub->n_load_scenario; ?>
						<td>{{round($sub->n_load_target)}}</td> <?php $n_target_total += $sub->n_load_target; ?>
						<td>{{round($sub->n_load_scenario - $sub->n_load_target)}}</td> <?php $n_rem_total += $sub->n_load_scenario - $sub->n_load_target; ?>
						<td>{{round($sub->n_load_att_septic)}}</td> <?php $n_rem_septic += $sub->n_load_att_septic; ?>
						<td>{{round($sub->n_load_att_treated)}}</td> <?php $n_rem_treated += $sub->n_load_att_treated; ?>
					</tr>
					@endforeach
					
					<tr>
						<td><strong>Subembayment Totals</strong></td>
						<td><strong><?php echo round($n_att_total);?>kg</strong></td>
						<td><strong><?php echo round($n_att_rem_total);?>kg</strong></td>
						<td><strong><?php echo round($n_scen_total);?>kg</strong></td>
						<td><strong><?php echo round($n_target_total);?>kg</strong></td>
						<td><strong><?php echo round($n_rem_total);?>kg</strong></td>
						<td><strong><?php echo round($n_rem_septic);?>kg</strong></td>
						<td><strong><?php echo round($n_rem_treated);?>kg</strong></td>
					</tr>
				</tbody>

			</table>
			<p>* The "Original N" value is calculated (attenuated) total Nitrogen for the subembayment. </p>
			<p>** A negative number in this column represents Nitrogen <br />added to a subembayment as part of a collection treatment.</p>
			<p>*** A negative number in this column means the user has exceeded the threshold for this subembayment.</p>		

		</div>
		</div>
	</div>
	</body>
</html>