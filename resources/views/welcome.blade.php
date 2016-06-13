<!DOCTYPE html>
<!-- This is the starting page for the WMVP3 Wizard where the user logs in or selects a watershed to run a scenario -->
<html>
<head>
	<title>WatershedMVP 3.0</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link href="{{url('css/app.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css">
	<style>
		#map	{ z-index: -10; }
	</style>
	<script src="https://js.arcgis.com/3.16/"></script>
	<script>
		var map;

		require([
		"esri/map", 
		"esri/InfoTemplate",
		"esri/layers/FeatureLayer",
		"dojo/dom-construct",
		"dojo/domReady!"
		],
		function (Map,
		  // InfoWindowLite,
		  InfoTemplate,
		  FeatureLayer,
		  // Extent,
	
		  domConstruct
		 ) {

			  map = new Map("map", {
					center: [-70.35, 41.68], //#TODO find a new center for the start page, to shift the map to the left
					// extent: initialExtent,
					zoom: 11,
					basemap: "gray"
			  });


			var template = new InfoTemplate();
			template.setTitle("<b>${EMBAY_DISP}</b>");
			template.setContent("<a href='{{url('map')}}/${EMBAY_ID}'>Create a scenario for ${EMBAY_DISP}</a>");  

			var embayLayer = new FeatureLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4", {
				mode: FeatureLayer.MODE_ONDEMAND,
				infoTemplate:template,
				outFields: ["EMBAY_DISP", "EMBAY_ID"]
			});
			embayLayer.show();
			map.addLayer(embayLayer);
		});
	</script>
</head>
<body class="start">
	<div id="map" class="map"></div>
		<div class="secondary start">
		<img src="http://www.watershedmvp.org/Images/mvplogo.png" alt="WatershedMVP 3.0 by Cape Cod Commission">
		
		<fieldset>

			<p>The Cape Cod Commission developed the WatershedMVP application for professionals, municipal officials and community members in order to assist in creating the most cost-effective and efficient solutions to Cape Cod’s wastewater problem.</p>

			<p>The application is an informational resource intended to provide regional estimates for planning purposes. WatershedMVP is an initiative of the Cape Cod Commission’s Strategic Information Office (SIO). To learn more about the WatershedMVP application and the Cape Cod Commission and its SIO, please <a href="http://www.capecodcommission.org/index.php?id=205" target="_blank">contact us</a>.</p>

			<p>You can select a watershed from the list below, or click on the map to get started.</p>
		
			<p>
				<select id="embayment" class="Filter" >
					<option value="">Select an embayment</option>
					@foreach ($embayments as $embayment)
						<option value="{{$embayment->EMBAY_ID}}">{{$embayment->EMBAY_DISP}}</option>
					@endforeach
				</select>
			</p>

			<p>
				<a href="{{url('/map')}}" id="startwizard" class="button">Get Started</a>
			</p>

		</fieldset>
			
		</div>
		 

		<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.1.js"></script>
		<script>
			$(document).ready(function(){
				$('#embayment').on('change', function(){
					var watershed = $(this).val();
					$('#startwizard').attr('href', "{{url('/map')}}/"+watershed);
				});
			});
		</script>
		
</body>
</html>
