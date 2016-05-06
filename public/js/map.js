var map;

var watershed;
require([
	"esri/map",
	// "esri/dijit/BasemapGallery",
	"esri/arcgis/utils",
	"dojo/parser",
	"esri/layers/ArcGISDynamicMapServiceLayer",
	"esri/layers/ImageParameters",
	"esri/layers/FeatureLayer",

	"esri/toolbars/draw",
	"esri/symbols/SimpleFillSymbol", 
	"esri/graphic", 
	"esri/Color", 

	"esri/tasks/query", 
	"esri/tasks/QueryTask",
	"esri/dijit/LayerList",
	"esri/geometry/Extent",

	
	// "esri/SpatialReference",
	// "dijit/layout/BorderContainer", 
	// "dijit/layout/ContentPane", 
	// "dijit/TitlePane",

	"dojo/dom", 
	"dojo/on", 
 "dojo/dom-construct",
	"dojo/domReady!"
],
function(
			Map, 
			// BasemapGallery, 
			arcgisUtils,
			parser,
			ArcGISDynamicMapServiceLayer, 
			ImageParameters, 
			FeatureLayer, 
//  adding in polygon drawing tool
		// Draw,
		// SimpleMarkerSymbol, SimpleLineSymbol,
		// SimpleFillSymbol, CartographicLineSymbol, 
		// Graphic, 
		// Color, 
		Draw,
		SimpleFillSymbol, 
		Graphic, 
		Color,

			Query, 
			QueryTask, 
			LayerList, 
			Extent,
			dom, on,
			domConstruct
		) 
{
	 parser.parse();

	map = new Map("map", {
		center: [-70.35, 41.68],
		zoom: 11,
		basemap: "gray",
		slider: true,
		sliderOrientation: "horizontal"
	});
	// map.on("load", createToolbar);
	map.on("load", initToolbar);

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
			tb.activate(tool);
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
		  	var url = "/testmap/Nitrogen"+'/'+polystring;
					$.ajax({
						method: 'GET',
						url: url
					})
						.done(function(msg){
							// msg = $.parseJSON(msg);
							console.log(msg);
							// console.log(msg);
							// var txtmsg = "Total Nitrogen in Polygon: " + msg[0].UnAttenFull;
							// alert(txtmsg);
							
						});

		  // console.log(symbol);
		  var area = evt.geometry.getExtent();
		  // console.log(area);
		}

// This is the Esri Leaflet code
// Commenting this out for now

 // var layer = L.esri.basemapLayer('Topographic').addTo(map);
 //  var layerLabels;

 //  function setBasemap(basemap) {
 //    if (layer) {
 //      map.removeLayer(layer);
 //    }

 //    layer = L.esri.basemapLayer(basemap);

 //    map.addLayer(layer);

 //    if (layerLabels) {
 //      map.removeLayer(layerLabels);
 //    }

 //    if (basemap === 'ShadedRelief'
 //     || basemap === 'Oceans'
 //     || basemap === 'Gray'
 //     || basemap === 'DarkGray'
 //     || basemap === 'Imagery'
 //     || basemap === 'Terrain'
 //   ) {
 //      layerLabels = L.esri.basemapLayer(basemap + 'Labels');
 //      map.addLayer(layerLabels);
 //    }
 //  }

  // function changeBasemap(basemaps){
  // var basemap = basemaps.value;
  // setBasemap(basemap);
  // }

/*******************************
*
*	This is the ArcGIS Basemap Gallery which breaks everything
*
*********************************/

	// var basemapGallery = new BasemapGallery({
 //        showArcGISBasemaps: true,
 //        map: map
 //      }, "basemapGallery");
 //      basemapGallery.startup();
      
 //      basemapGallery.on("error", function(msg) {
 //        console.log("basemap gallery error:  ", msg);
 //      });


	var extent;
	var layerDefs = [];
	var queryTask = new QueryTask("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4");

	var query = new Query();
	query.returnGeometry = true;
	query.outFields = ["*"];
	query.where = "EMBAY_ID =" + selectlayer;
	queryTask.execute(query, showResults);
	var imageParameters = new ImageParameters();

	function showResults(results) 
	{
		var resultItems = [];
		var resultCount = results.features.length;
		for (var i = 0; i < resultCount; i++) 
		{
			var featureAttributes = results.features[i].attributes;
			watershed = featureAttributes['EMBAY_DISP'];
		}

		var featureSet = results || {};
		var features = featureSet.features || [];

		extent = esri.graphicsExtent(features);
		// console.log(features.length);
		if (!extent && features.length == 1) 
		{
			var point = features[0];
			map.centerAndZoom(point, 12);
		}
		else 
		{
			map.setExtent(extent, true);
		}

		
		// layerDefs[0] = "Embayment='" + watershed + "'";
		layerDefs[4] = "EMBAY_ID=" + selectlayer;
		// layerDefs[11] = "Subembayments";
		// // layerDefs[4] = 'towns';
		// layerDefs[1] = 'wastewater';
		imageParameters.layerDefinitions = layerDefs;
		imageParameters.layerIds = [4];
		imageParameters.layerOption = ImageParameters.LAYER_OPTION_SHOW;
		imageParameters.transparent = true;


		var graphicsAreaLayer = new esri.layers.GraphicsLayer();
		graphicsAreaLayer.disableMouseEvents();
		map.addLayer(graphicsAreaLayer);

		//construct ArcGISDynamicMapServiceLayer with imageParameters from above
		// var dynamicMapServiceLayer = new ArcGISDynamicMapServiceLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer", { "imageParameters": imageParameters});
		var dynamicMapServiceLayer = new ArcGISDynamicMapServiceLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer", { "imageParameters": imageParameters});


		map.addLayer(dynamicMapServiceLayer);
	
		// console.log(map.extent);

		var featureSet = results || {};
		var features = featureSet.features || [];

		extent = esri.graphicsExtent(features);
		// console.log(features.length);
		if (!extent && features.length == 1) 
		{
			var point = features[0];
			map.centerAndZoom(point, 12);
		}
		else 
		{
			map.setExtent(extent, true);
		}

	}

var Subwatersheds = new FeatureLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer/22",
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		// maxAllowableOffset: map.extent,
		opacity: 1
		});
		Subwatersheds.hide();
		// Subwatersheds.setExtent(extent);
		map.addLayer(Subwatersheds);


