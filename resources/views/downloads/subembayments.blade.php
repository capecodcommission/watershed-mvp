<html>
	<head>
		<title>WMVP Results by Subembayment</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{{-- {{ HTML::style('css/download.css') }} --}}
	</head>
	<body>

		<div class="wrapper results_download">
		<div class="content">
			<h1>Scenario: {{$scenario->ScenarioID}} for {{$scenario->AreaName}}</h1>
			<p>Link to scenario: {{url('map', $scenario->AreaID, $scenario->ScenarioID)}}</p>
			<div id="app">
			
			<h2>Subembayments</h2>
			<table>
				<thead>
					<tr>
						<th>Subembayment</th>
						<th>Original N *</th>
						<th>N Removed (Attenuated) **</th>
						<th>Scenario N</th>
						<th>Target N</th>
						<th>N Remaining to Target ***</th>
					</tr>
				</thead>
				<tbody>
					@foreach($subembayments as $sub)
					<tr>
						<td>{{$sub->subem_disp}}</td>
						<td>{{round($sub->n_load_att)}}</td>
						<td>{{round($sub->n_load_att_removed)}}</td>
						<td>{{round($sub->n_load_scenario)}}</td>
						<td>{{round($sub->n_load_target)}}</td>
						<td>{{round($sub->n_load_scenario - $sub->n_load_target)}}</td>
					</tr>

					@endforeach
				</tbody>

			</table>
			<p>* The "Original N" value is calculated (attenuated) total Nitrogen for the subembayment. </p>
			<p>** A negative number in this column represents Nitrogen <br />added to a subembayment as part of a collection treatment.</p>
			<p>*** A negative number in this column means the user has exceeded the target for this subembayment.</p>		

		</div>
		</div>
	</div>
	</body>
</html>