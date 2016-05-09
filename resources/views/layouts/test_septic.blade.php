<html>
	<head>
		<title>Testing Septic Treatment</title>
		<link rel="stylesheet" href="{{url('/css/app.css')}}">
<style>
	td { border-left: 1px dotted #666; padding: .5em; }
</style>
	</head>
	<body>
		<div class="wrapper">
			<h1>Treatment for Polygon</h1>
			<p>(all values reported are unattenuated)</p>
			<div id="app">
			
			<p>Enter a treatment reduction ppm for this technology: 
				<input type="text" id="nreduce" style="width:5em; display:inline-block;" v-model="treatment">
			<button id='applytreatment'>Apply treatment</button></p>
			<p><strong>Original Septic N load for this polygon</strong>: {{$total_septic_nitrogen}}kg</p>
			<p><strong>Treated Nitrogen value</strong>: <span id="updatedN"></span></p>
			<p><strong>Nitrogen Removed</strong>: <span id="removedN"></span></p>
			<p>Formula: <code>NLoad = ((((WWFlowExisting)*[new ppm])*365)*3.785)/1000000</code></p>
			<table>
				<thead>
					<tr>
						<th>Town</th>
						<th>Parcel ID</th>
						<th>Land Use (existing)</th>
						<th>Subwatershed</th>
						<th>Unattenuated Septic Load</th>
						<th>WastewaterFlow</th>					
						<!-- <th>Flowthrough Coefficient</th> -->
					</tr>
				</thead>
				<tbody>

	@foreach($parcels as $parcel)
 {{--					<parcel 
						wtp-nload-septic = {{$parcel->wtp_nload_septic}}
						wtp-parcel-id="{{$parcel->wtp_parcel_id}}"
						treatment-wiz-id = {{$parcel->treatment_wiz_id}}
						wtp-land-use-existing = '{{$parcel->wtp_land_use_existing}}'
						wtp-town-id = {{ $parcel->wtp_town_id }}
						wtp-subwater-id = {{$parcel->wtp_subwater_id}}
						wtp-wwf-existing = {{$parcel->wtp_wwf_existing}}
						v-bind:my-treatment="treatment"

					>
					</parcel>
				@endforeach 	
	
				--}}

					
					<tr class="parcel">
						<td>{{$parcel->wtp_town_id}}</td>
						<td>{{$parcel->wtp_parcel_id}}</td>
						<td>{{$parcel->wtp_land_use_existing}}</td>
						<td>{{$parcel->wtp_subwater_id}}</td>
						<td>{{$parcel->wtp_nload_septic}}kg</td>
						<td class="wwf" data-wwf_existing={{$parcel->wtp_wwf_existing}}>{{$parcel->wtp_wwf_existing}}</td>
						{{-- <td>{{$parcel->wtp_coeff}}</td>	--}}
					</tr>
				@endforeach	 

				</tbody>
			</table>

			


		</div>
		<script src="{{url('/js/main.js')}}"></script>
		<script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
		<script>
			$(document).ready(function(){
				$('#applytreatment').on('click', function(e){
					e.preventDefault();
					var treatedNitrogen = 0;
					var total_wwf = 0;
					var treatmentval = $('#nreduce').val();
					var removedN = 0;
					$('.wwf').each(function(){
						total_wwf += parseFloat($(this).data('wwf_existing'));
					});
					console.log(total_wwf);
					console.log(treatmentval);
					treatedNitrogen = ((((total_wwf)*treatmentval)*365)*3.785)/1000000;
					console.log(treatedNitrogen);
					$('#updatedN').text(treatedNitrogen);
					removedN = <?php echo $total_septic_nitrogen;?> - treatedNitrogen;
					$('#removedN').text(removedN);

				});
			});
		</script>

	</body>
</html>