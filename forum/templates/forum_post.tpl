		# INCLUDE forum_top #
		
		<script type='text/javascript'>
		<!--
		function check_form_post(){
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #
			
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
				document.getElementById('add_poll_field' + this.max_field_p).innerHTML += '<label><input type="text" size="25" name="a' + this.max_field_p + '" value="" class="text" /></label><br /><span id="add_poll_field' + (this.max_field_p + 1) + '"></span>';
			}
		}
		function XMLHttpRequest_change_statut()
		{
			var idtopic = {IDTOPIC};
			if( document.getElementById('forum_change_img') )
				document.getElementById('forum_change_img').src = '{PATH_TO_ROOT}/templates/{THEME}/images/loading.gif';
			
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&msg_d=' + idtopic);
			xhr_object.onreadystatechange = function()
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{
					if( document.getElementById('forum_change_img') )
						document.getElementById('forum_change_img').src = xhr_object.responseText == '1' ? '{PICTURES_DATA_PATH}/images/msg_display2.png' : '{PICTURES_DATA_PATH}/images/msg_display.png';
					if( document.getElementById('forum_change_msg') )
						document.getElementById('forum_change_msg').innerHTML = xhr_object.responseText == '1' ? "{L_EXPLAIN_DISPLAY_MSG_BIS}" : "{L_EXPLAIN_DISPLAY_MSG}";
				}
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		-->
		</script>
		<div class="module_position">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span></div>
			<div class="module_contents">		
				<form action="{U_ACTION}" method="post" onsubmit="return check_form_post();">
					# INCLUDE message_helper #

					# IF C_FORUM_PREVIEW_MSG #
					<div class="module_position">
						<div class="module_top_l"></div>
						<div class="module_top_r"></div>
						<div class="module_top">
							<span style="float:left;">{L_PREVIEW}</span>
							<span style="float:right;"></span>&nbsp;
						</div>
					</div>	
					<div class="msg_position">
						<div class="msg_container">
							<div class="msg_pseudo_mbr"></div>
							<div class="msg_top_row">
								<div style="float:left;">&nbsp;&nbsp;<img src="{PATH_TO_ROOT}/templates/{THEME}/images/ancre.png" alt="" /> {DATE}</div>
								<div style="float:right;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/quote.png" alt="" title="" />&nbsp;&nbsp;</div>
							</div>
							<div class="msg_contents_container">
								<div class="msg_info_mbr">
								</div>
								<div class="msg_contents">
									<div class="msg_contents_overflow">
										{CONTENTS_PREVIEW}
									</div>
								</div>
							</div>
						</div>	
						<div class="msg_sign">		
							<hr />
							<span style="float:left;">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/pm.png" alt="pm" />
							</span>
							<span style="float:right;font-size:10px;">
							</span>&nbsp;
						</div>	
					</div>
					<div class="msg_position">
						<div class="msg_bottom_l"></div>
						<div class="msg_bottom_r"></div>
						<div class="msg_bottom">&nbsp;</div>
					</div>
					<br /><br />
					# ENDIF #
					
					<div class="fieldset_content">
						<fieldset>
							<legend>{L_ACTION}</legend>
							<p>{L_REQUIRE}</p>
							# IF C_FORUM_CUT_CAT #
							<dl>
								<dt><label for="to">* {L_CAT}</label></dt>
								<dd><label>
									<select id="to" name="to">
										{CATEGORIES}
									</select>
								</label></dd>
							</dl>
							# ENDIF #
							<dl>
								<dt><label for="title">* {L_TITLE}</label></dt>
								<dd><label><input type="text" size="51" maxlength="100" id="title" name="title" value="{TITLE}" class="text" /></label></dd>
							</dl>
							<dl>
								<dt><label for="desc">{L_DESC}</label></dt>
								<dd><label><input type="text" size="51" maxlength="75" id="desc" name="desc" value="{DESC}" class="text" /></label></dd>
							</dl>
							
							<label for="contents">* {L_MESSAGE}</label>
							{KERNEL_EDITOR}
							<label><textarea rows="25" cols="40" id="contents" name="contents">{CONTENTS}</textarea></label>
							
							<br /><br />
							
							# IF C_FORUM_POST_TYPE #
							<dl>
								<dt><label for="type">{L_TYPE}</label></dt>
								<dd>
									<label><input type="radio" name="type" id="type" value="0" {CHECKED_NORMAL} /> {L_DEFAULT}</label>
									<label><input type="radio" name="type" value="1" {CHECKED_POSTIT} /> {L_POST_IT}</label>
									<label><input type="radio" name="type" value="2" {CHECKED_ANNONCE} /> {L_ANOUNCE}</label>
								</dd>
							</dl>
							# ENDIF #
						</fieldset>

						<fieldset>	
							<legend>{L_POLL}</legend>
							<p id="hidepoll_link" style="text-align:center"><a href="javascript:hide_poll('hidepoll')">{L_OPEN_MENU_POLL}</a></p>
							<div id="hidepoll">
								<dl>
									<dt><label for="question">* {L_QUESTION}</label></dt>
									<dd><label><input type="text" size="40" name="question" id="question" value="{POLL_QUESTION}" class="text" /></label></dd>
								</dl>
								<dl>
									<dt><label for="poll_type">{L_POLL_TYPE}</label></dt>
									<dd>
										<label><input type="radio" name="poll_type" id="poll_type" value="0" {SELECTED_SIMPLE} /> {L_SINGLE}</label>
										<label><input type="radio" name="poll_type" value="1" {SELECTED_MULTIPLE} /> {L_MULTIPLE}</label>	
									</dd>
								</dl>
								# IF C_DELETE_POLL #
								<dl>
									<dt><label for="del_poll">{L_DELETE_POLL}</label></dt>
									<dd><label><input type="checkbox" name="del_poll" id="del_poll" value="true" /></label></dd>
								</dl>
								# ENDIF #
								<dl>
									<dt><label>{L_ANSWERS}</label></dt>
									<dd>
										# START answers_poll #
										<label><input type="text" size="25" name="a{answers_poll.ID}" value="{answers_poll.ANSWER}" class="text" /> <em>{answers_poll.NBR_VOTES} {answers_poll.L_VOTES}</em></label><br />
										# END answers_poll #
										<span id="add_poll_field{NBR_POLL_FIELD}"></span>	
										
										<p style="text-align:center;width:165px;" id="add_poll_field_link">
											# IF C_ADD_POLL_FIELD #
											<a href="javascript:add_poll_field({NBR_POLL_FIELD})"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" alt="+" /></a>
											# ENDIF #
										</p>
									</dd>
								</dl>
							</div>
							<script type='text/javascript'>
							<!--
							if( {NO_DISPLAY_POLL} )
								document.getElementById('hidepoll').style.display = 'none';
							-->
							</script>
						</fieldset>	
						
						<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
							<input type="hidden" name="idm" value="{IDM}" />
							<input type="submit" name="post_topic" value="{L_SUBMIT}" class="submit" />
							&nbsp;&nbsp;
							<input value="{L_PREVIEW}" type="submit" name="prw_t" id="previs_topic" class="submit" />
							<script type="text/javascript">
							<!--
							document.getElementById('previs_topic').style.display = 'none';
							document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" type="button" class="submit" />');
							-->
							</script>
							&nbsp;&nbsp;
							<input type="reset" value="{L_RESET}" class="reset" />
						
							# IF C_DISPLAY_MSG #
							<br /><br /><br />
							<span id="forum_change_statut">
								<a href="action{U_ACTION_MSG_DISPLAY}#go_bottom">{ICON_DISPLAY_MSG}</a>	<a href="action{U_ACTION_MSG_DISPLAY}#go_bottom">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</a>
							</span>
							<script type="text/javascript">
							<!--
							document.getElementById('forum_change_statut').style.display = 'none';
							document.write('<a href="javascript:XMLHttpRequest_change_statut()">{ICON_DISPLAY_MSG2}</a> <a href="javascript:XMLHttpRequest_change_statut()"><span id="forum_change_msg">{L_EXPLAIN_DISPLAY_MSG_DEFAULT}</span></a>');
							-->
							</script>
							# ENDIF #
						</fieldset>
					</div>
				</form>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span></div>
		</div>
		
		# INCLUDE forum_bottom #
