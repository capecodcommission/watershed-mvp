		<title>{{$tech->Technology_Strategy}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
		

<div class="popdown-content" >
	<header>
		<div class = 'row'>
			<div class = 'col'>
				<h2>{{$tech->Technology_Strategy}}<button style = 'position: absolute; right: 20; top: 10' id = "closeWindow"><i class = 'fa fa-times'></i></button></h2>
			</div>
		</div>
	</header>
	<section class="body">

			<div class="technology">
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
					<img src="http://2016.watershedmvp.org/images/SVG/{{$tech->Icon}}" width="75">
				 <i class="fa fa-question-circle"></i>
				</a>			
			</div>

	
		<table>
			<thead>
				<tr>
					<th colspan="1">Fertilizer Nitrogen (Before Treatment)</th>
					<th colspan="2">After Treatment</th>
					<th></th>
				</tr>
				<tr>
					<th>Unattenuated</th>
					<!-- <th>Attenuated</th> -->
					<th>Unattenuated</th>
					<!-- <th>Attenuated</th> -->
					<!-- <th>N Removed</th> -->
				</tr>
			</thead>
			<tbody>
				<tr>
				
				 		<td>@{{fert_unatt | round}}kg</td>
						<!-- <td>@{{fert_att | round }}kg</td> -->
						<td>@{{fert_unatt_treated | round }}kg</td>
						<!-- <td>@{{fert_treated | round }}kg <sup>1</sup></td> -->
						<!-- <td>@{{fert_difference | round }}kg</td> -->
				</tr>
				
			</tbody>
		</table>
			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.<br />
				
				<input type="range" id="{{$type}}-percent" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="fert_percent" value="{{$tech->Nutri_Reduc_N_Low}}"> @{{fert_percent}}%
			</p>
			<p>
				<button id="applytreatment">Apply</button>
				<button id="canceltreatment" class='button--cta right'>Cancel</button>
			</p>

	</section>
</div>


<script src="{{url('/js/main.js')}}"></script>
{{-- <script src="{{url('/js/app.js')}}"></script> --}}


<script>
	$(document).ready(function(){

		$('div.fa.fa-spinner.fa-spin').remove()

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
					var newtreatment = '<li class="technology" data-treatment="{{$treatment->TreatmentID}}"><a href="{{url('/edit', $treatment->TreatmentID)}}" class="popdown"><img src="http://2016.watershedmvp.org/images/SVG/{{$tech->Icon}}" alt=""></a></li>';
					$('ul.selected-treatments').append(newtreatment);
					$('ul.selected-treatments li[data-treatment="{{$treatment->TreatmentID}}"] a').popdown();
				});

		});

		$('#closeWindow').on('click', function (e) {

			$('#popdown-opacity').hide();

			var url = "{{url('cancel', $treatment->TreatmentID)}}";

			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(msg){
				
				for (var i = map.graphics.graphics.length - 1; i >= 0; i--) {
                
	                if (map.graphics.graphics[i].attributes) {

	                    if (map.graphics.graphics[i].attributes.treatment_id == treatment) {

	                    	map.graphics.remove(map.graphics.graphics[i])
	                    }
	                }
           		}
           	})
		})

		$('#canceltreatment').on('click', function(e){
			$('#popdown-opacity').hide();
			$('#fert-percent').val(0) 
			var url = "{{url('cancel', $treatment->TreatmentID)}}";
			$.ajax({
				method: 'GET',
				url: url
			}).done(function(msg){});
		});

	});
</script>