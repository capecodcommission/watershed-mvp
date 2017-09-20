<!DOCTYPE html>
<!-- This is the starting page for the WMVP3 Wizard where the user logs in or selects a watershed to run a scenario -->
<html>
<head>
    <title>WatershedMVP 3.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <link href="{{url('css/app.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css">
    <style>
        #map    { z-index: -10; }
    </style>
    <script src="https://js.arcgis.com/3.16/"></script>
    <script>
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
          Graphic
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

            $('#embayments').on('click', function(e) {

                e.preventDefault();
                if ($(this).attr('data-visible') == 'off') {

                    embayLayer.show()
                    $(this).attr('data-visible', 'on');
                } else {

                    embayLayer.hide();
                    $(this).attr('data-visible', 'off');
                }
            });

            $('#select_polygon').on('click', function(f){

                f.preventDefault()
                tb = new Draw(map);
                tb.on("draw-end", addGraphic);

                tb.activate('polygon');
            });

            function addGraphic(evt) {

                tb.deactivate();

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

                var url = '/poly2' + '/' + polystring;
                        
                var data = {polystring: polystring};

                $.ajax({
                    method: 'POST',
                    // data: data,
                    url: url
                })
                .done(function (msg) {

                    console.log(msg)
                })
                .fail(function(msg){

                    alert('There was a problem saving the polygon. Please send this error message to mario.carloni@capecodcommission.org: <br />Response: ' + msg.status + ' ' + msg.statusText );
                });

                evt.geometry.getExtent();
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
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </p>


            
        </div>

        <div class="js-menu sliding-panel-content is-visible" style = "z-Index: 100;">
            <div class="info"  data-dojo-type="dijit/layout/ContentPane"> 
            <h4>Layers</h4>
                <ul id="layers">
                    <li>
                        <a id="embayments" data-visible="on"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Embayments</a>
                    </li>      
                </ul>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.0.0.min.js"   integrity="sha256-JmvOoLtYsmqlsWxa7mDSLMwa6dZ9rrIdtrrVYRnDRH0="   crossorigin="anonymous"></script>
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