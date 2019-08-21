<title>{{$tech->Technology_Strategy}}</title>
<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
<meta name="csrf-token" id="token" content="{{ csrf_token() }}">
		
<div class="popdown-content" id="app">
	<header>
		<div class = 'row'>
			<div class = 'col'>
				<h2>
					{{$tech->Technology_Strategy}}
					<button style = 'position: absolute; right: 20; top: 10' id = "closeWindow">
						<i class = 'fa fa-times'></i>
					</button>
				</h2>
			</div>
		</div>
	</header>
	<section class="body">
		<div class="technology">
			<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
				<img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}" width="75">
				<br />
				{{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
			</a>			
		</div>
		<fieldset>
			<h3>Treatment Stats</h3>
			<ul>
				<li>Treatment reduction rate: <strong>{{$treatment->Treatment_Value}}ppm</strong></li>
				<li>Nitrogen removed by this treatment: <strong>{{round($treatment->Nload_Reduction)}}kg</strong></li>
				<li>Parcels affected: <strong>{{$treatment->Treatment_Parcels}}</strong></li>
				<li>Total Treatment Cost: <strong>{{money_format('%10.0n', $treatment->Cost_Total)}}</strong></li>
			</ul>
		</fieldset>	
		<p>
			Enter a valid reduction rate between {{round($tech->Nutri_Reduc_N_Low_ppm)}} and {{round($tech->Nutri_Reduc_N_High_ppm)}} ppm.<br />
			<input 
				type="range" 
				id="septic-rate" 
				min="{{$tech->Nutri_Reduc_N_Low_ppm}}" 
				max="{{$tech->Nutri_Reduc_N_High_ppm}}" 
				v-model="septic_rate" 
				value="{{$treatment->Treatment_Value}}"
			>
			@{{septic_rate}}
		</p>
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
		typeid = {{$treatment->TreatmentType_ID}};
		func = 'collect';

		$('#select_polygon').on('click', function(f){
			f.preventDefault();
			$('#popdown-opacity').hide();
			map.disableMapNavigation();
			tb.activate('polygon');
			// console.log(tb);
			$('#select_polygon').hide();
			$('#select_destination').show();
			// console.log(msg);
		});

		$('#select_destination').on('click', function(f){
			f.preventDefault();
			// console.log('button clicked');
			$('#popdown-opacity').hide();
			map.on('click', function(e){
				// console.log(e);
			
				var url = "{{url('/map/move/')}}"+'/'+e.mapPoint.x+'/'+ e.mapPoint.y +'/' + treatment;
				$.ajax({
					method: 'GET',
					url: url
				})
				.done(function(msg){
					msg = $.parseJSON(msg);
					// console.log(msg.SUBEM_DISP);
					// console.log(msg);
					$('#'+msg.SUBEM_NAME+'> .stats').show();
					// $('.notification_count').remove();
					$('#popdown-opacity').show();
					$('.select > span').text('Selected: '+msg.SUBEM_DISP);
					$('.select > span').show();
					$('#select_destination').hide();
				})
			});
		});

		$('#closeWindow').on('click', function (e) {

			$('#popdown-opacity').hide();
		})

		$('#updatetreatment').on('click', function(e) {
			e.preventDefault();
			var rate = $('#septic-rate').val();
			var url = "{{url('/update/collect', $treatment->TreatmentID)}}"  + '/' + rate;
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(msg){
				$('#popdown-opacity').hide();
				$( "#update" ).trigger( "click" );
			});
		});

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
				$('#update').trigger('click')
			});
		});
	});
</script>