<!-- This will be a Vue component -->
<div id="progress"><br />
<img src="http://www.watershedmvp.org/Images/mvplogo.png" alt="WatershedMVP 3.0 by Cape Cod Commission"><br /><br />
	<h2>Subembayments for {{$embayment->EMBAY_DISP}}</h2>
{{--	@foreach($subembayments as $subem)
		<subembayment 
			title="{{$subem->subem_disp}}" 
			percent="{{$subem->n_load_target/$subem->n_load_att}}" 
			NLoad_Orig = {{$subem->n_load_att}}
			NLoad_Target= {{$subem->n_load_target/1}}
			id="{{$subem->subem_id}}"
			:my-effective="effective" >
		</subembayment>
	@endforeach
	--}}
	@foreach($subembayments as $subem)
		<div class="subembayment" id="subem_{{$subem->subem_id}}">
			<div class="sub-progress-container">
				<div class="sub-target">
					
				</div>
				<div class="sub-progress" style="width: {{($subem->n_load_target/$subem->n_load_att)*100}}%">
					
				</div>
				<h3>{{$subem->subem_disp}} (<span id="progress_{{$subem->subem_id}}">{{round(($subem->n_load_target/$subem->n_load_att)*100)}}</span>%)</h3>
			</div>
			<div class="stats">
				<div class="stat-group">
					<div class="stat-label">Original Attenuated:</div> 	<div class="stat-data">{{round($subem->n_load_att)}}kg</div>
				</div>

				<div class="stat-group">
					<div class="stat-label">Scenario Attenuated:</div> 	<div class="stat-data">kg</div>
				</div>

				<div class="stat-group">
					<div class="stat-label">Target:</div> 				<div class="stat-data">{{round($subem->n_load_target/1)}}kg</div>
				</div>
			</div>
		</div>
	@endforeach
</div>

<!-- <template id="subembayment-template"> -->

	
	<!-- </template> -->







		 <style>
			.sub-progress-container		{	position: relative; font-family: TrendSansOne, Futura, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; height: 2em; margin: 0; overflow: hidden;}
			.sub-progress-container div	{	position: absolute; width: 8em; display: inline-block; bottom: 0; margin: 0;}
			#progress div.sub-progress-container h3 	{	vertical-align: middle; text-align: center; color: #fff; z-index: 20; position: absolute; top: 0;}
			#progress .subembayment div.sub-progress-container h3 span { display: inline; }
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

		