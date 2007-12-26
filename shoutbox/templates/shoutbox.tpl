		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('contents').value == "") {
				alert("{L_ALERT_TEXT}");
				return false;
		    }
			return true;
		}
		function Confirm_shout() {
		return confirm("{L_DELETE_MSG}");
		}

		-->
		</script>

		<form action="shoutbox.php{SID}{UPDATE}" method="post" onsubmit="return check_form();" class="fieldset_mini">	
			<fieldset>
				<legend>{L_ADD_MSG}{L_UPDATE_MSG}</legend>
				<p>{L_REQUIRE}</p>
				
				# START visible #
				<dl>
					<dt><label for="shout_pseudo">* {L_PSEUDO}</label></dt>
					<dd><label><input type="text" size="25" maxlength="25" name="shout_pseudo" id="shout_pseudo" value="{visible.PSEUDO}" class="text" /></label></dd>
				</dl>
				# END visible #

				<label for="shout_contents">* {L_MESSAGE}</label>
				{BBCODE}
				<label><textarea rows="10" cols="50" id="shout_contents" name="shout_contents">{CONTENTS}</textarea></label>
				<p>
					<strong>{L_FORBIDDEN_TAGS}</strong> {DISPLAY_FORBIDDEN_TAGS}
				</p>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				# START hidden #
					<input size="25" maxlength="25" type="hidden" class="text" name="shout_pseudo" value="{hidden.PSEUDO}" />
				# END hidden #
				
				<input type="hidden" name="shout_contents_ftags" id="shout_contents_ftags" value="{FORBIDDEN_TAGS}" />
				<input type="submit" name="shoutbox" value="{L_SUBMIT}" class="submit" />
				<script type="text/javascript">
				<!--				
				document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
				-->
				</script>
				<input type="reset" value="{L_RESET}" class="reset" />			
			</fieldset>	
		</form>

		<br />
		# START error_handler #
		<span id="errorh"></span>
		<div class="{error_handler.CLASS}">
			<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
		</div>
		<br />		
		# END error_handler #
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top" style="text-align:center;">{PAGINATION}&nbsp;</div>	
		</div>
		# START shoutbox #
		<div class="msg_position">
			<div class="msg_container">
				<span id="m{shoutbox.ID}">
				<span id="com"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{shoutbox.USER_ONLINE} {shoutbox.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="{shoutbox.U_ANCHOR}"><img src="../templates/{THEME}/images/ancre.png" alt="{shoutbox.ID}" /></a> {shoutbox.DATE}</div>
					<div style="float:right;">{shoutbox.EDIT}{shoutbox.DEL}&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{shoutbox.USER_RANK}</p>
						<p style="text-align:center;">{shoutbox.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{shoutbox.USER_AVATAR}</p>
						<p style="text-align:center;">{shoutbox.USER_GROUP}</p>
						{shoutbox.USER_SEX}
						{shoutbox.USER_DATE}<br />
						{shoutbox.USER_MSG}<br />
						{shoutbox.USER_LOCAL}
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							{shoutbox.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					{shoutbox.USER_SIGN}
				</div>				
				<hr />
				<div style="float:right;font-size:10px;">
					{shoutbox.WARNING} {shoutbox.PUNISHMENT}
				</div>&nbsp;
			</div>	
		</div>				
		# END shoutbox #		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">{PAGINATION}&nbsp;</div>
		</div>
		