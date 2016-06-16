<html>
	<head>
		 <script src="http://d3js.org/d3.v3.min.js" language="JavaScript"></script>
		 <script src="{{url('/js/liquidFillGauge.js')}}"></script>
	</head>
	<body>
		<svg id="fillgauge1" width="150" height="150" onclick="gauge1.update(NewValue());"></svg>
		<script>
			 var gauge1 = loadLiquidFillGauge("fillgauge1", 55, config1);
		    var config1 = liquidFillGaugeDefaultSettings();
		    config1.circleColor = "#FF7777";
		    config1.textColor = "#FF4444";
		    config1.waveTextColor = "#FFAAAA";
		    config1.waveColor = "#FFDDDD";
		    config1.circleThickness = 0.2;
		    config1.textVertPosition = 0.2;
		    config1.waveAnimateTime = 1000;
		    config1.backgroundColor = '#ff0000';


		        function NewValue(){
        if(Math.random() > .5){
            return Math.round(Math.random()*100);
        } else {
            return (Math.random()*100).toFixed(1);
        }
    }
		</script>
	</body>
</html>