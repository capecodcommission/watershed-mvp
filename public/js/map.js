var map;

var watershed;
var embay_shape;
var treatment;
var func;
var edit_active;
var destination_active;
var treatment_polygons = new Array();
require([
		"esri/map",
		"esri/dijit/BasemapGallery",
		"esri/arcgis/utils",
		"dojo/parser",
		"esri/layers/ArcGISDynamicMapServiceLayer",
		"esri/layers/ImageParameters",
		"esri/layers/FeatureLayer",
		"esri/dijit/Legend",

		"esri/toolbars/draw",
		"esri/toolbars/edit",
		"esri/symbols/SimpleFillSymbol",
		"esri/symbols/SimpleMarkerSymbol",
		"esri/renderers/SimpleRenderer",
		"esri/symbols/SimpleLineSymbol",
		"esri/graphic",
		"esri/Color",
		"esri/renderers/Renderer",
		"esri/renderers/UniqueValueRenderer",
		"esri/renderers/ClassBreaksRenderer",

		"esri/tasks/query",
		"esri/tasks/QueryTask",
		"esri/tasks/identify",
		"esri/InfoTemplate",
		      // "esri/dijit/Popup",
        //    "esri/dijit/PopupTemplate",
		// "esri/tasks/infoWindow",
		"esri/dijit/LayerList",
		"esri/geometry/Extent",


		// "esri/SpatialReference",
		// "dijit/layout/BorderContainer", 
		// "dijit/layout/ContentPane", 
		// "dijit/TitlePane",

		 "dojo/_base/event",
		"dojo/dom",
		"dojo/on", "dijit/registry", 
		"esri/geometry/geometryEngine",
		"dojo/dom-construct",
		"dojo/domReady!",
		"esri/geometry/geometryEngine"
	],
	function(
		Map,
		// Popup, PopupTemplate,
		BasemapGallery, 
		arcgisUtils,
		parser,
		ArcGISDynamicMapServiceLayer,
		ImageParameters,
		FeatureLayer,
		Legend,

		Draw,
		Edit,
		SimpleFillSymbol,
		SimpleMarkerSymbol,
		SimpleRenderer,
		SimpleLineSymbol,
		Graphic,
		Color,
		Renderer,
		UniqueValueRenderer,
		ClassBreaksRenderer,

		Query,
		QueryTask,

		identify,
		InfoTemplate,
		// infoWindow,
		LayerList,
		Extent,
		event,
		dom, on,
		registry,

		geometryEngine,
		domConstruct
	) {
		parser.parse();

		var initialExtent = new Extent({ "xmin": -7980970.14, "ymin": 5033003.02, "xmax": -7705796.84, "ymax": 5216451.89, "spatialReference": { "wkid": 102100 } });
		if (!center_x ) {
			center_x = -70.35;
			center_y = 41.68;
		}
		// console.log('x: ' + center_x + ' and y: '+ center_y);
		map = new Map("map", {
			// center: [-70.35, 41.68],
			center: [center_x, center_y],
			// extent: initialExtent,
			// infoWindow: subem_template,
			zoom: 14,
			basemap: "gray",
			slider: true,
			sliderOrientation: "horizontal"
		});
		// map.on("load", createToolbar);
		// map.on("load", initToolbar);
		map.on("load", function(e){
			initToolbar();
			map.infoWindow.resize(375,400)
			if (treatments.length > 0) 
			{
				addTreatmentPolygons(treatments);
			}
		});
	
		//add the legend
      	map.on("layers-add-result", function (evt) {
	        var layerInfo = arrayUtils.map(evt.layers, function (layer, index) {
	          return {layer:layer.layer, title:layer.layer.name};
	        });
	        if (layerInfo.length > 0) {
	          var legendDijit = new Legend({
	            map: map,
	            layerInfos: layerInfo
	          }, "legendDiv");
	          legendDijit.startup();
	        }
	      });

		var fillSymbol = new SimpleFillSymbol();

		function initToolbar() {
			tb = new Draw(map);
			tb.on("draw-end", addGraphic);
			
			// event delegation so a click handler is not
			// needed for each individual button
			on(dom.byId("info"), "click", function(evt) {
				if (evt.target.id === "info") {
					return;
				}

				var tool = evt.target.id.toLowerCase();
				map.disableMapNavigation();
				tb.activate(tool);
			});
			
			editToolbar = new Edit(map);
			// $('.edit_poly').on('click', function(e){
			on(dom.byId('edit_polygon'), 'click', function(e){
				// console.log(e);
				edit_active = 1;
				// polyGLs[0].on('click', function(evt){
					// console.log(this);

				map.graphics.on("click", function(evt) {
					// console.log(edit_active);
					if (edit_active > 0) {
					// console.log(this);
					$('#save_polygon').show();
					event.stop(evt);
					activateToolbar(evt.graphic);
				}
			  });
				 //deactivate the toolbar when you click outside a graphic
			  map.on("click", function(evt){
				editToolbar.deactivate();
				event.stop(e);
				// e.remove()
			  });
			  $('#save_polygon').on('click', function(evt){
			  	editToolbar.deactivate();
			  	$('#save_polygon').hide();
			  	event.stop(e);
			  })
			 editToolbar.on("deactivate", function(evt) {
				if(evt.info.isModified){
				// firePerimeterFL.applyEdits(null, [evt.graphic], null);
				// console.log(evt.graphic);
				update_treatment_poly(evt.graphic);
					edit_active = 0;
				}
			});
		});
		}



		/***********************************
			Need to have an array of custom polygons so we can access them later
			See 208 viewer for example with technology icon & color coding

		************************************/
		function addGraphic(evt) {
			//deactivate the toolbar and clear existing graphics 
			tb.deactivate();
			map.enableMapNavigation();
			// console.log(treatment);
			// figure out which symbol to use
			var symbol;
			symbol = fillSymbol;
			var polystring = '';
			var attr = {'treatment_id': treatment};
			map.graphics.add(new Graphic(evt.geometry, symbol, attr));

			for (var i = 0; i < evt.geometry.rings[0].length; i++) {
				polystring += evt.geometry.rings[0][i][0] + ' ';
				polystring += evt.geometry.rings[0][i][1] + ', ';
			}
			var len = polystring.length;
			polystring = polystring.substring(0, len - 2);
			treatment_polygons[treatment] = polystring;
			
			var url = '/poly';
						
			var data = {treatment: treatment, polystring: polystring};

			$.ajax({
					method: 'POST',
					data: data,
					url: url
				})
				.done(function(msg) {
					// console.log(msg);
					$('#total_nitrogen_polygon').text(msg);
					$('#popdown-opacity').show();
					
				}).fail(function(msg){
					// console.log(msg);
					alert('There was a problem saving the polygon. Please send this error message to sue@bluegear.io: <br />Response: ' + msg.status + ' ' + msg.statusText );
				});

			var area = evt.geometry.getExtent();
		}




		function activateToolbar(graphic) {
		  var tool = 0;
		  
		  if (registry.byId("tool_move").checked) {
			tool = tool | Edit.MOVE; 
		  }
		  if (registry.byId("tool_vertices").checked) {
			tool = tool | Edit.EDIT_VERTICES; 
		  }
		  if (registry.byId("tool_scale").checked) {
			tool = tool | Edit.SCALE; 
		  }
		  if (registry.byId("tool_rotate").checked) {
			tool = tool | Edit.ROTATE; 
		  }
		  // enable text editing if a graphic uses a text symbol
		  if ( graphic.symbol.declaredClass === "esri.symbol.TextSymbol" ) {
			tool = tool | Edit.EDIT_TEXT;
		  }
		  //specify toolbar options        
		  var options = {
			allowAddVertices: true,//registry.byId("vtx_ca").checked,
			allowDeleteVertices: true, //registry.byId("vtx_cd").checked,
			uniformScaling: true //registry.byId("uniform_scaling").checked
		
		  };
		  editToolbar.activate(tool, graphic, options);
		}



		function addTreatmentPolygons(treatments)
		{	
			var polyGLs = [];
			var polyGL = new esri.layers.GraphicsLayer();
			var areaGL = new esri.layers.GraphicsLayer();
			polyGLs.push(polyGL);
			// console.log(polyGLs);
			// areaGLs.push(areaGL);
			var sr = { wkid: 102100, latestWkid: 3857 };
			for (var i = treatments.length - 1; i >= 0; i--) 
			{
				var Treatment = treatments[i];
				var xList = [];
				var yList = [];
				var scenarioID = Treatment.ScenarioID;
				// console.log(Treatment);
				var treatmentArea = Math.round(Treatment.Treatment_Acreage);
				var treatmentClass = Treatment.Treatment_Class;
				var parcels = Treatment.Treatment_Parcels;
				var treatmentType = Treatment.TreatmentType_Name;
				var n_removed = Math.round(Treatment.Nload_Reduction);
				var popupVal = treatmentType + ' (' + Treatment.TreatmentID + ')';
				if (treatmentType) 
				{ //navy
					var polySymbol = new esri.symbol.SimpleFillSymbol(
						SimpleFillSymbol.STYLE_SOLID,
						   new SimpleLineSymbol(
							   SimpleLineSymbol.STYLE_SOLID,
							   new Color([0, 77, 168, 0.9]),
								   4
								   ),
							   new Color([0, 0, 0, 0.0])
							   );
					var imageURL = "http://www.cch2o.org/Matrix/icons/"+Treatment.treatment_icon;
				}
				// treatments[i]
				if (Treatment.Custom_POLY == 1) 
				{
					var nodes = [];
					var rings = [];
					var poly_string = Treatment.POLY_STRING;
						poly_string = poly_string.replace('POLYGON((', '');
						poly_string = poly_string.replace('))', '');
					var geometry = poly_string.split(', ');

					for (var j = 0; j < geometry.length; j++) 
					{
						var space = geometry[j].indexOf(' ');
						var x = geometry[j].substr(0, space);
						var y = geometry[j].substr(space);
						// console.log('geometry: ' + geometry[j]);
						// console.log('x: ' + x + ' y: '+y);
						
						xList.push(x);
						yList.push(y);
						var point = [parseFloat(x), parseFloat(y)];
						nodes.push(point);
					};
					rings.push(nodes);
					var geo = { rings: rings, spatialReference: sr };

					// var popupVal2 = "Scenario " + scenarioID;
					
					var poly = new esri.geometry.Polygon(geo);
					var attr = {'treatment_id': Treatment.TreatmentID};
					var polyGraphic = new esri.Graphic(poly, polySymbol, attr);
					var template = new InfoTemplate({
						title: popupVal,
						content: '<div align="left" class="treatment info technology"><img style="width:60pxfloat:right;margin-right:10px;" src=" '
									+ imageURL + '" /><strong>Treatment Stats</strong>:<br /> ' 
									+ treatmentArea + " Acres<br/>" 
									+ parcels + " parcels treated<br/>" + n_removed + "kg (unatt) N removed.<br />"
									// + "<button class='edit_poly' data-treatment='"+Treatment.TreatmentID+"'>Edit Polygon</button>  "
									// + "<button class='save_poly' data-treatment='"+Treatment.TreatmentID+"'>Save Polygon</button></div>"

					});
					polyGraphic.setInfoTemplate(template);
					map.graphics.add(polyGraphic);
				}
			}
		}





		function update_treatment_poly(treatment_poly)
		{
			// need to do an ajax call to a stored procedure that accepts the new polygon and treatment id and updates the treatment info
			// make sure wiz_treatment_parcels info gets deleted and recreated or updated
			var new_polygon = '';
			// console.log(treatment_poly.attributes.treatment_id);
			// console.log(treatment_poly.geometry);
			for (var i = 0; i < treatment_poly.geometry.rings[0].length; i++) {
				// console.log('x: ' + treatment_poly.geometry.rings[0][i][0]);
				// console.log('y: '+ treatment_poly.geometry.rings[0][i][1]);
				new_polygon += treatment_poly.geometry.rings[0][i][0] + ' ';
				new_polygon += treatment_poly.geometry.rings[0][i][1] + ', ';
			}
			// console.log(new_polygon);
			var treat_id = treatment_poly.attributes.treatment_id;
			var len = new_polygon.length;
			new_polygon = new_polygon.substring(0, len - 2);

			var data = {treatment: treat_id, polystring: new_polygon};
			// treatment_polygons[treatment] = polystring;
			// console.log('exec CapeCodMa.Get_NitrogenFromPolygon \'' + polystring + '\'');
			// console.log(new_polygon);
			// console.log(polystring);
			var url = "/update_polygon";
			// var url = '/polygon/' + func + '/' + treatment + '/' + polystring;
			// console.log(url);
			$.ajax({
					method: 'POST',
					data: data,
					url: url
				})
				.done(function(msg) {
					$("li.technology[data-treatment='"+treat_id+"'] a").trigger('click');
				}).fail(function(msg){
					// console.log(msg);
					alert('There was a problem saving the polygon. Please send this error message to sue@bluegear.io: <br />Response: ' + msg.status + ' ' + msg.statusText );
				});

		}




	
		/*******************************
		 *
		 *	This is the ArcGIS Basemap Gallery which (used to) break everything
		 *
		 *********************************/

		var basemapGallery = new BasemapGallery({
			   showArcGISBasemaps: true,
			   map: map
			 }, "basemapGallery");
			 basemapGallery.startup();

			 basemapGallery.on("error", function(msg) {
			   console.log("basemap gallery error:  ", msg);
			 });


		var extent;


		var embayments = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4', {
			mode: FeatureLayer.MODE_ONDEMAND,
			outFields: ["*"],
			// maxAllowableOffset: map.extent,
			opacity: 1
		});
		embayments.setDefinitionExpression('EMBAY_ID = ' + selectlayer);

		map.addLayer(embayments);
		// var point = (embayments.X_Centroid, embayments.Y_Centroid);
		// map.centerAndZoom(point, 11);
		// map.setExtent(embayments.fullExtent);

		var subwater_template = new InfoTemplate({

			title: "<b>Subwatershed</b>", 
			content: "${SUBWATER_D}"
		});


		var Subwatersheds = new FeatureLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/6", {
			mode: FeatureLayer.MODE_ONDEMAND,
			outFields: ["*"],
			infoTemplate: subwater_template,
			opacity: 1
		});
		Subwatersheds.setDefinitionExpression('EMBAY_ID = ' + selectlayer);

		Subwatersheds.hide();
		// Subwatersheds.setExtent(extent);
		map.addLayer(Subwatersheds);

		var subem_template = new InfoTemplate({

			title: "<b>Subembayment</b>", 
			content: "${SUBEM_DISP}"
		});
			// subem_template.setTitle("<b>${SUBEM_DISP}</b>");
			// subem_template.setContent("${SUBEM_DISP}");  

		var Subembayments = new FeatureLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/11", {
			mode: FeatureLayer.MODE_ONDEMAND,
			outFields: ["*"],
			infoTemplate: subem_template,
			opacity: 1
		});
		Subembayments.setDefinitionExpression('EMBAY_ID = ' + selectlayer);
		// Subembayments.show();
		Subembayments.hide();
		// console.log(Subembayments);
		map.addLayer(Subembayments);

		var nitro_template = new InfoTemplate({

			title: "Info", 
			content: "<table class = 'table'><tbody>" +
						"<tr style = 'height: 2px'>" + "<td style = 'padding: 0px; margin: 0px;'>" + "Water Use (Gal/Day): " + "</td>" + "<td style = 'padding: 0px; margin: 0px;'>" + "${WaterUseExisting:NumberFormat(places:2)}" + "</td>" + "</tr>" +
						"<tr style = 'height: 2px'>" + "<td style = 'padding: 0px; margin: 0px;'>" + "Waste Water Treatment: " + "</td>" + "<td style = 'padding: 0px; margin: 0px;'>" + "${WWTreatmentExisting}" + "</td>" + "</tr>" +
						"<tr style = 'height: 2px'>" + "<td style = 'padding: 0px; margin: 0px;'>" + "Land Use Category: " + "</td>" + "<td style = 'padding: 0px; margin: 0px;'>" + "${LandUseCatExisting}" + "</td>" + "</tr>" +
						"<tr style = 'height: 2px'>" + "<td style = 'padding: 0px; margin: 0px;'>" + "Water Use Source: " + "</td>" + "<td style = 'padding: 0px; margin: 0px;'>" + "${WaterUseSource}" + "</td>" + "</tr>" +
						"<tr style = 'height: 2px'>" + "<td style = 'padding: 0px; margin: 0px;'>" + "Unattn Nitrogen Load (Septic) (Kg/Yr): " + "</td>" + "<td style = 'padding: 0px; margin: 0px;'>" + "${NLoad_Septic_Existing:NumberFormat(places:2)}" + "</td>" + "</tr>" +
						"<tr style = 'height: 2px'>" + "<td style = 'padding: 0px; margin: 0px;'>" + "Unattn Nitrogen Load (Fertilization) (Kg/Yr): " + "</td>" + "<td style = 'padding: 0px; margin: 0px;'>" + "${Nload_Fert:NumberFormat(places:2)}" + "</td>" + "</tr>" +
						"<tr style = 'height: 2px'>" + "<td style = 'padding: 0px; margin: 0px;'>" + "Unattn Nitrogen Load (Stormwater) (Kg/Yr): " + "</td>" + "<td style = 'padding: 0px; margin: 0px;'>" + "${Nload_Stormwater:NumberFormat(places:2)}" + "</td>" + "</tr>" +
						"<tr style = 'height: 2px'>" + "<td style = 'padding: 0px; margin: 0px;'>" + "Unattn Nitrogen Load (Atmosphere) (Kg/Yr): " + "</td>" + "<td style = 'padding: 0px; margin: 0px;'>" + "${Nload_Atmosphere:NumberFormat(places:2)}" + "</td>" + "</tr>" +
						"<tr style = 'height: 2px'>" + "<td style = 'padding: 0px; margin: 0px;'>" + "Unattn Nitrogen Load (Full) (Kg/Yr): " + "</td>" + "<td style = 'padding: 0px; margin: 0px;'>" + "${Nload_Full:NumberFormat(places:2)}" + "</td>" + "</tr>" + 
						"</tbody></table>"
		});

		// http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/0
		var NitrogenLayer = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/13', {
				mode: FeatureLayer.MODE_ONDEMAND,
				outFields: ["*"],
				opacity: 1,
				infoTemplate: nitro_template
			}
		);

		var symbol = new SimpleMarkerSymbol()
			symbol.setStyle(SimpleMarkerSymbol.STYLE_CIRCLE)
			symbol.setOutline(null)
			symbol.setColor(new Color([255,153,0]))
			symbol.setSize("8")

		var renderer = new SimpleRenderer(symbol)
			renderer.setSizeInfo({
	        	field: "Nload_Full",
	        	minSize: 1,
	        	maxSize: 20,
	        	minDataValue: 0,
	        	maxDataValue: 250
	        })

	        var query = new Query()
				query.where = "1=1"

			Subembayments.queryFeatures(query, selectinBuffer)

			NitrogenLayer.setRenderer(renderer)

	        NitrogenLayer.hide()
			map.addLayer(NitrogenLayer);


		var WasteWater = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/13', {
				mode: FeatureLayer.MODE_ONDEMAND,
				outFields: ["*"],
				opacity: 1,
				infoTemplate: nitro_template
			}

		);

		var wasteSymbol = new SimpleMarkerSymbol()
			wasteSymbol.setStyle(SimpleMarkerSymbol.STYLE_CIRCLE)
			wasteSymbol.setOutline(null)
			wasteSymbol.setColor(new Color([124,252,0]))
			wasteSymbol.setSize("8")

		var wasteRenderer = new SimpleRenderer(wasteSymbol)
			wasteRenderer.setSizeInfo({
	        	field: "WWFlowsExisting",
	        	minSize: 5,
	        	maxSize: 20,
	        	minDataValue: 0,
	        	maxDataValue: 10000
	        })

	    WasteWater.setRenderer(wasteRenderer)

		WasteWater.hide();
		map.addLayer(WasteWater);


		var Towns = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/5', {
				mode: FeatureLayer.MODE_ONDEMAND,
				outFields: ["*"],
				opacity: .4,
				// styling: false,
				color: [255, 0, 0, 1],
				width: 3
			}

		);
		Towns.hide();
		map.addLayer(Towns);


		var TreatmentType = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/13', {
				mode: FeatureLayer.MODE_ONDEMAND,
				outFields: ["*"],
				opacity: 1,
				infoTemplate: nitro_template
			}

		);

		var treattypeSymbol = new SimpleMarkerSymbol()
			treattypeSymbol.setStyle(SimpleMarkerSymbol.STYLE_CIRCLE)
			treattypeSymbol.setOutline(null)
			treattypeSymbol.setColor(new Color([124,252,0]))
			treattypeSymbol.setSize("5")

		var treattypeRenderer = new UniqueValueRenderer(treattypeSymbol, "WWTreatmentExisting")
			treattypeRenderer.addValue("GWDP", new SimpleMarkerSymbol().setColor(new Color([124,252,0])).setSize("5").setOutline(null))
			treattypeRenderer.addValue("SEPTIC", new SimpleMarkerSymbol().setColor(new Color([205,133,63])).setSize("5").setOutline(null))
			treattypeRenderer.addValue("SEWERED", new SimpleMarkerSymbol().setColor(new Color([238,130,238])).setSize("5").setOutline(null))

	    TreatmentType.setRenderer(treattypeRenderer)

		TreatmentType.hide();
		map.addLayer(TreatmentType);


		var TreatmentFacilities = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/9', {
				mode: FeatureLayer.MODE_ONDEMAND,
				outFields: ["*"],
				opacity: 1
			}

		);
		TreatmentFacilities.hide();
		map.addLayer(TreatmentFacilities);


		var EcologicalIndicators = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer/10', {
				mode: FeatureLayer.MODE_ONDEMAND,
				outFields: ["*"],
				opacity: 1
			}

		);
		EcologicalIndicators.hide();
		map.addLayer(EcologicalIndicators);

		var ShallowGroundwater = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer/32', {
				mode: FeatureLayer.MODE_ONDEMAND,
				outFields: ["*"],
				opacity: .5
			}

		);
		ShallowGroundwater.hide();
		map.addLayer(ShallowGroundwater);


		var LandUse = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/13', {
				mode: FeatureLayer.MODE_ONDEMAND,
				outFields: ["*"],
				opacity: .5,
				infoTemplate: nitro_template
			}

		);

		var landuseSymbol = new SimpleMarkerSymbol()
			landuseSymbol.setStyle(SimpleMarkerSymbol.STYLE_CIRCLE)
			landuseSymbol.setOutline(null)
			landuseSymbol.setColor(new Color([124,252,0]))
			landuseSymbol.setSize("5")

		var landuseRenderer = new UniqueValueRenderer(landuseSymbol, "LandUseCatExisting")
			landuseRenderer.addValue("RESSINGLEFAM", new SimpleMarkerSymbol().setColor(new Color([122, 182, 245, 255])).setSize("5").setOutline(null))
			landuseRenderer.addValue("COMMERCIAL", new SimpleMarkerSymbol().setColor(new Color([255, 255, 0, 255])).setSize("5").setOutline(null))
			landuseRenderer.addValue("INDUSTRIAL", new SimpleMarkerSymbol().setColor(new Color([115, 223, 255, 255])).setSize("5").setOutline(null))
			landuseRenderer.addValue("OTHERDEV", new SimpleMarkerSymbol().setColor(new Color([107, 181, 123, 255])).setSize("5").setOutline(null))
			landuseRenderer.addValue("OTHERNONDEV", new SimpleMarkerSymbol().setColor(new Color([2197, 0, 255, 255])).setSize("5").setOutline(null))
			landuseRenderer.addValue("RESCONDOAPT", new SimpleMarkerSymbol().setColor(new Color([205, 205, 102, 255])).setSize("5").setOutline(null))
			landuseRenderer.addValue("RESMULTIFAM", new SimpleMarkerSymbol().setColor(new Color([205, 46, 49, 255])).setSize("5").setOutline(null))
			landuseRenderer.addValue("VACANTDEV", new SimpleMarkerSymbol().setColor(new Color([168, 0, 0, 255])).setSize("5").setOutline(null))
			landuseRenderer.addValue("VACANTNONDEV", new SimpleMarkerSymbol().setColor(new Color([76, 115, 0, 255])).setSize("5").setOutline(null))

		LandUse.setRenderer(landuseRenderer)

		LandUse.hide();
		map.addLayer(LandUse);

		var FlowThrough = new FeatureLayer('http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/12', {
			mode: FeatureLayer.MODE_ONDEMAND,
			outFields: ["*"],
			opacity: 1,
			infoTemplate: nitro_template
		});

		// var flowthroughSymbol = new SimpleMarkerSymbol()
		// 	flowthroughSymbol.setStyle(SimpleMarkerSymbol.STYLE_CIRCLE)
		// 	flowthroughSymbol.setOutline(null)
		// 	flowthroughSymbol.setColor(new Color([124,252,0]))
		// 	flowthroughSymbol.setSize("5")

		// var landuseRenderer = new UniqueValueRenderer(landuseSymbol, "LandUseCatExisting ")
		// 	landuseRenderer.addValue("RESSINGLEFAM", new SimpleMarkerSymbol().setColor(new Color([122, 182, 245, 255])).setSize("5"))
		// 	landuseRenderer.addValue("COMMERCIAL", new SimpleMarkerSymbol().setColor(new Color([255, 255, 0, 255])).setSize("5"))
		// 	landuseRenderer.addValue("INDUSTRIAL", new SimpleMarkerSymbol().setColor(new Color([115, 223, 255, 255])).setSize("5"))
		// 	landuseRenderer.addValue("OTHERDEV", new SimpleMarkerSymbol().setColor(new Color([107, 181, 123, 255])).setSize("5"))
		// 	landuseRenderer.addValue("OTHERNONDEV", new SimpleMarkerSymbol().setColor(new Color([2197, 0, 255, 255])).setSize("5"))
		// 	landuseRenderer.addValue("RESCONDOAPT", new SimpleMarkerSymbol().setColor(new Color([205, 205, 102, 255])).setSize("5"))
		// 	landuseRenderer.addValue("RESMULTIFAM", new SimpleMarkerSymbol().setColor(new Color([205, 46, 49, 255])).setSize("5"))
		// 	landuseRenderer.addValue("VACANTDEV", new SimpleMarkerSymbol().setColor(new Color([168, 0, 0, 255])).setSize("5"))
		// 	landuseRenderer.addValue("VACANTNONDEV", new SimpleMarkerSymbol().setColor(new Color([76, 115, 0, 255])).setSize("5"))



		FlowThrough.hide();
		map.addLayer(FlowThrough);


		// console.log('testing');
		// Turn on/off each layer when the user clicks the link in the sidebar.


		var inBuffer = []; 
		var queryString = ""

		function selectinBuffer(response) {

			var feature;  
    		var features = response.features;   

    		for (var i = 0; i < features.length; i++) {
    			
    			feature = features[i]

    			inBuffer.push(feature.attributes["SUBEM_ID"])
    		}

    		for (var j = 0; j < inBuffer.length; j++) {

    			queryString += "SUBEM_ID = " + String(inBuffer[j]) + " OR "
    		}

    		queryString = queryString.substring(0,queryString.lastIndexOf("OR")) + "";

    		console.log(queryString)
		}

		$('#nitrogen').on('click', function(e) {
			e.preventDefault();
			// console.log(NitrogenLayer);
			if ($(this).attr('data-visible') == 'off') {

				NitrogenLayer.setDefinitionExpression(queryString.toString())
				NitrogenLayer.show()
				legendDijit.refresh([{layer: NitrogenLayer, title: "Nitrogen Load"}])
				$(this).attr('data-visible', 'on');
			} else {
				NitrogenLayer.hide();
				$(this).attr('data-visible', 'off');
			}
			// 
		});


		$('#subembayments').on('click', function(e) {
			e.preventDefault();

			if ($(this).attr('data-visible') == 'off') {
				// Subembayments.setDefinitionExpression('EMBAY_ID = ' + selectlayer);
				Subembayments.show();
				// console.log(Subembayments);
				$(this).attr('data-visible', 'on');
			} else {
				Subembayments.hide();
				$(this).attr('data-visible', 'off');
			}
			// 
		});

		$('#subwatersheds').on('click', function(e) {
			e.preventDefault();

			if ($(this).attr('data-visible') == 'off') {
				Subwatersheds.show();
				$(this).attr('data-visible', 'on');
			} else {
				Subwatersheds.hide();
				$(this).attr('data-visible', 'off');
			}
			// 
		});

		$('#wastewater').on('click', function(e) {
			e.preventDefault();

			if ($(this).attr('data-visible') == 'off') {

				WasteWater.setDefinitionExpression(queryString.toString())
				WasteWater.show();
				$(this).attr('data-visible', 'on');
			} else {
				WasteWater.hide();
				$(this).attr('data-visible', 'off');
			}
			// 
		});


		$('#towns').on('click', function(e) {
			e.preventDefault();

			if ($(this).attr('data-visible') == 'off') {
				Towns.show();
				$(this).attr('data-visible', 'on');
			} else {
				Towns.hide();
				$(this).attr('data-visible', 'off');
			}
			// 
		});

		$('#treatmenttype').on('click', function(e) {
			e.preventDefault();

			if ($(this).attr('data-visible') == 'off') {

				TreatmentType.setDefinitionExpression(queryString.toString())
				TreatmentType.show();
				$(this).attr('data-visible', 'on');
			} else {
				TreatmentType.hide();
				$(this).attr('data-visible', 'off');
			}
			// 
		});

		$('#treatmentfacilities').on('click', function(e) {
			e.preventDefault();

			if ($(this).attr('data-visible') == 'off') {
				TreatmentFacilities.show();
				$(this).attr('data-visible', 'on');
			} else {
				TreatmentFacilities.hide();
				$(this).attr('data-visible', 'off');
			}
			// 
		});


		$('#ecologicalindicators').on('click', function(e) {
			e.preventDefault();

			if ($(this).attr('data-visible') == 'off') {
				EcologicalIndicators.show();
				$(this).attr('data-visible', 'on');
			} else {
				EcologicalIndicators.hide();
				$(this).attr('data-visible', 'off');
			}
			// 
		});

		$('#shallowgroundwater').on('click', function(e) {
			e.preventDefault();

			if ($(this).attr('data-visible') == 'off') {
				ShallowGroundwater.show();
				$(this).attr('data-visible', 'on');
			} else {
				ShallowGroundwater.hide();
				$(this).attr('data-visible', 'off');
			}
			// 
		});

		$('#landuse').on('click', function(e) {
			e.preventDefault();

			if ($(this).attr('data-visible') == 'off') {

				LandUse.setDefinitionExpression(queryString.toString())
				LandUse.show();
				$(this).attr('data-visible', 'on');
			} else {
				LandUse.hide();
				$(this).attr('data-visible', 'off');
			}
			// 
		});


		$('#flowthrough').on('click', function(e) {
			e.preventDefault();

			if ($(this).attr('data-visible') == 'off') {


				FlowThrough.show();
				$(this).attr('data-visible', 'on');
			} else {
				FlowThrough.hide();
				$(this).attr('data-visible', 'off');
			}
			// 
		});

		
		$('.subembayment').on('click', function(e){
			// console.log('subembayment clicked');
			var sub = $(this).data('layer');
			Subembayments.setDefinitionExpression('SUBEM_ID = ' + sub);
			Subembayments.show();


		});


