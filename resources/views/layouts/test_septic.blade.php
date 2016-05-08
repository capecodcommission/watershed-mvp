<html>
	<head>
		<title>Testing Septic Treatment</title>
		<link rel="stylesheet" href="{{url('/css/app.css')}}">

	</head>
	<body>
		<div class="wrapper">
			<h1>Treatment for Polygon</h1>
			<div id="app">

			
		
			<table>
				<thead>
					<tr>
						<th>Parcel ID</th>
						<th>Subwatershed</th>
						<th>Unattenuated Septic Load</th>
						<th>Land Use (existing)</th>
						<th>Town</th>
					</tr>
				</thead>
				<tbody>
				@foreach($parcels as $parcel)
					<parcel 
						wtp-nload-septic = {{$parcel->wtp_nload_septic}}
						wtp-parcel-id="{{$parcel->wtp_parcel_id}}"
						treatment-wiz-id = {{$parcel->treatment_wiz_id}}
						wtp-land-use-existing = '{{$parcel->wtp_land_use_existing}}'
						wtp-town-id = {{ $parcel->wtp_town_id }}
						wtp-subwater-id = {{$parcel->wtp_subwater_id}}
					>
					</parcel>
				@endforeach
				
					{{-- 
					<tr>
						
						<td>{{$parcel->wtp_parcel_id}}</td>
						<td>{{$parcel->wtp_subwater_id}}</td>
						<td>{{$parcel->wtp_nload_septic}}kg</td>
						<td>{{$parcel->wtp_land_use_existing}}</td>
						<td>{{$parcel->wtp_town_id}}</td>
					</tr>
				@endforeach	 --}}

				</tbody>
			</table>
<template id="parcel-template">
		<div class="parcel" id="@{{WtpParcelId}}">
				<p>
					Subwatershed: @{{WtpSubwaterId}}<br />
					Septic Nitrogen: @{{WtpNloadSeptic}}kg <br />
					Land Use: @{{WtpLandUseExisting}}<br />
					Town: @{{WtpTownId}}
				</p>
		</div>
	</template>
			<p>Total Septic N load for this polygon: {{$total_septic_nitrogen}}kg</p>


		</div>
		<script src="{{url('/js/main.js')}}"></script>

	</body>
</html>