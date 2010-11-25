		# IF C_CONFIRM_REGISTER #
		<form action="" method="post">
			<table class="module_table">
				<tr>
					<th colspan="2">
						{L_REGISTER}
					</th>
				</tr>
				<tr>
					<td colspan="2" class="row3" style="text-align:center;">						
						<span class="text_strong">{L_REGISTRATION_TERMS}</span>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="row2">
						# IF L_HAVE_TO_ACCEPT #
							<div class="warning" style="margin:15px auto;width:550px;">{L_HAVE_TO_ACCEPT}</div>
						# ENDIF #
						{MSG_REGISTER}
					</td>
				</tr>
				<tr>
					<td class="row3" style="text-align:center;">
						<label><input type="checkbox" name="confirm" value="true" /> {L_ACCEPT}</label>
					</td>	
				</tr>	
			</table>
			<br /><br />
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="register_valid" value="{L_SUBMIT}" class="submit" />
			</fieldset>
		</form>
		# ENDIF #


		# IF C_ACTIVATION_REGISTER #
		<form action="" method="post">
		<table class="module_table">
			<tr>
				<th colspan="2">
					{L_REGISTER}
				</th>
			</tr>
			<tr>
				<td colspan="2" class="row3" style="text-align:center;">
					<br /><br />
					<span class="text_strong">{L_ACTIVATION_REPORT}</span>
					<br /><br /><br />
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border: solid 1px black;" class="news_bottom">
					&nbsp;
				</td>
			</tr>
		</table>
		</form>
		# ENDIF #


		# IF C_REGISTER #
		<script type="text/javascript">
		<!--
		function check_form()
		{
			if(document.getElementById('mail').value == "") {
				alert("{L_REQUIRE_MAIL}");
				return false;
		    }
			if(document.getElementById('log').value == "") {
				alert("{L_REQUIRE_PSEUDO}");
				return false;
		    }
			if(document.getElementById('pass').value == "") {
				alert("{L_REQUIRE_PASSWORD}");
				return false;
		    }
			if( document.getElementById('pass_bis').value == "" ) {
				alert("{L_REQUIRE_PASSWORD}");
				return false;
		    }
			{L_REQUIRE_VERIF_CODE}
			
			# IF C_MISCELLANEOUS #
				# START extend_fields_js_list #
				if(document.getElementById('{extend_fields_js_list.ID}') && document.getElementById('{extend_fields_js_list.ID}').value == "") {
					alert("{extend_fields_js_list.L_REQUIRED}");
					return false;
				}
				# END extend_fields_js_list #	
			# ENDIF #
			
			return true;
		}
		function img_sex(url)
		{
			if( document.getElementById('img_sex') )
			{
				var img_sex = '';
				if( url == 1 )
					img_sex = 'man.png';
				else if( url == 2 )
					img_sex = 'woman.png';
				document.getElementById('img_sex').innerHTML = (img_sex != '') ? '<img src="../templates/{THEME}/images/' + img_sex + '" alt="" />' : '';
			}
		}
		function change_img_theme(id, value)
		{
			if(document.images )
				document.images[id].src = "../templates/" + value + "/theme/images/theme.jpg";
		}
		
		var array_identifier = new Array();
		{JS_LANG_IDENTIFIER}
		function change_img_lang(id, lang)
		{
			if (array_identifier[lang] && document.getElementById(id)) 
				document.getElementById(id).src = '../images/stats/countries/' + array_identifier[lang] + '.png';
		}
		
		function XMLHttpRequest_register_login(value)
		{
			document.getElementById('msg_login').innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" />';
			data = "login=" + value;
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/member/register_xmlhttprequest.php');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText == '1' )
				{
					document.getElementById('msg_login').innerHTML = '<img src="../templates/{THEME}/images/forbidden_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_login_div').innerHTML = "{L_PSEUDO_AUTH}";
				}
				else if( xhr_object.readyState == 4 )
				{
					document.getElementById('msg_login').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_login_div').innerHTML = '';
				}
			}
			xmlhttprequest_sender(xhr_object, data);
		}
		function check_login(value) 
		{
			if (value.length<3)			
			{	
				document.getElementById('msg_login').innerHTML = '<img src="../templates/{THEME}/images/forbidden_mini.png" alt="" class="valign_middle" />';
				document.getElementById('msg_login_div').innerHTML = "{L_PSEUDO_HOW}";
			}
			else	
				XMLHttpRequest_register_login(value);
		}
		
		function XMLHttpRequest_register_mail(value)
		{
			document.getElementById('msg_email').innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" />';
			data = "mail=" + value;
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/member/register_xmlhttprequest.php');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText == '1' )
				{
					document.getElementById('msg_email').innerHTML = '<img src="../templates/{THEME}/images/forbidden_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_email_div').innerHTML = "{L_MAIL_AUTH}";
				}
				else if( xhr_object.readyState == 4 )
				{	
					document.getElementById('msg_email').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_email_div').innerHTML = "";
				}
			}
			xmlhttprequest_sender(xhr_object, data);
		}
		function check_email(value) 
		{
			if (!check_mail_validity(value))
			{	
				document.getElementById('msg_email').innerHTML = '<img src="../templates/{THEME}/images/forbidden_mini.png" alt="" class="valign_middle" />';
				document.getElementById('msg_email_div').innerHTML = "{L_MAIL_INVALID}";
			}
			else	
				XMLHttpRequest_register_mail(value);	
		}
		function check_password(value) 
		{
			if (value.length<6)
			{	
				document.getElementById('msg_password1').innerHTML = '<img src="../templates/{THEME}/images/forbidden_mini.png" alt="" class="valign_middle" />';
				document.getElementById('msg_password1_div').innerHTML = "{L_PASSWORD_HOW}";
			}
			else
			{
				var password = document.getElementById('pass_bis').value;
				if (password == value)
				{
					document.getElementById('msg_password1').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_password1_div').innerHTML = '';
					document.getElementById('msg_password2').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_password2_div').innerHTML = '';
				}
				else if (password.length > 0)
				{	
					document.getElementById('msg_password1').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_password1_div').innerHTML = '';
					document.getElementById('msg_password2').innerHTML = '<img src="../templates/{THEME}/images/forbidden_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_password2_div').innerHTML = "{L_PASSWORD_SAME}";
				}
				else
				{
					document.getElementById('msg_password1').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_password1_div').innerHTML = '';
				}
			}	
		}
		function check_password2(value) 
		{
			if (value.length<6)
			{	
				document.getElementById('msg_password2').innerHTML = '<img src="../templates/{THEME}/images/forbidden_mini.png" alt="" class="valign_middle" />';
				document.getElementById('msg_password2_div').innerHTML = "{L_PASSWORD_HOW}";
			}
			else
			{
				var password = document.getElementById('pass').value;
				if (password == value)
				{
					document.getElementById('msg_password1').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_password1_div').innerHTML = '';
					document.getElementById('msg_password2').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_password2_div').innerHTML = '';
				}
				else if (password.length > 0)
				{	
					document.getElementById('msg_password2').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_password2_div').innerHTML = '';
					document.getElementById('msg_password1').innerHTML = '<img src="../templates/{THEME}/images/forbidden_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_password1_div').innerHTML = "{L_PASSWORD_SAME}";
				}
				else
				{
					document.getElementById('msg_password2').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_password2_div').innerHTML = '';
				}
			}	
		}
		-->		
		</script>

		# IF C_ERROR_HANDLER #
		<span id="errorh"></span>
		<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
			<br />	
		</div>
		<br />		
		# ENDIF #
		
		
		<script type="text/javascript">
		<!--
			var theme = '{THEME}';
		-->
		</script>
		<script type="text/javascript" src="../kernel/lib/js/phpboost/calendar.js"></script>
		<form action="../member/register_valid.php" enctype="multipart/form-data" method="post" onsubmit="return check_form();" class="fieldset_content">
			<fieldset>
				<legend>{L_REGISTER}</legend>
				<p>
					{L_REQUIRE}
					
					# START activ_mbr #
					<br />
					<strong>{activ_mbr.L_ACTIV_MBR}</strong>
					# END activ_mbr #
				</p>
				
				<dl>
					<dt><label for="log">* {L_PSEUDO}</label><br /><span>{L_PSEUDO_HOW}</span></dt>
					<dd><label><input size="25" type="text" class="text" name="log" id="log" maxlength="25" onblur="check_login(this.value);" /> &nbsp;<span id="msg_login"></span><div style="font-weight:bold" id="msg_login_div"></div></label></dd>			
				</dl>
				<dl>
					<dt><label for="mail">* {L_MAIL}</label><br /><span>{L_VALID}</span></dt>
					<dd><label><input size="30" type="text" class="text" name="mail" id="mail" maxlength="50" onblur="check_email(this.value);" /> &nbsp;<span id="msg_email"></span><div style="font-weight:bold" id="msg_email_div"></div></label></dd>			
				</dl>
				<dl>
					<dt><label for="pass">* {L_PASSWORD}</label><br /><span>{L_PASSWORD_HOW}</span></dt>
					<dd><label><input size="30" type="password" class="text" name="pass" id="pass" maxlength="30" onblur="check_password(this.value);" /> &nbsp;<span id="msg_password1"></span><div style="font-weight:bold" id="msg_password1_div"></div></label></dd>			
				</dl>
				<dl>
					<dt><label for="pass_bis">* {L_CONFIRM_PASSWORD}</label></dt>
					<dd><label><input size="30" type="password" class="text" name="pass_bis" id="pass_bis" maxlength="30" onblur="check_password2(this.value);" /> &nbsp;<span id="msg_password2"></span><div style="font-weight:bold" id="msg_password2_div"></div></label></dd>			
				</dl>
				# IF C_VERIF_CODE #
				<dl>
					<dt><label for="verif_code">* {L_VERIF_CODE}</label><br /><span>{L_VERIF_CODE_EXPLAIN}</span></dt>
					<dd>
						<label>
							{VERIF_CODE}
						</label>
					</dd>			
				</dl>
				# ENDIF #
				<dl>
					<dt><label for="user_lang">* {L_LANG_CHOOSE}</label></dt>
					<dd>
						<label>
							<select name="user_lang" id="user_lang" onchange="change_img_lang('img_lang', this.options[this.selectedIndex].value)">						
								# START select_lang #
									<option value="{select_lang.IDNAME}"{select_lang.SELECTED}>{select_lang.NAME}</option>
								# END select_lang #					
							</select>
							&nbsp;<img id="img_lang" src="{IMG_LANG_IDENTIFIER}" alt="" class="valign_middle" />
						</label>
					</dd>			
				</dl>
			</fieldset>
				
			<fieldset>
				<legend>{L_OPTIONS}</legend>					
				<dl>
					<dt><label for="user_theme">{L_THEME_CHOOSE}</label></dt>
					<dd>
						<label>
							<select name="user_theme" id="user_theme" onchange="change_img_theme('img_theme', this.options[selectedIndex].value)">			
								# START select_theme #
									<option value="{select_theme.IDNAME}"{select_theme.SELECTED}>{select_theme.NAME}</option>
								# END select_theme #
							</select>
							<img id="img_theme" src="../templates/{THEME}/theme/images/theme.jpg" alt="" style="vertical-align:top" />
						</label>
					</dd>			
				</dl>
				<dl>
					<dt><label for="user_editor">{L_EDITOR_CHOOSE}</label></dt>
					<dd>
						<label>
							<select name="user_editor" id="user_editor">	
								{SELECT_EDITORS}						
							</select>
						</label>
					</dd>			
				</dl>
				<dl>
					<dt><label for="user_timezone">{L_TIMEZONE_CHOOSE}</label><br /><span>{L_TIMEZONE_CHOOSE_EXPLAIN}</span></dt>
					<dd>
						<label>
							<select name="user_timezone" id="user_timezone">	
								{SELECT_TIMEZONE}						
							</select>
						</label>
					</dd>			
				</dl>
				<dl>
					<dt><label for="user_show_mail">{L_HIDE_MAIL}</label></dt>
					<dd><label><input type="checkbox" class="text" name="user_show_mail" id="user_show_mail" checked="checked" /></label></dd>			
				</dl>
			</fieldset>	

			<fieldset>
				<legend>{L_INFO}</legend>			
				<dl>
					<dt><label for="user_web">{L_WEB_SITE}</label><br /><span>{L_VALID}</span></dt>
					<dd><label><input size="30" type="text" class="text" name="user_web" id="user_web" maxlength="70" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="user_local">{L_LOCALISATION}</label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_local" id="user_local" maxlength="25" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="user_occupation">{L_JOB}</label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_occupation" id="user_occupation" maxlength="50" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="user_hobbies">{L_HOBBIES}</label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_hobbies" id="user_hobbies" maxlength="50" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="user_sex">{L_SEX}</label></dt>
					<dd><label>
						<select name="user_sex" id="user_sex" onchange="img_sex(this.options[selectedIndex].value)">
							<option selected="seleted" value="0">--</option>
							<option value="1">{L_MALE}</option>
							<option value="2">{L_FEMALE}</option>
						</select>
						<span id="img_sex"></span>
					</label></dd>			
				</dl>
				<dl class="overflow_visible">
					<dt><label for="user_born">{L_DATE_OF_BIRTH}</label><br /><span>{L_VALID}</span></dt>
					<dd><label>
						<input size="10" maxlength="10" type="text" class="text" id="user_born" name="user_born" /> 
						
						<div style="position:relative;z-index:100;top:26px;margin-left:25px;float:left;display:none;" id="calendar1">
							<div id="calendar" class="calendar_block" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);"></div>
						</div>
						<a onclick="xmlhttprequest_calendar('calendar', '?input_field=user_born&amp;field=calendar&amp;lyear=1');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgcalendar" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
					</label></dd>			
				</dl>
				<dl>
					<dt><label for="user_sign">{L_SIGN}</label><br /><span>{L_SIGN_WHERE}</span></dt>
					<dd><label><textarea class="post" rows="4" cols="27" name="user_sign" id="user_sign"></textarea> </label></dd>			
				</dl>
			</fieldset>
			
			<fieldset>
				<legend>{L_CONTACT}</legend>			
				<dl>
					<dt><label for="user_msn">MSN</label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_msn" id="user_msn" maxlength="50" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="user_yahoo">Yahoo</label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_yahoo" id="user_yahoo" maxlength="50" /></label></dd>			
				</dl>			
			</fieldset>
			
			<fieldset>
				<legend>{L_AVATAR_MANAGEMENT}</legend>		
				# START upload_avatar #
				<dl>
					<dt><label for="avatars">{L_UPLOAD_AVATAR}</label><br /><span>{L_UPLOAD_AVATAR_WHERE}</span></dt>
					<dd><label>
						<input type="file" name="avatars" id="avatars" size="30" class="text" />					
						<input type="hidden" name="max_file_size" value="2000000" />
						<br />
						{L_WEIGHT_MAX}: {upload_avatar.WEIGHT_MAX} ko
						<br />
						{L_HEIGHT_MAX}: {upload_avatar.HEIGHT_MAX} pixels
						<br />
						{L_WIDTH_MAX}: {upload_avatar.WIDTH_MAX} pixels
					</label></dd>			
				</dl>
				# END upload_avatar #		
				<dl>
					<dt><label for="user_avatar">{L_AVATAR_LINK}</label><br /><span>{L_AVATAR_LINK_WHERE}</span></dt>
					<dd><label><input type="text" name="user_avatar" id="user_avatar" size="30" /></label></dd>			
				</dl>
			</fieldset>

			# IF C_MISCELLANEOUS #
			<fieldset>
				<legend>{L_MISCELLANEOUS}</legend>	
					
				# START extend_fields_list #
				<dl>
					<dt><label for="{extend_fields_list.ID}">{extend_fields_list.NAME}</label><br /><span>{extend_fields_list.DESC}</span></dt>
					<dd>{extend_fields_list.FIELD}</dd>
				</dl>
				# END extend_fields_list #	
			</fieldset>
			# ENDIF #

			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />	
			</fieldset>
		</form>
		# ENDIF #
		