<div class="tool" style="padding: .8em 1em;">		
<label>
	<i class="fa fa-globe fa-globe-white fa-2x js-menu-trigger sliding-panel-button"></i>
</label>
<label style="position:absolute; right:50px; top:20px; z-index: 2;">
	<i id ='disable-popups' class="fa fa-eye fa-eye-white fa-2x js-menu-trigger enabled"></i>
</label>
    <div style="position:absolute; right:95px; top:20px; z-index: 3;">
        <div data-dojo-type="dijit/TitlePane"
             data-dojo-props="title:'Switch Basemap', closable:false, open:false">
            <div data-dojo-type="dijit/layout/ContentPane" style="width:380px; height:280px; overflow:auto;">
                <div id="basemapGallery"></div>
            </div>
        </div>
        <div id="basemaps-wrapper" class="leaflet-bar"></div>
    </div>
    <div id = 'legendWrapper' style="position:absolute; right:250px; top:20px; z-index: 2; display:none">
        <div data-dojo-type="dijit/TitlePane" data-dojo-props="title:'Legend', closable:false, open:false">
            <div data-dojo-type="dijit/layout/ContentPane" style="width:200px; height:420px; overflow:auto; ">
                <div id="legendDiv"></div>
            </div>
        </div>
    </div>
		<div class="js-menu sliding-panel-content is-visible" style = "z-Index: 2;">
		
			<div class="info"  data-dojo-type="dijit/layout/ContentPane">
			<h4>Map Layers</h4>
				<ul id="layers">
					<li title = 'Town Boundaries (Cape-wide)'>
						<a id="towns" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Towns</a>
					</li>
					<li title = 'Subembayment Boundaries (Embayment-wide)'>
						<a id="subembayments" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Subembayments</a>
					</li>
					<li title = 'Subwatershed Boundaries (Embayment-wide)'>
						<a href="" id="subwatersheds" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Subwatersheds</a>
					</li>					

					<li title = 'Wastewater Treatment Facilities (Cape-wide)'>
						<a href="" id="treatmentfacilities" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Wastewater Treatment Facilities</a>
					</li>
					<li class='inLegend' title = 'Qualitative description related to measured water quality indications within Barnstable County embayments. (Cape-wide)'>
						<a href="" id="ecologicalindicators" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Ecological Indicators</a>
					</li>
					<li title = 'Depth to Groundwater < 20ft (Cape-wide)'>
						<a href="" id="shallowgroundwater" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Depth to Groundwater < 20ft</a>
					</li>
					<li class='inLegend' title = 'Parcel centroids visualized by nitrogen load (Embayment-wide)'>
						<a href="" id="nitrogen" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Existing Nitrogen Load</a>				
					</li>
					<li class='inLegend' title = 'Parcel centroids visualized by wastewater load (Embayment-wide)'>
						<a href="" id="wastewater" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Wastewater</a>
					</li>
					<li class='inLegend' title = 'Parcel centroids visualized by wastewater treatment type (Embayment-wide)'>
						<a href="" id="treatmenttype" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Wastewater by Treatment Type</a>
					</li>
					<li class='inLegend' title = 'Parcel centroids visualized by land use category (Embayment-wide)'>
						<a href="" id="landuse" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Land Use Category</a>
					</li>
					<li class='inLegend' title = 'Parcel centroids visualized by flow-through coefficients (Cape-wide)'>
						<a href="" id="flowthrough" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> FlowThrough Co-efficient</a>
					</li>
					<li class='inLegend' title = 'Water table contours (Cape-wide)'>
						<a href="" id="contours" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Water Table 2ft Contours</a>
					</li>					
				</ul>
			</div>
		</div>

		<div class="js-menu-screen sliding-panel-fade-screen"></div>
</div>