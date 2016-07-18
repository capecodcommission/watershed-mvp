@if (count($errors) > 0)
	<div data-alert class="flash-error rounded">
		<h3>There were errors with your submission. Please see highlighted fields below.</h3>
		<ul>
		  @foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		  @endforeach
		
		</ul>

		
	</div>
@endif