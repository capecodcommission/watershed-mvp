@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				

				<div class="panel-body">
				   <h2>Welcome to WatershedMVP 4.1</h2>
				   <p>The Cape Cod Commission developed the WatershedMVP application for professionals, municipal officials and community members in order to assist in creating the most cost-effective and efficient solutions to Cape Cod’s wastewater problem.</p>

            <p>The application is an informational resource intended to provide regional estimates for planning purposes. WatershedMVP is an initiative of the Cape Cod Commission’s Strategic Information Office (SIO). To learn more about the WatershedMVP application and the Cape Cod Commission and its SIO, please <a href="http://www.capecodcommission.org/index.php?id=205" target="_blank">contact us</a>.</p>
				<h3>Getting Started</h3>
				<p>To create a scenario, you first need to <a href="{{url('/register')}}">create an account</a>. Once you are logged in, you can create a scenario by selecting an embayment in which to plan.</p>
				<p>Once you've selected an embayment, you will be able to view the subembayments' and overall progress towards the Nitrogen threshold. The tool will guide you through the steps to select technologies to treat Nitrogen at different sources:</p>
				<ul>
					<li>Fertilizer</li>
					<li>Stormwater</li>
					<li>Septic</li>
					<li>Groundwater</li>
					<li>In-Embayment</li>
				</ul><br>
				<p>Some technologies will prompt you to select an area (polygon) to apply the treatment. You can use the Map Tools to show/hide different map layers (Wastewater, Nitrogen levels, Subembayments, Subwatersheds, etc.) as well as change the basemap layer.</p>
				<p>Once you've applied a treatment, you can view its effect on the overall Nitrogen level by clicking on the icon in the top toolbar. You may also view the summary at any time during the scenario planning and download the detailed results. </p>
				<p>You can edit polygons for treatments by clicking the "Edit Polygon" button and then selecting the polygon you would like to update. Once you have finished adjusting the perimeter or rotating the polygon, click the "Save Polygon" button in the top toolbar and then click "Update" when the treatment info window appears. </p>
				</div>
			</div>
		</div>
	</div>
</div>

	
@endsection
