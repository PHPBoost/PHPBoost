		<script type='text/javascript'>
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
			return true;
		}

		function hide_poll(divID)
		{
			if( document.getElementById(divID) ) 
			{
				document.getElementById(divID).style.display = 'block';
				if( document.getElementById('hidepoll_link') )
					document.getElementById('hidepoll_link').style.display = 'none';
			}
		}

		function add_field(i, i_max) 
		{
			var i2 = i + 1;

			document.getElementById('a'+i).innerHTML = '<input type="text" size="25" name="a'+i+'" value="" class="text" /><br /></span>';
			
			document.getElementById('a'+i).innerHTML += (i < i_max) ? '<p id="a'+i2+'"style="text-align:center"><a href="javascript:add_field('+i2+', '+i_max+')"><img src="../templates/{THEME}/images/form/plus.png" alt="+" /></a></a></p>' : '';
		}

		-->
		</script>
		
		<div class="forum_title">{FORUM_NAME}</div>		
		<div class="module_position">
			<div class="row2">
				<span style="float:left;" class="text_strong">
					<a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span>
				</span>
				<span style="float:right;">
					{U_SEARCH}
					{U_TOPIC_TRACK}
					{U_LAST_MSG_READ}
					{U_MSG_NOT_READ}
				</span>&nbsp;
			</div>
		</div>
		
		<br />
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"></div>
			<div class="module_contents">		
				<form action="{U_ACTION}" method="post" onsubmit="return check_form_post();">		
					# START error_handler #
					<br />	
					<span id="errorh"></span>
					<div class="{error_handler.CLASS}">
						<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
					</div>
					<br />		
					# END error_handler #

					# START show_msg #		
					<div class="module_position">					
						<div class="module_top_l"></div>		
						<div class="module_top_r"></div>
						<div class="module_top">
							<span style="float:left;">{show_msg.L_PREVIEW}</span>
							<span style="float:right;"></span>&nbsp;
						</div>
					</div>	
					<div class="msg_position">
						<div class="msg_container">
							<div class="msg_pseudo_mbr"></div>
							<div class="msg_top_row">
								<div style="float:left;">&nbsp;&nbsp;<img src="../templates/{THEME}/images/ancre.png" alt="" /> {show_msg.DATE}</div>
								<div style="float:right;"><img src="../templates/{THEME}/images/{LANG}/quote.png" alt="" title="" />&nbsp;&nbsp;</div>
							</div>
							<div class="msg_contents_container">
								<div class="msg_info_mbr">
								</div>
								<div class="msg_contents">
									<div class="msg_contents_overflow">
										{show_msg.CONTENTS}
									</div>
								</div>
							</div>
						</div>	
						<div class="msg_sign">		
							<hr />
							<span style="float:left;">
								<img src="../templates/{THEME}/images/{LANG}/pm.png" alt="pm" />
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
					# END show_msg #			
					
					<div class="fieldset_content">
						<fieldset>
							<legend>{L_ACTION}</legend>
							<p>{L_REQUIRE}</p>
							# START cut_cat #
							<dl>
								<dt><label for="to">* {L_CAT}</label></dt>
								<dd><label>
									<select id="to" name="to">
										{cut_cat.CATEGORIES}
									</select>
								</label></dd>
							</dl>
							# END cut_cat #
							<dl>
								<dt><label for="title">* {L_TITLE}</label></dt>
								<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="{TITLE}" class="text" /></label></dd>
							</dl>
							<dl>
								<dt><label for="desc">{L_DESC}</label></dt>
								<dd><label><input type="text" size="50" maxlength="75" id="desc" name="desc" value="{DESC}" class="text" /></label></dd>
							</dl>
							
							<label for="contents">* {L_MESSAGE}</label>
							{BBCODE}<label><textarea type="text" rows="25" cols="40" id="contents" name="contents">{CONTENTS}</textarea></label>
							
							<br /><br />
							
							# START type #
							<dl>
								<dt><label for="type">{type.L_TYPE}</label></dt>
								<dd>
									<label><input type="radio" name="type" id="type" value="0" {type.CHECKED_NORMAL} /> {type.L_DEFAULT}</label>
									<label><input type="radio" name="type" value="1" {type.CHECKED_POSTIT} /> {type.L_POST_IT}</label>
									<label><input type="radio" name="type" value="2" {type.CHECKED_ANNONCE} /> {type.L_ANOUNCE}</label>
								</dd>
							</dl>	
							# END type #	
						</fieldset>	

						<fieldset>	
							<legend>{L_POLL}</legend>
							<p id="hidepoll_link" style="text-align:center"><a href="javascript:hide_poll('hidepoll')">{L_OPEN_MENU_POLL}</a></p>
							<div style="display:none;" id="hidepoll">
								<dl>
									<dt><label for="question">* {L_QUESTION}</label></dt>
									<dd><label><input type="text" size="40" name="question" id="question" value="{QUESTION}" class="text" /></label></dd>
								</dl>
								<dl>
									<dt><label for="poll_type">{L_POLL_TYPE}</label></dt>
									<dd>
										<label><input type="radio" name="poll_type" id="poll_type" value="0" {SELECTED_SIMPLE} /> {L_SINGLE}</label>
										<label><input type="radio" name="poll_type" value="1" {SELECTED_MULTIPLE} /> {L_MULTIPLE}</label>	
									</dd>
								</dl>
								# START delete_poll #
								<dl>
									<dt><label for="del_poll">{L_DELETE_POLL}</label></dt>
									<dd><label><input type="checkbox" name="del_poll" id="del_poll" value="true" /></label></dd>
								</dl>
								# END delete_poll #
								<dl>
									<dt><label>{L_ANSWERS}</label></dt>
									<dd><label>									
										<table style="border:0">
											<tr>
												<td>								
													<input type="text" size="25" name="a0" value="{ANSWER0}" class="text" /><br />
													<input type="text" size="25" name="a1" value="{ANSWER1}" class="text" /><br />
													<input type="text" size="25" name="a2" value="{ANSWER2}" class="text" /><br />
													<input type="text" size="25" name="a3" value="{ANSWER3}" class="text" /><br />
													<input type="text" size="25" name="a4" value="{ANSWER4}" class="text" /><br />
													<p id="a11"style="text-align:center"><a href="javascript:add_field(11, 15)"><img src="../templates/{THEME}/images/form/plus.png" alt="+" /></a></a></p>
												</td>
												<td>
													<input type="text" size="25" name="a5" value="{ANSWER5}" class="text" /><br />
													<input type="text" size="25" name="a6" value="{ANSWER6}" class="text" /><br />
													<input type="text" size="25" name="a7" value="{ANSWER7}" class="text" /><br />
													<input type="text" size="25" name="a8" value="{ANSWER8}" class="text" /><br />
													<input type="text" size="25" name="a9" value="{ANSWER9}" class="text" /><br />
													<p id="a16"style="text-align:center"><a href="javascript:add_field(16, 20)"><img src="../templates/{THEME}/images/form/plus.png" alt="+" /></a></a></p>
												</td>
											</tr>	
										</table>
									</label></dd>
								</dl>
							</div>
						</fieldset>	
						
						<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
							<input type="hidden" name="idm" value="{IDM}" />
							<input type="submit" name="post_topic" value="{L_SUBMIT}" class="submit" />
							&nbsp;&nbsp; 									
							<script type="text/javascript">
							<!--				
							document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
							-->
							</script>						
							<noscript><input value="{L_PREVIEW}" type="submit" name="prw_t" class="submit" /></noscript>
							&nbsp;&nbsp;
							<input type="reset" value="{L_RESET}" class="reset" />				
						</fieldset>
					</div>
				</form>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		
		<br />
		
		<div class="module_position">
			<div class="row2">
				<span style="float:left;" class="text_strong">
					<a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span>
				</span>
				<span style="float:right;">
					{U_SEARCH}
					{U_TOPIC_TRACK}
					{U_LAST_MSG_READ}
					{U_MSG_NOT_READ}
				</span>&nbsp;
			</div>
		</div>
		
		# START display #
		<br /><br />
		<div class="forum_action" style="padding:5px;">
			{display.ICON_DISPLAY_MSG}
			<a href="action{display.U_ACTION_MSG_DISPLAY}">{display.L_EXPLAIN_DISPLAY_MSG}</a>
		</div>		
		# END display #	
		