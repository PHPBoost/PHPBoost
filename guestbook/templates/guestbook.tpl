		<script type="text/javascript">
		<!--
		function check_form_or(){
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #
			
			if(document.getElementById('guestbook_contents').value == "") {
				alert("{L_ALERT_TEXT}");
				return false;
		    }
			return true;
		}

		function Confirm() {
		return confirm("{L_DELETE_MSG}");
		}
		
		-->
		</script>

		<form action="guestbook.php{UPDATE}" method="post" onsubmit="return check_form_or();" class="fieldset_mini">
			<fieldset>
				<legend>{L_ADD_MSG}{L_UPDATE_MSG}</legend>
				<p>{L_REQUIRE}</p>
				
				# IF C_VISIBLE_GUESTBOOK #
				<dl>
					<dt><label for="guestbook_pseudo">* {L_PSEUDO}</label></dt>
					<dd><label><input type="text" size="25" maxlength="25" name="guestbook_pseudo" id="guestbook_pseudo" value="{PSEUDO}" class="text" /></label></dd>
				</dl>
				# ENDIF #	
				
				# IF C_VERIF_CODE #
				<dl>
					<dt><label for="verif_code">* {L_VERIF_CODE}</label></dt>
					<dd>
						<label>
							{VERIF_CODE}
						</label>
					</dd>			
				</dl>
				# ENDIF #
				
				<label for="guestbook_contents">* {L_MESSAGE}</label>
				<div class="fieldset_mini">{KERNEL_EDITOR}</div>
				<label><textarea rows="10" cols="47" id="guestbook_contents" name="guestbook_contents">{CONTENTS}</textarea></label>
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				# IF C_HIDDEN_GUESTBOOK #
					<input type="hidden" size="25" maxlength="25" name="guestbook_pseudo" value="{PSEUDO}" class="text" />
				# ENDIF #
				
				<input type="hidden" name="guestbook_contents_ftags" id="guestbook_contents_ftags" value="{FORBIDDEN_TAGS}" />
				<input type="submit" name="guestbook" value="{L_SUBMIT}" class="submit" />
				
				<input value="{L_PREVIEW}" type="submit" name="previs" id="previs_guestbook" class="submit" />
				<script type="text/javascript">
				<!--				
				document.getElementById('previs_guestbook').style.display = 'none';
				document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" type="button" class="submit" />');
				-->
				</script>
				
				<input type="reset" value="{L_RESET}" class="reset" />			
			</fieldset>	
		</form>

		<br />
		# IF C_ERROR_HANDLER #
		<span id="errorh"></span>
		<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
			<br />	
		</div>
		<br />		
		# ENDIF #
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top" style="text-align:center;">{PAGINATION}&nbsp;</div>	
		</div>
		# START guestbook #
		<div class="msg_position">
			<div class="msg_container{guestbook.CLASS_COLOR}">
				<span id="m{guestbook.ID}"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{guestbook.USER_ONLINE} {guestbook.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="{guestbook.U_ANCHOR}"><img src="../templates/{THEME}/images/ancre.png" alt="{guestbook.ID}" /></a> {guestbook.DATE}</div>
					<div style="float:right;">{guestbook.EDIT}{guestbook.DEL}&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{guestbook.USER_RANK}</p>
						<p style="text-align:center;">{guestbook.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{guestbook.USER_AVATAR}</p>
						<p style="text-align:center;">{guestbook.USER_GROUP}</p>
						{guestbook.USER_SEX}
						{guestbook.USER_DATE}<br />
						{guestbook.USER_MSG}<br />
						{guestbook.USER_LOCAL}
					</div>
					<div class="msg_contents{guestbook.CLASS_COLOR}">
						<div class="msg_contents_overflow">
							{guestbook.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign{guestbook.CLASS_COLOR}">				
				<div class="msg_sign_overflow">
					{guestbook.USER_SIGN}	
				</div>				
				<hr />
				<div style="float:left;">
					{guestbook.U_USER_PM} {guestbook.USER_MAIL} {guestbook.USER_MSN} {guestbook.USER_YAHOO} {guestbook.USER_WEB}
				</div>
				<div style="float:right;font-size:10px;">
					{guestbook.WARNING} {guestbook.PUNISHMENT}
				</div>&nbsp;
			</div>	
		</div>				
		# END guestbook #		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">{PAGINATION}&nbsp;</div>
		</div>
		