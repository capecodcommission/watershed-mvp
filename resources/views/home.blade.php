@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				

				<div class="panel-body">
				   <h2>Your Saved Scenarios</h2>
				   <table>
				   <thead>
						<th>Area</th>
						<th>ScenarioID</th>
						<th>Created On</th>
						<th>View Wizard</th>
						<th>View Details</th>
						<th>Download (.xls)</th>
						<th>Delete</th>
				   </thead>
				   <tbody>
					   @foreach($user->scenarios as $scenario)
						<tr id="scenario_{{$scenario->ScenarioID}}">
							<td>{{$scenario->AreaName}}</td>
							<td>{{$scenario->ScenarioID}}</td> 
							<!-- TODO: Format date from postgres. Causes "trailing data" error when parsing from postgres container -->
							<td>{{date('Y-m-d', strtotime($scenario->CreateDate))}}</td>
							<td><a href="{{url('map', [$scenario->AreaID, $scenario->ScenarioID])}}" ><i class="fa fa-globe"></i> Wizard</a>
							</td> 
							<td><a href="{{url('results', $scenario->ScenarioID)}}" ><i class="fa fa-list"></i> Details</a></td>
							{{--
								would be nice to have a flag for incomplete/empty scenarios but this adds a significant amount of time to the page load
								@if(count($scenario->treatments) < 1) <i class="fa fa-exclamation-circle"></i>@endif
							--}}
							<td><a href="{{url('download', $scenario->ScenarioID)}}" target="_blank"><i class="fa fa-download"></i> Download</a></td>
							<td>
								<a id="delete_{{$scenario->ScenarioID}}" data-scenario="{{$scenario->ScenarioID}}" class="deletescenario button--cta"><i class="fa fa-trash-o" title="Delete this scenario?"></i> </a>
								<a id="confirm_{{$scenario->ScenarioID}}" class="confirmdelete button--cta"><i class="fa fa-check" title="confirm delete?"></i> </a>
								<a id="cancel_{{$scenario->ScenarioID}}" class="canceldelete button"><i class="fa fa-undo" title="cancel"></i> </a>
							</td>
						</tr>
					   @endforeach
					</tbody>
				   </table>
				   {{-- <p><i class="fa fa-exclamation-circle"></i>: Scenarios do not have any treatments applied </p> --}}
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.confirmdelete, .canceldelete { display: none; }
</style>
	<script>
	$(document).ready(function(){
		// Remove scrolling from body if routed from login to home
		$('#app-layout').removeClass('scrollable');

		$('.deletescenario').on('click', function(e){
			var scenario = $(this).data('scenario');
			e.preventDefault();
			$('#scenario_'+scenario).css('background', '#dddddd');
			$('#scenario_'+scenario).css('border-top', '2px solid #999999');
			$('#scenario_'+scenario).css('border-bottom', '2px solid #999999');

			$(this).hide();
			$('#confirm_'+scenario).show();
			$('#cancel_'+scenario).show();
			$('#confirm_'+scenario).on('click', function(f)
			{
				f.preventDefault();
							
				var url = "{{url('delete_scenario')}}" + '/' + scenario;
				$.ajax({
					method: 'GET',
					url: url
				})
					.done(function(msg){
						if(msg > 0)
						{
							$('#scenario_'+scenario).fadeOut('slow').delay(4000).remove();
							
						}	
						else
						{
							alert("You don't have permission for this action.");
						}
					});
				});
			$('#cancel_'+scenario).on('click', function(g)
			{
				$('#scenario_'+scenario).css('background', 'transparent');
				$('#scenario_'+scenario).css('border-top', '0px solid #999999');
				$('#scenario_'+scenario).css('border-bottom', '0px solid #999999');
				$('#confirm_'+scenario).hide();
				$('#cancel_'+scenario).hide();
				$('#delete_'+scenario).show();
			});
		});

			
	});
	</script>
@endsection
