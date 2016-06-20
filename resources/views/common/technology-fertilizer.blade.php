		<title>{{$tech->Technology_Strategy}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
		

<div class="popdown-content" >
	<header><h2>{{$tech->Technology_Strategy}}</h2></header>
	<section class="body">

			<div class="technology">
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
					<img src="http://www.cch2o.org/Matrix/icons/{{$tech->Icon}}" width="75">
				 <i class="fa fa-question-circle"></i>
				</a>			
			</div>

	
		<table>
			<thead>
				<tr>
					<th colspan="2">Fertilizer Nitrogen</th>
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
				
				 		<td>@{{fert_unatt | round}}kg</td>
						<td>@{{fert_att | round }}kg</td>
						<td>@{{fert_unatt_treated | round }}kg</td>
						<td>@{{fert_treated | round }}kg <sup>1</sup></td>
						<td>@{{fert_difference | round }}kg</td>
				</tr>
				
			</tbody>
		</table>
			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.<br />
				
				<input type="range" id="{{$type}}-percent" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="fert_percent"> @{{fert_percent}}%
			</p>
			<p>
				<button id="applytreatment">Apply</button>
			</p>


	</section>
</div>


<script src="{{url('/js/main.js')}}"></script>
<script src="{{url('/js/app.js')}}"></script>


<script>
	$(document).ready(function(){

		$('#applytreatment').on('click', function(e){
			// need to save the treated N values and update the subembayment progress
			// 
			e.preventDefault();
			// console.log('clicked');
			var percent = $('#fert-percent').val();
			var url = "{{url('/apply_percent')}}" + '/' +  {{$treatment->TreatmentID}} + '/' + percent + '/fert';
			// console.log(url);
			$.ajax({
				method: 'GET',
				url: url
			})
				.done(function(msg){
					// console.log(msg);
					msg = Math.round(msg);
					$('#n_removed').text(msg);
					$('#popdown-opacity').hide();
					$( "#update" ).trigger( "click" );
				});

		});


	});
</script>