<div id="module-poll-mini">
	# START question #
	<form method="post" action="{PATH_TO_ROOT}/poll/poll{question.ID}" class="form-poll">
		<span class="poll-container-title">{question.QUESTION}</span>
		<hr>

		<p class="poll-question-mini-container">
			# START question.radio #
			<label class="infos-options" for="poll-radio-{question.radio.NAME}">
				<input type="radio" id="poll-radio-{question.radio.NAME}" name="radio" value="{question.radio.NAME}">
				<span class="smaller">{question.radio.ANSWERS}</span>
			</label>
			# END question.radio #

			# START question.checkbox #
			<label class="infos-options" for="poll-checkbox-{question.checkbox.NAME}">
				<input type="checkbox" id="poll-checkbox-{question.checkbox.NAME}" name="{question.checkbox.NAME}" value="{question.checkbox.NAME}">
				<span class="smaller">{question.checkbox.ANSWERS}</span>
			</label>
			# END question.checkbox #
		</p>

		<input type="hidden" name="token" value="{TOKEN}">
		<button type="submit" name="valid_poll" value="true" class="submit">{L_VOTE}</button>
		<a class="infos-options small" href="{PATH_TO_ROOT}/poll/poll{U_POLL_RESULT}">{L_POLL_RESULT}</a>
	</form>
	# END question #

	# START result #
	<span class="poll-container-title">{result.QUESTION}</span>
	<hr>

		# START result.answers #
		<p class="poll-question-mini-container">
			<span class="infos-options smaller">{result.answers.ANSWERS} : {result.answers.PERCENT}%</span>
			<div class="progressbar-container" aria-label="{result.answers.WIDTH}%">
				<div class="progressbar-infos">{result.answers.WIDTH}%</div>
				<div class="progressbar" style="width:{result.answers.WIDTH}%;"></div>
			</div>
		</p>
		# END result.answers #

	<span class="smaller">{result.VOTES} {L_VOTE}</span>
	# END result #
</div>
