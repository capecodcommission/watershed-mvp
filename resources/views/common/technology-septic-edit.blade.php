		<title>{{$treatment->TreatmentType_Name}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">

		

<div class="popdown-content" id="app">
	<header><h2>{{$treatment->TreatmentType_Name}}</h2></header>
	<section class="body">

			<div class="technology">
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$treatment->TreatmentType_ID}}" target="_blank">
					<img src="http://www.cch2o.org/Matrix/icons/{{$treatment->treatment_icon}}" width="75">
				<br />{{$treatment->TreatmentType_Name}}&nbsp;<i class="fa fa-question-circle"></i>
				</a>			
			</div>
					{{-- <p class="select"><button id="select_polygon" v-on:click="drawPolygon">Select a polygon</button> <span>@{{subembayment}}</span></p> --}}

					
					
			</div>

			<p>
			Nitrogen removed by this treatment: {{round($treatment->Nload_Reduction)}}kg
			{{--	--}}
			</p>
			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low_ppm}} and {{$tech->Nutri_Reduc_N_High_ppm}} ppm.<br />
				<input type="range" id="septic-rate" min="{{$tech->Nutri_Reduc_N_Low_ppm}}" max="{{$tech->Nutri_Reduc_N_High_ppm}}" v-model="septic_rate" value="{{$treatment->Treatment_Value}}">@{{septic_rate}} 
			</p>

			<p>
				<button id="updatetreatment">Update</button>
			</p>
	</section>
</div>


	<template id="treatment-template">
		<div class="treatment" id="@{{TreatmentID}}">
			<p>Total Unattenuated Nitrogen: <span id="total_nitrogen_polygon">@{{Total_Orig_Nitrogen}}</span>; Nitrogen Removed by Treatment: <span id="Nitrogen_Removed">@{{Nitrogen_Removed}}</span></p>
		</div>
	</template>




<script src="{{url('/js/main.js')}}"></script>
{{-- <script src="{{url('/js/app.js')}}"></script> --}}


<script>
	$(document).ready(function(){
	 treatment = {{$treatment->TreatmentID}};


		$('#select_polygon').on('click', function(f){
			f.preventDefault();
			$('#popdown-opacity').hide();
			func = 'septic';
			map.disableMapNavigation();
			tb.activate('polygon');
			$('#select_polygon').hide();
			// $('#select_destination').show();

		});
		
	$('#applytreatment').on('click', function(e){
			e.preventDefault();
			var rate = $('#septic-rate').val();
			var url = "{{url('/apply_septic')}}" + '/' +  treatment + '/' + rate + '/septic';
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