// var Subwatersheds = new ArcGISDynamicMapServiceLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer/22", {"imageParameters": imageParameters
// 		// {
// 		// mode: FeatureLayer.MODE_ONDEMAND,
// 		// outFields: ["*"],
// 		// maxAllowableOffset: map.extent,
// 		// opacity: 1
// 		});
// 		// Subwatersheds.hide();
// 		// Subwatersheds.setExtent(extent);
// 		map.addLayer(Subwatersheds);


var Subembayments = new FeatureLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/11",
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		opacity: 1
		});
		Subembayments.hide();
		map.addLayer(Subembayments);

var NitrogenLayer = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/0',
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		opacity: 1
		}
		
	);
	NitrogenLayer.hide();
	map.addLayer(NitrogenLayer);


var WasteWater = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/1',
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		opacity: 1
		}
		
	);
	WasteWater.hide();
	map.addLayer(WasteWater);


var Towns = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/5',
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		opacity: .4,
		// styling: false,
		color: [255,0,0, 1],
		width: 3
		}
		
	);
	Towns.hide();
	map.addLayer(Towns);


var TreatmentType = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/10',
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		opacity: 1
		}
		
	);
	TreatmentType.hide();
	map.addLayer(TreatmentType);


var TreatmentFacilities = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/9',
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		opacity: 1
		}
		
	);
	TreatmentFacilities.hide();
	map.addLayer(TreatmentFacilities);


var Embayments = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4',
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		opacity: 1
		}
		
	);
	TreatmentFacilities.hide();
	map.addLayer(TreatmentFacilities);


