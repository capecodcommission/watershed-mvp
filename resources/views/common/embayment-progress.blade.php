<!-- This will be a Vue component -->
<?php 
	$percent = $goal/$embayment_nitrogen->Total_UnAtt;
	echo 'percent: ' . $percent;
	$percent = round($percent);

	$total = number_format(round($embayment_nitrogen->Total_UnAtt, 0));
?>
<div id="embayment_progress" style="background: #2caae4; background: -moz-linear-gradient(bottom,  #2caae4 {{$percent}}%, #f9ae1b 100%); 
	background: -webkit-linear-gradient(bottom,  #2caae4 {{$percent}}%, #f9ae1b 100%); 
	background: linear-gradient(to top,  #2caae4 {{$percent}}%, #f9ae1b 100%); 
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2caae4', endColorstr='#f9ae1b',GradientType=1 );">
	<span>
	Original Unattenuated N: {{$total}}kg <br />
	Goal: {{number_format($goal)}}kg<br />
		{{$percent}}% N reduced towards goal

	</span>
	
</div>

{{--

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
					<!-- Percent of Reduction from treatment: @{{myEffective}}%<br />
					Original Unattenuated: @{{parseFloat(NLoad_Orig)|round}}kg<br />
					Scenario Unattenuated : @{{NLoad_Current| round }}kg <br />
					Target: @{{NLoad_Target}}kg
				</p>
			</div>
		</div>
	</template> --> --}}

	{{-- 	 percent="{{$subem->Total_Tar_Kg/$subem->Nload_Total}}"   --}}
	{{-- 
		background: #2caae4; background: -moz-linear-gradient(left,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	background: -webkit-linear-gradient(left,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	background: linear-gradient(to right,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2caae4', endColorstr='#f9ae1b',GradientType=1 );
	--}}