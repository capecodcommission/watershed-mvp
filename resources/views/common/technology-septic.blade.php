<title>{{$tech->Technology_Strategy}}</title>
<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
<meta name="csrf-token" id="token" content="{{ csrf_token() }}">	

<div class="popdown-content" id="app">
	<header>
		<div class = 'row'>
			<div class = 'col'>
				<h2>{{$tech->Technology_Strategy}}<button style = 'position: absolute; right: 20; top: 10' id = "closeWindow"><i class = 'fa fa-times'></i></button></h2>
			</div>
		</div>
	</header>
	<section class="body">
		<div class="technology">
			<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->technology_id}}" target="_blank">
				<img src="http://www.cch2o.org/Matrix/icons/{{$tech->icon}}" width="75">
				<br />{{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
			</a>			
		</div>
		<p class="select"><button id="select_polygon">Draw a polygon</button> <span>@{{subembayment}}</span></p>
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
			<button id="apply_treatment">Apply</button>
			<button id="cancel_treatment" class='button--cta right'><i class="fa fa-ban"></i> Cancel</button>
		</p>
		<!-- TODO: Add warning that sewered parcels will not be affected -->
	</section>
</div>




<script src="{{url('/js/main.js')}}"></script>


<script>
	$(document).ready(function(){

		// Append technology id to div to be parsed for polygon creation
		// Obtain icon filename and technology id from props
		$('#select_polygon').data('techId','{{$tech->technology_id}}')
		icon = '{{$tech->icon}}'
		techId = '{{$tech->technology_id}}'

		$('#select_polygon').on('click', function(f){
			f.preventDefault();
			map.disableMapNavigation();
			deleteGraphic();
			$('#popdown-opacity').hide()
			$('.modal-wrapper').hide()
			tb.activate('polygon');
		});

		$('#apply_treatment').on('click', function(e){
			e.preventDefault();
			var rate = $('#septic-rate').val();
			var url = "{{url('/apply_septic')}}" + '/' + rate + '/' + techId;
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(treatment_id){
				$( "#update" ).trigger( "click" );
				addTreatmentIdToGraphic(treatment_id);
				addToStack(treatment_id, icon);
			});
		});
	});
</script>