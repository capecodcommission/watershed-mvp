var map;

var watershed;
require([
	"esri/map",
	"esri/dijit/BasemapGallery",
	"esri/arcgis/utils",
	"dojo/parser",
	"esri/layers/ArcGISDynamicMapServiceLayer",
	"esri/layers/ImageParameters",
	"esri/layers/FeatureLayer",
	"esri/tasks/query", 
	"esri/tasks/QueryTask",
	"esri/dijit/LayerList",
	"esri/geometry/Extent",

	
	// "esri/SpatialReference",
	"dijit/layout/BorderContainer", 
	"dijit/layout/ContentPane", 
	"dijit/TitlePane",
 "dojo/dom-construct",
	"dojo/domReady!"
],
function(
			Map, 
			BasemapGallery, 
			arcgisUtils,
			parser,
			ArcGISDynamicMapServiceLayer, 
			ImageParameters, 
			FeatureLayer, 
			Query, 
			QueryTask, 
			LayerList, 
			Extent,
			domConstruct
			
			// SpatialReference,
			
			
			// BorderContainer,
			// ContentPane,
			 
			
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

	var basemapGallery = new BasemapGallery({
        showArcGISBasemaps: true,
        map: map
      }, "basemapGallery");
      basemapGallery.startup();
      
      basemapGallery.on("error", function(msg) {
        console.log("basemap gallery error:  ", msg);
      });


	var extent;
	var layerDefs = [];
	var queryTask = new QueryTask("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4");

	var query = new Query();
	query.returnGeometry = true;
	query.outFields = ["*"];
	query.where = "EMBAY_ID =" + selectlayer;
	queryTask.execute(query, showResults);

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

		var imageParameters = new ImageParameters();
		// layerDefs[0] = "Embayment='" + watershed + "'";
		layerDefs[4] = "EMBAY_ID=" + selectlayer;
		// layerDefs[11] = "Subembayments";
		// layerDefs[4] = 'towns';
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
	


	}

var Subwatersheds = new FeatureLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer/22",
		{
		mode: FeatureLayer.MODE_ONDEMAND,
		outFields: ["*"],
		opacity: 1
		});
		Subwatersheds.hide();
		map.addLayer(Subwatersheds);

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
