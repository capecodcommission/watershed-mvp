<!DOCTYPE html>
<html>  
<head> 
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
  <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no"/>
  <title></title>

  <link rel="stylesheet" href="https://js.arcgis.com/3.16/dijit/themes/claro/claro.css">    
  <link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css">
  <style> 
	html, body { height: 100%; width: 100%; margin: 0; padding: 0; }
	#map{
	  padding:0;
	}
  </style> 
  
  <script src="https://js.arcgis.com/3.16/"></script>
  <script> 
	var map;
	

	require([
      "esri/map", 
      "esri/dijit/BasemapGallery", 
      "esri/arcgis/utils",
      "dojo/parser",
      "esri/layers/FeatureLayer",
      "dijit/layout/BorderContainer", 
      "dijit/layout/ContentPane", 
      "dijit/TitlePane",
      "dojo/dom-construct",
      "dojo/domReady!"
      
    ], function(
		Map, 
		BasemapGallery, 
		arcgisUtils,
		parser,
		FeatureLayer,
		domConstruct

    ) {
      parser.parse();







	// require([
	//   "esri/map", 
	//   "esri/dijit/BasemapGallery", 
	//   "esri/arcgis/utils",
	//   "dojo/parser",
	// 	// 	"esri/InfoTemplate",
	// 	// "esri/layers/FeatureLayer",
	// 	"dojo/dom-construct",
	//   "dijit/layout/BorderContainer", 
	//   "dijit/layout/ContentPane", 
	//   "dijit/TitlePane",
	//   "dojo/domReady!"
	// ], function(
	//   Map, BasemapGallery, arcgisUtils,  parser
	  
	// ) {
	//   parser.parse();

	  map = new Map("map", {
		center: [-70.35, 41.68],
		zoom: 11,
		basemap: "gray",
		slider: true,
		sliderOrientation: "horizontal"
	});

	  //add the basemap gallery, in this case we'll display maps from ArcGIS.com including bing maps
	  var basemapGallery = new BasemapGallery({
		showArcGISBasemaps: true,
		map: map
	  }, "basemapGallery");
	  basemapGallery.startup();
	  
	  basemapGallery.on("error", function(msg) {
		console.log("basemap gallery error:  ", msg);
	  });


	  // var template = new InfoTemplate();
			// template.setTitle("<b>${EMBAY_DISP}</b>");
			// template.setContent("<a href='{{url('map')}}/${EMBAY_ID}'>Create a scenario for ${EMBAY_DISP}</a>");  

	  var embayLayer = new FeatureLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4", {
				mode: FeatureLayer.MODE_ONDEMAND
				// infoTemplate:template,
				// outFields: ["EMBAY_DISP", "EMBAY_ID"]
			});
			embayLayer.show();
			map.addLayer(embayLayer);
	});
  </script> 
</head> 

<body class="claro"> 
  <div data-dojo-type="dijit/layout/BorderContainer" 
	   data-dojo-props="design:'headline', gutters:false" 
	   style="width:100%;height:100%;margin:0;">

	<div id="map" 
		 data-dojo-type="dijit/layout/ContentPane" 
		 data-dojo-props="region:'center'" 
		 style="padding:0;">

	  <div style="position:absolute; right:20px; top:10px; z-Index:999;">
		<div data-dojo-type="dijit/TitlePane" 
			 data-dojo-props="title:'Switch Basemap', closable:false, open:false">
		  <div data-dojo-type="dijit/layout/ContentPane" style="width:380px; height:280px; overflow:auto;">
			<div id="basemapGallery"></div>
		  </div>
		</div>
	  </div>

	</div>
  </div>
</body> 

</html>
