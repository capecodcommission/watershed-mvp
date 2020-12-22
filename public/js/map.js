var map;
var watershed;
var embay_shape;
var treatment;
var func;
var editGeoClicked;
var popupsDisabled;
require([
    "esri/map",
    "esri/dijit/BasemapGallery",
    "esri/graphicsUtils",
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
    "esri/symbols/PictureMarkerSymbol",
    "esri/geometry/Point",
    "esri/graphic",
    "esri/tasks/query",
    "esri/tasks/QueryTask",
    "esri/tasks/identify",
    "esri/InfoTemplate",
    "esri/dijit/LayerList",
    "esri/geometry/Extent",
    "dojo/_base/event",
    "dojo/dom",
    "dojo/on",
    "dijit/registry",
    "esri/geometry/geometryEngine",
    "dojo/dom-construct",
    "dojo/domReady!",
    "esri/geometry/geometryEngine"
], function (
    Map,
    BasemapGallery,
    graphicsUtils,
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
    PictureMarkerSymbol,
    Point,
    Graphic,
    Query,
    QueryTask,
    identify,
    InfoTemplate,
    LayerList,
    Extent,
    event,
    dom,
    on,
    registry,
    geometryEngine,
    domConstruct
) {
    parser.parse();

    var initialExtent = new Extent({
        xmin: -7980970.14,
        ymin: 5033003.02,
        xmax: -7705796.84,
        ymax: 5216451.89,
        spatialReference: { wkid: 102100 }
    });
    if (!center_x) {
        center_x = -70.35;
        center_y = 41.68;
    }

    map = new Map("map", {
        center: [center_x, center_y],
        // zoom: 13,
        basemap: "dark-gray",
        slider: true,
        sliderOrientation: "horizontal",
        logo: false,
        showAttribution: false
    });

    const TreatmentLayerClassName="Treatment_layer";

    map.on("load", function (e) {
        initToolbar();
        map.infoWindow.resize(375, 400);

        if (treatments.length > 0) {
            addGraphicsOnLoad(treatments);
        }
    });

    var fillSymbol = new SimpleFillSymbol();

    // Handle map Draw and Edit toolbar functionality
    function initToolbar() {
        // Create and listen for polygon Draw events
        tb = new Draw(map);
        tb.on("draw-end", addGraphicOnSelect);

        on(dom.byId("info"), "click", function (evt) {
            if (evt.target.id === "info") {
                return;
            }
            var tool = evt.target.id.toLowerCase();
            tb.activate(tool);
        });

        // Create and listen for geometry Edit events
        editToolbar = new Edit(map);
        editGeoClicked = 0;

        // Handler to activate edit toolbar for appropriate geometry
        $(document).on("click", ".blade_container #edit_geometry", function (e) {
            e.preventDefault();

            // Hide the modal, activate edit toolbar for edit modal's relevant geometry
            toggleUI();
            $("#editDesc").show();
            map.disableDoubleClickZoom();
            map.setInfoWindowOnClick(false);
            editGeoClicked = 1;

            // Activate toolbar for treatment geometry
            let treatment_id = $(this).data("treatment");
            let graphics = getGraphics();
            let treatmentGraphic = graphics.filter(graphic => {
                let attribs = graphic.attributes;
                if (attribs) {
                    return attribs.treatment_id == treatment_id;
                }
            });
            if (treatmentGraphic) {
                activateToolbar(treatmentGraphic[0]);
            }
        });

        // Save edited geometry on double-click
        map.on("dbl-click", function (evt) {
            if (editGeoClicked) {
                editToolbar.deactivate();
            }
        });

        // On-deactivate, handle new geometry coordinates in separate function
        editToolbar.on("deactivate", function (evt) {
            editGeoClicked = 0;
            map.enableDoubleClickZoom();
            map.setInfoWindowOnClick(true);
            saveGeometry(evt.graphic);
        });
    }

    // Create and color polygon using passed rgba array
    function createPolySymbol(colorArray) {
        var polySymbol = new esri.symbol.SimpleFillSymbol(
            SimpleFillSymbol.STYLE_SOLID,
            new SimpleLineSymbol(
                SimpleLineSymbol.STYLE_SOLID,
                new Color(colorArray),
                4
            ),
            new Color([0, 0, 0, 0.0])
        );

        return polySymbol;
    }

    // Create colored-polygon using the technology_id
    function selectPoly(techId) {
        let polySymbol = {};

        switch (techId) {
            case "101":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "102":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "103":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "104":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "105":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "106":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "107":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "108":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "109":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "110":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "201":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;

            case "202":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;

            case "203":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;

            case "204":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "205":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "206":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "207":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "208":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "209":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "210":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "300":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "301":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "302":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "303":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "400":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "401":
                polySymbol = createPolySymbol([102, 43, 145, 1.0]);
                break;

            case "402":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "403":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "404":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "500":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;

            case "501":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;

            case "502":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;

            case "503":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;

            case "504":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;

            case "505":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;

            case "506":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;

            case "600":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "601":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "602":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "603":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "604":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "605":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "606":
                polySymbol = createPolySymbol([43, 171, 227, 1.0]);
                break;

            case "607":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;

            case "608":
                polySymbol = createPolySymbol([143, 199, 77, 1.0]);
                break;
        }

        return polySymbol;
    }

    // Create and add point graphic with properties to map
    // Save point coordinates to Laravel session
    function addPointOnSelect(evt) {
        // Create point graphic based on clicked-coordinates
        var icon = $("#select_area").data("icon");
        var imageURL = "https://gis-web-assets.capecodcommission.org/icons/SVG/" + icon;
        var pointSymbology = new PictureMarkerSymbol(imageURL, 30, 30);
        var pointGeometry = new Point({
            x: evt.geometry.x,
            y: evt.geometry.y,
            spatialReference: { wkid: 102100, latestWkid: 3857 }
        });
        var pointGraphic = new Graphic(pointGeometry, pointSymbology, {
            keeper: true,
            treatment_id: 1,
            editInProgress: 0,
            parent_id: 0
        });

        // Add point graphic to the map, deactivate draw toolbar, enable map navigation
        let graphicLayer = new esri.layers.GraphicsLayer();
        graphicLayer.add(pointGraphic);
        map.addLayer(graphicLayer);

        tb.deactivate();
        map.enableMapNavigation();

        var techId = $('#select_area').data('techId');

        // Save clicked coordinates to Laravel session
        var url = "/map/point" + "/" + evt.geometry.x + "/" + evt.geometry.y + "/" + techId;
        $.ajax({
            method: "GET",
            url: url
        }).done(function (allClear) {
            if (allClear != 0) {
                toggleUI(true);
                $('#select_area').text('Reselect')
                $("#unit_metric_label").show();
                $("#unit_metric").show();
                $("#subembayment-rate-label").show();
                $("#subembayment-rate").show();
                $("#subembayment-rate-selected").show();
                $('#selected-subembayment').show();
                $('#selected-subembayment').text('Selected Subembayment: ' + allClear[0].SUBEM_DISP + ' | ID:' + allClear[0].SUBEM_ID);
                $('#applyTreatmentInEmbayment').show();
                $('#updateTreatmentInEmbayment').show();
                $("#deletetreatment").hide();
            } else {
                alert(
                    "Error: Geometry falls outside of Scenario Embayment or lies within previous Septic treatment. Please redraw geometry or contact info@capecodcommission.org for technical assistance. Thank you."
                );
                map.removeLayer(graphicLayer)
                tb.activate("point");
            }
        });

        // Finish addGraphic function
        return;
    }

    // Convert polygon coordinate array from ArcGIS API to SQL Spatial string
    function parsePolyOnSelect(rings) {
        // Remove comma separator from individual node pairs then join together comma-separated as string
        let nodeString = rings
            .map(coords => {
                return coords.join(" ");
            })
            .join();

        return nodeString;
    }

    // Create and add polygon geometry to map on-selection
    function addPolygonOnSelect(evt) {
        // Obtain technology id from select polygon div
        let techId = $("#draw_collection").data("techId");

        // Create and color polygon using technology id
        let polySymbol = selectPoly(techId);

        let sr = { wkid: 102100, latestWkid: 3857 };
        let geo = { rings: evt.geometry.rings, spatialReference: sr };
        let polyGeometry = new esri.geometry.Polygon(geo);

        // Initialize treatment id attribute for polygon
        let polyGraphic = new Graphic(polyGeometry, polySymbol, {
            keeper: true,
            treatment_id: 1,
            editInProgress: 0,
            techId: techId
        });

        // Add polygon and symbology to map with attached treatment id 
        let graphicLayer = new esri.layers.GraphicsLayer();
        graphicLayer.add(polyGraphic);
        map.addLayer(graphicLayer);

        // Deactivate the toolbar and enable map navigation
        tb.deactivate();
        map.enableMapNavigation();

        // Retrieve coordinate arrays
        let nodeString = parsePolyOnSelect(evt.geometry.rings[0]);
        // Save coordinate string to session
        var url = "/map/poly";
        var data = { coordString: nodeString, tech_id: techId };
        $.ajax({
            method: "POST",
            data: data,
            url: url
        })
        .done(function(allClear) {
            if (allClear == 1) {
                $("#collect-label-reduc").show();
                $("#collect-label-rate").show();
                $("#collect-rate").show();
                $('#select_area').show();
                $('#unit_metric').show();
                $('#unit_metric_label').show();
                $('#draw_collection').text('Redraw');
                $("#applytreatment").show();
                toggleUI(true);
            }
            else {
                alert('Error: Geometry falls outside of Scenario Embayment or lies within previous Septic treatment. Please redraw geometry or contact info@capecodcommission.org for technical assistance. Thank you.');
                map.removeLayer(graphicLayer)
                tb.activate('polygon');
            }
        })
    }

    // Handle draw-end event by creating a point or polygon graphic on the map
    function addGraphicOnSelect(evt) {
        map.setInfoWindowOnClick(true);
        if (evt.geometry.type === "point") {
            addPointOnSelect(evt);
        } else if (evt.geometry.type === "polygon") {
            addPolygonOnSelect(evt);
        }
    }

    // Activate Edit polygon toolbar from ArcGIS API
    // Conditionally set tool based on graphic type
    function activateToolbar(graphic) {
        let tool;
        switch (graphic.geometry.type) {
            case "polygon":
                tool = Edit.EDIT_VERTICES;
                break;

            case "point":
                tool = Edit.MOVE;
                break;
        }
        //specify toolbar options
        let options = {
            allowAddVertices: true,
            allowDeleteVertices: true
        };
        editToolbar.activate(tool, graphic, options);
    }

    // Add point graphic with treatment properties to map
    function addPointOnLoad(
        treatmentid,
        treatmentArea,
        imageURL,
        parcels,
        n_removed,
        popupVal,
        sr,
        geo_string,
        parentid
    ) {
        // Remove SQL Spatial type and spatial reference from the geometry string
        // Create point geometry and symbology
        if (geo_string.includes("POINT(")) {
            geo_string = geo_string
                .replace("POINT(", "")
                .replace(", 3857)", "")
                .split(", ");
        }
        
        // Handle move-site dump points
        if (geo_string.includes("POINT (")) {
            geo_string = geo_string
                .replace("POINT (", "")
                .replace(")", "")
                .split(" ");
        }
        var pointGeom = new Point({
            x: parseFloat(geo_string[0]),
            y: parseFloat(geo_string[1]),
            spatialReference: sr
        });
        var pointSymbol = new PictureMarkerSymbol(imageURL, 30, 30);

        // Create graphic using geometry and symbology, adding properties
        var pointGraphic = new Graphic(pointGeom, pointSymbol, {
            keeper: true,
            treatment_id: treatmentid,
            editInProgress: 0,
            parent_id: parentid,
            image_URL: imageURL
        });

        // Create popup template containing treatment properties
        var template = new InfoTemplate({
            title: popupVal,
            content:
                '<div align="left" class="treatment info technology"><img style="width:60pxfloat:right;margin-right:10px;" src=" ' +
                imageURL +
                '" /><strong>Treatment Stats</strong>:<br /> ' +
                treatmentArea +
                " Acres<br/>" +
                parcels +
                " parcels treated<br/>" +
                n_removed +
                "kg (unatt) N removed.<br />"
        });

        // Assign popup template to point graphic
        // Add graphic to map
        let graphicLayer = new esri.layers.GraphicsLayer();
        graphicLayer.className=TreatmentLayerClassName;
        graphicLayer.add(pointGraphic);
        graphicLayer.setInfoTemplate(template);
        map.addLayer(graphicLayer);
    }

    // Add polygon graphic with treatment properties to map
    function addPolygonOnLoad(
        treatmentid,
        treatmentArea,
        imageURL,
        parcels,
        n_removed,
        popupVal,
        sr,
        geo_string,
        polySymbol
    ) {
        // Translate SQL Spatial geometry string to an array of polygon nodes consumable for polygon creation
        let geoArray = geo_string
            .replace("POLYGON((", "")
            .replace("POLYGON ((", "")
            .replace("))", "")
            .split(",");

        let nodes = [];
        geoArray.map(coords => {
            let splitCoords = coords.split(" ");
            if (splitCoords.length > 2) {
                splitCoords.splice(0,1)
            }
            let node = [parseFloat(splitCoords[0]), parseFloat(splitCoords[1])];
            nodes.push(node);
        });
        nodes = [nodes];

        // Create polygon geometry and symbology using treatment properties
        let geo = { rings: nodes, spatialReference: sr };
        let poly = new esri.geometry.Polygon(geo);
        let attr = { treatment_id: treatmentid, editInProgress: 0 };

        let polyGraphic = new esri.Graphic(poly, polySymbol, attr);
        let template = new InfoTemplate({
            title: popupVal,
            content:
                '<div align="left" class="treatment info technology"><img style="width:60pxfloat:right;margin-right:10px;" src=" ' +
                imageURL +
                '" /><strong>Treatment Stats</strong>:<br /> ' +
                treatmentArea +
                " Acres<br/>" +
                parcels +
                " parcels treated<br/>" +
                n_removed +
                "kg (unatt) N removed.<br />"
        });

        // Assign popup template to polygon graphic
        // Add graphic to map
        let graphicLayer = new esri.layers.GraphicsLayer();
        graphicLayer.className=TreatmentLayerClassName;
        graphicLayer.add(polyGraphic);
        graphicLayer.setInfoTemplate(template);
        map.addLayer(graphicLayer);

    }

    // Add point and polygon graphics based on treatment geometry on-load of map
    function addGraphicsOnLoad(treatments) {
        treatments.map(row => {
            // Retrieve appropriate treatment properties to pass into point or polygon graphics loading
            const treatmentType = row.TreatmentType_Name;
            const customPoly = row.Custom_POLY;
            const treatmentid = row.TreatmentID;
            const imageURL =
                "https://gis-web-assets.capecodcommission.org/icons/SVG/" + row.treatment_icon;
            const treatmentArea = Math.round(row.Treatment_Acreage);
            const parcels = row.Treatment_Parcels;
            const n_removed = Math.round(row.Nload_Reduction);
            const popupVal = treatmentType + " (" + row.TreatmentID + ")";
            const sr = { wkid: 102100, latestWkid: 3857 };
            const treatmentTypeId = row.TreatmentType_ID;
            const polySymbol = selectPoly(treatmentTypeId);
            const geo_string = row.POLY_STRING;
            const parentid = row.Parent_TreatmentId;
            const treatClass = row.Treatment_Class;

            // Load point or polygon creation by geometry type
            if (geo_string.startsWith("POINT")) {
                addPointOnLoad(
                    treatmentid,
                    treatmentArea,
                    imageURL,
                    parcels,
                    n_removed,
                    popupVal,
                    sr,
                    geo_string,
                    parentid
                );
            } else if (geo_string.startsWith("POLYGON") && treatClass != 'Management') {
                addPolygonOnLoad(
                    treatmentid,
                    treatmentArea,
                    imageURL,
                    parcels,
                    n_removed,
                    popupVal,
                    sr,
                    geo_string,
                    polySymbol
                );
            }
        });
    }

    // Save updated geometry coordinates to Laravel session
    function saveGeometry(evt) {
        evt.attributes.editInProgress = 1;

        // Retrieve geometry properties
        let treatment_id = evt.attributes.treatment_id;
        let type = evt.geometry.type;
        let geometry;

        // Parse geometry based on type
        switch (type) {
            case "polygon":
                geometry = parsePolyOnSelect(evt.geometry.rings[0]);
                break;

            case "point":
                geometry = [evt.geometry.x, evt.geometry.y];
                break;
        }

        // Package geometry as payload
        var data = { treatment: treatment_id, geoObj: geometry, geoType: type };
        var url = "/update_geometry";
        $.ajax({
            method: "POST",
            data: data,
            url: url
        }).done(function (allClear) {
            if (allClear == 1) {
                editToolbar.deactivate();
                editGeoClicked = 0;
                $(".popdown-opacity").show();
                toggleUI(true);
                $("#deletetreatment").hide();
                $("#updateStormwaterNonManangement").show();
                $('#updateCollectMove').show();
                $('#updateCollectStay').show();
            } else {
                // Alert user if save unsuccessful
                alert(
                    "Error: Geometry falls outside of Scenario Embayment or lies within previous Septic treatment. Please redraw geometry or contact info@capecodcommission.org for technical assistance. Thank you."
                );
                map.disableDoubleClickZoom();
                map.setInfoWindowOnClick(false);
                editGeoClicked = 1;
                activateToolbar(evt);
            }
        });
    }

    // Global objects to house currently hightlighted graphic and icon URL from treatment stack
    let treatmentGraphics = null;
    let pointURL = "";

    // Retrieves all graphics layers in the map by layer className (optional)
    // @param className Optional classname to identify layer.
    function getGraphicsLayers(className) {
        const layerIds = map.graphicsLayerIds;
        let graphicLayers = [];
        layerIds.forEach(id => graphicLayers.push(map.getLayer(id)))
        if (className) {
            graphicLayers = graphicLayers.filter(layer => layer.className === className);
        }
        return graphicLayers;
    }

    // Retrieves all graphics in the map
    function getGraphics() {
        return getGraphicsLayers().map(layer => layer.graphics[0] || null).filter(g => !!g)
    }

    // Highlight symbololgy based on geometry type
    function highlightSymbolOnEnter(treatment_id) {
        // Retrieve graphic by treatment id, matching to graphic treatment id or graphic parent id
        let layerGraphics = getGraphics();
        treatmentGraphics = layerGraphics.filter(graphic => {
            let attribs = graphic.attributes;
            if (attribs) {
                return attribs.treatment_id == treatment_id || attribs.parent_id == treatment_id;
            }
        });

        // If parent & (optional) child treatments exist, highlight them
        if (treatmentGraphics.length) {
            treatmentGraphics.map ((graphic => {
                let geoType = graphic.geometry.type;
                let highlightColor = [252, 236, 3, 1.0];
                let highlightSymbol = {};
                switch (geoType) {
                    case "polygon":
                        highlightSymbol = createPolySymbol(highlightColor);
                        graphic.setSymbol(highlightSymbol);
                        break;
    
                    case "point":
                        pointURL = graphic.symbol.url;
                        highlightSymbol = new SimpleMarkerSymbol(
                            SimpleMarkerSymbol.STYLE_CIRCLE,
                            30,
                            null,
                            new Color(highlightColor)
                        );
                        graphic.setSymbol(highlightSymbol);
                        break;
                }
            }))
        }
    }

    // Reset symbology for parent & (optional) child treatments based on geometry type
    function resetSymbolOnLeave(techId) {
        // If graphic exists, reset original symbology
        if (treatmentGraphics.length) {
                treatmentGraphics.map ((graphic => {
                let geoType = graphic.geometry.type;
                let originalGraphic = {};
                switch (geoType) {
                    case "polygon":
                        originalGraphic = selectPoly(techId);
                        graphic.setSymbol(originalGraphic);
                        break;

                    case "point":
                        originalGraphic = new PictureMarkerSymbol(graphic.attributes.image_URL, 30, 30);
                        graphic.setSymbol(originalGraphic);
                        break;
                }
                treatmentGraphics = null;
                pointURL = null;
            }))
        }
    }

    // Handle highlighting and resetting symbology of hovered treatment in stack
    $(document)
        .on("mouseover", "#stackList li.technology", function (e) {
            e.preventDefault();
            let treatment_id = $(this).data("treatment");
            highlightSymbolOnEnter(treatment_id);
        })
        .on("mouseout", "#stackList li.technology", function (e) {
            e.preventDefault();
            let techId = $(this).data("techid");
            if (techId) {
                resetSymbolOnLeave(techId.toString());
            }
        });

    // Handler to reset edited geometry to its original position on-close of a new edit modal
    $(document).on("click", ".blade_container .modal-close", function (e) {
        e.preventDefault();
        let layerGraphics = getGraphics();

        $( "#update" ).trigger( "click" );
    });

    // Handles popup setting and updating for all graphics post-apply or delete
    $("#update").on("click", function (e) {
        e.preventDefault();

        // Obtain latest treatment info from API
        var url = "/get_treatments";
        $.ajax({
            method: "GET",
            url: url
        })
            .done(function (treatments) {
                if (treatments) {
                    // Obtain and remove treatment graphics from map
                    let graphicsLayers = getGraphicsLayers(TreatmentLayerClassName);
                    graphicsLayers.map(layer => map.removeLayer(layer))

                    // Recreate graphics and popups with new treatment data
                    addGraphicsOnLoad(treatments);
                }
            })
            .fail(function (msg) {
                alert(
                    "Error: Geometry failed to reset, please contact info@capecodcommission for technical support. Thank you." +
                    msg.statusText
                );
            });
    });

// ArcGIS Basemap Gallery

    var basemapGallery = new BasemapGallery(
        {
            showArcGISBasemaps: true,
            map: map
        },
        "basemapGallery"
    );
    basemapGallery.startup();

    basemapGallery.on("error", function (msg) {
        console.log("basemap gallery error:  ", msg);
    });

    var extent;

    var embayments = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP4/MapServer/1",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1
        }
    );
    embayments.setDefinitionExpression("EMBAY_ID = " + selectlayer);

    map.addLayer(embayments);

    embayments.on('load', () => {
        var query = new Query();
        query.where = "EMBAY_ID = " + selectlayer;
        embayments.queryExtent(query, result => {
            map.setExtent(result.extent);
        });
    })

    var subwater_template = new InfoTemplate({
        title: "<b>Subwatershed</b>",
        content: "${SUBWATER_D}"
    });

    var Subwatersheds = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP4/MapServer/3",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            infoTemplate: subwater_template,
            opacity: 1
        }
    );
    Subwatersheds.setDefinitionExpression("EMBAY_ID = " + selectlayer);

    Subwatersheds.hide();
    map.addLayer(Subwatersheds);

    var subem_template = new InfoTemplate({
        title: "<b>Subembayment</b>",
        content: "${SUBEM_DISP}"
    });

    var Subembayments = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP4/MapServer/6",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            infoTemplate: subem_template,
            opacity: 1
        }
    );
    Subembayments.setDefinitionExpression("EMBAY_ID = " + selectlayer);
    Subembayments.hide();
    map.addLayer(Subembayments);

    var nitro_template = new InfoTemplate({
        title: "Info",
        content:
            "<table class = 'table'><tbody>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Water Use (Gal/Day): " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${WaterUseExisting:NumberFormat(places:2)}" +
            "</td>" +
            "</tr>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Waste Water Treatment: " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${WWTreatmentExisting}" +
            "</td>" +
            "</tr>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Land Use Category: " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${LandUseCatExisting}" +
            "</td>" +
            "</tr>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Water Use Source: " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${WaterUseSource}" +
            "</td>" +
            "</tr>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Unattn Nitrogen Load (Septic) (Kg/Yr): " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${Nload_Septic_Existing:NumberFormat(places:2)}" +
            "</td>" +
            "</tr>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Unattn Nitrogen Load (Fertilization) (Kg/Yr): " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${Nload_Fertilizer:NumberFormat(places:2)}" +
            "</td>" +
            "</tr>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Unattn Nitrogen Load (Stormwater) (Kg/Yr): " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${Nload_Stormwater:NumberFormat(places:2)}" +
            "</td>" +
            "</tr>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Unattn Nitrogen Load (Atmosphere) (Kg/Yr): " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${Nload_Atmosphere:NumberFormat(places:2)}" +
            "</td>" +
            "</tr>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Unattn Nitrogen Load (Full) (Kg/Yr): " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${Nload_Full:NumberFormat(places:2)}" +
            "</td>" +
            "</tr>" +
            "</tbody></table>"
    });

    var flowThroughTemplate = new InfoTemplate({
        title: "Info",
        content:
            "<table class = 'table'><tbody>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Subwatershed Name: " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${SUBWATER_DISP}" +
            "</td>" +
            "</tr>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Subwatershed Flowthough Total: " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${SUBWATER_TOTAL:NumberFormat(places:2)}" +
            "</td>" +
            "</tr>" +
            "</tbody></table>"
    });

    // Layer 13 is now used for all point layers
    var NitrogenLayer = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP4/MapServer/0",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1,
            infoTemplate: nitro_template
        }
    );

    var symbol = new SimpleMarkerSymbol();
    symbol.setStyle(SimpleMarkerSymbol.STYLE_CIRCLE);
    symbol.setOutline(null);
    symbol.setColor(new Color([255, 153, 0]));

    var renderer = new SimpleRenderer(symbol);
    renderer.setSizeInfo({
        field: "Nload_Full",
        minSize: 2,
        maxSize: 20,
        minDataValue: 1,
        maxDataValue: 25,
        legendOptions: {
            customValues: [5, 10, 20, 50, 100]
        }
    });

    var query = new Query();
    query.where = "1=1";

    Subembayments.queryFeatures(query, selectinBuffer);

    NitrogenLayer.setRenderer(renderer);

    NitrogenLayer.hide();
    map.addLayer(NitrogenLayer);

    // Layer 13 is now used for all point layers
    var WasteWater = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP4/MapServer/0",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1,
            infoTemplate: nitro_template
        }
    );

    var wasteSymbol = new SimpleMarkerSymbol();
    wasteSymbol.setStyle(SimpleMarkerSymbol.STYLE_CIRCLE);
    wasteSymbol.setOutline(null);
    wasteSymbol.setColor(new Color([124, 252, 0]));
    wasteSymbol.setSize("8");

    var wasteRenderer = new SimpleRenderer(wasteSymbol);
    wasteRenderer.setSizeInfo({
        field: "WWFlowsExisting",
        minSize: 3,
        maxSize: 20,
        minDataValue: 50,
        maxDataValue: 1000,
        legendOptions: {
            customValues: [200, 400, 600, 800, 1000]
        }
    });

    WasteWater.setRenderer(wasteRenderer);

    WasteWater.hide();
    map.addLayer(WasteWater);

    var Towns = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP4/MapServer/2",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 0.4,
            // styling: false,
            color: [255, 0, 0, 1],
            width: 3
        }
    );
    Towns.hide();
    map.addLayer(Towns);

    // Layer 13 is now used for all point layers
    var TreatmentType = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP4/MapServer/0",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1,
            infoTemplate: nitro_template
        }
    );

    var treattypeSymbol = new SimpleMarkerSymbol();
    treattypeSymbol.setStyle(SimpleMarkerSymbol.STYLE_CIRCLE);
    treattypeSymbol.setOutline(null);
    treattypeSymbol.setColor(new Color([124, 252, 0]));
    treattypeSymbol.setSize("5");

    var treattypeRenderer = new UniqueValueRenderer(
        treattypeSymbol,
        "WWTreatmentExisting"
    );
    treattypeRenderer.addValue(
        "GWDP",
        new SimpleMarkerSymbol()
            .setColor(new Color([124, 252, 0]))
            .setSize("5")
            .setOutline(null)
    );
    treattypeRenderer.addValue(
        "SEPTIC",
        new SimpleMarkerSymbol()
            .setColor(new Color([205, 133, 63]))
            .setSize("5")
            .setOutline(null)
    );
    treattypeRenderer.addValue(
        "SEWERED",
        new SimpleMarkerSymbol()
            .setColor(new Color([238, 130, 238]))
            .setSize("5")
            .setOutline(null)
    );
    treattypeRenderer.addValue(
        "IA",
        new SimpleMarkerSymbol()
            .setColor(new Color([24, 255, 220]))
            .setSize("5")
            .setOutline(null)
    );

    TreatmentType.setRenderer(treattypeRenderer);

    TreatmentType.hide();
    map.addLayer(TreatmentType);

    var TreatmentFacilities = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP4/MapServer/5",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1
        }
    );
    TreatmentFacilities.hide();
    map.addLayer(TreatmentFacilities);

    var EcologicalIndicators = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer/10",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1
        }
    );
    EcologicalIndicators.hide();
    map.addLayer(EcologicalIndicators);

    var ShallowGroundwater = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer/32",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 0.5
        }
    );
    ShallowGroundwater.hide();
    map.addLayer(ShallowGroundwater);

    // Layer 13 is now used for all point layers
    var LandUse = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP4/MapServer/0",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 0.5,
            infoTemplate: nitro_template
        }
    );

    var landuseSymbol = new SimpleMarkerSymbol();
    landuseSymbol.setStyle(SimpleMarkerSymbol.STYLE_CIRCLE);
    landuseSymbol.setOutline(null);
    landuseSymbol.setColor(new Color([124, 252, 0]));
    landuseSymbol.setSize("5");

    var landuseRenderer = new UniqueValueRenderer(
        null,
        "LandUseCatExisting"
    );

    landuseRenderer.addValue({
        value: "RESSINGLEFAM",
        symbol: new SimpleMarkerSymbol()
            .setColor(new Color([122, 182, 245, 255]))
            .setSize("5")
            .setOutline(null),
        label: "Residential Single Family"
    });
    landuseRenderer.addValue({
        value: "COMMERCIAL",
        symbol: new SimpleMarkerSymbol()
            .setColor(new Color([255, 255, 0, 255]))
            .setSize("5")
            .setOutline(null),
        label: "Commercial"
    });
    landuseRenderer.addValue({
        value: "INDUSTRIAL",
        symbol: new SimpleMarkerSymbol()
            .setColor(new Color([115, 223, 255, 255]))
            .setSize("5")
            .setOutline(null),
        label: "Industrial"
    });
    landuseRenderer.addValue({
        value: "OTHERDEV",
        symbol: new SimpleMarkerSymbol()
            .setColor(new Color([107, 181, 123, 255]))
            .setSize("5")
            .setOutline(null),
        label: "Other Developable"
    });
    landuseRenderer.addValue({
        value: "OTHERNONDEV",
        symbol: new SimpleMarkerSymbol()
            .setColor(new Color([2197, 0, 255, 255]))
            .setSize("5")
            .setOutline(null),
        label: "Other Non-Developable"
    });
    landuseRenderer.addValue({
        value: "RESCONDOAPT",
        symbol: new SimpleMarkerSymbol()
            .setColor(new Color([205, 205, 102, 255]))
            .setSize("5")
            .setOutline(null),
        label: "Residential Condo/Apartments"
    });
    landuseRenderer.addValue({
        value: "RESMULTIFAM",
        symbol: new SimpleMarkerSymbol()
            .setColor(new Color([205, 46, 49, 255]))
            .setSize("5")
            .setOutline(null),
        label: "Residential Multi Family"
    });
    landuseRenderer.addValue({
        value: "VACANTDEV",
        symbol: new SimpleMarkerSymbol()
            .setColor(new Color([168, 0, 0, 255]))
            .setSize("5")
            .setOutline(null),
        label: "Vacant Developable Land"
    });
    landuseRenderer.addValue({
        value: "VACANTNONDEV",
        symbol: new SimpleMarkerSymbol()
            .setColor(new Color([76, 115, 0, 255]))
            .setSize("5")
            .setOutline(null),
        label: "Vacant Non-Developable Land"
    });

    LandUse.setRenderer(landuseRenderer);

    LandUse.hide();
    map.addLayer(LandUse);

    var FlowThrough = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP4/MapServer/7",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1,
            infoTemplate: flowThroughTemplate
        }
    );

    FlowThrough.hide();
    map.addLayer(FlowThrough);

    var Contours = new FeatureLayer(
        "https://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP4/MapServer/8",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1,
            infoTemplate: nitro_template
        }
    );

    Contours.hide();
    map.addLayer(Contours);

    // Turn on/off each layer when the user clicks the link in the sidebar.

    var inBuffer = [];
    var queryString = "";

    function selectinBuffer(response) {
        var feature;
        var features = response.features;

        for (var i = 0; i < features.length; i++) {
            feature = features[i];

            inBuffer.push(feature.attributes["SUBEM_ID"]);
        }

        for (var j = 0; j < inBuffer.length; j++) {
            queryString += "Subem_ID = " + "'" + String(inBuffer[j]) + "'" + " OR ";
        }

        queryString =
            queryString.substring(0, queryString.lastIndexOf("OR")) + "";
    }

    var legendDijit = new Legend(
        {
            map: map,
            layerInfos: [
                { layer: NitrogenLayer, title: "Nitrogen Load" },
                { layer: WasteWater, title: "Wastewater" },
                { layer: TreatmentType, title: "Treatment Type" },
                { layer: EcologicalIndicators, title: "Ecological Indicators" },
                { layer: LandUse, title: "Land Use Category" },
                { layer: FlowThrough, title: "FlowThrough Coefficients" },
                { layer: Contours, title: "2ft Contours" }
            ]
        },
        "legendDiv"
    );
    legendDijit.startup();


    // Show/hide legend dropdown from activated Map Layers
    function toggleLegend() {

        // Toggle legend dropdown display by checking 'visible' hyperlink prop in each list element of the map layer panel
        $('#layers li.inLegend').each(function() {
            let childA = $(this).find('a');
            if ( childA.attr("data-visible") == "on" ) {
                $('#legendWrapper').css("display", "block");
                return false;
            } else {
                $('#legendWrapper').css("display", "none");
            }
        })
    }


    // Toggle layer visilbility properties on map and layer panel
    function toggleLayer(tag, layer, title=null, defExp=null) {

        let vizSwitch = tag.attr('data-visible');

        if ( vizSwitch == 'off' ) {

            // Repopulate legend contents and filter map layer
            if (title) {
                legendDijit.refresh([
                    { layer: layer, title: title }
                ]);
            }
            if (defExp) {
                layer.setDefinitionExpression(queryString.toString());
            }

            // Show layer and toggle visibility prop
            layer.show();
            tag.attr("data-visible", "on");
        } else {
            layer.hide()
            tag.attr("data-visible", "off");
        }

        // Disable popups if layer toggled during geometry creation
        if (editGeoClicked || popupsDisabled) {
            map.setInfoWindowOnClick(false);
        } else {
            map.setInfoWindowOnClick(true);
        }
    }

    // Handler for all toggleable Map layers
    $(document).on('click', '#layers li', function(e) {
        e.preventDefault();

        // Parse child <a> tag properties from each list element
        let childA = $(this).find("a");
        let layerListName = $(childA).attr('id');

        // Pass tag and props to toggleLayer() based on layer name
        switch (layerListName) {
            case 'nitrogen':
                toggleLayer(childA, NitrogenLayer, "Nitrogen Load", true);
                break;
            case 'subembayments':
                toggleLayer(childA, Subembayments);
                break;
            case 'subwatersheds':
                toggleLayer(childA, Subwatersheds);
                break;
            case 'wastewater':
                toggleLayer(childA, WasteWater, "Wastewater Load", true);
                break;
            case 'towns':
                toggleLayer(childA, Towns);
                break;
            case 'treatmenttype':
                toggleLayer(childA, TreatmentType, "Wastewater Treatment Type", true);
                break;
            case 'treatmentfacilities':
                toggleLayer(childA, TreatmentFacilities);
                break;
            case 'ecologicalindicators':
                toggleLayer(childA, EcologicalIndicators, "Ecological Indicators");
                break;
            case 'shallowgroundwater':
                toggleLayer(childA, ShallowGroundwater);
                break;
            case 'landuse':
                toggleLayer(childA, LandUse, "Land Use Category", true);
                break;
            case 'flowthrough':
                toggleLayer(childA, FlowThrough, "Flowthough Coefficient");
                break;
            case 'contours':
                toggleLayer(childA, Contours, "2ft Contours");
                break;
        }

        toggleLegend();
    })

    $(".subembayment").on("click", function (e) {
        var sub = $(this).data("layer");

        Subembayments.setDefinitionExpression("SUBEM_ID = " + sub);
        Subembayments.show();
    });

    $(document).on("click", "#disable-popups", function (e) {
        if ($(this).hasClass("enabled")) {
            popupsDisabled = true;
            map.setInfoWindowOnClick(false);
            $(this).toggleClass("enabled fa-eye-slash");
        } else {
            popupsDisabled = false;
            map.setInfoWindowOnClick(true);
            $(this).toggleClass("enabled fa-eye-slash");
        }
    });
});
