<!DOCTYPE html>
<!-- This is the starting page for the WMVP3 Wizard where the user logs in or selects a watershed to run a scenario -->
<html>
<head>
    <title>WatershedMVP 3.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{url('css/app.css')}}" rel="stylesheet" type="text/css">
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css">
    <style>
        #map    { z-index: -10; }
    </style>
    <script src="https://js.arcgis.com/3.16/"></script>
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"   integrity="sha256-JmvOoLtYsmqlsWxa7mDSLMwa6dZ9rrIdtrrVYRnDRH0="   crossorigin="anonymous"></script>
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var map;
        require([
        "esri/map", 
        "esri/InfoTemplate",
        "esri/layers/FeatureLayer",
        "dojo/dom-construct",
        "esri/toolbars/draw",
        "esri/symbols/SimpleFillSymbol",
        "esri/symbols/SimpleLineSymbol",
        "esri/Color",
        "esri/graphic",
        "esri/symbols/SimpleMarkerSymbol",
        "esri/renderers/SimpleRenderer",
        "dojo/domReady!"
        ],
        function (Map,
          // InfoWindowLite,
          InfoTemplate,
          FeatureLayer,
          // Extent,
    
          domConstruct,
          Draw,
          SimpleFillSymbol,
          SimpleLineSymbol,
          Color,
          Graphic,
          SimpleMarkerSymbol,
          SimpleRenderer
         ) {
 var spatialReference = new esri.SpatialReference({ wkid: 102100 });
                var extent = new esri.geometry.Extent(-7893678, 5069311, -7769404, 5192999, spatialReference);
    var map = new esri.Map("map", 
        { 
            wrapAround180: true, 
            // infoWindow: popup, 
            extent: extent,
            basemap: "gray" 
            // sliderStyle: "large" 
        });
              // map = new Map("map", {
                    // center: [-70.35, 41.68], //#TODO find a new center for the start page, to shift the map to the left
                    // // extent: initialExtent,
                    // zoom: 11,
                    // basemap: "gray"
              // });
            var template = new InfoTemplate();
            template.setTitle("<b>${EMBAY_DISP}</b>");
            template.setContent("<a href='{{url('map')}}/${EMBAY_ID}'>Create a scenario for ${EMBAY_DISP}</a>");  
            var embayLayer = new FeatureLayer("http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4", {
                mode: FeatureLayer.MODE_ONDEMAND,
                infoTemplate:template,
                outFields: ["EMBAY_DISP", "EMBAY_ID"]
            });
            embayLayer.show();
            map.addLayer(embayLayer);//

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

              // Layer 13 is now used for all point layers
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
              symbol.setColor(new Color([34, 209, 219]))
              symbol.setSize("8")

              var renderer = new SimpleRenderer(symbol)
              renderer.setSizeInfo({
                field: "Nload_Full",
                minSize: 3,
                maxSize: 20,
                minDataValue: 5,
                maxDataValue: 250,
                legendOptions: {
                    customValues: [50,100,150,200,250]
                }
              })

              NitrogenLayer.setRenderer(renderer)
              NitrogenLayer.hide()

              map.addLayer(NitrogenLayer)

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

              Subwatersheds.hide()
              map.addLayer(Subwatersheds)

              var layers = [Subwatersheds, embayLayer]

            $('#embayments').on('click', function(e) {

                e.preventDefault();
                if ($(this).attr('data-visible') == 'off') {

                    if (typeof tb != 'undefined') {
                      if (tb._points.length > 1) {
                        embayLayer.setInfoTemplate(null)
                      } else {
                        embayLayer.setInfoTemplate(template)
                      }
                    }
                    embayLayer.show()
                    $(this).attr('data-visible', 'on');
                } else {

                    embayLayer.hide();
                    $(this).attr('data-visible', 'off');
                }
            });

            $('#subwatersheds').on('click', function(e) {

                e.preventDefault();
                if ($(this).attr('data-visible') == 'off') {

                    if (typeof tb != 'undefined') {
                      if (tb._points.length > 1) {
                        Subwatersheds.setInfoTemplate(null)
                      } else {
                        Subwatersheds.setInfoTemplate(subwater_template)
                      }
                    }
                    Subwatersheds.show()
                    $(this).attr('data-visible', 'on');
                } else {

                    Subwatersheds.hide();
                    $(this).attr('data-visible', 'off');
                }
            });


            $('#select_polygon').on('click', function(f){

                map.graphics.remove(map.graphics.graphics[1])
                NitrogenLayer.hide()

                layers.map((i) => {
                  if (i.visible) {
                    i.setInfoTemplate(null)
                  }
                })

                f.preventDefault()
                tb = new Draw(map);
                tb.on("draw-end", addGraphic);

                tb.activate('polygon');
            });

            function addGraphic(evt) {

              tb.deactivate();
              NitrogenLayer.hide()

              var symbol = new esri.symbol.SimpleFillSymbol(
                  SimpleFillSymbol.STYLE_SOLID,
                  new SimpleLineSymbol(
                      SimpleLineSymbol.STYLE_SOLID,
                      new Color([43, 171, 227, 1.0]), 4),
                  new Color([0, 0, 0, 0.0])
                  );

              map.graphics.add(new Graphic(evt.geometry, symbol));

              var polystring = '';

              for (var i = 0; i < evt.geometry.rings[0].length; i++) {

                  polystring += evt.geometry.rings[0][i][0] + ' ';
                  polystring += evt.geometry.rings[0][i][1] + ', ';
              }

              var len = polystring.length;
              polystring = polystring.substring(0, len - 2);

              var url = '/sumTotalsWithinPolygon';
                      
              var data = {polystring: polystring};

              $.ajax({
                  method: 'POST',
                  data: data,
                  url: url
              })
              .done(function (msg) {

                  $('#parcelcount').html('<b>Parcels</b>: ' + msg[0]['parcelCount'].toLocaleString())
                  $('#nitrogenload').html('<b>Nitrogen Load</b>: ' + Math.round(msg[0]['nitrogenLoad']).toLocaleString() + ' kg/year')
                  $('#wwload').html('<b>Wastewater Load</b>: ' + Math.round(msg[0]['wwLoad']).toLocaleString() + ' gal/day')

                  var poly3URL = '/getIDArrayWithinPolygon'
                  $.ajax({
                    method: 'POST',
                    data: data,
                    url: poly3URL
                  })
                  .done(function(response) {

                    var queryTypeString = response.map(i => {return "'" + i.otherID + "'"}).join(',')
                    NitrogenLayer.setDefinitionExpression('Other_ID IN (' + queryTypeString + ')')
                    NitrogenLayer.show()

                    if (Subwatersheds.infoTemplate == null) {Subwatersheds.setInfoTemplate(subwater_template)}
                    if (embayLayer.infoTemplate == null) {embayLayer.setInfoTemplate(template)}
                  })
                  .fail(function (response) {

                    console.log(response.statusText)
                  })
              })
              .fail(function(msg){

                  alert('There was a problem saving the polygon. Please send this error message to mario.carloni@capecodcommission.org: <br />Response: ' + msg.status + ' ' + msg.statusText );
              });

              // NitrogenLayer.setDefinitionExpression(evt.geometry + '.STContains(SHAPE,3857)')
              // NitrogenLayer.show()
            }
        });
    </script>
