<!-- This will be a Vue component -->
<div id="progress">
	<h2>Subembayments for {{$embayment->EMBAY_DISP}}</h2>
	@foreach($subembayments as $subem)
		<subembayment 
			title="{{$subem->SUBEM_DISP}}" 
			percent="{{$subem->Total_Tar_Kg/$subem->Nload_Total}}" 
			NLoad_Orig = '{{$subem->Nload_Total}}' 
			NLoad_Target='{{$subem->Total_Tar_Kg}}' 
			id="{{$subem->SUBEM_NAME}}"
			:my-effective="effective" >
		</subembayment>
	@endforeach
</div>

<template id="subembayment-template">
		<div class="subembayment" id="@{{id}}">
			<span style="/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#2caae4+0,f9ae1b+66 */
	background: #2caae4; /* Old browsers */
	background: -moz-linear-gradient(left,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); /* FF3.6-15 */
	background: -webkit-linear-gradient(left,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); /* Chrome10-25,Safari5.1-6 */
	background: linear-gradient(to right,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2caae4', endColorstr='#f9ae1b',GradientType=1 ); /* IE6-9 */
	">@{{title}} (@{{percent | round }}%)</span>
			<div class="stats">
				<p>
					Percent of Reduction from treatment: @{{myEffective}}%<br />
					Original Unattenuated Nitrogen: @{{NLoad_Orig}}kg<br />
					Current Unattenuated Scenario: @{{NLoad_Current| round }}kg <br />
					Target: @{{NLoad_Target}}kg
				</p>
			</div>
		</div>
	</template>