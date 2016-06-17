<html>
	<head>
		 <script src="http://d3js.org/d3.v3.min.js" language="JavaScript"></script>
		 <script src="{{url('/js/liquidFillGauge.js')}}"></script>
		 <style>
			.container		{	position: relative; font-family: TrendSansOne, Futura, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;}
			.container svg	{	position: absolute; }
			div.labels 		{	clear: both;}
			div.labels h3 	{	font-size: 1em;  margin: 0 0 .25em}
			h3.progress 	{ 	color: rgb(23, 139, 202); }
			h3.nitrogen 	{ 	color: #51721B; }
		 </style>
	</head>
	<body>
		<div class="container">
			<div class="labels">
				<h3 class="progress">Overall Progress: 75%</h3>
				<h3 class="nitrogen">Nitrogen Remaining: 25%</h3>
			</div>
			<svg id="overall_progress" width="150" height="150" onclick="overall_progress_gauge.update(NewValue());"></svg>
			<svg id="nitrogen_reduction" width="150" height="150" onclick="nitrogen_reduction_gauge.update(NewValue());"></svg>

		</div>
		
		<script>
			
			var config1 = liquidFillGaugeDefaultSettings();
			// config1.circleColor = "#FF7777";
			// config1.textColor = "#FF4444";
			// config1.waveTextColor = "#FFAAAA";
			// config1.waveColor = "#FFDDDD";
			// config1.circleThickness = 0.2;
			config1.textVertPosition = 0.75;
			config1.waveAnimateTime = 1000;
			config1.textSize = 0.5;
			var overall_progress_gauge = loadLiquidFillGauge("overall_progress", 75, config1);

			
			var config2 = liquidFillGaugeDefaultSettings();
			// config2.circleColor = "#51721B";
			config2.textColor = "#FF4444";
			// config2.waveTextColor = "";
			config2.waveColor = "#51721B";
			config2.circleThickness = 0.1;
			config2.textVertPosition = 0.25;
			config2.textSize = 0.5
			// config2.waveAnimateTime = 1000;
			var nitrogen_reduction_gauge = loadLiquidFillGauge("nitrogen_reduction", 25, config2);

			function NewValue()
			{
				if(Math.random() > .5)
				{
					return Math.round(Math.random()*100);
				} 
				else 
				{
					return (Math.random()*100).toFixed(1);
				}
			}
		</script>
	</body>
</html>