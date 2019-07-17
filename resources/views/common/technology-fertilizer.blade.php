<!-- Set the title to 'Technology_Strategy' from the dbo.v_Technology_Matrix obtained by 'TechnologyController.php' -->
<title>{{$tech->Technology_Strategy}}</title>
<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
<!-- Set the popdown up with a header, a body with the technology, a table and reduction rate selection -->
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
				<img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}" width="75">
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
					<th>Unattenuated</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>@{{fert_unatt | round}}kg</td>
					<td>@{{fert_unatt_treated | round }}kg</td>
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

<!-- Import the vue data and computed properties -->
<script src="{{url('/js/main.js')}}"></script>


<script>
	$(document).ready(function(){
		// Check the state of readyness for applyTreatment, closeWindow & canceltreatment - remove the spinner once ready
		$('div.fa.fa-spinner.fa-spin').remove()
		
		// On Click of treatment icon set the percent variable for the fertilization percent for reduction selection by user
		// and set the url to use to send an ajax GET method to route the user input slider value for the fertilzation percent
		$('#applytreatment').on('click', function(e){
			e.preventDefault();
			var percent = $('#fert-percent').val();
			var url = "{{url('/apply_percent')}}" + '/' +  {{$treatment->TreatmentID}} + '/' + percent + '/fert';
			$.ajax({
				method: 'GET',
				url: url
			})
			// Once the GET method is complete, format the returned message, route that to the text, hide the popdown, click
			// the update subemebayments progress and embayment progress, set the newtreatment variable and add it to the
			// selected treatments popdown tray at top
			.done(function(msg){
				msg = Math.round(msg);
				$('#n_removed').text(msg);
				$('#popdown-opacity').hide();
				$( "#update" ).trigger( "click" );
				var newtreatment = '<li class="technology" data-treatment="{{$treatment->TreatmentID}}"><a href="{{url('/edit', $treatment->TreatmentID)}}" class="popdown"><img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}" alt=""></a></li>';
				$('ul.selected-treatments').append(newtreatment);
				$('ul.selected-treatments li[data-treatment="{{$treatment->TreatmentID}}"] a').popdown();
			});

		});
		
		// Clicking the close window button: hide the popdown, set the url to cancel the treatment which runs the DELtreatment
		// stored procedure, send an ajax GET method to the url and once the method is complete, use the return to remove the fert
		// treatment polygon
		$('#closeWindow').on('click', function (e) {
			
			$('#popdown-opacity').hide();
			$('#fert-percent').val(0);
			let fertApplied = {{session('fert_applied')}};
			console.log(fertApplied);
			var url = "{{url('cancel', $treatment->TreatmentID)}}";
			
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(msg){
				// for (var i = map.graphics.graphics.length - 1; i >= 0; i--) {
				// 		if (map.graphics.graphics[i].attributes) {
				// 			if (map.graphics.graphics[i].attributes.treatment_id == treatment) {
				// 				map.graphics.remove(map.graphics.graphics[i])
	            //         	}
	            //     	}
				// 	   }
			});
			$('#fert-percent').val(0);
		})

		// On clicking cancel treatment, hide the popdown, reset the fertilizer percent back to 0, cancel the treatment in the
		// same fashion as closeWindow and return an empty message
		$('#canceltreatment').on('click', function(e){
			$('#popdown-opacity').hide();
			$('#fert-percent').val(0);
			var url = "{{url('cancel', $treatment->TreatmentID)}}";
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(msg){});
		});
	});
</script>