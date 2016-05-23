<!DOCTYPE html>

<html>
<head>
	<title>WatershedMVP 3.0 Wizard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link href="{{url('/css/app.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css">
	<script src="https://js.arcgis.com/3.16/"></script>
  <link rel="stylesheet" href="https://js.arcgis.com/3.16/dijit/themes/claro/claro.css">    

<!-- 	*****************************************************
		These are the Leaflet/Esri scripts Hiding them for now 
		*****************************************************
-->

  <!-- Load Leaflet from CDN-->
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1.0.0-rc.1/leaflet.css" /> -->
  <!-- <script src="https://cdn.jsdelivr.net/leaflet/1.0.0-rc.1/leaflet-src.js"></script> -->
  <!-- Load Esri Leaflet from CDN -->
  <!-- <script src="https://cdn.jsdelivr.net/leaflet.esri/2.0.0/esri-leaflet.js"></script> -->
	
<!-- 

	<style>
		#basemaps-wrapper 
		{
			position: absolute;
			top: 10px;
			right: 10px;
			z-index: 400;
			background: white;
			padding: 10px;
		}
		#basemaps 
		{
			margin-bottom: 5px;
		}
	</style> -->


</head>
<body class="claro">

<div data-dojo-type="dijit/layout/BorderContainer" 
	   data-dojo-props="design:'headline', gutters:false" 
	   style="width:100%;height:100%;margin:0;">

	<div id="map">

	</div>
  </div>
	<!-- <div id="map" class="map"></div> -->
	<div class="wrapper">
		<div class="content">
			<nav class="toolbar">
				@include('common/map-tools')
			</nav>
			
			@include('common/subembayment-progress')
			@include('common/embayment-progress')
			@include('common/wizard-steps')
		</div>
	</div>
	
	<script>
		var selectlayer = {{$embayment->EMBAY_ID}};
	</script>
	<script src="{{url('/js/map.js')}}"></script>
	  <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.1.js"></script>
		<script src="{{url('/js/app.js')}}"></script>
		<script src="{{url('/js/main.js')}}"></script>
		<script type="text/javascript" src="{{url('/js/jquery.popdown.js')}}" /></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$('.popdown').popdown();
			$('#closeACC').on('click', function(event){
				$('.state').prop('checked', false);
			});
			$('.sliding-panel-button').on('click', function(e){
				$('.sliding-panel-content').toggleClass('is-visible');
				// console.log('button clicked');
			});

		});
	</script>
	
</body>
</html>
