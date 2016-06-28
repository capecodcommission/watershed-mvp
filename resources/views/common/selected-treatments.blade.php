<div class="selected-treatments">
	<ul class="selected-treatments">
			@foreach($treatments as $treatment)
				<li class="technology" data-treatment="{{$treatment->TreatmentID}}"><a href="{{url('/edit', $treatment->TreatmentID)}}" class="popdown"><img src="http://www.cch2o.org/Matrix/icons/{{$treatment->treatment_icon}}" alt=""></a></li>
			@endforeach	
	</ul>

</div>