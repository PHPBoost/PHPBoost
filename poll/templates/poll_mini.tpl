<div id="module-poll-mini">
	# START question #
	<form method="post" action="{PATH_TO_ROOT}/poll/poll{question.ID}" class="form-poll">
		<span class="poll-container-title">{question.QUESTION}</span>
		<hr>
	
		<p class="poll-question-mini-container">
			# START question.radio #
			<label><input type="radio" name="radio" value="{question.radio.NAME}"> <span class="smaller">{question.radio.ANSWERS}</span></label>
			<br /><br />
			# END question.radio #
		
			# START question.checkbox #
			<label><input type="checkbox" name="{question.checkbox.NAME}" value="{question.checkbox.NAME}"> <span class="smaller">{question.checkbox.ANSWERS}</span></label>
			<br /><br />
			# END question.checkbox #
		</p>
	
		<input type="hidden" name="token" value="{TOKEN}">
		<button type="submit" name="valid_poll" value="true" class="submit">{L_VOTE}</button><br />
		<a class="small" href="{PATH_TO_ROOT}/poll/poll{U_POLL_RESULT}">{L_POLL_RESULT}</a>
	</form>
	# END question #
	
	# START result #
	<span class="poll-container-title">{result.QUESTION}</span>
	<hr>
	
		# START result.answers #
		<p class="poll-question-mini-container">
			<span class="smaller">{result.answers.ANSWERS} : {result.answers.PERCENT}%</span>
			<br />
			<div class="progressbar-container" title="{result.answers.WIDTH}%">
				<div class="progressbar-infos">{result.answers.WIDTH}%</div>
				<div class="progressbar" style="width:{result.answers.WIDTH}%;"></div>
			</div>
		</p>
		# END result.answers #
	
	<span class="smaller">{result.VOTES} {L_VOTE}</span>
	# END result #
</div>
