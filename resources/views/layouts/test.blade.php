<html>
	<head>
		<title>Testing Nitrogen Values</title>
		<link rel="stylesheet" href="{{url('/css/app.css')}}">

	</head>
	<body>
		<div class="wrapper">
			<h1>Embayment: {{$embayment->EMBAY_DISP}}</h1>

			<div id="app">
			<table>
				<thead>
					<tr>
						<th>Source</th>
						<th>Unattenuated</th>
						<th>Attenuated</th>
						<th>Reduction <br />by Treatment</th>
						<th>Unattenuated<br />after treatment</th>
						<th>Attenuated<br />After Treatment</th>
						<th>Difference</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Fertilizer</td>
						<td>@{{ fert_unatt | round }}kg</td>
						<td>@{{ fert_att | round }}kg</td>
						<td><input type="range" id="percent" min="0" value="10" max="100" v-model="fert_percent"> (@{{fert_percent}}%)</td>
						<td>@{{fert_unatt_treated | round }}kg</td>
						<td>@{{fert_treated | round }}kg <sup>1</sup></td>
						<td>@{{fert_difference | round }}kg</td>
					</tr>
					<tr>
						<td>Stormwater</td>
						<td>@{{storm_unatt | round 0}}kg</td>
						<td>@{{storm_att | round }}kg</td>
						<td><input type="range" id="storm-percent" min="0" value="10" max="100" v-model="storm_percent"> (@{{storm_percent}}%)</td>
						<td>@{{storm_unatt_treated | round }}kg</td>
						<td>@{{storm_treated | round }}kg</td>
						<td>@{{storm_difference | round }}kg</td>
					</tr>
					<tr>
						<td>Septic</td>
						<td>@{{septic_unatt | round}}kg</td>
						<td>@{{septic_att | round}}kg</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Atmospheric*</td>
						<td>?</td>
						<td>?</td>
						<td>Can't be treated</td>
						<td>?</td>
						<td>?</td>
						<td></td>
					</tr>
					<tr>
						<td>Groundwater<sup>2</sup></td>
						<td>@{{groundwater_unatt | round }}kg</td>
						<td>@{{groundwater_att | round }}kg</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Embayment <sup>3</sup></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>Total</strong></td>
						<td>@{{total_unatt | round }}kg</td>
						<td>@{{total_att | round }}kg</td>
						<td></td>
						<td></td>
						<td><span class="treated">@{{total_treated | round }}kg</span></td>
						<td>@{{difference | round }}kg</td>
					</tr>

				</tbody>
			</table>




			<h3>Notes/Explanations</h3>
			<p><sup>1</sup> The total treated Fertilizer load is determined by reducing the unattenuated fertilizer load (@{{fert_att}}) by the percentage selected (@{{fert_percent}}%) and then applying the attenuation. </p>
			<p><sup>2</sup>Groundwater starting values are calculated by adding the <strong>unattenuated</strong> Nitrogen totals for Fertilizer, Stormwater, and Septic <strong>after treatment</strong> and then the atmospheric load* is added.</p>
			<p><sup>3</sup> Embayment starting value is the Groundwater attenuated/treated value (the net N after all treatments and attenuation have been applied).</p>
			<p>* Currently don't have a value to use for atmospheric load so right now this is 0.</p>
			<p>Go <a href="{{url('map', $embayment->EMBAY_ID)}}">back to the map</a>.</p>


		</div>
		<script src="{{url('/js/main.js')}}"></script>
		<script>
			console.log(nitrogen);
		</script>
	</body>
</html>