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
				document.getElementById('a'+i).innerHTML = '<label><input type="text" name="a'+i+'" value="" /></label><br /><span id="a'+i2+'"></span>';
			if( document.getElementById('v'+i) )
				document.getElementById('v'+i).innerHTML = '<label><input class="poll-vote" type="text" name="v'+i+'" value="" /></label><br /><span id="v'+i2+'"></span>';
			if( document.getElementById('s'+i) )
				document.getElementById('s'+i).innerHTML = (i < i_max) ? '<span id="s'+i2+'"><a href="javascript:add_field('+i2+', '+i_max+')" title="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus"></i></a></span>' : '';
		}
		-->
		</script>

		<nav id="admin-quick-menu">
			<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
				<i class="fa fa-bars"></i> {L_POLL_MANAGEMENT}
			</a>
			<ul>
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
			
			<form action="admin_poll_add.php" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_POLL_ADD}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label for="question">* {L_QUESTION}</label>
							<div class="form-field"><input type="text" maxlength="100" id="question" name="question"></div>
						</div>
						<div class="form-element">
							<label for="type">* {L_ANSWERS_TYPE}</label>
							<div class="form-field">
								<div class="form-field-radio">
									<input type="radio" name="type" id="type1" value="1" checked="checked">
									<label for="type1"></label> 
								</div>
								<span class="form-field-radio-span">{L_SINGLE}</span>
								<div class="form-field-radio">
									<input type="radio" name="type" id="type2" value="0">
									<label for="type2"></label> 
								</div>
								<span class="form-field-radio-span">{L_MULTIPLE}</span>
							</div>
						</div>
						<div class="form-element">
							<label for="archive">* ${LangLoader::get_message('hidden', 'common')}</label>
							<div class="form-field">
								<div class="form-field-radio">
									<input type="radio" name="archive" id="archive1" value="1">
									<label for="archive1"></label> 
								</div>
								<span class="form-field-radio-span">{L_YES}</span>
								<div class="form-field-radio">
									<input type="radio" name="archive" id="archive2" value="0" checked="checked">
									<label for="archive2"></label> 
								</div>
								<span class="form-field-radio-span">{L_NO}</span>
							</div>
						</div>
						<div class="form-element">
							<label for="a0">* {L_ANSWERS}</label>
							<div class="form-field">
								<table id="table" class="admin-poll">
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
											<td class="no-separator">
												<label><input type="text" name="a0" id="a0" value="{ANSWER0}" /></label><br />
												<label><input type="text" name="a1" value="{ANSWER1}" /></label><br />
												<label><input type="text" name="a2" value="{ANSWER2}" /></label><br />
												<span id="a3"></span>
											</td>
											<td class="no-separator">
												<label><input class="poll-vote" type="text" name="v0" value="{VOTES0}" /> {PERCENT0}</label><br />
												<label><input class="poll-vote" type="text" name="v1" value="{VOTES1}" /> {PERCENT1}</label><br />
												<label><input class="poll-vote" type="text" name="v2" value="{VOTES2}" /> {PERCENT2}</label><br />
												<span id="v3"></span>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<span id="s3"><a href="javascript:add_field(3, 20)" title="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus"></i></a></span>
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
						<div class="form-element">
							<label for="release_date">{L_RELEASE_DATE}</label>
							<div class="form-field">
								<div onclick="document.getElementById('start_end_date').checked = true;">
									<div class="form-field-radio">
										<input type="radio" value="2" name="visible" id="start_end_date" {VISIBLE_WAITING} />
										<label for"start_end_date"></label>
									</div>
									{CALENDAR_START}
									
									{L_UNTIL}&nbsp;
									
									{CALENDAR_END}
								</div>
								<br />
								<div class="form-field-radio">
									<input type="radio" value="1" id="release_date" name="visible" {VISIBLE_ENABLED} />
									<label for="release_date"></label>
								</div>
								<span class="form-field-radio-span">{L_IMMEDIATE}</span>
								<br />
								<div class="form-field-radio">
									<input type="radio" value="0" id="unaprob" name="visible" {VISIBLE_UNAPROB} />
									<label for="unaprob"></label>
								</div>
								<span class="form-field-radio-span">{L_UNAPROB}</span>
							</div>
						</div>
						<div class="form-element">
							<label for="current_date">* {L_POLL_DATE}</label>
							<div class="form-field">
								{CALENDAR_CURRENT_DATE}
								{L_AT}
								<input class="input-date" type="text" name="hour" value="{HOUR}" /> h <input class="input-date" type="text" maxlength="2" name="min" value="{MIN}" />
							</div>
						</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<div class="fieldset-inset">
						<button type="submit" name="valid" value="true" class="submit">{L_SUBMIT}</button>
						<button type="reset" value="true">{L_RESET}</button>
						<input type="hidden" name="token" value="{TOKEN}">
					</div>
				</fieldset>
			</form>
		</div>
