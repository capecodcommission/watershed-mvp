<html>
	<head>
		<title>{{$tech->Technology_Strategy}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
	</head>
	<body>
		

<div class="popdown-content" id="app">
	<header><h2>{{$tech->Technology_Strategy}}</h2></header>
	<section class="body">
			<div class="technology">
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
					<img src="http://www.cch2o.org/Matrix/icons/{{$tech->Icon}}" width="75">
				<br />{{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
				</a>			
			</div>
			<div>
				@if($tech->Unit_Metric =='Acres')
					<p class="select"><button id="select_area">Select a location</button> <span></span></p>
					<p>
						<label for="acres">Enter number of acres to be treated: 
						<input type="text" id="acres" name="acres" size="3" style="width: auto;"></label>
					</p>
				@endif
			</div>
		<table>
			<thead>
				<tr>
					<th colspan="2">Starting Values</th>
					<th colspan="2">After Treatment</th>
					<th></th>
				</tr>
				<tr>
					<th>Unattenuated</th>
					<th>Attenuated</th>
					<th>Unattenuated</th>
					<th>Attenuated</th>
					<th>N Removed</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>@{{ fert_unatt | round }}kg</td>
					<td>@{{fert_att | round }}kg</td>
					<td>@{{fert_unatt_treated |round}}kg</td>
					<td>@{{fert_treated | round}}kg</td>
					<td>@{{fert_difference | round}}kg</td> 
				</tr>
				
			</tbody>
		</table>
			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.<br />
				<input type="range" id="effective" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="fert_percent">
			</p>
			<!-- <p><a href="#" class="button">Apply</a></p> -->
	</section>
</div>

<script>
		$('#select_area').on('click', function(f){
		f.preventDefault();
		$('#popdown-opacity').hide();
		map.on('click', function(e){
			console.log(e.mapPoint.x, e.mapPoint.y);

				var url = "{{url('/map/point/')}}"+'/'+e.mapPoint.x+'/'+ e.mapPoint.y;
				$.ajax({
					dataType: 'json',
					method: 'GET',
					url: url
					// ,
					// data: { x: e.mapPoint.x, y: e.mapPoint.y }
				})
					.done(function(msg){
						console.log(msg);
						// var sub = json_decode(msg);
						// console.log(sub);
						$('#'+msg.SUBEM_NAME+'> .stats').show();
						// $('.notification_count').remove();
						$('#popdown-opacity').show();
						$('.select > span').text('Selected: '+msg.SUBEM_DISP);
					})

		});
	});
</script>
<script src="{{url('/js/main.js')}}"></script>
	</body>
</html>