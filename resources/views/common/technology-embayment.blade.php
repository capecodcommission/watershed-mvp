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
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
					<img src="http://www.watershedmvp.org/images/SVG/{{$tech->Icon}}" width="75">
				 {{$tech->Technology_Strategy}}&nbsp;<i class="fa fa-question-circle"></i>
				</a>
			</div>

			<!--
					This needs to be a case/switch based on the show_in_wmvp field
					0 => (this shouldn't ever appear because this technology shouldn't have been listed)
					1 => user will enter a unit metric to use for calculations (acres, linear feet, etc)
					2 => user will need to select a polygon for the treatment area
					3 => user will select a polygon and enter the unit metric for the treatment area calculation
						unit metric is used to calculate cost
					4 => user does not enter a treatment area (Fertilizer Mgmt or Stormwater BMPs)
			 -->

				<!-- @if($tech->Show_In_wMVP == 1) -->
					<!-- <p class="select"><button id="select_area">Select a location</button> <span>@{{subembayment}}</span></p> -->
					<!-- <p>
						<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated:
						<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;"></label>
					</p>
				@elseif($tech->Show_In_wMVP == 2) -->
					<!-- <div id="info">Select a polygon for the treatment area:  -->
						<!-- <button id="select_polygon">Draw Polygon</button> -->
					<!-- </div> -->
					<!-- <p class="select"><button id="select_area">Select a polygon</button> <span>@{{subembayment}}</span></p> -->
				<!-- @elseif($tech->Show_In_wMVP == 3)
					<p class="select"><button id="select_area">Select a location</button> <span>@{{subembayment}}</span></p>
					<p>
						<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated:
						<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;"></label>
					</p>
				@endif -->
				<p>
						<label for="unit_metric">Enter number of {{$tech->Unit_Metric}} to be treated:
						<input type="text" id="unit_metric" name="unit_metric" size="3" style="width: auto;"></label>
					</p>
<p class="select"><button id="select_area">Select a Subembayment</button> <span></span></p>
			<p>
				Enter a valid reduction rate between {{round($tech->Absolu_Reduc_perMetric_Low)}} and {{round($tech->Absolu_Reduc_perMetric_High)}}kg per {{$tech->Unit_Metric}}.<br />

				<input type="range" id="embayment_percent" min="{{round($tech->Absolu_Reduc_perMetric_Low, 2)}}" max="{{round($tech->Absolu_Reduc_perMetric_High, 2)}}" v-model="subembayment_amount" value="{{$tech->Nutri_Reduc_N_Low}}" step=".01"> @{{subembayment_amount}}
			</p>
			<p>
				<button id="applytreatment">Apply</button>
				<button id="canceltreatment" class='button--cta right'><i class="fa fa-ban"></i> Cancel</button>
			</p>


	</section>
</div>


<script src="{{url('/js/main.js')}}"></script>


<script>
	$(document).ready(function(){

		// TO BE DELETED
		$('div.fa.fa-spinner.fa-spin').remove()
		var subem_id = ''
		treatment = {{$treatment->TreatmentID}};


		icon = '{{$tech->icon}}'
		$('#select_area').data('icon', icon.toString());
		techId = '{{$tech->technology_id}}'
		 
		$('#select_area').on('click', function(f) {
			f.preventDefault();
			$('.modal-wrapper').hide();
			deleteGraphic('dump');
			map.setInfoWindowOnClick(false);
			tb.activate('point');
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

		$('#applytreatment').on('click', function(e){
			// need to save the treated N values and update the subembayment progress
			e.preventDefault();
			var percent = $('#embayment_percent').val();
			var units = $('#unit_metric').val();
			var url = "{{url('apply_embayment')}}" + '/' + percent + '/' + units + '/' + techId;
			// console.log(url);
			$.ajax({
				method: 'GET',
				url: url
			})
			.done(function(treatment_id) {
				destroyModalContents();
				$( "#update" ).trigger( "click" );
				addTreatmentIdToGraphic(treatment_id);
				addToStack(treatment_id, icon, techId);
			});
		});

		$('#canceltreatment').on('click', function(e){
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
