# START question #
	<form method="post" action="{PATH_TO_ROOT}/poll/poll{question.ID}" class="form-poll">
		<div class="cell-body">
			<div class="cell-content align-center"><span class="poll-container-title">{question.QUESTION}</span></div>
		</div>


		<div class="cell-list">
			<ul>
				# START question.radio #
					<li>
						<label class="infos-options radio" for="poll-radio-{question.radio.NAME}">
							<input type="radio" id="poll-radio-{question.radio.NAME}" name="radio" value="{question.radio.NAME}">
							<span class="smaller">{question.radio.ANSWERS}</span>
						</label>
					</li>
				# END question.radio #
			</ul>

			<ul>
				# START question.checkbox #
					<li>
						<label class="infos-options checkbox" for="poll-checkbox-{question.checkbox.NAME}">
							<input type="checkbox" id="poll-checkbox-{question.checkbox.NAME}" name="{question.checkbox.NAME}" value="{question.checkbox.NAME}">
							<span class="smaller">{question.checkbox.ANSWERS}</span>
						</label>
					</li>
				# END question.checkbox #
			</ul>
		</div>
		<div class="cell-body">
			<div class="cell-content grouped-inputs">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" name="valid_poll" value="true" class="button submit">{L_VOTE}</button>
				<a class="grouped-element small" href="{PATH_TO_ROOT}/poll/poll{U_POLL_RESULT}">{L_POLL_RESULT}</a>
			</div>
		</div>


	</form>
# END question #

# START result #
	<div class="cell-body">
		<div class="cell-content align-center"><span class="poll-container-title">{result.QUESTION}</span></div>
	</div>
	<div class="cell-list">
		<ul>
			<li>
				# START result.answers #
					<span class="infos-options smaller">{result.answers.ANSWERS} : {result.answers.PERCENT}%</span>
					<div class="progressbar-container" aria-label="{result.answers.WIDTH}%">
						<div class="progressbar-infos">{result.answers.WIDTH}%</div>
						<div class="progressbar" style="width:{result.answers.WIDTH}%;"></div>
					</div>
				# END result.answers #
			</li>
			<li class="align-center">{result.VOTES} {L_VOTE}</li>
		</ul>
	</div>
# END result #
