<div class="selected-treatments">
	<ul id = 'stackList' class="selected-treatments">
		@foreach($treatments as $treatment)
			@if(!$treatment->Parent_TreatmentId)
				<li class="technology" data-techid="{{$treatment->TreatmentType_ID}}" data-route="/edit/{{$treatment->TreatmentID}}" data-treatment="{{$treatment->TreatmentID}}">
					<a href="" title = "{{$treatment->TreatmentID}}">
						<img src="{{$_ENV['CCC_ICONS_SVG'] . $treatment->treatment_icon}}" alt="">
					</a>
				</li>
			@endif
		@endforeach	
	</ul>
</div>