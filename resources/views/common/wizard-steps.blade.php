<div class="accordion" id="new_accordion">
        <h2 id="new_accordion_title"><b>SCENARIO CONTROLLER</b></h2>
        <section class="accordion_container">
            <div class="accordion_top_row">
                    <input type="radio" class="accordion_item_input" id="close_accordion_items" name="rd">
                    <label for="close_accordion_items" class="accordion_item_close">&times;</label>
                <div id="plotlyDiv"></div>
                <div id="accordion_top_row_button">
                    <a href="{{url('results', session('scenarioid'))}}" class="fa fa-external-link button" title="Results" target="wmvp_results_{{session('scenarioid')}}"></a>
                    <a id = 'saved' class="fa fa-save save button" title="Save"></a>
                    <a href="{{url('download', session('scenarioid'))}}" class="fa fa-download button" aria-hidden="true" title="Download"></a>
                </div>
            </div>
            <div class="accordion_item">
                <input type="radio" class="accordion_item_input" id="scenario_overview" name="rd">
                <label class="accordion_item_label" for="scenario_overview">OVERVIEW</label>
                <div class="accordion_item_content">
                    <div class="span-6">
                        <h3>
                            Nitrogen is treated at different entrance points:
                        </h3>
                        <ul class="wizard-bullets">
                            <li>Fertilizer (applied to the ground directly)</li>
                            <li>Stormwater Runoff</li>
                            <li>Septic</li>
                            <li>Groundwater</li>
                            <li>Embayment</li>
                        </ul>
                        <p>
                            For each of these stages, you can select technologies to remove Nitrogen from the embayment. For some, you can select the area that will be treated by drawing a polygon on the map. Your progress towards the embayment's Target Nitrogen Removal will be displayed in the graph to the left. In addition to the overall target, each sub-embayment will have its own individual Nitrogen load and target, which you can track using the graphs in the left sidebar.
                        </p>
                        <div>
                            <a id="fim" class="button">Financial Impact Model</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion_item">
                <input type="radio" class="accordion_item_input" id="fertilizer_management" name="rd">
                <label class="accordion_item_label" for="fertilizer_management">MANAGEMENT STRATEGIES<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_reduction.svg'}}"><img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_remediation.svg'}}"></label>
                <div class="accordion_item_content">
                    <div class="technology" id = "fertMan" data-route = "/tech/400">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FertilizerManagement.svg'}}" title="Fertilizer Management">
                        </a>
                    </div>
                    <div id = "stormMan" class="technology" data-route = "/tech/401">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterManagement.svg'}}" title="Stormwater Management">
                        </a>
                    </div>
                </div>
            </div>
            <div class="accordion_item">
                <input type="radio" class="accordion_item_input" id="stormwater_management" name="rd">
                <label class="accordion_item_label" for="stormwater_management">STORMWATER STRATEGIES<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_remediation.svg'}}"></label>
                <div class="accordion_item_content">
                    <div class="technology" data-route = "/tech/108">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterGravelWetland.svg'}}" title="Gravel Wetland">
                        </a>
                    </div>
                    <div data-route = "/tech/109" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterBioretentionSoilMediaFilters.svg'}}" title="Bioretention/Soil Media Filters">
                        </a>
                    </div>
                    <div data-route = "/tech/106" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterBMPs.svg'}}" title="Phytobuffers">
                        </a>
                    </div>
                    <div data-route = "/tech/107" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterBMPs.svg'}}" title="Vegetated Swale">
                        </a>
                    </div>
                    <div data-route = "/tech/110" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterBMPs.svg'}}" title="Constructed Wetlands">
                        </a>
                    </div>
                </div>
            </div>
            <div class="accordion_item">
                <input type="radio" class="accordion_item_input" id="septic" name="rd">
                <label class="accordion_item_label" for="septic">SEPTIC STRATEGIES<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_reduction.svg'}}"></label>
                <div class="accordion_item_content">
                    <div class="technology" data-route="/tech/603">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SingleStageCluster.svg'}}" title="Single-Stage Cluster">
                        </a>
                    </div>
                    <div class="technology" data-route="/tech/604">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_TwoStageCluster.svg'}}" title="Two-Stage Cluster">
                        </a>
                    </div>
                    <div class="technology" data-route="/tech/605">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_ConventionalTreatment.svg'}}" title="Conventional Treatment">
                        </a>
                    </div>
                    <div class="technology" data-route="/tech/606">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_AdvancedTreatment.svg'}}" title="Advanced Treatment">
                        </a>
                    </div>
                    <div class="technology" data-route="/tech/607">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SatelliteTreatment.svg'}}" title="Satellite Treatment">
                        </a>
                    </div>
                    <div class="technology" data-route="/tech/608">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SatelliteTreatmentEnhanced.svg'}}" title="Satellite Treatment - Enhanced">
                        </a>
                    </div>
                    <div data-route = "/tech/300" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_CompostingToilet.svg'}}" title="Composting Toilets">
                        </a>
                    </div>
                    <div data-route = "/tech/301" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_IncineratingToilet.svg'}}" title="Incinerating Toilets">
                        </a>
                    </div>
                    <div data-route = "/tech/302" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_PackagingToilet.svg'}}" title="Packaging Toilets">
                        </a>
                    </div>
                    <div data-route = "/tech/303" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_UrineDivertingToilet.svg'}}" title="Urine Diverting Toilets">
                        </a>
                    </div>
                    <div data-route = "/tech/601" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_IA.svg'}}" title="Innovative/Alternative (I/A) Systems">
                        </a>
                    </div>
                    <div data-route = "/tech/602" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_EnhancedIA.svg'}}" title="Innovative/Alternative (I/A) Enhanced Systems">
                        </a>
                    </div>
                </div>
            </div>
            <div class="accordion_item">
                <input type="radio" class="accordion_item_input" id="groundwater" name="rd">
                <label class="accordion_item_label" for="groundwater">GROUNDWATER STRATEGIES<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_remediation.svg'}}"></label>
                <div class="accordion_item_content">
                    <div data-route = "/tech/101" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SurfaceWaterRemediationWetlands.svg'}}" title="Constructed Wetlands - Surface Flow">	
                        </a>
                    </div>
                    <div data-route = "/tech/102" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SurfaceWaterRemediationWetlands.svg'}}" title="Constructed Wetlands - Subsurface Flow">
                        </a>
                    </div>
                    <div data-route = "/tech/103" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SurfaceWaterRemediationWetlands.svg'}}" title="Constructed Wetlands - Groundwater Flow">
                        </a>
                    </div>
                    <div data-route = "/tech/104" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_HydroponicTreatment.svg'}}" title="Hydroponic Treatment">
                        </a>
                    </div>
                    <div data-route = "/tech/105" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_Phytoremediation.svg'}}" title="Phytoirrigation">
                        </a>
                    </div>
                    <div data-route = "/tech/204" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_Phytoremediation.svg'}}" title="Phytoremediation">
                        </a>
                    </div>
                    <div data-route = "/tech/207" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FertigationWellsTurf.svg'}}" title="Fertigation Wells (Turf)">
                        </a>
                    </div>
                    <div data-route = "/tech/208" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FertigationWellsTurf.svg'}}" title="Fertigation Wells (Cranberry Bogs)">
                        </a>
                    </div>
                    <div data-route="/tech/205" class="technology" >
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_PRBTrench.svg'}}" title="PRB - Trench">
                        </a>
                    </div>
                    <div data-route="/tech/206" class="technology">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_PRBInjectionWell30.svg'}}" title="PRB - Injection Well (30')">
                        </a>
                    </div>
                </div>
            </div>
            <div class="accordion_item">
                <input type="radio" class="accordion_item_input" id="embayment" name="rd">
                <label class="accordion_item_label" for="embayment">EMBAYMENT STRATEGIES<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_restoration.svg'}}"></label>
                <div class="accordion_item_content">
                    <div class="technology" data-route="/tech/202">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_AquacultureAboveEstuaryBed.svg'}}" title="Aquaculture - Above Estuary Bed">
                        </a>
                    </div>
                    <div class="technology" data-route="/tech/203">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_AquacultureMariculture.svg'}}" title="Aquaculture - Mariculture">
                        </a>
                    </div>
                    <div class="technology" data-route="/tech/500">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_InletCulvertWidening.svg'}}" title="Inlet/Culvert Widening">
                        </a>
                    </div>
                    <div class="technology" data-route="/tech/501">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_CoastalHabitatRestoration.svg'}}" title="Coastal Habitat Restoration">	
                        </a>
                    </div>
                    <div class="technology" data-route="/tech/502">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FloatingConstructedWetlands.svg'}}" title="Floating Constructed Wetlands">
                        </a>
                    </div>
                    <div class="technology" data-route="/tech/504">
                        <a>
                            <img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SurfaceWaterRemediationWetlands.svg'}}" title="Surface Water Remediation Wetlands">
                        </a>
                    </div>
                </div>
            </div>
            <div id="info"></div>
        </section>
    </div>
    
    <script>
        $(document).ready(function(){
            // Retrieve session data values
            scenario = {{session('scenarioid')}};
            fertApplied = {{session('fert_applied')}};
            stormApplied = {{session('storm_applied')}};
            
            $('#fim').on('click', function(e) {
                // if below doesn't work, add to fim button above ----> href = "http://2016.watershedmvp.org/fim/scenario/{{session('scenarioid')}}/treatmentsDetails"
                window.open("http://www.watershedmvp.org/fim/scenario/" + scenario + "/treatmentsDetails")
            });

            // $('#sam').on('click', function(e) {
            // 	var path = "http://www.watershedmvp.org/sam/#/home";
            // 	var samSite = window.open(path + "/" + scenario);
            // 	samSite.onload = function() {
            // 		var scenarioID = scenario;
            // 		localStorage.setItem("scenarioID",scenarioID);
            // 	};
            // });

            $('.save').on('click', function(e){
                e.preventDefault();
                var url = "{{url('save')}}" + '/' + scenario;
                $.ajax({
                    method: 'GET',
                    url: url
                }).done(function(msg){
                    $('#saved').addClass('button--cta')
                });
            });
        });
    </script>
    