var EcologicalIndicators = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer/10',
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		opacity: 1
		}
		
	);
	EcologicalIndicators.hide();
	map.addLayer(EcologicalIndicators);
	
var ShallowGroundwater = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer/32',
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		opacity: .5
		}
		
	);
	ShallowGroundwater.hide();
	map.addLayer(ShallowGroundwater);


var LandUse = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/3',
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		opacity: .5
		}
		
	);
	LandUse.hide();
	map.addLayer(LandUse);

var FlowThrough = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/12',
	{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"]
		// opacity: 1	
	}
);
	FlowThrough.hide();
	map.addLayer(FlowThrough);



// Turn on/off each layer when the user clicks the link in the sidebar.
	
$('#nitrogen').on('click', function(e){
	e.preventDefault();
	console.log(NitrogenLayer);
	if($(this).attr('data-visible')=='off')
	{
		NitrogenLayer.show();
		$(this).attr('data-visible', 'on');	
	}
	else
	{
		NitrogenLayer.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});

$('#embayments').on('click', function(e){
	e.preventDefault();
	
	if($(this).attr('data-visible')=='off')
	{
		Embayments.show();
		$(this).attr('data-visible', 'on');	
	}
	else
	{
		Embayments.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});

$('#subembayments').on('click', function(e){
	e.preventDefault();
	
	if($(this).attr('data-visible')=='off')
	{
		Subembayments.show();
		$(this).attr('data-visible', 'on');	
	}
	else
	{
		Subembayments.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});

$('#subwatersheds').on('click', function(e){
	e.preventDefault();
	
	if($(this).attr('data-visible')=='off')
	{
		Subwatersheds.show();
		$(this).attr('data-visible', 'on');	
	}
	else
	{
		Subwatersheds.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});

$('#wastewater').on('click', function(e){
	e.preventDefault();
	
	if($(this).attr('data-visible')=='off')
	{
		WasteWater.show();
		$(this).attr('data-visible', 'on');	
	}
	else
	{
		WasteWater.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});


$('#towns').on('click', function(e){
	e.preventDefault();

	if($(this).attr('data-visible')=='off')
	{
		Towns.show();
		$(this).attr('data-visible', 'on');	
	}
	else
	{
		Towns.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});

$('#treatmenttype').on('click', function(e){
	e.preventDefault();
	
	if($(this).attr('data-visible')=='off')
	{
		TreatmentType.show();
		$(this).attr('data-visible', 'on');	
	}
	else
	{
		TreatmentType.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});

$('#treatmentfacilities').on('click', function(e){
	e.preventDefault();
	
	if($(this).attr('data-visible')=='off')
	{
		TreatmentFacilities.show();
		$(this).attr('data-visible', 'on');			 
	}
	else
	{
		TreatmentFacilities.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});


$('#ecologicalindicators').on('click', function(e){
	e.preventDefault();
	
	if($(this).attr('data-visible')=='off')
	{
		EcologicalIndicators.show();
		$(this).attr('data-visible', 'on');			 
	}
	else
	{
		EcologicalIndicators.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});

$('#shallowgroundwater').on('click', function(e){
	e.preventDefault();
	
	if($(this).attr('data-visible')=='off')
	{
		ShallowGroundwater.show();
		$(this).attr('data-visible', 'on');			 
	}
	else
	{
		ShallowGroundwater.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});

$('#landuse').on('click', function(e){
	e.preventDefault();
	
	if($(this).attr('data-visible')=='off')
	{
		LandUse.show();
		$(this).attr('data-visible', 'on');			 
	}
	else
	{
		LandUse.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});


$('#flowthrough').on('click', function(e){
	e.preventDefault();
	
	if($(this).attr('data-visible')=='off')
	{
		FlowThrough.show();
		$(this).attr('data-visible', 'on');			 
	}
	else
	{
		FlowThrough.hide();
		$(this).attr('data-visible', 'off');
	}
	// 
});

});
