<!DOCTYPE html>
<html>  
<head> 
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
  <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no"/>
  <title>Testing Map Features</title>

  <link rel="stylesheet" href="https://js.arcgis.com/3.16/dijit/themes/claro/claro.css" />    
  <link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css" />
  <style> 
	html, body { height: 100%; width: 100%; margin: 0; padding: 0; }
	#map {   padding: 0; height:100%; width: 100%;	}
  </style> 
  
  <script src= "https://js.arcgis.com/3.16"></script>
  <script> 
	var map;
	var tb;

	require([

		"esri/map",
		"esri/layers/FeatureLayer",
		"esri/toolbars/draw",
		"esri/symbols/SimpleFillSymbol", 
		"esri/graphic", 
		"esri/Color", 
		"dojo/dom", 
		"dojo/on", 
		"dojo/domReady!"
	], function(
		Map, 
		FeatureLayer,
		Draw,
		SimpleFillSymbol, 
		Graphic, 
		Color, dom, on

	) {
	  // parser.parse();

	  map = new Map("map", {
		center: [-70.35, 41.68],
		zoom: 11,
		basemap: "topo",
		slider: true,
		sliderOrientation: "horizontal"
	});
   map.on("load", initToolbar);
	  // add the basemap gallery, in this case we'll display maps from ArcGIS.com including bing maps
	 //  var basemapGallery = new BasemapGallery({
		// showArcGISBasemaps: true,
		// map: map
	 //  }, "basemapGallery");
	 //  basemapGallery.startup();
	  
	 //  basemapGallery.on("error", function(msg) {
		// console.log("basemap gallery error:  ", msg);
	 //  });

	   var embayLayer = new FeatureLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4", {
				mode: FeatureLayer.MODE_ONDEMAND
				// infoTemplate:template,
				// outFields: ["EMBAY_DISP", "EMBAY_ID"]
			});
			embayLayer.show();
			map.addLayer(embayLayer);

 var fillSymbol = new SimpleFillSymbol();

  function initToolbar() {
		  tb = new Draw(map);
		  tb.on("draw-end", addGraphic);

		  // event delegation so a click handler is not
		  // needed for each individual button
		  on(dom.byId("info"), "click", function(evt) {
			if ( evt.target.id === "info" ) {
			  return;
			}
			var tool = evt.target.id.toLowerCase();
			map.disableMapNavigation();
			tb.activate(tool, {showTooltips: false});
		  });
		}

		function addGraphic(evt) {
		  //deactivate the toolbar and clear existing graphics 
		  tb.deactivate(); 
		  map.enableMapNavigation();

		  // figure out which symbol to use
		  var symbol;
			symbol = fillSymbol;
			var polystring = '';
		  map.graphics.add(new Graphic(evt.geometry, symbol));
		  // console.log(evt.geometry);
		  // console.log('entering loop');


		  for (var i = 0; i < evt.geometry.rings[0].length; i++) {
		  	polystring += evt.geometry.rings[0][i][0] + ' ';
		  	polystring += evt.geometry.rings[0][i][1] + ', ';
		  }
		   var len = polystring.length;
		  polystring = polystring.substring(0,len-2);
		  
		  // console.log('exec CapeCodMa.Get_NitrogenFromPolygon \'' + polystring + '\'');
		  
		  console.log(polystring);
		  	var url = "{{url('/testmap/Nitrogen/')}}"+'/'+polystring;
		  	$('#testparcel').attr('href', url);
					// $.ajax({
					// 	method: 'GET',
					// 	url: url
					// })
					// 	.done(function(msg){
					// 		// msg = $.parseJSON(msg);
					// 		console.log(msg);
					// 		// console.log(msg);
					// 		// var txtmsg = "Total Nitrogen in Polygon: " + msg[0].UnAttenFull;
					// 		// alert(txtmsg);
							
					// 	});

		  // console.log(symbol);
		  var area = evt.geometry.getExtent();
		  // console.log(area);
		}

	 
	});
  </script> 
</head> 

<body class="claro"> 
	<div id="info">
	  <div>Select a shape then draw on map to add graphic</div>

	  <button id="Polygon">Polygon</button>

	</div>
	<p><a href="" id="testparcel">Get Parcel Data for Polygon</a></p>

  <div data-dojo-type="dijit/layout/BorderContainer" 
	   data-dojo-props="design:'headline', gutters:false" 
	   style="width:100%;height:100%;margin:0;">

	<div id="map" data-dojo-type="dijit/layout/ContentPane" 
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
  <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
</body> 

</html>
