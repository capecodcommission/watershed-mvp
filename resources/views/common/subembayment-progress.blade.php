
<div id="progress"><br />
<img src="http://www.watershedmvp.org/Images/mvplogo.png" alt="WatershedMVP 3.0 by Cape Cod Commission"><br /><br />
	<h2>Subembayments for {{$embayment->EMBAY_DISP}}</h2>
	@foreach($subembayments as $subem)
		<div class="subembayment" id="subem_{{$subem->subem_id}}">
			<div class="sub-progress-container">
				<div class="sub-target">
					
				</div>
				<div class="sub-progress" style="width: {{($subem->n_load_target/$subem->n_load_att)*100}}%">
					
				</div>
				<h3>{{$subem->subem_disp}} (<span id="progress_{{$subem->subem_id}}">{{round(($subem->n_load_target/$subem->n_load_att)*100)}}</span>%)</h3>
			</div>
			<div class="stats">
				<div class="stat-group">
					<div class="stat-label">Original Attenuated:</div> 	<div class="stat-data">{{round($subem->n_load_att)}}kg</div>
				</div>

				<div class="stat-group">
					<div class="stat-label">Scenario Attenuated:</div> 	<div class="stat-data scenario-progress">kg</div>
				</div>

				<div class="stat-group">
					<div class="stat-label">Target:</div> 	<div class="stat-data">{{round($subem->n_load_target/1)}}kg</div>
				</div>
			</div>
		</div>
	@endforeach
</div>
