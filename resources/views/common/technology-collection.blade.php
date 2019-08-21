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
				<br />{{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
			</a>			
		</div>

		<p class="select"><button id="select_polygon_{{$treatment->TreatmentID}}">Draw a polygon</button> </p>

		<p class="select_point">
			<button id="select_destination_{{$treatment->TreatmentID}}" style="display:none;">
				Select a destination
			</button> 
			<span>@{{subembayment}}</span>
		</p>

		<p>
			Enter a valid reduction rate between {{round($tech->Nutri_Reduc_N_Low_ppm)}} and {{round($tech->Nutri_Reduc_N_High_ppm)}} ppm.<br />
			<input 
				type="range" 
				id="septic-rate" 
				min="{{$tech->Nutri_Reduc_N_Low_ppm}}" 
				max="{{$tech->Nutri_Reduc_N_High_ppm}}" 
				v-model="septic_rate" 
				value="{{$tech->Nutri_Reduc_N_Low_ppm}}"
			>
			@{{septic_rate}}
		</p>
		<p>
			<button id="apply_treatment_{{$treatment->TreatmentID}}">Apply</button>
			<button id="cancel_treatment_{{$treatment->TreatmentID}}" class='button--cta right'>Cancel</button>
		</p>
	</section>
</div>

<script src="{{url('/js/main.js')}}"></script> 

<script>
	$(document).ready(function(){

		let destination_active = 0;

		// Remove spinner, retrieve id's from treatment object
		$('div.fa.fa-spinner.fa-spin').remove()
		treatment = {{$treatment->TreatmentID}};
		typeid = {{$treatment->TreatmentType_ID}};
		func = 'collect';

		// Handle on-click event for custom polygon creation
		$('#select_polygon_'+treatment).on('click', function(f){
			f.preventDefault();
			$('#popdown-opacity').hide();

			// Disable map navigation, activate draw-toolbar for polygon creation
			// Once polygon creation is complete, hide the polygon button, show the parcel dump button
			map.disableMapNavigation();
			tb.activate('polygon');
			$('#select_polygon_'+treatment).hide();
			$('#select_destination_'+treatment).show();
		});

		// Handle on-click event for closing the window and deleting the treatment
		// NOTE: May be removed in future versions
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

		// Handle on-click event for parcel nitrogen dump
		$('#select_destination_'+ treatment).on('click', function(f) {
			f.preventDefault();
			destination_active = 1;
			$('#popdown-opacity').hide();
			map.on('click', function(e) {		
				if (destination_active) {
					var url = "{{url('/map/move/')}}"+'/'+e.mapPoint.x+'/'+ e.mapPoint.y +'/' + treatment;
					$.ajax({
						method: 'GET',
						url: url
					})
					.done(function(msg){
						msg = $.parseJSON(msg);
						$('#'+msg.SUBEM_NAME+'> .stats').show();
						$('#popdown-opacity').show();
						$('.select > span').text('Selected: '+msg.SUBEM_DISP);
						$('.select > span').show();
						$('#select_destination_'+treatment).hide();
						destination_active = 0;
					})
				}
			});
		});

		// Handle on-click event for treatment application
		$('#apply_treatment_'+treatment).on('click', function(e){
			e.preventDefault();
			var rate = $('#septic-rate').val();
			var url = "{{url('/apply_septic')}}" + '/' +  treatment + '/' + rate;
			$.ajax({
				method: 'GET',
				url: url
			})
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

		// Handle on-click even for cancelling deleting the treatment
		$('#cancel_treatment_'+treatment).on('click', function(e){
			var url = "{{url('cancel', $treatment->TreatmentID)}}";
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(msg){
				$('#popdown-opacity').hide();
				for (var i = map.graphics.graphics.length - 1; i >= 0; i--) {
					if (map.graphics.graphics[i].attributes) {
						if (map.graphics.graphics[i].attributes.treatment_id == treatment) {
							map.graphics.remove(map.graphics.graphics[i])
						}
					}
				}
			});
		});
	});
</script>