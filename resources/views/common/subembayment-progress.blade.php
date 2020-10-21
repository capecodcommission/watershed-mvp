
<div id="progress">
	<h2>
		<div>Subembayments for </div>
		<div>{{$embayment->EMBAY_DISP}}</div>
	</h2>
	
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
		<div class="subembayment" id="subem_{{$subem->SUBEM_ID}}" data-layer="{{$subem->SUBEM_ID}}">
			<div class="sub-progress-container">
				<div class="sub-target">
					
				</div>
				<div class="sub-progress" style="width: {{$percent}}%">
					
				</div>
				<h3>{{$subem->SUBEM_DISP}} (<span id="progress_{{$subem->SUBEM_ID}}">{{round($percent)}}</span>%)</h3>
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
