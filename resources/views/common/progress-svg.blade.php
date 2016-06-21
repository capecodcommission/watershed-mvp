		<div id="overall_progress">
			
		
			<h4 id="update">Scenario Progress</h4>

			<div class="progress-container">
				<div class="target"></div>
				<div class="progress">
					<h3></h3>
					
				</div>
			</div>
		</div>
		<script>
			var progress;
			progress = {{$progress}};

			$('div.progress').css('height', progress+'%');
			$('div.progress h3').text(progress + '%');
			$('#update').on('click', function(e){
				var url= '/getScenarioProgress';

				$.ajax({
						method: 'GET',
						url: url
					})
						.done(function(msg){
							progress = msg;
							$('div.progress h3').text(progress + '%');

							$('div.progress').animate({'height': progress+'%'}, 500);
							
						})
			});
		</script>