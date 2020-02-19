		<script>
		<!--
		function check_form(){
			if(document.getElementById('question').value == "") {
				alert("{L_REQUIRE_QUESTION}");
				return false;
		    }
			if(document.getElementById('reponses').value == "") {
				alert("{L_REQUIRE_ANSWER}");
				return false;
		    }
				if(document.getElementById('type').value == "") {
				alert("{L_REQUIRE_ANSWER_TYPE}");
				return false;
		    }

			return true;
		}

		function add_field(i, i_max)
		{
			var i2 = i + 1;

			if( document.getElementById('a'+i) )
				document.getElementById('a'+i).innerHTML = '<label><input type="text" name="a'+i+'" value="" /></label><span id="a'+i2+'"></span>';
			if( document.getElementById('v'+i) )
				document.getElementById('v'+i).innerHTML = '<label><input class="poll-vote" type="text" name="v'+i+'" value="" /> 0.0%</label><span id="v'+i2+'"></span>';
			if( document.getElementById('s'+i) )
				document.getElementById('s'+i).innerHTML = (i < i_max) ? '<span id="s'+i2+'"><a href="javascript:add_field('+i2+', '+i_max+')" aria-label="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus" aria-hidden="true"></i></a></span>' : '';
		}

		-->
		</script>

		<nav id="admin-quick-menu">
			<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
				<i class="fa fa-bars" aria-hidden="true"></i> {L_POLL_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="${Url::to_rel('/poll')}" class="quick-link">${LangLoader::get_message('home', 'main')}</a>
				</li>
				<li>
					<a href="admin_poll.php" class="quick-link">{L_POLL_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_poll_add.php" class="quick-link">{L_POLL_ADD}</a>
				</li>
				<li>
					<a href="admin_poll_config.php" class="quick-link">{L_POLL_CONFIG}</a>
				</li>
			</ul>
		</nav>

		<div class="admin-module-poll" id="admin-contents">
			# INCLUDE message_helper #

			<form action="admin_poll.php" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="align-center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_POLL_MANAGEMENT}</legend>
					<div class="fieldset-inset">
						<div class="form-element top-field third-field">
							<label for="question">* {L_QUESTION}</label>
							<div class="form-field"><input type="text" maxlength="100" id="question" name="question" value="{QUESTIONS}" /></div>
						</div>
						<div class="form-element top-field third-field custom-radio inline-radio">
							<label for="type">* {L_ANSWER_TYPE}</label>
							<div class="form-field">
								<div class="form-field-radio">
									<label class="radio" for="type1">
										<input type="radio" name="type" id="type1" value="1"# IF C_TYPE_UNIQUE # checked="checked"# ENDIF # />
										<span>{L_SINGLE}</span>
									</label>
								</div>
								<div class="form-field-radio">
									<label class="radio" for="type2">
										<input type="radio" name="type" id="type2" value="0"# IF C_TYPE_MULTIPLE # checked="checked"# ENDIF # />
										<span>{L_MULTIPLE}</span>
									</label>
								</div>
							</div>
						</div>
						<div class="form-element top-field third-field custom-radio inline-radio">
							<label for="archive">* {L_ARCHIVES}</label>
							<div class="form-field">
								<div class="form-field-radio">
									<label class="radio" for="archive1">
										<input type="radio" name="archive" id="archive1" value="1"# IF C_ARCHIVES_ENABLED # checked="checked"# ENDIF # />
										<span>${LangLoader::get_message('yes', 'common')}</span>
									</label>
								</div>
								<div class="form-field-radio">
									<label class="radio" for="archive2">
										<input type="radio" name="archive" id="archive2" value="0"# IF C_ARCHIVES_DISABLED # checked="checked"# ENDIF # />
										<span>${LangLoader::get_message('no', 'common')}</span>
									</label>
								</div>
							</div>
						</div>
						<div class="form-element full-field">
							<label>* {L_ANSWERS}</label>
							<div class="form-field">
								<table class="table admin-poll">
									<tbody>
										<tr>
											<td class="text-strong">
												{L_ANSWERS}
											</td>
											<td class="text-strong">
												{L_NUMBER_VOTE}
											</td>
										</tr>
										<tr>
											<td class="align-left">
												# START answers #
												<label><input type="text" name="a{answers.ID}" value="{answers.ANSWER}" /></label>
												# END answers #
												<span id="a{MAX_ID}"></span>
											</td>
											<td class="align-left">
												# START votes #
												<label><input class="poll-vote" type="text" name="v{votes.ID}" value="{votes.VOTES}" /> {votes.PERCENT}</label>
												# END votes #
												<span id="v{MAX_ID}"></span>
											</td>
										</tr>
										<tr>
											<td class="align-left" colspan="2">
												<script>
													if( {MAX_ID} < 19 )
														document.write('<span id="s{MAX_ID}"><a href="javascript:add_field({MAX_ID}, 19)" aria-label="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus" aria-hidden="true"></i></a></span>');
												</script>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</fieldset>

				<fieldset>
					<legend>{L_DATE}</legend>
					<div class="fieldset-inset">
						<div class="form-element half-field custom-radio">
							<label for="release_date">{L_RELEASE_DATE}</label>
							<div class="form-field poll-form-field">
								<div onclick="document.getElementById('start_end_date').checked = true;">
									<div class="form-field-radio">
										<label class="radio" for="start_end_date">
											<input type="radio" value="2" name="visible" id="start_end_date"# IF C_VISIBLE_WAITING # checked="checked"# ENDIF # />
											<span>
												{CALENDAR_START}
												{L_UNTIL}&nbsp;
												{CALENDAR_END}
											</span>
										</label>
									</div>
								</div>
								<div class="form-field-radio">
									<label class="radio" for="release_date">
										<input type="radio" value="1" id="release_date" name="visible"# IF C_VISIBLE_ENABLED # checked="checked"# ENDIF # />
										<span>{L_IMMEDIATE}</span>
									</label>
								</div>
								<div class="form-field-radio">
									<label class="radio" for="unaprob">
										<input type="radio" value="0" id="unaprob" name="visible"# IF C_VISIBLE_UNAPROB # checked="checked"# ENDIF # />
										<span>{L_UNAPROB}</span>
									</label>
								</div>
							</div>
						</div>
						<div class="form-element half-field poll-form-field top-field">
							<label for="current_date">* {L_POLL_DATE}</label>
							<div class="form-field">
								<label>
								{CALENDAR_CURRENT_DATE}
								{L_AT}
								<input class="input-date" type="text" maxlength="2" name="hour" value="{HOUR}" /> h <input class="input-date" type="text" maxlength="2" name="min" value="{MIN}" />
							</label></div>
						</div>
					</div>
				</fieldset>

				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<div class="fieldset-inset">
						<input type="hidden" name="id" value="{IDPOLL}">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="valid" value="true" class="button submit">{L_UPDATE}</button>
						<button type="reset" class="button reset-button" value="true">{L_RESET}</button>
					</div>
				</fieldset>
			</form>
		</div>
