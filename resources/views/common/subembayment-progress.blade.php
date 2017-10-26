
<div id="progress"><br />
<img src="http://www.watershedmvp.org/images/mvplogo.png" alt="WatershedMVP 3.0 by Cape Cod Commission"><br /><br />
	<h2>Subembayments for {{$embayment->EMBAY_DISP}}</h2>
	
	@foreach($subembayments as $subem)
	<?php 

		if ($subem->n_load_target == 0 and $subem->n_load_att == 0) 
		{
			$percent = 100;
		}
		else
		{
			$percent = $subem->n_load_target / ($subem->n_load_att - $subem->n_load_att_removed);
		}

		if($percent < 1 and $percent > 0)
		{
			$percent = $percent * 100;
		}
		else
		{
			$percent = 100;
		}

	?>
		<div class="subembayment" id="subem_{{$subem->subem_id}}" data-layer="{{$subem->subem_id}}">
			<div class="sub-progress-container">
				<div class="sub-target">
					
				</div>
				<div class="sub-progress" style="width: {{$percent}}%">
					
				</div>
				<h3>{{$subem->subem_disp}} (<span id="progress_{{$subem->subem_id}}">{{round($percent)}}</span>%)</h3>
			</div>
			<div class="stats">
				<div class="stat-group">
					<div class="stat-label">Original Attenuated:</div> 	<div class="stat-data">{{round($subem->n_load_att)}}kg</div>
				</div>

				<div class="stat-group">
					<div class="stat-label">Scenario Attenuated:</div> 	<div class="stat-data scenario-progress">{{round($subem->n_load_att - $subem->n_load_att_removed)}}kg</div>
				</div>

				<div class="stat-group">
					<div class="stat-label">Threshold:</div> 	<div class="stat-data">{{round($subem->n_load_target/1)}}kg</div>
				</div>
			</div>
		</div>
	@endforeach
</div>
