		<script type="text/javascript">
		<!--
		function check_form_or(o){
			if(o.quotes_contents.value == "") {
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

		<form action="quotes.php{UPDATE}" method="post" onsubmit="return check_form_or(this);" class="fieldset_mini">
			<fieldset>
				<legend>{L_ADD_QUOTES}{L_UPDATE_QUOTES}</legend>
				<p>{L_REQUIRE}</p>
				
				# IF C_VISIBLE_QUOTES #
				<dl>
					<dt><label for="quotes_pseudo">* {L_PSEUDO}</label></dt>
					<dd><input type="text" size="25" maxlength="25" name="quotes_pseudo" id="quotes_pseudo" value="{PSEUDO}" class="text" /></dd>
				</dl>
				# ENDIF #			
				
				<label for="quotes_contents">* {L_CONTENTS}</label><br />
				<textarea rows="6" cols="25" id="quotes_contents" name="quotes_contents">{CONTENTS}</textarea>
				
				<label for="quotes_author">* {L_AUTHOR}</label><br />
				<input type="text" size="25" maxlength="25" id="quotes_author" name="quotes_author" value="{AUTHOR}" />
				
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="quotes" value="{L_SUBMIT}" class="submit" />				
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>
			# IF C_HIDDEN_QUOTES #
				<input type="hidden" size="25" maxlength="25" name="quotes_pseudo" value="{PSEUDO}" class="text" />
			# ENDIF #
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
		# START quotes #
		<div class="msg_position">
			<div class="msg_container{quotes.CLASS_COLOR}">
				<span id="m{quotes.ID}"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{quotes.USER_ONLINE} {quotes.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="{quotes.U_ANCHOR}"><img src="../templates/{THEME}/images/ancre.png" alt="{quotes.ID}" /></a> {quotes.DATE}</div>
					<div style="float:right;">{quotes.EDIT}{quotes.DEL}&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{quotes.USER_RANK}</p>
						<p style="text-align:center;">{quotes.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{quotes.USER_AVATAR}</p>
						<p style="text-align:center;">{quotes.USER_GROUP}</p>
						{quotes.USER_SEX}
						{quotes.USER_DATE}<br />
						{quotes.USER_MSG}<br />
						{quotes.USER_LOCAL}
					</div>
					<div class="msg_contents{quotes.CLASS_COLOR}">
						<div class="msg_contents_overflow">
							{quotes.CONTENTS}
						</div>
						<div class="msg_contents_overflow">
							{quotes.AUTHOR}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign{quotes.CLASS_COLOR}">				
				<div class="msg_sign_overflow">
					{quotes.USER_SIGN}	
				</div>				
				<hr />
				<div style="float:left;">
					{quotes.U_USER_PM} {quotes.USER_MAIL} {quotes.USER_MSN} {quotes.USER_YAHOO} {quotes.USER_WEB}
				</div>
				<div style="float:right;font-size:10px;">
					{quotes.WARNING} {quotes.PUNISHMENT}
				</div>&nbsp;
			</div>	
		</div>				
		# END quotes #		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">{PAGINATION}&nbsp;</div>
		</div>
