<!-- This will be a Vue component -->
<div id="progress"><br />
<img src="http://www.watershedmvp.org/Images/mvplogo.png" alt="WatershedMVP 3.0 by Cape Cod Commission"><br /><br />
	<h2>Subembayments for {{$embayment->EMBAY_DISP}}</h2>
	@foreach($subembayments as $subem)
		<subembayment 
			title="{{$subem->subem_disp}}" 
			percent="{{$subem->n_load_target/$subem->n_load_att}}" 
			NLoad_Orig = {{$subem->n_load_att}}
			NLoad_Target= {{$subem->n_load_target/1}}
			{{-- id="{{$subem->subem_name}}" --}}
			:my-effective="effective" >
		</subembayment>
	@endforeach
</div>

<template id="subembayment-template">
		<div class="subembayment" id="@{{id}}">
			<span style="background: #2caae4; background: -moz-linear-gradient(left,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	background: -webkit-linear-gradient(left,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	background: linear-gradient(to right,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2caae4', endColorstr='#f9ae1b',GradientType=1 );">
				@{{title}} (@{{percent | round }}%)
			</span>
			<div class="stats">
				<p>
					<!-- Percent of Reduction from treatment: @{{myEffective}}%<br /> -->
					Original Attenuated: @{{parseFloat(NLoad_Orig)|round}}kg<br />
					Scenario Attenuated : @{{NLoad_Current| round }}kg <br />
					Target: @{{NLoad_Target| round }}kg
				</p>
			</div>
		</div>
	</template>