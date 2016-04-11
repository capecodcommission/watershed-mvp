<!-- This will be a Vue component -->
@foreach($subembayments as $subem)
	<subembayment 
		title="{{$subem->SUBEM_DISP}}" 
		percent="{{$subem->Total_Tar_Kg/$subem->Nload_Total}}" 
		NLoad_Orig = '{{$subem->Nload_Total}}' 
		NLoad_Target='{{$subem->Total_Tar_Kg}}' 
		:my-effective="effective" >
	</subembayment>
@endforeach