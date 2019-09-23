<title>{{$treatment->TreatmentType_Name}}</title>
<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">	

<div class="popdown-content" id="app">
	<header>
		<div class = 'row'>
			<div class = 'col'>
				<h2>{{$treatment->TreatmentType_Name}}<button style = 'position: absolute; right: 20; top: 10' id = "closeWindow"><i class = 'fa fa-times'></i></button></h2>
			</div>
		</div>
	</header>
	<section class="body">
		<div class="technology">
			<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$treatment->TreatmentType_ID}}" target="_blank">
				<img src="http://www.cch2o.org/Matrix/icons/{{$treatment->treatment_icon}}" width="75">
			<br />{{$treatment->TreatmentType_Name}}&nbsp;<i class="fa fa-question-circle"></i>
			</a>			
		</div>
		{{-- <p class="select"><button id="select_polygon" v-on:click="drawPolygon">Select a polygon</button> <span>@{{subembayment}}</span></p> --}}		
		<fieldset>
			<legend><h3>Treatment Stats</h3></legend>
			<ul>
				<li>Nitrogen removed by this treatment: {{round($treatment->Nload_Reduction)}}kg</li>
				<li>Parcels affected: {{$treatment->Treatment_Parcels}}</li>
				<li>Total Treatment Cost: {{money_format('%10.0n', $treatment->Cost_Total)}}</li>
			</ul>
		</fieldset>	
		@if($tech->Nutri_Reduc_N_High_ppm > $tech->Nutri_Reduc_N_Low_ppm)
			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.<br />
				<input type="range" id="septic-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="septic_rate" value="{{$tech->Nutri_Reduc_N_Low}}">@{{septic_rate}}
			</p>
		@else
			<p>Reduction rate: {{$tech->Nutri_Reduc_N_Low_ppm}} ppm.</p>
			<input type="hidden" name="septic-rate" id="septic-rate" value="{{$tech->Nutri_Reduc_N_Low_ppm}}">
		@endif

		<p>
			<button id="updatetreatment">Update</button>
			<button id="deletetreatment" class='button--cta right'><i class="fa fa-trash-o"></i> Delete</button>
		</p>
	</section>
</div>

<script src="{{url('/js/main.js')}}"></script>


<script>
	$(document).ready(function(){

		$('div.fa.fa-spinner.fa-spin').remove()
		treatment = {{$treatment->TreatmentID}};


		$('#select_polygon').on('click', function(f){
			f.preventDefault();
			$('#popdown-opacity').hide();
			func = 'septic';
			// map.disableMapNavigation();
			tb.activate('polygon');
			$('#select_polygon').hide();
			// $('#select_destination').show();

		});

		$('#closeWindow').on('click', function (e) {

			$('#popdown-opacity').hide();
		})
		
		$('#updatetreatment').on('click', function(e)
		{
			e.preventDefault();
			var rate = $('#septic-rate').val();
			var url = "{{url('/update/toilets', $treatment->TreatmentID)}}"  + '/' + rate;
			$.ajax({
				method: 'GET',
				url: url
			})
				.done(function(msg){
					$('#popdown-opacity').hide();
					$( "#update" ).trigger( "click" );
				});

		});		

	// $('#updatetreatment').on('click', function(e){
	// 		e.preventDefault();
	// 		var rate = $('#septic-rate').val();
	// 		var url = "{{url('/apply_septic')}}" + '/' +  treatment + '/' + rate + '/septic';
	// 		// console.log(url);
	// 		$.ajax({
	// 			method: 'GET',
	// 			url: url
	// 		})
	// 			.done(function(msg){
	// 				// console.log(msg);
	// 				msg = Math.round(msg);
	// 				$('#n_removed').text(msg);
	// 				$('#popdown-opacity').hide();
	// 				$( "#update" ).trigger( "click" );
	// 			});

	// 	});

		$('#deletetreatment').on('click', function(e){
		var url = "{{url('delete_treatment', $treatment->TreatmentID)}}";
		$.ajax({
			method: 'GET',
			url: url
		})
			.done(function(msg){
				$('#popdown-opacity').hide();
				$("li[data-treatment='{{$treatment->TreatmentID}}']").remove();
				
				for (var i = map.graphics.graphics.length - 1; i >= 0; i--) {
                
	                if (map.graphics.graphics[i].attributes) {

	                    if (map.graphics.graphics[i].attributes.treatment_id == treatment) {

	                    	map.graphics.remove(map.graphics.graphics[i])
	                    }
	                }
           		}

           		$( "#update" ).trigger( "click" );
			});

		});



	});
</script>