</head>
<body class="start">
    <div id="map" class="map"></div>
        <div class="secondary start">
        <img src="http://www.watershedmvp.org/images/mvplogo.png" alt="WatershedMVP 3.0 by Cape Cod Commission">
        


            <p>The Cape Cod Commission developed the WatershedMVP application for professionals, municipal officials and community members in order to assist in creating the most cost-effective and efficient solutions to Cape Cod’s wastewater problem.</p>

            <p>The application is an informational resource intended to provide regional estimates for planning purposes. WatershedMVP is an initiative of the Cape Cod Commission’s Strategic Information Office (SIO). To learn more about the WatershedMVP application and the Cape Cod Commission and its SIO, please <a href="http://www.capecodcommission.org/index.php?id=205" target="_blank">contact us</a>.</p>

            <p>You can select a watershed from the list below, or click on the map to get started.</p>
        
            <p>
                <select id="embayment" class="Filter" >
                    <option value="">Select an embayment</option>
                    @foreach ($embayments as $embayment)
                        <option value="{{$embayment->EMBAY_ID}}">{{$embayment->EMBAY_DISP}}</option>
                    @endforeach
                </select>
            </p>

            <p>
                <a href="{{url('/map')}}" id="startwizard" class="button">Get Started</a>
                <a id="select_polygon" class="button pull-right">Draw polygon</a>
            </p>

            <p>
                <div id = 'parcelcount'></div>
                <div id = 'nitrogenload'></div>
                <div id = 'wwload'></div>
            </p>


            
        </div>

        <div class="js-menu sliding-panel-content is-visible" style = "z-Index: 100;">
            <div class="info"  data-dojo-type="dijit/layout/ContentPane"> 
            <h4>Layers</h4>
                <ul id="layers">
                    <li>
                        <a id="embayments" data-visible="on"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Embayments</a>
                        <a id="subwatersheds" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Subwatersheds</a>
                    </li>      
                </ul>
            </div>
        </div>
        <script>
            $(document).ready(function(){

                $('#embayment').on('change', function(){

                    var watershed = $(this).val();
                    $('#startwizard').attr('href', "{{url('/map')}}/"+watershed);
                });
            });
        </script>
        
    </body>
</html>