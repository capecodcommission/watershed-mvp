
		 <style>
			.progress-container		{	position: relative; font-family: TrendSansOne, Futura, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; height: 180px; margin: 0;}
			.progress-container div	{	position: absolute; width: 8em; display: inline-block; bottom: 0; margin: 0;}
			#overall_progress { width: 250px; height: 220px; position: absolute; bottom: 0; }
		/*	div.labels 		{	clear: both;}
			div.labels h3 	{	font-size: 1em;  margin: 0 0 .25em}
			h3.progress 	{ 	color: rgb(23, 139, 202); }
			h3.nitrogen 	{ 	color: #51721B; }*/
			div.target 	{	background-color: #f9ae1b; height: 100%;}
			div.progress 	{ background-color: #2caae4; }
			div.progress h3 	{	vertical-align: middle; text-align: center; color: #fff;}
			h4#update i 	{ color: #666; z-index: 20; }
		 </style>

		<!-- <div class="containerx"> -->
<!-- 			<div class="labels">
				<h3 class="progress">Overall Progress: 75%</h3>
				<h3 class="nitrogen">Nitrogen Remaining: 25%</h3>
			</div> -->
<!-- 			<svg>
				<defs>
				    <linearGradient id="Gradient-1" x1="0" y1="0" x2="0" y2="75%" >
				        <stop offset="0%" stop-color="#f9ae1b" />
				        <stop offset="70%" stop-color="#2caae4" />
				    </linearGradient>
				</defs>
	    		<rect width="100" height="200" fill= "url(#Gradient-1)"  stroke="#333333" stroke-width="0px" />
			</svg> -->

		<!-- </div> -->
		<div id="overall_progress">
			
		
			<h4 id="update">Scenario Progress <i class="fa fa-refresh" aria-hidden="true"></i></h4>

			<div class="progress-container">
				<div class="target"></div>
				<div class="progress">
					<h3></h3>
					
				</div>
			</div>
		</div>
		<script>
			var progress;
			progress = 25;

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
							$('div.progress h3').text(progress + '%');
							$('div.progress').css('height', progress+'%');
							
						})
			});
		</script>