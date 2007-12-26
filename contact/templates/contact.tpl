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
		
		function refresh_img()
		{
			if ( typeof this.img_id == 'undefined' )
				this.img_id = 0;
			else
				this.img_id++;
			
			var xhr_object = null;
			var data = null;
			var filename = "../member/verif_code.php";
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;

			data = "new=1";
			xhr_object.open("POST", filename, true);					
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 ) 
				{					
					document.getElementById('verif_code_img').src = '../member/verif_code.php?new=' + img_id;	
				}
			}

			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(data);
		}
		-->
		</script>

		# START error_handler #
		<span id="errorh"></span>
		<div class="{error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
			<br />	
		</div>
		# END error_handler #
				
		<form action="contact.php{U_ACTION_CONTACT}" method="post" onsubmit="return check_form_mail();" class="fieldset_mini">
			<fieldset>
				<legend>{L_CONTACT_MAIL}</legend>
				<p>{L_REQUIRE}</p>
				<dl>
					<dt><label for="mail_email">* {L_MAIL}</label><br /><span>{L_VALID_MAIL}</span></dt>
					<dd><label><input type="text" size="30" maxlength="50" id="mail_email" name="mail_email" value="{MAIL}" class="text" /></label></dd>
				</dl>		
				<dl>
					<dt><label for="mail_objet">{L_OBJET}</label></dt>
					<dd><label><input type="text" size="30" name="mail_objet" id="mail_objet" class="text" /></label></dd>
				</dl>
				# START verif_code #
				<dl>
					<dt><label for="verif_code">* {L_VERIF_CODE}</label><br /><span>{L_VERIF_CODE_EXPLAIN}</span></dt>
					<dd><label>
						<img src="../member/verif_code.php" id="verif_code_img" alt="" style="padding:2px;" />
						<br />
						<input size="30" type="text" class="text" name="verif_code" id="verif_code" />
						<a href="javascript:refresh_img()"><img src="../templates/{THEME}/images/refresh.png" alt="" class="valign_middle" /></a>
					</label></dd>			
				</dl>
				# END verif_code #
				<label for="mail_contents">* {L_CONTENTS}</label>
				<label><textarea rows="10" cols="47" id="mail_contents" name="mail_contents"></textarea></label>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="mail_valid" value="{L_SUBMIT}" class="submit" />
				&nbsp;
				<input type="reset" value="{L_RESET}" class="reset" />			
			</fieldset>
		</form>
