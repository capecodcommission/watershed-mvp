<!DOCTYPE html>

<html>
<head>
	<title>Testing Laravel for map</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link href="/css/app.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css">
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
			template.setContent("<a href='/map/${EMBAY_ID}'>Create a scenario for ${EMBAY_DISP}</a>");  

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
			<h1>WatershedMVP 3.0</h1>
			<p>Welcome to the Cape Cod Commission's WatershedMVP tool. The Cape Cod Commission developed the WatershedMVP application for professionals, municipal officials and community members in order to assist in creating the most cost-effective and efficient solutions to Cape Cod’s wastewater problem. The application is an informational resource intended to provide regional estimates for planning purposes. WatershedMVP is an initiative of the Cape Cod Commission’s Strategic Information Office (SIO). To learn more about the WatershedMVP application and the Cape Cod Commission and its SIO, please Contact Us.</p>
					<fieldset>
						<legend>Get Started</legend>
						<p>Register or Sign In to see your saved scenarios</p>
						<p>
							<label for="email">Email Address</label>
							<input type="email" placeholder = "Email Address">  
						</p>
						<p>
							<label for="password">Password</label>
							<input type="password" value="123456789">
						</p>
						<p>Name your Scenario (optional): <input type="text"></p>
						<p>Description or comments (optional) <input type="text"></p>
					</fieldset>
					<fieldset>
						<legend>Choose your area</legend>
						<p>You can select a watershed from the list below, or click on the map to get started.</p>
					
						<p>Select your embayment from the list or click on the map to get started. 
							<select id="ddlEmbayment" class="Filter" >
								<option value="">Select an embayment</option>
								@foreach ($embayments as $embayment)
									<option value="{{$embayment->EMBAY_ID}}">{{$embayment->EMBAY_DISP}}</option>
								@endforeach
							<?php 
								// foreach ($embay_list as $embayment) {
								// 	echo "<option value='" . $embayment['embay_id'] . "'>" . $embayment['embay_disp'] . "</option>";
								// }

							?>
							</select>
						</p>
					</fieldset>
					<p>
						<a href="/map" id="startwizard" class="button">Get Started</a>
					</p>
		</div>
</body>
</html>
