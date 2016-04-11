<?php

	include 'embeds/_header.php';
	include 'embeds/db.php';

	$embay_query = "select embay_id, embay_disp from CapeCodMA.Embayments order by embay_disp asc";
	$embay_result = mssql_query($embay_query);
	$embay_list = array();
	while ($row = mssql_fetch_assoc($embay_result)) {
		$embay_list[] = $row;
	}
	
?>

<script src="https://js.arcgis.com/3.16/"></script>
	<script>
	  var map;

	  require([
		"esri/map", 
		// "esri/dijit/InfoWindowLite",
		"esri/InfoTemplate",
		"esri/layers/FeatureLayer",
       // "esri/geometry/Extent",

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


			
			  // var initialExtent = new Extent({ "xmin": -7980970.14, "ymin": 5033003.02, "xmax": -7705796.84, "ymax": 5216451.89, "spatialReference": { "wkid": 102100 } });

		  map = new Map("map", {
				center: [-70.35, 41.68], //#TODO find a new center for the start page, to shift the map to the left
				// extent: initialExtent,
				zoom: 11,
				basemap: "gray"
		  });


		var template = new InfoTemplate();
		template.setTitle("<b>${EMBAY_DISP}</b>");
		template.setContent("<a href='<?php echo $url;?>/map.php?id=${EMBAY_ID}'>Create a scenario for ${EMBAY_DISP}</a>");  

		var embayLayer = new FeatureLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4", {
		  mode: FeatureLayer.MODE_ONDEMAND,
		  infoTemplate:template,
		  outFields: ["EMBAY_DISP", "EMBAY_ID"]
		});
		embayLayer.show();
		map.addLayer(embayLayer);
		});
	</script>
  <style>
	#map {
		position: fixed;
	  top: 50%;
  left: 50%;
  /* bring your own prefixes */
  transform: translate(-50%, -50%);
		height: 100%;
		width: 100%;
		z-index: -10;
	}
	/* Can't use the usual primary/secondary/wrapper layout because the map needs to be full-width in the background but still clickable. 
	*/
.secondary.start
{
	width: 30%;
	float: left;
	background: #efefef;
	border-radius: 25px;
	padding: 1.5em;
}
  </style>

</head>
<body class="start">

<div id="map" class="map">	</div>

	<div class="secondary start">
		<h1>WatershedMVP 3.0</h1>
		<p>Welcome to the Cape Cod Commission's WatershedMVP tool. The Cape Cod Commission developed the WatershedMVP application for professionals, municipal officials and community members in order to assist in creating the most cost-effective and efficient solutions to Cape Cod’s wastewater problem. The application is an informational resource intended to provide regional estimates for planning purposes. WatershedMVP is an initiative of the Cape Cod Commission’s Strategic Information Office (SIO). To learn more about the WatershedMVP application and the Cape Cod Commission and its SIO, please Contact Us.</p>
					<fieldset>
						<legend>Get Started</legend>
						<p>Register or Sign In to see your saved scenarios</p>
						<p><label for="email">Email Address</label>
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
							<?php 
								foreach ($embay_list as $embayment) {
									echo "<option value='" . $embayment['embay_id'] . "'>" . $embayment['embay_disp'] . "</option>";
								}

							?>
							</select>
						</p>
					</fieldset>
					<p>
						<a href="/map.php" id="startwizard" class="button">Get Started</a>
					</p>
	</div>
<script src="/js/app.js"></script> 
<script>
	$('#ddlEmbayment').on('change', function(){
		var watershed = $(this).val();
		$('#startwizard').attr('href','<?php echo $url;?>/map.php?id='+watershed);
	});
</script>
</body>
</html>