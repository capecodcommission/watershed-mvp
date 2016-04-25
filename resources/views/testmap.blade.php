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
      "esri/toolbars/draw",
		"esri/symbols/SimpleMarkerSymbol", 
		"esri/symbols/SimpleLineSymbol",
		"esri/symbols/PictureFillSymbol", 
		"esri/symbols/CartographicLineSymbol", 
		"esri/graphic", 
		"esri/Color", 
		"dojo/dom", 
		"dojo/on",
      "dojo/dom-construct",
      "dojo/domReady!"
      
    ], function(
		Map, 
		BasemapGallery, 
		arcgisUtils,
		parser,
		FeatureLayer,
		 Draw,
        SimpleMarkerSymbol, SimpleLineSymbol,
        PictureFillSymbol, CartographicLineSymbol, 
        Graphic, 
        Color, dom, on,
		domConstruct

    ) {
      parser.parse();

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

	   var embayLayer = new FeatureLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4", {
				mode: FeatureLayer.MODE_ONDEMAND
				// infoTemplate:template,
				// outFields: ["EMBAY_DISP", "EMBAY_ID"]
			});
			embayLayer.show();
			map.addLayer(embayLayer);

 var fillSymbol = new PictureFillSymbol(
          "images/mangrove.png",
          new SimpleLineSymbol(
            SimpleLineSymbol.STYLE_SOLID,
            new Color('#000'), 
            1
          ), 
          42, 
          42
        );

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
            tb.activate(tool);
          });
        }

        function addGraphic(evt) {
          //deactivate the toolbar and clear existing graphics 
          tb.deactivate(); 
          map.enableMapNavigation();

          // figure out which symbol to use
          var symbol;
          if ( evt.geometry.type === "point" || evt.geometry.type === "multipoint") {
            symbol = markerSymbol;
          } else if ( evt.geometry.type === "line" || evt.geometry.type === "polyline") {
            symbol = lineSymbol;
          }
          else {
            symbol = fillSymbol;
          }

          map.graphics.add(new Graphic(evt.geometry, symbol));
        }

	  // var template = new InfoTemplate();
			// template.setTitle("<b>${EMBAY_DISP}</b>");
			// template.setContent("<a href='{{url('map')}}/${EMBAY_ID}'>Create a scenario for ${EMBAY_DISP}</a>");  

	 
	});
  </script> 
</head> 

<body class="claro"> 
	<div id="info">
      <div>Select a shape then draw on map to add graphic</div>

      <button id="Polygon">Polygon</button>

    </div>
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
