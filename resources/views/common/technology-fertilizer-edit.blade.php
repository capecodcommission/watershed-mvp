<!-- Set the title to 'Technology_Strategy' from the dbo.v_Technology_Matrix obtained by 'TechnologyController.php' -->
<title>{{$tech->Technology_Strategy}}</title>
<link rel="stylesheet" href="{{url('/css/jquery.popdown.css')}}">
<!-- Set the popdown up with a header, a body with the technology, N removed, Treatment reduction rate, update button
and delete button -->
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
		
		<p>Nitrogen removed by this treatment: {{round($treatment->Nload_Reduction)}}kg</p>
		<p>Treatment reduction rate: {{$treatment->Treatment_Value}}%</p>
		<p>
			Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.<br />
			<input type="range" id="fert-percent" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="fert_percent" value="{{$treatment->Treatment_Value}}"> @{{fert_percent}}%
		</p>
		<p>
			<button v-show="fert_percent != {{$treatment->Treatment_Value}}" id="updatetreatment">Update</button>
			<button data-treatment = "{{$treatment->TreatmentID}}" id="deletetreatment" class='button--cta right'><i class="fa fa-trash-o"></i> Delete</button>
		</p>
	</section>
</div>

<!-- Import the vue data and computed properties -->
<script src="{{url('/js/main.js')}}"></script>


<script>
	$(document).ready(function() {
		// Check the state of readyness for applyTreatment, closeWindow & canceltreatment - remove the spinner once ready
		$('div.fa.fa-spinner.fa-spin').remove()
		// set the treatment variable to the TreatmentID from dbo.Treatment_Wiz
		let treatment = {{$treatment->TreatmentID}};

		// On Click of treatment icon from the applied treatments tray, get the percent variable for the fertilization percent
		// for reduction selection by user and set the url to use to send an ajax GET method to route the user input slider value
		// for the fertilzation percent
		$('#updatetreatment').on('click', function(e) {
			e.preventDefault();
			var percent = $('#fert-percent').val();
			var url = "{{url('/update/fert', $treatment->TreatmentID)}}"  + '/' + percent;
			$.ajax({
				method: 'GET',
				url: url
			})
			// Once the GET method is complete, format the returned message, hide the popdown, fire off the update subemebayments
			// progress and embayment progress, set the newtreatment variable and add it to the selected treatments popdown tray at top
			.done(function(msg) {
				msg = Math.round(msg);
				$('#popdown-opacity').hide();
				$( "#update" ).trigger( "click" );
			});
		});

		// Clicking the close window button, hide the popdown
		$('#closeWindow').on('click', function (e) {
			$('#popdown-opacity').hide();
		})
		
		// On clicking delete treatment, set the treatment variable to the treatment from
		// the data object, set the url for the delete treatment route, once the ajax finishes,
		// hide the popdown, delete the treatment from the applied treatment tray and click the
		// update subemebayments progress and embayment progress
		$('#deletetreatment').on('click', function(e) {
		let treat = $(this).data('treatment');
		let url = "{{url('delete_treatment')}}" + '/' + treat + '/' + 'fert';
		$.ajax({
			method: 'GET',
			url: url
		})
		.done(function(msg) {
			$('#popdown-opacity').hide();
			$("li[data-treatment='{{$treatment->TreatmentID}}']").remove();
			$("#update").trigger("click");
		});
		});
	});
</script>