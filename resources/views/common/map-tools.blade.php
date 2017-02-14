<div class="tool" style="padding: .8em 1em;">		
<label><i class="fa fa-globe fa-2x js-menu-trigger sliding-panel-button"></i>
</label>
    <div style="position:absolute; right:80px; top:20px; z-Index:100;">
        <div data-dojo-type="dijit/TitlePane"
             data-dojo-props="title:'Switch Basemap', closable:false, open:false">
            <div data-dojo-type="dijit/layout/ContentPane" style="width:380px; height:280px; overflow:auto;">
                <div id="basemapGallery"></div>
            </div>
        </div>

        <div id="basemaps-wrapper" class="leaflet-bar">
 <!--  <select name="basemaps" id="basemaps" onChange="changeBasemap(basemaps)">
    <option value="Topographic">Topographic<options>
    <option value="Streets">Streets</option>
    <option value="NationalGeographic">National Geographic<options>
    <option value="Oceans">Oceans<options>
    <option value="Gray">Gray<options>
    <option value="DarkGray">Dark Gray<options>
    <option value="Imagery">Imagery<options>
    <option value="ShadedRelief">Shaded Relief<options>
  </select> -->
		</div>
    </div>
    <div style="position:absolute; right:250px; top:20px; z-Index:100;">
        <div data-dojo-type="dijit/TitlePane"
             data-dojo-props="title:'Legend', closable:false, open:false">
            <div data-dojo-type="dijit/layout/ContentPane" style="width:380px; height:280px; overflow:auto;">
                <div id="legendDiv"></div>
            </div>
        </div>
    </div>
		<div class="js-menu sliding-panel-content is-visible">
<!-- 		<div id="info">
			<button id="Polygon">Draw Polygon</button>
		</div> -->
		
			<div class="info"  data-dojo-type="dijit/layout/ContentPane">
				
			<h4>Map Layers</h4>


				<ul id="layers">
					<li>
						<a id="towns" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Towns</a>
					</li>
					<li>
						<a id="subembayments" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Subembayments</a>
					</li>
					<li>
						<a href="" id="subwatersheds" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Subwatersheds</a>
					</li>					

					<li>
						<a href="" id="treatmentfacilities" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Wastewater Treatment Facilities</a>
					</li>
					<li>
						<a href="" id="ecologicalindicators" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Ecological Indicators</a>
					</li>
					<li>
						<a href="" id="shallowgroundwater" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Depth to Groundwater < 20ft</a>
					</li>
					<li>
						<a href="" id="nitrogen" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Existing Nitrogen Load</a>				
					</li>
					<li>
						<a href="" id="wastewater" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Wastewater</a>
					</li>
					<li>
						<a href="" id="treatmenttype" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Wastewater by Treatment Type</a>
					</li>
					<li>
						<a href="" id="landuse" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> Land Use Category</a>
					</li>
					<li>
						<a href="" id="flowthrough" data-visible="off"><i class="fa fa-eye-slash"></i> <i class="fa fa-eye"></i> FlowThrough Co-efficient</a>
					</li>					

				</ul>
			</div>
		</div>

		<div class="js-menu-screen sliding-panel-fade-screen"></div>
</div>