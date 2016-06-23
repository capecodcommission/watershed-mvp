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
		
		<p><a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="_blank">View Scenario Summary</a>
			Total Unattenuated Nitrogen: <span class="wizard-span">@{{unatt|round}}kg</span><br />
			Total Attenuated Nitrogen: <span  class="wizard-span">@{{att|round}}kg</span></p>
			<hr>
			<p>Nitrogen is treated at different entrance points:</p>
			<ul class="wizard-bullets">
				<li>Fertilizer (applied to the ground directly)</li>
				<li>Stormwater Runoff</li>
				<li>Septic</li>
				<li>Groundwater</li>
				<li>Embayment</li>
			</ul>

			<p>For each of these stages, you can select technologies to remove Nitrogen from the embayment. For some, you can select the area that will be treated by drawing a polygon on the map. Your progress towards the embayment's Target Nitrogen Removal will be displayed in the graph to the left. In addition to the overall target, each sub-embayment will have its own individual Nitrogen load and target, which you can track using the graphs in the left sidebar.</p>

			<p>At any time, you can <a href="{{url('results', session('scenarioid'))}}" target="_blank">view a summary of your scenario</a>.</p>
		
		
		</div>
	</article>
	</aside>


	<aside class="accHorizontal__item">
	<input type="radio" name="group-1" class="state" id="acc-1" />
	<label class="backdrop" for="acc-1"></label>
	<article class="acc_cBox">
		<div class="acc_cImg">
		<header>
			<h3>Fertilizer</h3><img src="http://www.cch2o.org/Matrix/icons/reduction.svg" alt="" width="60" style="display: inline;">
		</header>
		</div>
		<div class="acc_cCont">
			<p><a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="_blank">View Scenario Summary</a>
			Total Unattenuated Load from Fertilizer: <span class="wizard-span">@{{fert_unatt|round}}kg</span> <br>
			Total Attenuated Load from Fertilizer: <span class="wizard-span">@{{fert_att | round }}kg</span></p>
			<hr>
			@if(session('fert_applied')==1)
				<p>Fertilizer Management Policies have already been applied to this scenario</p>
			@else
				<div class="technology">
					<a href="/tech/fert/25" class="popdown"><img src="http://www.cch2o.org/Matrix/icons/npk_mgt.svg"></a><br />
						Fertilizer Management		
				</div>

			@endif


		</div>
	</article>
	</aside>
	
	<aside class="accHorizontal__item">
		<input type="radio" name="group-1" class="state" id="acc-2" />
		<label class="backdrop" for="acc-2"></label>
		<article class="acc_cBox">
			<div class="acc_cImg">
				<header>
					<h3>Stormwater</h3><img src="http://www.cch2o.org/Matrix/icons/remediation.svg" alt="">
				</header>
			</div>
			<div class="acc_cCont">
				<p>
				<a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="_blank">View Scenario Summary</a>
				Unattenuated Nitrogen from Stormwater: <span class="wizard-span">@{{storm_unatt|round}}kg</span> <br>
				Attenuated Nitrogen from Stormwater: <span class="wizard-span">@{{storm_att|round}}kg</span></p>
				<hr>
				
				<div class="technology_list">
					<div class="technology">
						<a href="{{url('/tech/storm/26')}}" class="popdown">
							<img src="http://www.cch2o.org/Matrix/icons/stormwater_bmps.svg"><br />
							 Stormwater Management
						</a>
					</div>
					<div class="technology">
						<a href="{{url('/tech/storm/8')}}" class="popdown">
							<img src="http://www.cch2o.org/Matrix/icons/stormwater_bmps.svg"><br />
							 Gravel Wetland
						</a>
					</div>
					<div class="technology">
						<a href="{{url('/tech/storm/9')}}"  class="popdown">
							<img src="http://www.cch2o.org/Matrix/icons/stormwater_bmps.svg"><br />
							 Bioretention/Soil Media Filters
						</a>
					</div>

					<div class="technology">
						<a href="{{url('/tech/storm/6')}}"  class="popdown">
							<img src="http://www.cch2o.org/Matrix/icons/stormwater_bmps.svg"><br />
							Phytobuffers
						</a>
					</div>
					<div class="technology">
						<a href="{{url('/tech/storm/7')}}"  class="popdown">
							<img src="http://www.cch2o.org/Matrix/icons/stormwater_bmps.svg"><br />
							Vegetated Swale
						</a>
					</div>	 
					<div class="technology">
						<a href="{{url('/tech/storm/10')}}"  class="popdown">
							<img src="http://www.cch2o.org/Matrix/icons/stormwater_bmps.svg"><br />
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
			<h3>Septic</h3><img src="http://www.cch2o.org/Matrix/icons/reduction.svg" alt="" width="60" style="display: inline;">
		</header>
		</div>
		<div class="acc_cCont">
		<p><a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="_blank">View Scenario Summary</a>
			Unattenuated Nitrogen from Septic: <span class="wizard-span">@{{septic_unatt|round}}kg</span><br>
			Attenuated Nitrogen from Septic: <span class="wizard-span">@{{septic_att|round}}kg</span></p>
			<hr>
			<div class="technology">
				<a href="{{url('/tech/collect/40')}}" class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/cluster_3.svg" alt="" ><br />
					Single-Stage Cluster</a>
			</div>
			<div class="technology">
				<a href="{{url('/tech/collect/41')}}" class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/cluster_3.svg" alt="" ><br />
					Two-Stage Cluster</a>
			</div>
			<div class="technology">
				<a href="{{url('/tech/collect/42')}}" class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/conventional_treatment.svg" alt="" ><br />
					Conventional Treatment</a>
			</div>			
			<div class="technology">
				<a href="{{url('/tech/collect/43')}}" class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/advanced_treatment.svg" alt="" ><br />
					Advanced Treatment</a>
			</div>			
			<div class="technology">
				<a href="{{url('/tech/collect/44')}}" class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/cluster_3.svg" alt="" ><br />
					Satellite Treatment</a>
			</div>	
			<div class="technology">
				<a href="{{url('/tech/collect/45')}}" class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/cluster_3.svg" alt="" ><br />
					Satellite Treatment - Enahnced</a>
			</div>	
			<div class="technology">
				<a href="{{url('/tech/septic/21')}}" class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/toilet.svg" alt="" ><br />
					Composting Toilets</a>
			</div>	
			<div class="technology">
				<a href="{{url('/tech/septic/22')}}" class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/toilet.svg" alt="" ><br />
					Incinerating Toilets</a>
			</div>	

			<div class="technology">
				<a href="{{url('/tech/septic/23')}}" class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/toilet.svg" alt="" ><br />
					Packaging Toilets</a>
			</div>		
			<div class="technology">
				<a href="{{url('/tech/septic/24')}}" class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/toilet.svg" alt="" ><br />
					Urine Diverting Toilets</a>
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
			<img src="http://www.cch2o.org/Matrix/icons/remediation.svg" alt="">
		</header>
		</div>
		<div class="acc_cCont">
			<p><a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="_blank">View Scenario Summary</a>
			Existing Nitrogen from Groundwater: (<span id="getNitrogen">Calculate Groundwater</span>)</p>
			<hr>

			<div class="technology_list">
				<div class="technology">
					<a href="{{url('/tech/groundwater/3')}}"  class="popdown">
						<img src="http://www.cch2o.org/Matrix/icons/constructed_wetland.svg" alt=""><br />
						Constructed Wetlands - Surface
					</a>
				</div>
				<div class="technology">
					<a href="{{url('/tech/groundwater/2')}}"  class="popdown">
						<img src="http://www.cch2o.org/Matrix/icons/constructed_wetland.svg" alt=""><br />
						Constructed Wetlands - Subsurface Flow
					</a>
				</div>
				<div class="technology">
					<a href="{{url('/tech/groundwater/3')}}"  class="popdown">
						<img src="http://www.cch2o.org/Matrix/icons/constructed_wetland.svg" alt=""><br />
						Constructed Wetlands - Groundwater Flow
					</a>
				</div>	
				<div class="technology">
					<a href="{{url('/tech/groundwater/14')}}"  class="popdown">
						<img src="http://www.cch2o.org/Matrix/icons/phytoremediation.svg" alt=""><br />
						Phytoremediation
					</a>
				</div>	
				<div class="technology">
					<a href="{{url('/tech/groundwater/16')}}"  class="popdown">
						<img src="http://www.cch2o.org/Matrix/icons/prb.svg" alt=""><br />
						PRB - Injection Well
					</a>
				</div>		

				<div class="technology">
					<a href="{{url('/tech/groundwater/19')}}"  class="popdown">
						<img src="http://www.cch2o.org/Matrix/icons/fertigation_wells.svg" alt=""><br />
						Fertigation Wells
					</a>
				</div>		
				<div class="technology">
					<a href="{{url('/tech/embayment/34')}}"  class="popdown">
						<img src="http://www.cch2o.org/Matrix/icons/surface_water_remediation_wetlands.svg" alt=""><br />
						Surface Water Remediation Wetlands
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
			<img src="http://www.cch2o.org/Matrix/icons/restoration.svg" alt="">
		</header>
		</div>
		<div class="acc_cCont">
			<p><a class="button--cta right" href="{{url('results', session('scenarioid'))}}" target="_blank">View Scenario Summary</a>
			Some sort of intro will go here</p>
			<hr>
			<div class="technology">
				<a href="{{url('/tech/embayment/11')}}"  class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/aquaculture_shellfishing.svg" alt=""><br />
					Acquaculture in Estuary Bed
				</a>
			</div>
			<div class="technology">
				<a href="{{url('/tech/embayment/12')}}"  class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/aquaculture_shellfishing.svg" alt=""><br />
					Acquaculture Above Estuary Bed
				</a>
			</div>
			<div class="technology">
				<a href="{{url('/tech/embayment/13')}}"  class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/aquaculture_shellfishing.svg" alt=""><br />
					Acquaculture - Mariculture
				</a>
			</div>
			<div class="technology">
				<a href="{{url('/tech/embayment/30')}}"  class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/inlet_culvert_widening.svg" alt=""><br />
					Inlet/Culvert Widening
				</a>
			</div>
			<div class="technology">
				<a href="{{url('/tech/embayment/31')}}"  class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/coastal_habitat_restoration.svg" alt=""><br />
					Coastal Habitat Restoration
				</a>
			</div>
			<div class="technology">
				<a href="{{url('/tech/embayment/32')}}"  class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/constructed_wetlands_floating.svg" alt=""><br />
					Floating Constructed Wetlands
				</a>
			</div>			
