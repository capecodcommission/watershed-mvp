<!DOCTYPE html>

<html>
<head>
	<title>WatershedMVP 3.0 Wizard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link href="{{url('/css/app.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css">
	<script src="https://js.arcgis.com/3.16/"></script>

	
	
</head>
<body>
	<div id="map" class="map"></div>
	<div class="wrapper">
		<div class="content">
			<nav class="toolbar">
				@include('common/map-tools')
			</nav>
			
			@include('common/subembayment-progress')

			@include('common/wizard-steps')
		</div>
	</div>
	
	<script>
		var selectlayer = "{{$embayment->EMBAY_ID}}";
	</script>
	<script src="{{url('/js/map.js')}}"></script>
		<script src="{{url('/js/app.js')}}"></script>
	<script src="{{url('/js/main.js')}}"></script>
		<script type="text/javascript" src="{{url('/js/jquery.popdown.js?v=1')}}" /></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$('.popdown').popdown();
		});
	</script>
</body>
</html>
