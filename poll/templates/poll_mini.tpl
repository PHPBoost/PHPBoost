		# START question #
		<form method="post" action="{PATH_TO_ROOT}/poll/poll{question.ID}" class="normal_form">
			<div class="module-mini-container">
				<div class="module-mini-top">
					<h5 class="sub-title">{L_MINI_POLL}</h5>
				</div>
				<div class="module-mini-contents" style="text-align:center">
					<span style="font-size:10px;">{question.QUESTION}</span>

					<hr style="width:90%;" />
					<br />
					<p style="padding-left: 6px;text-align: left;">
						# START question.radio #
						<label><input type="radio" name="radio" value="{question.radio.NAME}"> <span class="smaller">{question.radio.ANSWERS}</span></label>
						<br /><br />
						# END question.radio #
					
						# START question.checkbox #
						<label><input type="checkbox" name="{question.checkbox.NAME}" value="{question.checkbox.NAME}"> <span class="smaller">{question.checkbox.ANSWERS}</span></label>
						<br /><br />
						# END question.checkbox #
					</p>
					<button type="submit" name="valid_poll" value="true">{L_VOTE}</button><br />
					<a class="small" href="{PATH_TO_ROOT}/poll/poll{U_POLL_RESULT}">{L_POLL_RESULT}</a>
				</div>
				<div class="module-mini-bottom">
				</div>
			</div>
		</form>
		# END question #

		# START result #
		<div class="module-mini-container">
			<div class="module-mini-top">
				<h5 class="sub-title">{L_MINI_POLL}</h5>
			</div>
			<div class="module-mini-contents" style="text-align:center">
				<span style="font-size:10px;">{result.QUESTION}</span>
				
				<hr style="width:90%;" />
				<br />
				# START result.answers #
				<p style="padding-left:6px;text-align:left;">
					<span class="smaller">{result.answers.ANSWERS} : {result.answers.PERCENT}%</span>
					<br />
					<div class="progressbar-container" title="{result.answers.WIDTH}%">
						<div class="progressbar-infos">{result.answers.WIDTH}%</div>
						<div class="progressbar" style="width:{result.answers.WIDTH}%;"></div>
					</div>
				</p>
				# END result.answers #
				
				<span class="smaller">{result.VOTES} {L_VOTE}</span>
			</div>
			<div class="module-mini-bottom">
			</div>
		</div>
		# END result #
			