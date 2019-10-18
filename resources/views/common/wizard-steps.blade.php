	<style>
		#closeACC { padding: .5em; background-color:  }
	</style>

	<div class="accordion">
		<span id="closeACC" class="button--cta">X</span>

<section class="row accHorizontal">
	<aside class="accHorizontal__item">
	<input type="radio" name="group-1" class="state" id="acc-0" />
	<label class="backdrop" for="acc-0"></label>
	<article class="acc_cBox">
		<div class="acc_cImg">
			<header>
				<h3>Overview</h3>
			</header>
		</div>
		<div class="acc_cCont">

		<p><a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="wmvp_results_{{session('scenarioid')}}">View Scenario Summary</a>
			<span class="remaining"><strong>Nitrogen Remaining to Threshold:</strong> <span></span>kg</span>
			</p>
			<hr>
			<fieldset class="right"><p>Want to choose a create a new scenario <br />or select a different embayment? <br /><a href="{{url('/')}}" class="button--cta">Start Over</a></p></fieldset>
			<p>Nitrogen is treated at different entrance points:</p>
			<ul class="wizard-bullets">
				<li>Fertilizer (applied to the ground directly)</li>
				<li>Stormwater Runoff</li>
				<li>Septic</li>
				<li>Groundwater</li>
				<li>Embayment</li>
			</ul>

			<p>For each of these stages, you can select technologies to remove Nitrogen from the embayment. For some, you can select the area that will be treated by drawing a polygon on the map. Your progress towards the embayment's Target Nitrogen Removal will be displayed in the graph to the left. In addition to the overall target, each sub-embayment will have its own individual Nitrogen load and target, which you can track using the graphs in the left sidebar.</p>

		</div>
	</article>
	</aside>


	<aside class="accHorizontal__item">
	<input type="radio" name="group-1" class="state" id="acc-1" />
	<label class="backdrop" for="acc-1"></label>
	<article class="acc_cBox">
		<div class="acc_cImg">
		<header>
			<h3>Fertilizer</h3><img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_reduction.svg'}}" alt="" width="60" style="display: inline;">
		</header>
		</div>
		<div class="acc_cCont">
			<p><a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="wmvp_results_{{session('scenarioid')}}">View Scenario Summary</a>
			<span class="remaining"><strong>Nitrogen Remaining to Threshold:</strong> <span></span>kg</span>
			</p>
			<hr>
			<!-- <div  v-on:click="updateClickedValue($event)" v-class="active: isActive" id = 'fertMan' class="technology"> -->
			<div id = 'fertMan' class="technology" data-route = "/tech/400">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FertilizerManagement.svg'}}">
				</a>
				<br />
				Fertilizer Management
			</div>
		</div>
	</article>
	</aside>

	<aside class="accHorizontal__item">
		<input type="radio" name="group-1" class="state" id="acc-2" />
		<label class="backdrop" for="acc-2"></label>
		<article class="acc_cBox">
			<div class="acc_cImg">
				<header>
					<h3>Stormwater</h3><img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_remediation.svg'}}" alt="">
				</header>
			</div>
			<div class="acc_cCont">
				<p>
				<a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="wmvp_results_{{session('scenarioid')}}">View Scenario Summary</a>
				<span class="remaining"><strong>Nitrogen Remaining to Threshold:</strong> <span></span>kg</span>
				</p>
				<hr>

				<div class="technology_list">
					<div id = "stormMan" class="technology" data-route = "/tech/401">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterManagement.svg'}}"><br />
							 Stormwater Management
						</a>
					</div>
					<div class="technology" data-route = "/tech/108">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterGravelWetland.svg'}}"><br />
							 Gravel Wetland
						</a>
					</div>
					<div data-route = "/tech/109" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterBioretentionSoilMediaFilters.svg'}}"><br />
							 Bioretention/Soil Media Filters
						</a>
					</div>

					<div data-route = "/tech/106" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterBMPs.svg'}}"><br />
							Phytobuffers
						</a>
					</div>
					<div data-route = "/tech/107" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterBMPs.svg'}}"><br />
							Vegetated Swale
						</a>
					</div>
					<div data-route = "/tech/110" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_StormwaterBMPs.svg'}}"><br />
							Constructed Wetlands
						</a>
					</div>
					<div id="info">

					</div>
				</div>

			</div>
		</article>
	</aside>

	<aside class="accHorizontal__item">
	<input type="radio" name="group-1" class="state" id="acc-3" />
	<label class="backdrop" for="acc-3"><!-- <i class="fa fa-times"></i> --></label>
	<article class="acc_cBox">
		<div class="acc_cImg">

		<header>
			<h3>Septic</h3><img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_reduction.svg'}}" alt="" width="60" style="display: inline;">
		</header>
		</div>
		<div class="acc_cCont">
		<p><a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="wmvp_results_{{session('scenarioid')}}">View Scenario Summary</a>
			<span class="remaining"><strong>Nitrogen Remaining to Threshold:</strong> <span></span>kg</span>
			</p>
			<hr>
			<div class="technology" data-route="/tech/603">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SingleStageCluster.svg'}}" alt="" ><br />
					Single-Stage Cluster</a>
			</div>
			<div class="technology" data-route="/tech/604">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_TwoStageCluster.svg'}}" alt="" ><br />
					Two-Stage Cluster</a>
			</div>
			<div class="technology" data-route="/tech/605">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_ConventionalTreatment.svg'}}" alt="" ><br />
					Conventional Treatment</a>
			</div>
			<div class="technology" data-route="/tech/606">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_AdvancedTreatment.svg'}}" alt="" ><br />
					Advanced Treatment</a>
			</div>
			<div class="technology" data-route="/tech/607">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SatelliteTreatment.svg'}}" alt="" ><br />
					Satellite Treatment</a>
			</div>
			<div class="technology" data-route="/tech/608">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SatelliteTreatmentEnhanced.svg'}}" alt="" ><br />
					Satellite Treatment - Enahnced</a>
			</div>
			<div data-route = "/tech/300" class="technology">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_CompostingToilet.svg'}}"><br />
					Composting Toilets
				</a>
			</div>
			<div data-route = "/tech/301" class="technology">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_IncineratingToilet.svg'}}"><br />
					Incinerating Toilets
				</a>
			</div>
			<div data-route = "/tech/302" class="technology">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_PackagingToilet.svg'}}"><br />
					Packaging Toilets
				</a>
			</div>
			<div data-route = "/tech/303" class="technology">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_UrineDivertingToilet.svg'}}"><br />
					Urine Diverting Toilets
				</a>
			</div>
			<div data-route = "/tech/601" class="technology">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_IA.svg'}}"><br />
					Innovative/Alternative (I/A) Systems
				</a>
			</div>
			<div data-route = "/tech/602" class="technology">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_EnhancedIA.svg'}}"><br />
					Innovative/Alternative (I/A) Enhanced Systems
				</a>
			</div>
		</div>
	</article>
	</aside>

	<aside class="accHorizontal__item">
	<input type="radio" name="group-1" class="state" id="acc-4" />
	<label class="backdrop" for="acc-4"><!-- <i class="fa fa-times"></i> --></label>
	<article class="acc_cBox">
		<div class="acc_cImg">
		<header>
			<h3>Groundwater</h3>
			<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_remediation.svg'}}" alt="">
		</header>
		</div>
		<div class="acc_cCont">
			<p><a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="wmvp_results_{{session('scenarioid')}}">View Scenario Summary</a>
			<span class="remaining"><strong>Nitrogen Remaining to Threshold:</strong> <span></span>kg</span></p>
			<hr>

			<div class="technology_list">
				<!-- <div class="technology"> -->
					<div data-route = "/tech/101" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SurfaceWaterRemediationWetlands.svg'}}"><br />
							Constructed Wetlands - Surface Flow
						</a>
					</div>
					<div data-route = "/tech/102" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SurfaceWaterRemediationWetlands.svg'}}"><br />
							Constructed Wetlands - Subsurface Flow
						</a>
					</div>
					<div data-route = "/tech/103" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SurfaceWaterRemediationWetlands.svg'}}"><br />
							Constructed Wetlands - Groundwater Flow
						</a>
					</div>
					<div data-route = "/tech/104" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_HydroponicTreatment.svg'}}"><br />
							Hydroponic Treatment
						</a>
					</div>
					<div data-route = "/tech/105" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_Phytoremediation.svg'}}"><br />
							Phytoirrigation
						</a>
					</div>
					<div data-route = "/tech/204" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_Phytoremediation.svg'}}"><br />
							Phytoremediation
						</a>
					</div>
					<div data-route = "/tech/207" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FertigationWellsTurf.svg'}}"><br />
							Fertigation Wells (Turf)
						</a>
					</div>
					<div data-route = "/tech/208" class="technology">
						<a>
							<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FertigationWellsTurf.svg'}}"><br />
							Fertigation Wells (Cranberry Bogs)
						</a>
					</div>
				<div class="technology" data-route="/tech/15">
					<a>
						<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_PRBTrench.svg'}}" alt=""><br />
						PRB - Trench
					</a>
				</div>
				<div class="technology" data-route="/tech/16">
					<a>
						<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_PRBInjectionWell30.svg'}}" alt=""><br />
						PRB - Injection Well (30')
					</a>
				</div>
			</div>
		</div>
	</article>
	</aside>
	<aside class="accHorizontal__item">
	<input type="radio" name="group-1" class="state" id="acc-5" />
	<label class="backdrop" for="acc-5"><!-- <i class="fa fa-times"></i> --></label>
	<article class="acc_cBox">
		<div class="acc_cImg">
		<header>
			<h3>Embayment</h3>
			<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_restoration.svg'}}" alt="">
		</header>
		</div>
		<div class="acc_cCont">
			<p><a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="wmvp_results_{{session('scenarioid')}}">View Scenario Summary</a>
			</p>
			<hr>
			<div class="technology" data-route="/tech/202">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_AquacultureAboveEstuaryBed.svg'}}" alt=""><br />
					Aquaculture Above Estuary Bed
				</a>
			</div>
			<div class="technology" data-route="/tech/203">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_AquacultureMariculture.svg'}}" alt=""><br />
					Aquaculture - Mariculture
				</a>
			</div>
			<div class="technology" data-route="/tech/500">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_InletCulvertWidening.svg'}}" alt=""><br />
					Inlet/Culvert Widening
				</a>
			</div>
			<div class="technology" data-route="/tech/501">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_CoastalHabitatRestoration.svg'}}" alt=""><br />
					Coastal Habitat Restoration
				</a>
			</div>
			<div class="technology" data-route="/tech/502">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_FloatingConstructedWetlands.svg'}}" alt=""><br />
					Floating Constructed Wetlands
				</a>
			</div>
			<div class="technology" data-route="/tech/504">
				<a>
					<img src="{{$_ENV['CCC_ICONS_SVG'].'Icon_SurfaceWaterRemediationWetlands.svg'}}" alt=""><br />
					Surface Water Remediation Wetlands
				</a>
			</div>
		</div>
	</article>
	</aside>

	<aside class="accHorizontal__item">
	<input type="radio" name="group-1" class="state" id="acc-6" />
	<label class="backdrop" for="acc-6"><!-- <i class="fa fa-times"></i> --></label>
	<article class="acc_cBox">
		<div class="acc_cImg">

		<header>
			<h3>Summary</h3>

		</header>
		</div>
		<div class="acc_cCont">
		<p><span class="remaining"><strong>Nitrogen Remaining to Threshold:</strong> <span></span>kg</span></p>

		<!-- <p>Overall Nitrogen Reduction: </p> -->

			<p><a href="{{url('download', session('scenarioid'))}}">Download Results (.xls)</a></p>
			<p><a href="{{url('results', session('scenarioid'))}}" class="button" target="wmvp_results_{{session('scenarioid')}}">View detailed results</a></p>
			<p><a id = 'fim' class = 'button'>Open Scenario in Financial Impact Model (FIM)</a></p>
			<p><a id = 'sam' class = 'button'>Open Scenario in Scenario Assessment Model (SAM)</a></p>
			<p><a id = 'saved' class="save button">Save Changes</a></p>
		</div>
	</article>
	</aside>
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

		$('#sam').on('click', function(e) {

			var path = "http://www.watershedmvp.org/sam/#/home";
			var samSite = window.open(path + "/" + scenario);

			samSite.onload = function() {
				var scenarioID = scenario;
				localStorage.setItem("scenarioID",scenarioID);
			};

		});

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
