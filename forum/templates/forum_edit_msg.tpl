		<script type='text/javascript'>
		<!--
		function check_form_post(){
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
			}
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
							<legend>{L_EDIT_MESSAGE}</legend>
							<p>{L_REQUIRE}</p>
							<label for="contents">* {L_MESSAGE}</label>
							{BBCODE}
							<label><textarea type="text" rows="25" cols="66" id="contents" name="contents">{CONTENTS}</textarea></label>
						</fieldset>
						
						<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
							<input type="hidden" name="p_update" value="{P_UPDATE}" />
							<input type="submit" name="edit_msg" value="{L_SUBMIT}" class="submit" />
							&nbsp;&nbsp; 									
							<script type="text/javascript">
							<!--				
							document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
							-->
							</script>						
							<noscript><input value="{L_PREVIEW}" type="submit" name="prw" class="submit" /></noscript>
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
		