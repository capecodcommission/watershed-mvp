<!-- This will be a Vue component -->
<div id="progress"><br />
<img src="http://www.watershedmvp.org/Images/mvplogo.png" alt="WatershedMVP 3.0 by Cape Cod Commission"><br /><br />
	<h2>Subembayments for {{$embayment->EMBAY_DISP}}</h2>
	@foreach($subembayments as $subem)
		<subembayment 
			title="{{$subem->subem_disp}}" 
			percent="{{$subem->n_load_target/$subem->n_load_att}}" 
			NLoad_Orig = {{$subem->n_load_att}}
			NLoad_Target= {{$subem->n_load_target/1}}
			id="{{$subem->subem_id}}"
			:my-effective="effective" >
		</subembayment>
	@endforeach
</div>

<template id="subembayment-template">
		<div class="subembayment" id="@{{id}}">
			<div class="sub-progress-container">
				<div class="sub-target">
					
				</div>
				<div class="sub-progress" style="width: @{{percent}}">
					
				</div>
				<h3>@{{title}} (<span id="progress_@{{id}}">@{{percent | round }}</span>%)</h3>
			</div>
<!-- 			<span style="background: #2caae4; background: -moz-linear-gradient(left,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	background: -webkit-linear-gradient(left,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	background: linear-gradient(to right,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2caae4', endColorstr='#f9ae1b',GradientType=1 );"> -->
				{{-- @{{title}} (@{{percent | round }}%) --}}
			<!-- </span> -->
			<div class="stats">
				<div class="stat-group">
					<div class="stat-label">Original Attenuated:</div> 	<div class="stat-data">@{{parseFloat(NLoad_Orig)|round}}kg</div>
				</div>

				<div class="stat-group">
					<div class="stat-label">Scenario Attenuated:</div> 	<div class="stat-data">@{{NLoad_Current| round }}kg</div>
				</div>

				<div class="stat-group">
					<div class="stat-label">Target:</div> 				<div class="stat-data">@{{NLoad_Target| round }}kg</div>
				</div>
			</div>
		</div>
	</template>







		 <style>
			.sub-progress-container		{	position: relative; font-family: TrendSansOne, Futura, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; height: 2em; margin: 0; overflow: hidden;}
			.sub-progress-container div	{	position: absolute; width: 8em; display: inline-block; bottom: 0; margin: 0;}
			#progress div.sub-progress-container h3 	{	vertical-align: middle; text-align: center; color: #fff; z-index: 20; position: absolute; top: 0;}
			div.sub-progress-conatiner h3 span { display: inline; }
			/*#overall_progress { width: 250px; height: 220px; position: absolute; bottom: 0; }*/
		/*	div.labels 		{	clear: both;}
			div.labels h3 	{	font-size: 1em;  margin: 0 0 .25em}
			h3.progress 	{ 	color: rgb(23, 139, 202); }
			h3.nitrogen 	{ 	color: #51721B; }*/
			div.sub-target 	{	background-color: #f9ae1b; height: 2em; width: 100%;}
			div.sub-progress 	{ background-color: #2caae4; height: 2em;}
		/*	#progress	{ overflow-x: hidden; }*/
			
			/*h4#update i 	{ color: #666; z-index: 20; font-size: 2em; top: 3em; position: absolute; left: 2em; }*/
		 </style>

		<script>
			var progress;
			progress = {{$progress}};

			$('div.progress').css('height', progress+'%');
			$('div.progress h3').text(progress + '%');
			$('#update').on('click', function(e){
				var url= '/getScenarioProgress';

				$.ajax({
						method: 'GET',
						url: url
					})
						.done(function(msg){
							progress = msg;
							$('div.sub-progress-container h3 span').text(progress + '%');

							$('div.sub-progress').animate({'width': progress+'%'}, 500);
							
						})
			});
		</script>