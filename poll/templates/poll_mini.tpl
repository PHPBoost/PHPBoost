		# START question #		
		<form method="post" action="../poll/poll{question.ID}" class="normal_form">
			<div class="module_mini_container">
				<div class="module_mini_top">
					<h5 class="sub_title">{L_MINI_POLL}</h5>
				</div>
				<div class="module_mini_table" style="text-align:center">
					<span style="font-size:10px;">{question.QUESTION}</span>

					<hr style="width:90%;" />
					<p style="padding-left: 6px;text-align: left;">		
						# START radio #
						<label><input type="{question.radio.TYPE}" name="radio" value="{question.radio.NAME}" /> <span class="text_small">{question.radio.ANSWERS}</span></label>
						<br /><br />	
						# END radio #
					
						# START checkbox #
						<label><input type="{question.checkbox.TYPE}" name="{question.checkbox.NAME}" value="{question.checkbox.NAME}" /> <span class="text_small">{question.checkbox.ANSWERS}</span></label>
						<br /><br />	
						# END checkbox #
					</p>
					<p>
						<input class="submit" name="valid_poll" type="submit" value="{L_VOTE}" /><br />
						<a class="small_link" href="../poll/poll{U_POLL_RESULT}">{L_POLL_RESULT}</a>
					</p>
				</div>	
				<div class="module_mini_bottom">
				</div>
			</div>		
		</form>	
		# END question #	

		# START result #
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_MINI_POLL}</h5>
			</div>
			<div class="module_mini_table" style="text-align:center">			
				<span style="font-size:10px;">{result.QUESTION}</span>
				
				<hr style="width:90%;" />
				# START answers #					
				<p style="padding-left: 6px;text-align: left;">
					<span class="text_small">{result.answers.ANSWERS}</span>
					<br />
					<img src="../templates/{THEME}/images/poll_left.png" height="8px" width="" alt="{result.answers.WIDTH}%" title="{result.answers.WIDTH}%" /><img src="../templates/{THEME}/images/poll.png" height="8px" width="{result.answers.WIDTH}" alt="{result.answers.WIDTH}%" title="{result.answers.WIDTH}%" /><img src="../templates/{THEME}/images/poll_right.png" height="8px" width="" alt="{result.answers.WIDTH}%" title="{result.answers.WIDTH}%" />
					<span class="text_small">
						{result.answers.PERCENT}%
					</span>
				</p>			
				# END answers #
				
				<div class="text_small" style="padding-left: 8px;padding-top: 10px;">
					{result.VOTES} {L_VOTE}
				</div>
			</div>		
			<div class="module_mini_bottom">
			</div>
		</div>
		# END result #
	
		<br /><br />
		