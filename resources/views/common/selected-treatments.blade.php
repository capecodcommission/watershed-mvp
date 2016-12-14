<div class="selected-treatments">
				
	<ul class="selected-treatments">
			@foreach($treatments as $treatment)
				@if(!$treatment->Parent_TreatmentId)
					<li class="technology" data-treatment="{{$treatment->TreatmentID}}">
						<a href="{{url('/edit', $treatment->TreatmentID)}}" class="popdown">
							<img src="http://www.cch2o.org/Matrix/icons/{{$treatment->treatment_icon}}" alt="">
						</a>
					</li>
				@endif
			@endforeach	
	</ul>
<button id="edit_polygon" style="float:right;">Edit Polygon</button>
<button id="save_polygon" style="display:none;float:right;">Save Polygon</button>
				<span style="visibility:hidden;"><div id="tool_move" data-dojo-type="dijit/form/ToggleButton" data-dojo-props="checked:'true', iconClass:'dijitCheckBoxIcon'">Move</div>
      <div id="tool_vertices" data-dojo-type="dijit/form/ToggleButton" data-dojo-props="checked:'true', iconClass:'dijitCheckBoxIcon'">Edit Vertices</div>
      <div id="tool_scale" data-dojo-type="dijit/form/ToggleButton" data-dojo-props="checked:'true', iconClass:'dijitCheckBoxIcon'">Scale</div>
      <div id="tool_rotate" data-dojo-type="dijit/form/ToggleButton" data-dojo-props="checked:'true', iconClass:'dijitCheckBoxIcon'">Rotate</div></span>
</div>
<style>
	#edit_polygon:focus { background-color: #ff0000; }
</style>