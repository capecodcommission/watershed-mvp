var map;
var watershed;
var embay_shape;
var treatment;
var func;
var editGeoClicked;
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
        zoom: 14,
        basemap: "dark-gray",
        slider: true,
        sliderOrientation: "horizontal",
        logo: false,
        showAttribution: false
    });

    // map.on("load", createToolbar);
    // map.on("load", initToolbar);
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
            $(".modal-wrapper").toggle();
            $("#editDesc").show();
            map.disableDoubleClickZoom();
            map.setInfoWindowOnClick(false);
            editGeoClicked = 1;

            // Activate toolbar for treatment geometry
            let treatment_id = $(this).data("treatment");
            let layers = map.graphics.graphics;
            let treatmentGraphic = layers.filter(graphic => {
                let attribs = graphic.attributes;
                if (attribs) {
                    return attribs.treatment_id == treatment_id;
                }
            });
            if (treatmentGraphic) {
                activateToolbar(treatmentGraphic[0]);
            }
        });

        // Legacy edit blade geometry update handler
        $(".modal-content").on("click",".popdown-content #edit_geometry", function (e) {
                // Hide the modal, activate edit toolbar for edit modal's relevant geometry
                $(".modal-wrapper").toggle();
                $(".popdown-opacity").hide();
                $("#editDesc").show();
                map.disableDoubleClickZoom();
                editGeoClicked = 1;
                map.setInfoWindowOnClick(false);

                // Activate toolbar for treatment geometry
                let treatment_id = $(this).data("treatment");
                let layers = map.graphics.graphics;
                let treatmentGraphic = layers.filter(graphic => {
                    let attribs = graphic.attributes;
                    if (attribs) {
                        return attribs.treatment_id == treatment_id;
                    }
                });
                if (treatmentGraphic) {
                    activateToolbar(treatmentGraphic[0]);
                }
            }
        );

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

    // Create and add point graphic to map
    // Save point coordinates to Laravel session
    function addPointOnSelect(evt) {
        // Create point graphic based on clicked-coordinates
        var icon = $("#select_area").data("icon");
        var imageURL = "http://www.cch2o.org/Matrix/icons/" + icon;
        var pointSymbology = new PictureMarkerSymbol(imageURL, 30, 30);
        var pointGeometry = new Point({
            x: evt.geometry.x,
            y: evt.geometry.y,
            spatialReference: { wkid: 102100, latestWkid: 3857 }
        });
        var pointGraphic = new Graphic(pointGeometry, pointSymbology, {
            keeper: true,
            treatment_id: 1,
            editInProgress: 0
        });

        // Add point graphic to the map, deactivate draw toolbar, enable map navigation
        map.graphics.add(pointGraphic);
        tb.deactivate();
        map.enableMapNavigation();

        // Save clicked coordinates to Laravel session
        var url = "/map/point" + "/" + evt.geometry.x + "/" + evt.geometry.y;
        $.ajax({
            method: "GET",
            url: url
        }).done(function (allClear) {
            if (allClear == 1) {
                $(".modal-wrapper").toggle();
                $("#unit_metric_label").show();
                $("#unit_metric").show();
            } else {
                alert(
                    "Error: Geometry falls outside of Scenario Embayment. Please redraw geometry or contact info@capecodcommission.org for technical assistance. Thank you."
                );
                map.graphics.remove(pointGraphic);
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
        map.graphics.add(polyGraphic);
        // Deactivate the toolbar and enable map navigation
        tb.deactivate();
        map.enableMapNavigation();

        // Retrieve coordinate arrays
        let nodeString = parsePolyOnSelect(evt.geometry.rings[0]);

        // Save coordinate string to session
        var url = "/map/poly";
        var data = { coordString: nodeString };
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
                $("#applytreatment").show();
                $(".modal-wrapper").toggle();
            }
            else {
                alert('Error: Geometry falls outside of Scenario Embayment. Please redraw geometry or contact info@capecodcommission.org for technical assistance. Thank you.');
                map.graphics.remove(polyGraphic);
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
        geo_string
    ) {
        // Remove SQL Spatial type and spatial reference from the geometry string
        // Create point geometry and symbology
        geo_string = geo_string
            .replace("POINT(", "")
            .replace(", 3857)", "")
            .split(", ");
        var pointGeom = new Point({
            x: parseFloat(geo_string[0]),
            y: parseFloat(geo_string[1]),
            spatialReference: sr
        });
        var pointSymbol = new PictureMarkerSymbol(imageURL, 30, 30);

        // Create graphic using geometry and symbology
        var pointGraphic = new Graphic(pointGeom, pointSymbol, {
            keeper: true,
            treatment_id: treatmentid,
            editInProgress: 0
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
        pointGraphic.setInfoTemplate(template);
        map.graphics.add(pointGraphic);
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
            .replace("))", "")
            .split(",");
        let nodes = [];
        geoArray.map(coords => {
            let splitCoords = coords.split(" ");
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
        polyGraphic.setInfoTemplate(template);
        map.graphics.add(polyGraphic);
    }

    // Add point and polygon graphics based on treatment geometry on-load of map
    function addGraphicsOnLoad(treatments) {
        treatments.map(row => {
            // Retrieve appropriate treatment properties to pass into point or polygon graphics loading
            const treatmentType = row.TreatmentType_Name;
            const customPoly = row.Custom_POLY;
            const treatmentid = row.TreatmentID;
            const imageURL =
                "http://www.cch2o.org/Matrix/icons/" + row.treatment_icon;
            const treatmentArea = Math.round(row.Treatment_Acreage);
            const parcels = row.Treatment_Parcels;
            const n_removed = Math.round(row.Nload_Reduction);
            const popupVal = treatmentType + " (" + row.TreatmentID + ")";
            const sr = { wkid: 102100, latestWkid: 3857 };
            const treatmentTypeId = row.TreatmentType_ID;
            const polySymbol = selectPoly(treatmentTypeId);
            const geo_string = row.POLY_STRING;

            // Load point or polygon creation by geometry type
            if (customPoly == 0 && geo_string.startsWith("POINT")) {
                addPointOnLoad(
                    treatmentid,
                    treatmentArea,
                    imageURL,
                    parcels,
                    n_removed,
                    popupVal,
                    sr,
                    geo_string
                );
            } else if (customPoly == 1 && geo_string.startsWith("POLYGON")) {
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
                $(".modal-wrapper").toggle();
                $("#deletetreatment").hide();
                $("#updateStormwaterNonManangement").show();
            } else {
                // Alert user if save unsuccessful
                alert(
                    "Error: Geometry falls outside of Scenario Embayment. Please redraw geometry or contact info@capecodcommission.org for technical assistance. Thank you."
                );
                map.disableDoubleClickZoom();
                map.setInfoWindowOnClick(false);
                editGeoClicked = 1;
                activateToolbar(evt);
            }
        });
    }

    // Global objects to house currently hightlighted graphic and icon URL from treatment stack
    let treatmentGraphic = null;
    let pointURL = "";

    // Highlight symbololgy based on geometry type
    function highlightSymbolOnEnter(treatment_id) {
        // Retrieve graphic by treatment id id possible
        let layerGraphics = map.graphics.graphics;
        treatmentGraphic = layerGraphics.filter(graphic => {
            let attribs = graphic.attributes;
            if (attribs) {
                return attribs.treatment_id == treatment_id;
            }
        });

        // If graphic exists, highlight it
        if (treatmentGraphic.length) {
            let geoType = treatmentGraphic[0].geometry.type;
            let highlightColor = [252, 236, 3, 1.0];
            let highlightSymbol = {};
            switch (geoType) {
                case "polygon":
                    highlightSymbol = createPolySymbol(highlightColor);
                    treatmentGraphic[0].setSymbol(highlightSymbol);
                    break;

                case "point":
                    pointURL = treatmentGraphic[0].symbol.url;
                    highlightSymbol = new SimpleMarkerSymbol(
                        SimpleMarkerSymbol.STYLE_CIRCLE,
                        30,
                        null,
                        new Color(highlightColor)
                    );
                    treatmentGraphic[0].setSymbol(highlightSymbol);
                    break;
            }
        }
    }

    // Reset symbology based on geometry type
    function resetSymbolOnLeave(techId) {
        // If graphic exists, reset original symbology
        if (treatmentGraphic.length) {
            let geoType = treatmentGraphic[0].geometry.type;
            let originalGraphic = {};
            switch (geoType) {
                case "polygon":
                    originalGraphic = selectPoly(techId);
                    treatmentGraphic[0].setSymbol(originalGraphic);
                    break;

                case "point":
                    originalGraphic = new PictureMarkerSymbol(pointURL, 30, 30);
                    treatmentGraphic[0].setSymbol(originalGraphic);
                    break;
            }
            treatmentGraphic = null;
            pointURL = null;
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
        let layerGraphics = map.graphics.graphics;

        // Filter to edited graphics
        let editedGraphic = layerGraphics.filter(graphic => {
            let attribs = graphic.attributes;
            if (attribs) {
                return attribs.editInProgress;
            }
        });

        if (editedGraphic.length) {
            // Obtain new treatment info, set popup
            var url = "/get_treatment" + "/" + editedGraphic[0].attributes.treatment_id;
            $.ajax({
                method: "GET",
                url: url
            })
                .done(function (treatment) {
                    // Add original geometry to map through either the global treatments object or the graphic attribute
                    if (treatment) {
                        map.graphics.remove(editedGraphic[0]);
                        addGraphicsOnLoad(treatment);
                    }
                })
                .fail(function (msg) {
                    alert(
                        "error: geometry failed to reset, please contact info@capecodcommission for technical support. Thank you." +
                        msg.statusText
                    );
                });
        }
    });

    // Handler to reset edited geometry to its original position on-close of a legacy edit modal
    $(".modal-content").on("click", ".popdown-content #closeWindow", function (e) {
        e.preventDefault();
        let layerGraphics = map.graphics.graphics;

        destroyModalContents();
        deleteGraphic();

        // Filter to edited graphics
        let editedGraphic = layerGraphics.filter(graphic => {
            let attribs = graphic.attributes;
            if (attribs) {
                return attribs.editInProgress;
            }
        });

        if (editedGraphic.length) {
            // Obtain new treatment info, set popup
            var url = "/get_treatment" + "/" + editedGraphic[0].attributes.treatment_id;
            $.ajax({
                method: "GET",
                url: url
            })
                .done(function (treatment) {
                    // Add original geometry to map through either the global treatments object or the graphic attribute
                    if (treatment) {
                        map.graphics.remove(editedGraphic[0]);
                        addGraphicsOnLoad(treatment);
                    }
                })
                .fail(function (msg) {
                    alert(
                        "error: geometry failed to reset, please contact info@capecodcommission for technical support. Thank you." +
                        msg.statusText
                    );
                });
        }
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
                    let layerGraphics = map.graphics.graphics;
                    layerGraphics
                        .filter(graphic => {
                            let attribs = graphic.attributes;
                            if (attribs) {
                                return attribs;
                            }
                        })
                        .map(graphic => {
                            map.graphics.remove(graphic);
                        });

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

    /*******************************
     *
     *	This is the ArcGIS Basemap Gallery which (used to) break everything
     *
     *********************************/

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
        "http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            // maxAllowableOffset: map.extent,
            opacity: 1
        }
    );
    embayments.setDefinitionExpression("EMBAY_ID = " + selectlayer);

    map.addLayer(embayments);
    // var point = (embayments.X_Centroid, embayments.Y_Centroid);
    // map.centerAndZoom(point, 11);
    // map.setExtent(embayments.fullExtent);

    var subwater_template = new InfoTemplate({
        title: "<b>Subwatershed</b>",
        content: "${SUBWATER_D}"
    });

    var Subwatersheds = new FeatureLayer(
        "http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/6",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            infoTemplate: subwater_template,
            opacity: 1
        }
    );
    Subwatersheds.setDefinitionExpression("EMBAY_ID = " + selectlayer);

    Subwatersheds.hide();
    // Subwatersheds.setExtent(extent);
    map.addLayer(Subwatersheds);

    var subem_template = new InfoTemplate({
        title: "<b>Subembayment</b>",
        content: "${SUBEM_DISP}"
    });
    // subem_template.setTitle("<b>${SUBEM_DISP}</b>");
    // subem_template.setContent("${SUBEM_DISP}");

    var Subembayments = new FeatureLayer(
        "http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/11",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            infoTemplate: subem_template,
            opacity: 1
        }
    );
    Subembayments.setDefinitionExpression("EMBAY_ID = " + selectlayer);
    // Subembayments.show();
    Subembayments.hide();
    // console.log(Subembayments);
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
            "${NLoad_Septic_Existing:NumberFormat(places:2)}" +
            "</td>" +
            "</tr>" +
            "<tr style = 'height: 2px'>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "Unattn Nitrogen Load (Fertilization) (Kg/Yr): " +
            "</td>" +
            "<td style = 'padding: 0px; margin: 0px;'>" +
            "${Nload_Fert:NumberFormat(places:2)}" +
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

    // Layer 13 is now used for all point layers
    var NitrogenLayer = new FeatureLayer(
        "http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/13",
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
    // symbol.setSize("8")

    var renderer = new SimpleRenderer(symbol);
    renderer.setSizeInfo({
        field: "Nload_Full",
        minSize: 2,
        // {
        //     type: 'sizeInfo',
        //     expression: 'view.scale',
        //     stops: [
        //         {value: 5, size: 20},
        //         {value: 10, size: 10},
        //         {value: 20, size: 8},
        //         {value: 50, size: 5},
        //         {value: 100, size: 4}
        //     ]
        // },
        maxSize: 20,
        // {
        //     type: 'sizeInfo',
        //     expression: 'view.scale',
        //     stops: [
        //         {value: 5, size: 20},
        //         {value: 10, size: 15},
        //         {value: 20, size: 10},
        //         {value: 50, size: 5},
        //         {value: 100, size: 4}
        //     ]
        // },
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
        "http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/13",
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
        "http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/5",
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
        "http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/13",
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

    TreatmentType.setRenderer(treattypeRenderer);

    TreatmentType.hide();
    map.addLayer(TreatmentType);

    var TreatmentFacilities = new FeatureLayer(
        "http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/9",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1
        }
    );
    TreatmentFacilities.hide();
    map.addLayer(TreatmentFacilities);

    var EcologicalIndicators = new FeatureLayer(
        "http://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer/10",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1
        }
    );
    EcologicalIndicators.hide();
    map.addLayer(EcologicalIndicators);

    var ShallowGroundwater = new FeatureLayer(
        "http://gis-services.capecodcommission.org/arcgis/rest/services/Projects/208_Plan/MapServer/32",
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
        "http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/13",
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
        landuseSymbol,
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
        "http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/12",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1,
            infoTemplate: nitro_template
        }
    );

    FlowThrough.hide();
    map.addLayer(FlowThrough);

    var Contours = new FeatureLayer(
        "http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/14",
        {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            opacity: 1,
            infoTemplate: nitro_template
        }
    );

    Contours.hide();
    map.addLayer(Contours);

    // console.log('testing');
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
            queryString += "SUBEM_ID = " + String(inBuffer[j]) + " OR ";
        }

        queryString =
            queryString.substring(0, queryString.lastIndexOf("OR")) + "";

        // console.log(queryString)
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

    $("#nitrogen").on("click", function (e) {
        e.preventDefault();
        // console.log(NitrogenLayer);
        if ($(this).attr("data-visible") == "off") {
            legendDijit.refresh([
                { layer: NitrogenLayer, title: "Nitrogen Load" }
            ]);
            NitrogenLayer.setDefinitionExpression(queryString.toString());
            NitrogenLayer.show();
            // legendDijit.refresh([{layer: NitrogenLayer, title: "Nitrogen Load"}])
            $(this).attr("data-visible", "on");
        } else {
            NitrogenLayer.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $("#subembayments").on("click", function (e) {
        e.preventDefault();

        if ($(this).attr("data-visible") == "off") {
            Subembayments.show();
            // console.log(Subembayments);
            $(this).attr("data-visible", "on");
        } else {
            Subembayments.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $("#subwatersheds").on("click", function (e) {
        e.preventDefault();

        if ($(this).attr("data-visible") == "off") {
            Subwatersheds.show();
            $(this).attr("data-visible", "on");
        } else {
            Subwatersheds.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $("#wastewater").on("click", function (e) {
        e.preventDefault();

        if ($(this).attr("data-visible") == "off") {
            legendDijit.refresh([{ layer: WasteWater, title: "Wastewater" }]);
            WasteWater.setDefinitionExpression(queryString.toString());
            WasteWater.show();
            $(this).attr("data-visible", "on");
        } else {
            WasteWater.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $("#towns").on("click", function (e) {
        e.preventDefault();

        if ($(this).attr("data-visible") == "off") {
            Towns.show();
            $(this).attr("data-visible", "on");
        } else {
            Towns.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $("#treatmenttype").on("click", function (e) {
        e.preventDefault();

        if ($(this).attr("data-visible") == "off") {
            legendDijit.refresh([
                { layer: TreatmentType, title: "Treatment Type" }
            ]);
            TreatmentType.setDefinitionExpression(queryString.toString());
            TreatmentType.show();
            $(this).attr("data-visible", "on");
        } else {
            TreatmentType.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $("#treatmentfacilities").on("click", function (e) {
        e.preventDefault();

        if ($(this).attr("data-visible") == "off") {
            TreatmentFacilities.show();
            $(this).attr("data-visible", "on");
        } else {
            TreatmentFacilities.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $("#ecologicalindicators").on("click", function (e) {
        e.preventDefault();

        if ($(this).attr("data-visible") == "off") {
            legendDijit.refresh([
                { layer: EcologicalIndicators, title: "Ecological Indicators" }
            ]);
            EcologicalIndicators.show();
            $(this).attr("data-visible", "on");
        } else {
            EcologicalIndicators.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $("#shallowgroundwater").on("click", function (e) {
        e.preventDefault();

        if ($(this).attr("data-visible") == "off") {
            ShallowGroundwater.show();
            $(this).attr("data-visible", "on");
        } else {
            ShallowGroundwater.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $("#landuse").on("click", function (e) {
        e.preventDefault();

        if ($(this).attr("data-visible") == "off") {
            legendDijit.refresh([
                { layer: LandUse, title: "Land Use Category" }
            ]);
            LandUse.setDefinitionExpression(queryString.toString());
            LandUse.show();
            $(this).attr("data-visible", "on");
        } else {
            LandUse.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $("#flowthrough").on("click", function (e) {
        e.preventDefault();

        if ($(this).attr("data-visible") == "off") {
            legendDijit.refresh([
                { layer: FlowThrough, title: "FlowThrough Coefficients" }
            ]);
            FlowThrough.show();
            $(this).attr("data-visible", "on");
        } else {
            FlowThrough.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $("#contours").on("click", function (e) {
        e.preventDefault();

        if ($(this).attr("data-visible") == "off") {
            legendDijit.refresh([{ layer: Contours, title: "2ft Contours" }]);
            Contours.show();
            $(this).attr("data-visible", "on");
        } else {
            Contours.hide();
            $(this).attr("data-visible", "off");
        }
        //
    });

    $(".subembayment").on("click", function (e) {
        // console.log('subembayment clicked');
        var sub = $(this).data("layer");

        Subembayments.setDefinitionExpression("SUBEM_ID = " + sub);
        Subembayments.show();
    });

    $("#disable-popups").on("click", function (e) {
        var layers = [
            NitrogenLayer,
            Subembayments,
            Subwatersheds,
            WasteWater,
            Towns,
            TreatmentType,
            TreatmentFacilities,
            EcologicalIndicators,
            ShallowGroundwater,
            LandUse,
            FlowThrough,
            Contours
        ];

        if ($(this).hasClass("enabled")) {
            for (var i = 0; i < layers.length; i++) {
                if (layers[i].visible) {
                    layers[i].setInfoTemplate(null);
                }
            }

            $(this).toggleClass("enabled fa-eye-slash");
        } else {
            for (var i = 0; i < layers.length; i++) {
                if (layers[i].visible) {
                    layers[i].setInfoTemplate(nitro_template);
                }
            }

            $(this).toggleClass("enabled fa-eye-slash");
        }
    });

    var getDestinationPoint = map.on("select-destination", getDestination);

    function getDestination(evt) {
        return evt;
        getDestinationPoint.remove();
    }

    var getDestinationPoint = map.on("select-destination", getDestination);

    function getDestination(evt) {
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
