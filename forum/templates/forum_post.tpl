		# INCLUDE forum_top #
		
		<script>
		<!--
		function check_form_post(){
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
		    }
			if(document.getElementById('title').value == "") {
				alert("{L_REQUIRE_TITLE}");
				return false;
		    }
			if(!poll_hidded && document.getElementById('question').value == "") {
				alert("{L_REQUIRE_TITLE_POLL}");
				return false;
			}
			return true;
		}
		var poll_hidded = true;
		function hide_poll(divID)
		{
			if( document.getElementById(divID) )
			{
				document.getElementById(divID).style.display = 'block';
				if( document.getElementById('hidepoll_link') )
				{
					document.getElementById('hidepoll_link').style.display = 'none';
					poll_hidded = false;
				}
			}
		}
		function add_poll_field(nbr_field)
		{
			if ( typeof this.max_field_p == 'undefined' )
				this.max_field_p = (nbr_field == 0) ? 5 : nbr_field;
			else
				this.max_field_p++;
			
			if( this.max_field_p < 20 )
			{
				if( this.max_field_p == 19 )
				{
					if( document.getElementById('add_poll_field_link') )
						document.getElementById('add_poll_field_link').innerHTML = '';
				}
				document.getElementById('add_poll_field' + this.max_field_p).innerHTML += '<label><input type="text" name="a' + this.max_field_p + '" value="" /></label><br><span id="add_poll_field' + (this.max_field_p + 1) + '"></span>';
			}
		}
		function XMLHttpRequest_change_statut()
		{
			var idtopic = {IDTOPIC};
			if( document.getElementById('forum_change_img') )
				document.getElementById('forum_change_img').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
			
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&msg_d=' + idtopic);
			xhr_object.onreadystatechange = function()
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{
					if( document.getElementById('forum_change_img') )
						document.getElementById('forum_change_img').innerHTML = xhr_object.responseText == '1' ? '<i class="fa fa-msg-not-display"></i>' : '<i class="fa fa-msg-display"></i>';
					if( document.getElementById('forum_change_msg') )
						document.getElementById('forum_change_msg').innerHTML = xhr_object.responseText == '1' ? "{L_EXPLAIN_DISPLAY_MSG_BIS}" : "{L_EXPLAIN_DISPLAY_MSG}";
				}
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		-->
		</script>
		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-post">
			<header>
				<h2>
					&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span><em>{DESC}</em></span>
				</h2>
			</header>

			<div class="content">
				<form action="{U_ACTION}" method="post" onsubmit="return check_form_post();">
					# INCLUDE message_helper #

					# IF C_FORUM_PREVIEW_MSG #
					<div class="module-position">
						<div class="module-top-l"></div>
						<div class="module-top-r"></div>
						<div class="module-top">
							<span class="float-left">{L_PREVIEW}</span>
							<span class="float-right"></span>&nbsp;
						</div>
					</div>
					<div class="msg-position">
						<div class="msg-container">
							<div class="msg-pseudo-mbr"></div>
							<div class="msg-top-row">
								<div class="float-left"><i class="fa fa-hand-o-right"></i> {DATE}</div>
								<div class="float-right"><i class="fa fa-quote-right"></i></div>
							</div>
							<div class="msg-contents-container">
								<div class="msg-info-mbr">
								</div>
								<div class="msg-contents">
									<div class="msg-contents-overflow">
										{CONTENTS_PREVIEW}
									</div>
								</div>
							</div>
						</div>
						<div class="msg-sign">
							<hr />
							<span class="float-left">
								<span class="basic-button smaller">MP</span>
							</span>
							<span class="float-left">
							</span>&nbsp;
						</div>
					</div>
					<div class="msg-position">
						<div class="msg-bottom-l"></div>
						<div class="msg-bottom-r"></div>
						<div class="msg-bottom">&nbsp;</div>
					</div>
					<br /><br />
					# ENDIF #
					
					<div class="fieldset-content">
						<p class="center">{L_REQUIRE}</p>
						<fieldset>
							<legend>{L_ACTION}</legend>
							# IF C_FORUM_CUT_CAT #
							<div class="form-element">
								<label for="to">* {L_CAT}</label>
								<div class="form-field"><label>
									<select id="to" name="to">
										{CATEGORIES}
									</select>
								</label></div>
							</div>
							# ENDIF #
							<div class="form-element">
								<label for="title">* {L_TITLE}</label>
								<div class="form-field"><label><input type="text" class="field-large" maxlength="100" id="title" name="title" value="{TITLE}"></label></div>
							</div>
							<div class="form-element">
								<label for="desc">{L_DESC}</label>
								<div class="form-field"><label><input type="text" class="field-large" maxlength="75" id="desc" name="desc" value="{DESC}"></label></div>
							</div>
							<div class="form-element-textarea">
								<label for="contents">* {L_MESSAGE}</label>
								{KERNEL_EDITOR}
								<div class="form-field-textarea">
									<textarea rows="25" cols="47" id="contents" name="contents">{CONTENTS}</textarea>
								</div>
							</div>
							# IF C_FORUM_POST_TYPE #
							<div class="form-element">
								<label for="type">{L_TYPE}</label>
								<div class="form-field">
									<label><input type="radio" name="type" id="type" value="0" {CHECKED_NORMAL}> {L_DEFAULT}</label>
									<label><input type="radio" name="type" value="1" {CHECKED_POSTIT}> {L_POST_IT}</label>
									<label><input type="radio" name="type" value="2" {CHECKED_ANNONCE}> {L_ANOUNCE}</label>
								</div>
							</div>
							# ENDIF #
						</fieldset>

						<fieldset>	
							<legend>{L_POLL}</legend>
							<p id="hidepoll_link" class="center"><a title="{L_OPEN_MENU_POLL}" href="" onclick="hide_poll('hidepoll');return false;">{L_OPEN_MENU_POLL}</a></p>
							<div id="hidepoll">
								<div class="form-element">
									<label for="question">* {L_QUESTION}</label>
									<div class="form-field"><label><input type="text" name="question" id="question" value="{POLL_QUESTION}"></label></div>
								</div>
								<div class="form-element">
									<label for="poll_type">{L_POLL_TYPE}</label>
									<div class="form-field">
										<label><input type="radio" name="poll_type" id="poll_type" value="0" {SELECTED_SIMPLE}> {L_SINGLE}</label>
										<label><input type="radio" name="poll_type" value="1" {SELECTED_MULTIPLE}> {L_MULTIPLE}</label>	
									</div>
								</div>
								# IF C_DELETE_POLL #
								<div class="form-element">
									<label for="del_poll">{L_DELETE_POLL}</label>
									<div class="form-field"><label><input type="checkbox" name="del_poll" id="del_poll" value="true"></label></div>
								</div>
								# ENDIF #
								<div class="form-element">
									<label>{L_ANSWERS}</label>
									<div class="form-field">
										# START answers_poll #
										<label><input type="text" name="a{answers_poll.ID}" value="{answers_poll.ANSWER}" /> <em>{answers_poll.NBR_VOTES} {answers_poll.L_VOTES}</em></label><br>
										# END answers_poll #
										<span id="add_poll_field{NBR_POLL_FIELD}"></span>
										
										<p class="center" id="add_poll_field_link">
											# IF C_ADD_POLL_FIELD #
											<a title="${LangLoader::get_message('add', 'common')}" href="" onclick="add_poll_field({NBR_POLL_FIELD});return false;"><i class="fa fa-plus"></i></a>
											# ENDIF #
										</p>
									</div>
								</div>
							</div>
							<script>
							<!--
							if( {NO_DISPLAY_POLL} )
								document.getElementById('hidepoll').style.display = 'none';
							else
								document.getElementById('hidepoll_link').style.display = 'none';
							-->
							</script>
						</fieldset>
						
						<fieldset class="fieldset-submit">
						<legend>{L_SUBMIT}</legend>
							<input type="hidden" name="idm" value="{IDM}">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" name="post_topic" value="true" class="submit">{L_SUBMIT}</button>
							<button onclick="XMLHttpRequest_preview();" type="button">{L_PREVIEW}</button>
							<button type="reset" value="true">{L_RESET}</button>
						
							# IF C_DISPLAY_MSG #
							<br /><br /><br />
							<span id="forum_change_statut">
								<a href="" onclick="XMLHttpRequest_change_statut();return false;" id="forum_change_img">{ICON_DISPLAY_MSG}</a> <a href="" onclick="XMLHttpRequest_change_statut();return false;"><span id="forum_change_msg">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</span></a>
							</span>
							# ENDIF #
						</fieldset>
					</div>
				</form>
			</div>
			<footer>
				&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span><em>{DESC}</em></span>
			</footer>
		</article>
		
		# INCLUDE forum_bottom #
