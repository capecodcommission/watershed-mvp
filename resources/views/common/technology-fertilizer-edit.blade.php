		<title>{{$tech->Technology_Strategy}}</title>
		<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
		

<div class="popdown-content" >
	<header><h2>{{$tech->Technology_Strategy}}</h2></header>
	<section class="body">

			<div class="technology">
				<a href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->id}}" target="_blank">
					<img src="http://2016.watershedmvp.org/images/SVG/{{$tech->Icon}}" width="75">
				 <i class="fa fa-question-circle"></i>
				</a>			
			</div>
		<p>Nitrogen removed by this treatment: {{round($treatment->Nload_Reduction)}}kg</p>
		<p>Treatment reduction rate: {{$treatment->Treatment_Value}}%</p>
	
			<p>
				Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.<br />
				
				<input type="range" id="fert-percent" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="fert_percent" value="{{$treatment->Treatment_Value}}"> @{{fert_percent}}%
			</p>
			<p>
				<button id="updatetreatment">Update</button>
				<button id="deletetreatment" class='button--cta right'><i class="fa fa-trash-o"></i> Delete</button>
			</p>

	</section>
</div>


<script src="{{url('/js/main.js')}}"></script>
{{-- <script src="{{url('/js/app.js')}}"></script> --}}


<script>
	$(document).ready(function(){

		treatment = {{$treatment->TreatmentID}};

		$('#updatetreatment').on('click', function(e){
			e.preventDefault();
			var percent = $('#fert-percent').val();
			var url = "{{url('/update/fert', $treatment->TreatmentID)}}"  + '/' + percent;
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
		// var url = "{{url('delete_treatment', $treatment->TreatmentID, 'fert')}}";
		var url = "{{url('delete_treatment')}}" + '/' + $treatment->TreatmentID + '/' + 'fert'
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