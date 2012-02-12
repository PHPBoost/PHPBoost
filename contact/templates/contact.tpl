		<script type="text/javascript">
		<!--
		function check_form_mail(){
			if(document.getElementById('mail_email').value == "") {
				alert("{L_REQUIRE_MAIL}");
				return false;
		    }
			if(document.getElementById('mail_contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
		    }
			{L_REQUIRE_VERIF_CODE}
			return true;
		}
		-->
		</script>

		# IF C_ERROR_HANDLER #
		<span id="errorh"></span>
		<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
			<br />	
		</div>
		# ENDIF #
				
		<form action="{U_ACTION_CONTACT}" method="post" onsubmit="return check_form_mail();" class="fieldset_mini">
			<fieldset>
				<legend>{L_CONTACT_MAIL}</legend>
				<p>{L_REQUIRE}</p>
				<dl>
					<dt><label for="mail_email">* {L_MAIL}</label><br /><span>{L_VALID_MAIL}</span></dt>
					<dd><label><input type="text" size="30" maxlength="50" id="mail_email" name="mail_email" value="{MAIL}" class="text" /></label></dd>
				</dl>		
				<dl>
					<dt><label for="mail_objet">{L_OBJET}</label></dt>
					<dd><label><input type="text" size="30" name="mail_object" id="mail_object" class="text" value="{CONTACT_OBJECT}" /></label></dd>
				</dl>
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
				<label for="mail_contents">* {L_CONTENTS}</label>
				<label><textarea rows="10" cols="47" id="mail_contents" name="mail_contents">{CONTACT_CONTENTS}</textarea></label>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="mail_valid" value="{L_SUBMIT}" class="submit" />
				&nbsp;
				<input type="reset" value="{L_RESET}" class="reset" />
				<input type="hidden" name="token" value="{TOKEN}" />			
			</fieldset>
		</form>
