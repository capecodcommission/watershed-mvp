<!-- This will be a Vue component -->
<div id="progress"><br />
<img src="http://www.watershedmvp.org/Images/mvplogo.png" alt="WatershedMVP 3.0 by Cape Cod Commission"><br /><br />
	<h2>Subembayments for {{$embayment->EMBAY_DISP}}</h2>
	@foreach($subembayments as $subem)
		<subembayment 
			title="{{$subem->subem_disp}}" 
			percent="{{$subem->Total_Tar_Kg/$subem->AttenFull}}" 
		{{--	 NLoad_Orig = {{$subem->AttenFull}} 
			Looks like adding up the attenuated N load for the subembayments doesn't match the value stored in Nload_Total
			So we're going to use Nload_Total since that is what we are using to compare on the Results page

		--}}
			NLoad_Orig = {{$subem->Nload_Total}}
			NLoad_Target= {{$subem->Total_Tar_Kg/1}}
			id="{{$subem->subem_name}}"
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
					Target: @{{NLoad_Target}}kg
				</p>
			</div>
		</div>
	</template>

	{{-- 	 percent="{{$subem->Total_Tar_Kg/$subem->Nload_Total}}"   --}}
	{{-- 
		background: #2caae4; background: -moz-linear-gradient(left,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	background: -webkit-linear-gradient(left,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	background: linear-gradient(to right,  #2caae4 @{{percent | round}}%, #f9ae1b 100%); 
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2caae4', endColorstr='#f9ae1b',GradientType=1 );
	--}}