<div class="selected-treatments">
	<ul id = 'stackList' class="selected-treatments">
		@foreach($treatments as $treatment)
			@if(!$treatment->Parent_TreatmentId)
				<li class="technology" data-route="/edit/{{$treatment->TreatmentID}}" data-treatment="{{$treatment->TreatmentID}}">
					<a href="" title = "{{$treatment->TreatmentID}}">
						<img src="http://www.cch2o.org/Matrix/icons/{{$treatment->treatment_icon}}" alt="">
					</a>
				</li>
			@endif
		@endforeach	
	</ul>
	<button id="edit_polygon" style="float:left;">Edit Geometry</button>
	<button id="save_polygon" style="display:none;float:left;">Save Geometry</button>
</div>
<style>
	#edit_polygon:focus { background-color: #ff0000; }
</style>