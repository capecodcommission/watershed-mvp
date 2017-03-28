		<title>{{$tech->Technology_Strategy}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
<meta name="csrf-token" id="token" content="{{ csrf_token() }}">
		

<div class="popdown-content" id="app">
	<header><h2>{{$tech->Technology_Strategy}}</h2></header>
	<section class="body">



			<div class="technology">
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
					<img src="http://2016.watershedmvp.org/images/SVG/{{$tech->Icon}}" width="75">
				<br />{{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
				</a>			
			</div>
					<p class="select"><button id="select_polygon_{{$treatment->TreatmentID}}">Draw a polygon</button> <span>@{{subembayment}}</span></p>

					
					
			</div>
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
				<button id="apply_treatment_{{$treatment->TreatmentID}}">Apply</button>
				<button id="cancel_treatment_{{$treatment->TreatmentID}}" class='button--cta right'><i class="fa fa-ban"></i> Cancel</button>
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
	 typeid = {{$treatment->TreatmentType_ID}};

		$('#select_polygon_'+treatment).on('click', function(f){
			f.preventDefault();
			$('#popdown-opacity').hide();
			func = 'septic';
			// map.disableMapNavigation();
			tb.activate('polygon');
			$('#select_polygon_'+treatment).hide();
			// $('#select_destination').show();

		});
		
	$('#apply_treatment_'+treatment).on('click', function(e){
			e.preventDefault();
			var rate = $('#septic-rate').val();
			var url = "{{url('/apply_septic')}}" + '/' +  treatment + '/' + rate;
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
					// location.reload()				
				});

			// location.reload()
		});


		$('#canceltreatment_'+treatment).on('click', function(e){
		var url = "{{url('cancel', $treatment->TreatmentID)}}";
		$.ajax({
			method: 'GET',
			url: url
		})
			.done(function(msg){
				$('#popdown-opacity').hide();
			});
		});

	});
</script>