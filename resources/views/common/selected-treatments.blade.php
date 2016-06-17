<div class="selected-treatments">
	<ul class="selected-treatments">
			@foreach($treatments as $treatment)
				<li class="technology"><img src="http://www.cch2o.org/Matrix/icons/{{$treatment->treatment_icon}}" alt=""></li>
			@endforeach	
	</ul>

</div>