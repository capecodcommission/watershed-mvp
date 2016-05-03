<html>
<head>
  <meta charset=utf-8 />
  <title>Switching basemaps</title>
  <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />

  <!-- Load Leaflet from CDN-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1.0.0-rc.1/leaflet.css" />
  <script src="https://cdn.jsdelivr.net/leaflet/1.0.0-rc.1/leaflet-src.js"></script>

  <!-- Load Esri Leaflet from CDN -->
  <script src="https://cdn.jsdelivr.net/leaflet.esri/2.0.0-beta.8/esri-leaflet.js"></script>

  <style>
    body { margin:0; padding:0; }
    #map { position: absolute; top:0; bottom:0; right:0; left:0; }
  </style>
</head>
<body>

<style>
  #basemaps-wrapper {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 400;
    background: white;
    padding: 10px;
  }
  #basemaps {
    margin-bottom: 5px;
  }
</style>

<div id="map"></div>

<div id="basemaps-wrapper" class="leaflet-bar">
  <select name="basemaps" id="basemaps" onChange="changeBasemap(basemaps)">
    <option value="Topographic">Topographic<options>
    <option value="Streets">Streets</option>
    <option value="NationalGeographic">National Geographic<options>
    <option value="Oceans">Oceans<options>
    <option value="Gray">Gray<options>
    <option value="DarkGray">Dark Gray<options>
    <option value="Imagery">Imagery<options>
    <option value="ShadedRelief">Shaded Relief<options>
  </select>
</div>

<script>

  // map = new Map("map", {
  //   center: [-70.35, 41.68],
  //   zoom: 11,
  //   basemap: "gray",
  //   slider: true,
  //   sliderOrientation: "horizontal"
  // });


   var map = L.map('map').setView([41.67, -70.47], 13);

  // L.esri.basemapLayer('Streets').addTo(map);
  L.esri.featureLayer({
    url: 'http://gis-services.capecodcommission.org/arcgis/rest/services/wMVP/wMVP3/MapServer/4'
  }).addTo(map);

    var layer = L.esri.basemapLayer('Gray').addTo(map);
  var layerLabels;
 function setBasemap(basemap) {
    if (layer) {
      map.removeLayer(layer);
    }

    layer = L.esri.basemapLayer(basemap);

    map.addLayer(layer);

    if (layerLabels) {
      map.removeLayer(layerLabels);
    }

    if (basemap === 'ShadedRelief'
     || basemap === 'Oceans'
     || basemap === 'Gray'
     || basemap === 'DarkGray'
     || basemap === 'Imagery'
     || basemap === 'Terrain'
   ) {
      layerLabels = L.esri.basemapLayer(basemap + 'Labels');
      map.addLayer(layerLabels);
    }
  }

    function changeBasemap(basemaps){
  var basemap = basemaps.value;
  setBasemap(basemap);
  }

L.polygon( <LatLng[]> latlngs, <Polyline options> options? )


  
</script>

</body>
</html>
Edit this sample on GitHub

