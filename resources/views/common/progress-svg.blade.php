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
			$('div.progress h3').text(progress + '%');
			if(progress >= 100)
			{
				progress = 100;
			}
			
			$('div.progress').css('height', progress+'%');

			$('#update').on('click', function(e){
				var url= '/getScenarioProgress';

				$.ajax({
						method: 'GET',
						url: url
					})
						.done(function(msg){
							// console.log(msg);
							progress = msg.embayment;
							// console.log(progress);

							$('div.progress h3').text(progress + '%');
							if(progress > 100)
							{
								progress = 100;
							}	

							$('div.progress').animate({'height': progress+'%'}, 500);

							subembayments = msg.subembayments;
							$.each(subembayments, function(key, value)
							{
								// console.log(value);
								var sub_progress = Math.round((value.n_load_target/value.n_load_scenario) * 100);
								$('#progress_'+value.subem_id).text(sub_progress);
								if (sub_progress > 100) 
								{
									sub_progress = 100;
								}
								$('#subem_'+value.subem_id + ' .sub-progress').animate({'width': sub_progress+'%'}, 500);
								$('#subem_'+value.subem_id + ' .stats .stat-data.scenario-progress').text(Math.round(value.n_load_scenario)+'kg');
							});
							
						})
			});
		</script>