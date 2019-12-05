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

			return true;
		}

		function add_field(i, i_max)
		{
			var i2 = i + 1;

			if( document.getElementById('a'+i) )
				document.getElementById('a'+i).innerHTML = '<label class="infos-options"><input type="text" name="a'+i+'" value="" /></label><span id="a'+i2+'"></span>';
			if( document.getElementById('v'+i) )
				document.getElementById('v'+i).innerHTML = '<label class="infos-options"><input class="poll-vote" type="text" name="v'+i+'" value="" /></label><span id="v'+i2+'"></span>';
			if( document.getElementById('s'+i) )
				document.getElementById('s'+i).innerHTML = (i < i_max) ? '<span id="s'+i2+'"><a href="javascript:add_field('+i2+', '+i_max+')" aria-label="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus" aria-hidden="true"></i></a></span>' : '';
		}
		-->
		</script>

		<nav id="admin-quick-menu">
			<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
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
				<li>
					<a href="${relative_url(PollUrlBuilder::documentation())}" class="quick-link">${LangLoader::get_message('module.documentation', 'admin-modules-common')}</a>
				</li>
			</ul>
		</nav>

		<div class="admin-module-poll" id="admin-contents">

			# INCLUDE message_helper #

			<form action="admin_poll_add.php" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="align-center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_POLL_ADD}</legend>
					<div class="fieldset-inset">
						<div class="form-element top-field">
							<label for="question">* {L_QUESTION}</label>
							<div class="form-field"><input type="text" maxlength="100" id="question" name="question"></div>
						</div>
						<div class="form-element top-field custom-radio inline-radio">
							<label for="type">* {L_ANSWERS_TYPE}</label>
							<div class="form-field">
								<div class="form-field-radio">
									<label class="radio" for="type1">
										<input type="radio" name="type" id="type1" value="1" checked="checked">
										<span>{L_SINGLE}</span>
									</label>
								</div>
								<div class="form-field-radio">
									<label class="radio" for="type2">
										<input type="radio" name="type" id="type2" value="0">
										<span>{L_MULTIPLE}</span>
									</label>
								</div>
							</div>
						</div>
						<div class="form-element top-field custom-radio inline-radio">
							<label for="archive">* ${LangLoader::get_message('hidden', 'common')}</label>
							<div class="form-field">
								<div class="form-field-radio">
									<label class="radio" for="archive1">
										<input type="radio" name="archive" id="archive1" value="1">
										<span>{L_YES}</span>
									</label>
								</div>
								<div class="form-field-radio">
									<label class="radio" for="archive2">
										<input type="radio" name="archive" id="archive2" value="0" checked="checked">
										<span>{L_NO}</span>
									</label>
								</div>
							</div>
						</div>
						<div class="form-element full-field">
							<label for="a0">* {L_ANSWERS}</label>
							<div class="form-field">
								<table class="table admin-poll">
									<tbody>
										<tr>
											<td class="no-separator text-strong">
												{L_ANSWERS}
											</td>
											<td class="no-separator text-strong">
												{L_NUMBER_VOTE}
											</td>
										</tr>
										<tr>
											<td class="align-left no-separator">
												<label class="infos-options"><input type="text" name="a0" id="a0" value="{ANSWER0}" /></label>
												<label class="infos-options"><input type="text" name="a1" value="{ANSWER1}" /></label>
												<label class="infos-options"><input type="text" name="a2" value="{ANSWER2}" /></label>
												<span id="a3"></span>
											</td>
											<td class="align-left no-separator">
												<label class="infos-options"><input class="poll-vote" type="text" name="v0" value="{VOTES0}" /> {PERCENT0}</label>
												<label class="infos-options"><input class="poll-vote" type="text" name="v1" value="{VOTES1}" /> {PERCENT1}</label>
												<label class="infos-options"><input class="poll-vote" type="text" name="v2" value="{VOTES2}" /> {PERCENT2}</label>
												<span id="v3"></span>
											</td>
										</tr>
										<tr>
											<td class="align-left" colspan="2">
												<span id="s3"><a href="javascript:add_field(3, 20)" aria-label="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus" aria-hidden="true"></i></a></span>
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
						<div class="form-element custom-radio">
							<label for="release_date">{L_RELEASE_DATE}</label>
							<div class="form-field poll-form-field">
								<div onclick="document.getElementById('start_end_date').checked = true;">
									<div class="form-field-radio">
										<label class="radio" for"start_end_date">
											<input type="radio" value="2" name="visible" id="start_end_date" {VISIBLE_WAITING} />
											<div class="grouped-inputs">{CALENDAR_START}</div>
											<div>{L_UNTIL}</div>
										    <div class="grouped-inputs">{CALENDAR_END}</div>
										</label>
									</div>
								</div>
							</div>
							<div class="form-field-radio">
								<label class="radio" for="release_date">
									<input type="radio" value="1" id="release_date" name="visible" {VISIBLE_ENABLED} />
									<span>{L_IMMEDIATE}</span>
								</label>
							</div>
							<div class="form-field-radio">
								<label class="radio" for="unaprob">
									<input type="radio" value="0" id="unaprob" name="visible" {VISIBLE_UNAPROB} />
									<span>{L_UNAPROB}</span>
								</label>
							</div>
						</div>
						<div class="form-element top-field">
							<label for="current_date">* {L_POLL_DATE}</label>
							<div class="form-field form-field-datetime grouped-inputs">
								{CALENDAR_CURRENT_DATE}
								<label class="label-time grouped-element" data-time="h">
									<input class="input-hours" type="number" name="hour" value="{HOUR}" />
								</label>
								<label class="label-time grouped-element" data-time="mn">
									<input class="input-minutes" type="number" maxlength="2" name="min" value="{MIN}" />
								</label>
							</div>
						</div>
					</div>
				</fieldset>

				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<div class="fieldset-inset">
						<button type="submit" name="valid" value="true" class="button submit">{L_SUBMIT}</button>
						<button type="reset" class="button reset" value="true">{L_RESET}</button>
						<input type="hidden" name="token" value="{TOKEN}">
					</div>
				</fieldset>
			</form>
		</div>