var getDestinationPoint = map.on("select-destination", getDestination);

function getDestination(evt){
  return evt;
  getDestinationPoint.remove();
}

// function map_click(e) {
//       editToolbar.deactivate();

//           clickQuery(e);

//       }




// 		// Adding info window
// 		function getIdentifyParams(point) {
// 			  var p = new esri.tasks.IdentifyParameters();
// 			  p.dpi = 96;
// 			  p.geometry = map.toMap(point);
// 			  p		  p.layerIds = [0];
// 			  p.spatialReference = spatialReference;
// 			  p.layerOption = esri.tasks.IdentifyParameters.LAYER_OPTION_VISIBLE;
// 			  p.mapExtent = map.extent;
// 			  p.tolerance = 8;
// 			  p.returnGeometry = false;
// 			  p.width = map.width;
// 			  return p;
// 			}

// 	function clickQuery(e) {
//       var identify = new esri.tasks.IdentifyTask(mapService);
//       var deferred = identify.execute(getIdentifyParams(clickPoint));
//       deferred.addCallback(function (response) {
//         // We're just gonna display the first result
//         var attributes = response[0].feature.attributes;

//         // Setup a template to be used by dojo.string.substitute (found in Default.aspx)
//         var template = $("#featureInfoTemplate").html();
//         var content = dojo.string.substitute(template, attributes, null, {
//           round: function (value, key) {
//             return dojo.number.format(value, { places: 2 });
//           },
//           integer: function (value, key) {
//             return dojo.number.format(value, { places: 0 });
//           }
//         });

//         // Set our info window content manually
//         map.infoWindow.setContent(content);
//         map.infoWindow.setTitle("Property Info");

//         map.infoWindow.show(clickPoint);

//         // Striping to table rows
//         $(".esriPopup .contentPane tr:odd td").css("background-color", "#eee");
//       });
//     }











	});
