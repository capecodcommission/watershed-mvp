<head>
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
</head>
<!-- Set the title to 'technology_strategy' from the dbo.v_Technology_Matrix obtained by 'TechnologyController.php' -->
<!-- Set the popdown up with a header, a body with the technology, a table and reduction rate selection -->
<div class="blade_container">
    <button class="modal-close" id ="closeModal">
		<i class="fa fa-times"></i>
	</button>
    <h4 class="blade_title" title="{{$tech->technology_strategy}}">
        {{$tech->technology_strategy}}
    </h4>
    <a title="{{$tech->technology_strategy}} - Technology Matrix" class="blade_image" href="http://www.cch2o.org/Matrix/detail.php?treatment={{$tech->TM_ID}}" target="_blank">
		<!-- <img v-show="{{$tech->technology_id == 400}}" src="{{$_ENV['CCC_ICONS_SVG'].'$tech->icon'}}">  TODO: FUTURE SYNTAX -->
		<img v-show="{{$tech->technology_id == 400}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FertilizerManagement.svg'}}">
		<img v-show="{{$tech->technology_id == 401}}" src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterManagement.svg'}}">
    </a>
    <div class="blade_slider" title="Enter a valid reduction rate between {{$tech->Nutri_Reduc_N_Low}} and {{$tech->Nutri_Reduc_N_High}} percent.">
        <label>Nutrient Reduction Rate</label>
        <label v-show="{{$tech->technology_id == 400}}">@{{fert_percent}}%</label>
        <label v-show="{{$tech->technology_id == 401}}">@{{storm_percent}}%</label>
        <input type="range" id="fert-percent"
        v-show="{{$tech->technology_id == 400}}" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="fert_percent" value="{{$treatment->Treatment_Value}}" step="1">
        <input type="range" id="storm-percent"
        v-show="{{$tech->technology_id == 401}}" min="{{$tech->Nutri_Reduc_N_Low}}" max="{{$tech->Nutri_Reduc_N_High}}" v-model="storm_percent" value="{{$treatment->Treatment_Value}}" step="1">
    </div>
    <button title="Delete Treatment" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" v-if="{{$tech->technology_id == 400}}" v-show="fert_percent == {{$treatment->Treatment_Value}}" id="deletetreatment">Delete</button>
    <button title="Delete Treatment" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" v-else="{{$tech->technology_id == 401}}" v-show="storm_percent == {{$treatment->Treatment_Value}}" id="deletetreatment">Delete</button>
    <button title="Update Treatment" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" v-if="{{$tech->technology_id == 400}}" v-show="fert_percent != {{$treatment->Treatment_Value}}" id="updatetreatment">Update</button>
    <button title="Update Treatment" data-treatment="{{$treatment->TreatmentID}}" class="blade_button" v-else="{{$tech->technology_id == 401}}" v-show="storm_percent != {{$treatment->Treatment_Value}}" id="updatetreatment">Update</button>
</div>

<!-- Import the vue data and computed properties -->
<script src="{{url('/js/main.js')}}"></script>
<script>
    $(document).ready(function() {
        // Check the state of readyness for applyTreatment, closeWindow & canceltreatment - remove the spinner once ready
        $('div.fa.fa-spinner.fa-spin').remove()
        // On Click of treatment icon set the percent variable for the fertilization percent for reduction selection by user
        // and set the url to use to send an ajax GET method to route the user input slider value for the fertilzation percent
        $('#updatetreatment').on('click', function(e) {
            let updateTreatmentButton = document.getElementById("updatetreatment");
		    let setUpdateTreatmentButtonStyling = updateTreatmentButton.setAttribute("style", "display:none;");
            e.preventDefault();
            if ("{{$tech->technology_id == 400}}") {
                let treatmentValue = $('#fert-percent').val();
                let url = "{{url('/update/management', $treatment->TreatmentID)}}" + '/' + treatmentValue;
                $.ajax({
                    method: 'GET',
                    url: url
                })
                // Once the GET method is complete, hide the modal, update the subembayments and embayment progresses,
                // set the newtreatment variable and add it to the treatment stack using the popdown generator
                .done(function(msg) {
                    destroyModalContents();
                    $( "#update" ).trigger( "click" );
                });
            }
            else {
                let treatmentValue = $('#storm-percent').val();
                let url = "{{url('/update/management', $treatment->TreatmentID)}}" + '/' + treatmentValue;
                $.ajax({
                    method: 'GET',
                    url: url
                })
                // Once the GET method is complete, hide the modal, update the subembayments and embayment progresses,
                // set the newtreatment variable and add it to the treatment stack using the popdown generator
                .done(function(msg) {
                    destroyModalContents();
                    $( "#update" ).trigger( "click" );
                });
            }
        });
        
        // On clicking delete treatment, set the treatment variable to the treatment from
        // the data object, set the url for the delete treatment route, once the ajax finishes,
        // hide the popdown, delete the treatment from the applied treatment tray and click the
        // update subemebayments progress and embayment progress
        $('#deletetreatment').on('click', function(e) {
            let deleteTreatmentButton = document.getElementById("deletetreatment");
		    let setDeleteTreatmentButtonStyling = deleteTreatmentButton.setAttribute("style", "display:none;"); 
            let treat = $(this).data('treatment');
            e.preventDefault();
            if ("{{$tech->technology_id == 400}}") {
                let url = "{{url('delete_treatment')}}" + '/' + treat + '/' + 'fert';
                $.ajax({
                    method: 'GET',
                    url: url
                })
                .done(function(msg) {
                    destroyModalContents();
                    $("li[data-treatment='{{$treatment->TreatmentID}}']").remove();
                    $("#update").trigger("click");
                });
            }
            else {
                let url = "{{url('delete_treatment')}}" + '/' + treat + '/' + 'storm';
                $.ajax({
                    method: 'GET',
                    url: url
                })
                .done(function(msg) {
                    destroyModalContents();
                    $("li[data-treatment='{{$treatment->TreatmentID}}']").remove();
                    $("#update").trigger("click");
                });
            }
        });
    });
</script>