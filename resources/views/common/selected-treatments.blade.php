<div class="selected-treatments">
				
	<ul class="selected-treatments">
			@foreach($treatments as $treatment)
				@if(!$treatment->Parent_TreatmentId)
					<li class="technology" data-route="{{url('/edit', $treatment->TreatmentID)}}" data-treatment="{{$treatment->TreatmentID}}">
						<a href="">
							<img src="http://www.watershedmvp.org/images/SVG/{{$treatment->treatment_icon}}" alt="">
						</a>
					</li>
				@endif
			@endforeach	
	</ul>
<button id="edit_polygon" style="float:left;">Edit Polygon</button>
<button id="save_polygon" style="display:none;float:left;">Save Polygon</button>
				<span style="visibility:hidden;"><div id="tool_move" data-dojo-type="dijit/form/ToggleButton" data-dojo-props="checked:'true', iconClass:'dijitCheckBoxIcon'">Move</div>
      <div id="tool_vertices" data-dojo-type="dijit/form/ToggleButton" data-dojo-props="checked:'true', iconClass:'dijitCheckBoxIcon'">Edit Vertices</div>
      <div id="tool_scale" data-dojo-type="dijit/form/ToggleButton" data-dojo-props="checked:'true', iconClass:'dijitCheckBoxIcon'">Scale</div>
      <div id="tool_rotate" data-dojo-type="dijit/form/ToggleButton" data-dojo-props="checked:'true', iconClass:'dijitCheckBoxIcon'">Rotate</div></span>
</div>
<style>
	#edit_polygon:focus { background-color: #ff0000; }
</style>

<!-- <script>
	$("li.technology").on('click', function (e) {

		$(this).append("<div class = 'fa fa-spinner fa-spin fa-inverse'></div>")
	})

</script> -->