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
				   </thead>
				   <tbody>
					   @foreach($user->scenarios as $scenario)
						<tr>
							<td>{{$scenario->AreaName}}</td>
							<td>{{$scenario->ScenarioID}}</td> 
							<td>{{date('Y-m-d', strtotime($scenario->CreateDate))}}</td>
							<td><a href="{{url('map', [$scenario->AreaID, $scenario->ScenarioID])}}" ><i class="fa fa-globe"></i> Wizard</a></td> 
							<td><a href="{{url('results', $scenario->ScenarioID)}}" ><i class="fa fa-list"></i> Details</a></td>
							<td><a href="{{url('download', $scenario->ScenarioID)}}" target="_blank"><i class="fa fa-download"></i> Download</a></td>
						</tr>
					   @endforeach
					</tbody>
				   </table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