<!-- 			<div class="technology">
				<a href="{{url('/tech/embayment/33')}}"  class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/pond_estuary_circulators.svg" alt=""><br />
					Pond &amp; Estuary Circulators
				</a>
			</div> -->
			<div class="technology">
				<a href="{{url('/tech/embayment/34')}}"  class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/surface_water_remediation_wetlands.svg" alt=""><br />
					Surface Water Remediation Wetlands
				</a>
			</div>
<!-- 			<div class="technology">
				<a href="{{url('/tech/embayment/36')}}"  class="popdown">
					<img src="http://www.cch2o.org/Matrix/icons/pond_estuary_dredging.svg" alt=""><br />
					Pond &amp; Estuary Dredging
				</a>
			</div> -->
		</div>
	</article>
	</aside>
	
	<aside class="accHorizontal__item">
	<input type="radio" name="group-1" class="state" id="acc-6" />
	<label class="backdrop" for="acc-6"><!-- <i class="fa fa-times"></i> --></label>
	<article class="acc_cBox">
		<div class="acc_cImg">
		<!-- <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/308355/img-4.jpg" alt="" /> -->
		<header>
			<h3>Summary</h3>
			<!-- <p>Web Designer</p> -->
		</header>
		</div>
		<div class="acc_cCont">
		<!-- <p>Existing Nitrogen Load: kg</p> -->
		<ul>
	{{--	@foreach($treatments as $treatment)
			 <li>{{$treatment->TreatmentType_ID}} -> {{$treatment->Nload_Reduction}}kg</li> 
		@endforeach--}}
		</ul>
		<p>Overall Nitrogen Reduction: </p>
			
			<p><a href="{{url('download', session('scenarioid'))}}">Download Results (.xls)</a></p>
			<p><a href="{{url('results', session('scenarioid'))}}" class="button">View detailed results</a></p>
		</div>
	</article>
	</aside>
</section>
</div>