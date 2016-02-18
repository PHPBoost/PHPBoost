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
				document.getElementById('a'+i).innerHTML = '<label><input type="text" name="a'+i+'" value="" /></label><br /><span id="a'+i2+'"></span>';
			if( document.getElementById('v'+i) )
				document.getElementById('v'+i).innerHTML = '<label><input class="poll-vote" type="text" name="v'+i+'" value="" /> 0.0%</label><br /><span id="v'+i2+'"></span>';
			if( document.getElementById('s'+i) )
				document.getElementById('s'+i).innerHTML = (i < i_max) ? '<span id="s'+i2+'"><a href="javascript:add_field('+i2+', '+i_max+')"><i class="fa fa-plus"></i></a></span>' : '';
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
			
			<form action="admin_poll.php" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_POLL_MANAGEMENT}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label for="question">* {L_QUESTION}</label>
							<div class="form-field"><input type="text" maxlength="100" id="question" name="question" value="{QUESTIONS}" /></div>
						</div>
						<div class="form-element">
							<label for="type">* {L_ANSWER_TYPE}</label>
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
									<input type="radio" name="archive" {ARCHIVES_ENABLED} id="archive1" value="1">
									<label for="archive1"></label> 
								</div>
								<span class="form-field-radio-span">{L_YES}</span>
								<div class="form-field-radio">
									<input type="radio" name="archive" {ARCHIVES_DISABLED} id="archive2" value="0">
									<label for="archive2"></label> 
								</div>
								<span class="form-field-radio-span">{L_NO}</span>
							</div>
						</div>
						<div class="form-element">
							<label>* {L_ANSWERS}</label>
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
												# START answers #
												<label><input type="text" name="a{answers.ID}" value="{answers.ANSWER}" /></label><br />
												# END answers #
												<span id="a{MAX_ID}"></span>
											</td>
											<td class="no-separator">
												# START votes #
												<label><input class="poll-vote" type="text" name="v{votes.ID}" value="{votes.VOTES}" /> {votes.PERCENT}</label><br />
												# END votes #
												<span id="v{MAX_ID}"></span>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<script>
												<!--
													if( {MAX_ID} < 19 )
														document.write('<span id="s{MAX_ID}"><a href="javascript:add_field({MAX_ID}, 19)"><i class="fa fa-plus"></i></a></span>');
												-->
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
						<div class="form-element">
							<label for="release_date">{L_RELEASE_DATE}</label>
							<div class="form-field">
								<div onclick="document.getElementById('start_end_date').checked = true;">
									<label>
										<div class="form-field-radio">
											<input type="radio" value="2" name="visible" id="start_end_date" {VISIBLE_WAITING} />
											<label for="start_end_date"></label> 
										</div>
										{CALENDAR_START}
										
										{L_UNTIL}&nbsp;
										
										{CALENDAR_END}
									</label>
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
							<div class="form-field"><label>
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
						<button type="submit" name="valid" value="true" class="submit">{L_UPDATE}</button>
						<button type="reset" value="true">{L_RESET}</button>
					</div>
				</fieldset>
			</form>
		</div>
