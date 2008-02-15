		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('mail').value == "") {
				alert("{L_REQUIRE_MAIL}");
				return false;
		    }
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
				document.images[id].src = "../templates/" + value + "/images/theme.jpg";
		}
		
		var array_identifier = new Array();
		{JS_LANG_IDENTIFIER}
		function change_img_lang(id, lang)
		{
			if( array_identifier[lang] && document.getElementById(id) ) 
				document.getElementById(id).src = '../images/stats/countries/' + array_identifier[lang] + '.png';
		}
		-->
		</script>

		# START update #

		# IF C_ERROR_HANDLER #
		<span id="errorh"></span>
		<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
			<br />	
		</div>
		<br />		
		# ENDIF #
		
		<script type="text/javascript" src="../templates/{THEME}/images/calendar.js"></script>
		<form action="member{update.U_MEMBER_ACTION_UPDATE}" enctype="multipart/form-data" method="post" onsubmit="return check_form();" class="fieldset_content">
			<fieldset>
				<legend>{L_PROFIL_EDIT}</legend>
				<dl>
					<dt><label for="mail">* {L_MAIL}</label><br /><span>{L_VALID}</span></dt>
					<dd><label><input type="text" maxlength="50" size="30" id="mail" name="mail" value="{update.MAIL}" class="text" /></label></dd>
				</dl>
				<dl>
					<dt><label for="pass_old">(*) {L_PREVIOUS_PASS}</label><br /><span>{L_EDIT_JUST_IF_MODIF}</span></dt>
					<dd><label><input size="30" type="password" class="text" name="pass_old" id="pass_old" maxlength="30" /></label></dd>
				</dl>
				<dl>
					<dt><label for="pass">(*) {L_NEW_PASS}</label><br /><span>{L_EDIT_JUST_IF_MODIF}</span></dt>
					<dd><label><input size="30" type="password" class="text" name="pass" id="pass" maxlength="30" /></label></dd>
				</dl>
				<dl>
					<dt><label for="pass_bis">(*) {L_CONFIRM_PASS}</label><br /><span>{L_EDIT_JUST_IF_MODIF}</span></dt>
					<dd><label><input size="30" type="password" class="text" name="pass_bis" id="pass_bis" maxlength="30" /></label></dd>
				</dl>
				<dl>
					<dt><label for="del_member">{L_DEL_MEMBER}</label></dt>
					<dd><label><input size="30" type="checkbox" name="del_member" id="del_member" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_lang">* {L_LANG_CHOOSE}</label></dt>
					<dd>
						<label>
							<select name="user_lang" id="user_lang" onchange="change_img_lang('img_lang', this.options[this.selectedIndex].value)">	
								# START update.select_lang #
								{update.select_lang.LANG}
								# END update.select_lang #
							</select> <img id="img_lang" src="{IMG_LANG_IDENTIFIER}" alt="" class="valign_middle" />
						</label>
					</dd>
				</dl>
			</fieldset>
				
			<fieldset>
				<legend>{L_OPTIONS}</legend>
				<dl>
					<dt><label for="user_theme">* {L_THEME_CHOOSE}</label></dt>
					<dd>
						<label>
							<select name="user_theme" id="user_theme" onChange="change_img_theme('img_theme', this.options[selectedIndex].value)">
								# START update.select_theme #
								{update.select_theme.THEME}
								# END update.select_theme #
							</select>
							<img id="img_theme" src="../templates/{update.USER_THEME}/images/theme.jpg" alt="" style="vertical-align:top" />
						</label>
					</dd>
				</dl>
				<dl>
					<dt><label for="user_editor">* {L_EDITOR_CHOOSE}</label></dt>
					<dd>
						<label>
							<select name="user_editor" id="user_editor">
								# START update.select_editor #
								{update.select_editor.SELECT_EDITORS}
								# END update.select_editor #
							</select>
						</label>
					</dd>
				</dl>
				<dl>
					<dt><label for="user_timezone">{L_TIMEZONE_CHOOSE}</label><br /><span>{L_TIMEZONE_CHOOSE_EXPLAIN}</span></dt>
					<dd>
						<label>
							<select name="user_timezone" id="user_timezone">	
								# START update.select_timezone #
								{update.select_timezone.SELECT_TIMEZONE}
								# END update.select_timezone #						
							</select>
						</label>
					</dd>			
				</dl>
				<dl>
					<dt><label for="user_show_mail">{L_HIDE_MAIL}</label><br /><span>{L_HIDE_MAIL_WHO}</span></dt>
					<dd><label><input type="checkbox" {update.SHOW_MAIL_CHECKED} name="user_show_mail" id="user_show_mail" /></label></dd>
				</dl>
			</fieldset>	
			
			<fieldset>
				<legend>{L_INFO}</legend>
				<dl>
					<dt><label for="user_web">{L_SITE_WEB}</label><br /><span>{L_VALID}</span></dt>
					<dd><label><input size="30" type="text" class="text" name="user_web" id="user_web" value="{update.WEB}" maxlength="70" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_local">{L_LOCALISATION}</label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_local" id="user_local" value="{update.LOCAL}" maxlength="25" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_occupation">{L_JOB}</label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_occupation" id="user_occupation" value="{update.OCCUPATION}" maxlength="50" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_hobbies">{L_HOBBIES}</label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_hobbies" id="user_hobbies" value="{update.HOBBIES}" maxlength="50" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_sex">{L_SEX}</label></dt>
					<dd><label>
						<select name="user_sex" id="user_sex" onchange="img_sex(this.options[selectedIndex].value)">
							# START update.select_sex #							
							{update.select_sex.SEX}							
							# END update.select_sex #
						</select>
						<span id="img_sex">{update.USER_SEX}</span>
					</label></dd>
				</dl>
				<dl class="overflow_visible">
					<dt><label for="user_born">{L_DATE_OF_BIRTH}</label><br /><span>{L_DATE_FORMAT}</span></dt>
					<dd><label>
						<input size="10" maxlength="10" type="text" class="text" id="user_born" name="user_born" value="{update.USER_BORN}" /> 

						<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
							<div id="calendar" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('calendar', '?input_field=user_born&amp;field=calendar&amp;lyear=1&amp;d={update.BORN_DAY}&amp;m={update.BORN_MONTH}&amp;y={update.BORN_YEAR}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img class="valign_middle" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
					</label></dd>
				</dl>
				<dl>
					<dt><label for="user_sign">{L_SIGN}</label><br /><span>{L_SIGN_WHERE}</span></dt>
					<dd><label><textarea class="post" rows="4" cols="27" name="user_sign" id="user_sign">{update.USER_SIGN}</textarea></label></dd>
				</dl>
				<dl>
					<dt><label for="user_desc">{L_BIOGRAPHY}</label></dt>
					<dd><label><textarea class="post" rows="6" cols="27" id="user_desc" name="user_desc">{update.USER_DESC}</textarea> </label></dd>
				</dl>
			</fieldset>
				
			<fieldset>
				<legend>{L_CONTACT}</legend>
				<dl>
					<dt><label for="user_msn">MSN</label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_msn" id="user_msn" value="{update.USER_MSN}" maxlength="50" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_yahoo">Yahoo</label></dt>
					<dd><input size="30" type="text" class="text" name="user_yahoo" id="user_yahoo" value="{update.USER_YAHOO}" maxlength="50" /></label></dd>
				</dl>
			</fieldset>	
				
			<fieldset>
				<legend>{L_AVATAR_MANAGEMENT}</legend>
				<dl>
					<dt><label>{L_CURRENT_AVATAR}</label></dt>
					<dd>{update.USER_AVATAR}</label></dd>
				</dl>	
				
				# START update.upload_avatar #
				<dl>
					<dt><label for="avatars">{L_UPLOAD_AVATAR}</label><br /><span>{L_UPLOAD_AVATAR_WHERE}</span></dt>
					<dd><label>
						<input type="file" name="avatars" id="avatars" size="30" class="submit" />					
						<input type="hidden" name="max_file_size" value="2000000" />
						<br />
						{L_WEIGHT_MAX}: {update.upload_avatar.WEIGHT_MAX} ko
						<br />
						{L_HEIGHT_MAX}: {update.upload_avatar.HEIGHT_MAX} pixels
						<br />
						{L_WIDTH_MAX}: {update.upload_avatar.WIDTH_MAX} pixels
					</label></dd>
				</dl>
				# END update.upload_avatar #
				
				<dl>
					<dt><label for="avatar">{L_AVATAR_LINK}</label><br /><span>{L_AVATAR_LINK_WHERE}</span></dt>
					<dd><label><input type="text" name="avatar" id="avatar" size="30" class="text" /></label></dd>
				</dl>
				<dl>
					<dt><label for="delete_avatar">{L_AVATAR_DEL}</label></dt>
					<dd><label><input type="checkbox" name="delete_avatar" id="delete_avatar" /></label></dd>
				</dl>
			</fieldset>
			
			# START update.miscellaneous #
			<fieldset>
				<legend>{L_MISCELLANEOUS}</legend>	
					
				# START update.miscellaneous.list #
				<dl>
					<dt><label for="{update.miscellaneous.list.ID}">{update.miscellaneous.list.NAME}</label><br /><span>{update.miscellaneous.list.DESC}</span></dt>
					<dd><label>{update.miscellaneous.list.FIELD}</label></dd>
				</dl>
				# END update.miscellaneous.list #	
			</fieldset>
			# END update.miscellaneous #	

			<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
				<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />	
				&nbsp;&nbsp; 
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>
		</form>

		# END update #



		# START msg_mbr #

		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><strong>{L_PROFIL}</strong></div>
			<div class="module_contents">
				<p style="text-align:center;" class="text_strong">{L_WELCOME} {msg_mbr.USER_NAME}</p>
				<table class="module_table">
					<tr>
						<td class="row2" style="text-align:center;width:33%">
							<a href="member{msg_mbr.U_MEMBER_ID}">{L_PROFIL_EDIT}</a>
							<br /><br />
							<a href="member{msg_mbr.U_MEMBER_ID}" title="">
								<img src="../templates/{THEME}/images/upload/member.png" alt="{L_PROFIL_EDIT}" title="{L_PROFIL_EDIT}" />
							</a>
						</td>
						<td class="row2" style="text-align:center;width:34%">
							<a href="pm{msg_mbr.U_MEMBER_PM}">{msg_mbr.PM} {L_PRIVATE_MESSAGE}</a> <br /><br />
							<a href="pm{msg_mbr.U_MEMBER_PM}">
								<img src="../templates/{THEME}/images/{msg_mbr.IMG_PM}" alt="{L_PRIVATE_MESSAGE}" title="{L_PRIVATE_MESSAGE}" />
							</a>
						</td>
						# START msg_mbr.files_management #
						<td class="row2" style="text-align:center;width:33%">
							<a href="upload.php{SID}">{L_FILES_MANAGEMENT}</a> <br /><br />
							<a href="upload.php{SID}">
								<img src="../templates/{THEME}/images/upload/files_add.png" alt="{L_FILES_MANAGEMENT}" title="{L_FILES_MANAGEMENT}" />
							</a>
						</td>				
						# END msg_mbr.files_management #
					</tr>
				</table>
				<br /><br />
				{msg_mbr.MSG_MBR}
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		# END msg_mbr #



		# START all #

		<script type="text/javascript">
		<!--
		function XMLHttpRequest_search()
		{
			var xhr_object = xmlhttprequest_init('../includes/xmlhttprequest.php?member=1');
			var login = document.getElementById("login_mbr").value;
			var data = null;
			
			if( login != "" )
			{
				data = 'login=' + login;	
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 ) 
					{
						document.getElementById("xmlhttprequest_result_search").innerHTML = xhr_object.responseText;
						hide_div("xmlhttprequest_result_search");
					}
				}
				xmlhttprequest_sender(xhr_object, data);
			}	
			else
				alert("{L_REQUIRE_LOGIN}");
		}

		function hide_div(divID)
		{
			if( document.getElementById(divID) )
				document.getElementById(divID).style.display = 'block';
		}
		-->
		</script>

		<table class="module_table" style="width:98%;">	
			<tr>
				<td style="vertical-align:top;" class="row2">
					<form action="../member/member.php{SID}" method="post">
						{L_SELECT_GROUP}: <select name="show_group" style="text-align:center;" onchange="document.location = {U_SELECT_SHOW_GROUP};">
							<option value="0" selected="selected">-- {L_LIST} --</option>
							# START all.group #
								{all.group.OPTION}
							# END all.group #
						</select>
						
						<noscript>
							<input type="submit" name="valid" value="{L_SEARCH}" class="submit" />
						</noscript>
					</form>				
				</td>

				<td style="vertical-align:top;" class="row2">
					<table>
						<tr>
							<td style="vertical-align: top">
								<form action="member.php{SID}" method="post">
									{L_SEARCH_MEMBER}: <input type="text" size="20" maxlenght="25" id="login_mbr" value="{all.LOGIN}" name="login_mbr" class="text" />
								
									<script type="text/javascript">
									<!--								
										document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">');
									-->
									</script>
									
									<noscript>
										<input type="submit" name="search_member" value="{L_SEARCH}" class="submit" />
									</noscript>
								</form>	
							</td>
							<td>
								<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
							</td>
						</tr>
					</table>			
				</td>
			</tr>
		</table>	

		<br /><br />
			
		<table class="module_table" style="width: 98%;">	
			<tr>
				<th colspan="8">
					{L_PROFIL}
				</th>
			</tr>
			<tr>
				<td colspan="8" class="row1">
					{PAGINATION}&nbsp;
				</td>
			</tr>	
			<tr style="font-weight:bold;text-align: center;">
				<td class="row3">
					<a href="member{U_MEMBER_ALPHA_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
					{L_PSEUDO} 
					<a href="member{U_MEMBER_ALPHA_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
				</td>
				<td class="row3">
					{L_MAIL}
				</td>
				<td class="row3">
					<a href="member{U_MEMBER_TIME_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
					{L_REGISTERED}
					<a href="member{U_MEMBER_TIME_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
				</td>
				<td class="row3">
					<a href="member{U_MEMBER_MSG_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
					{L_MESSAGE}
					<a href="member{U_MEMBER_MSG_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>	
				</td>
				<td class="row3">
					{L_LOCALISATION}
				</td>
				<td class="row3">
					<a href="member{U_MEMBER_LAST_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
					{L_LAST_CONNECT}
					<a href="member{U_MEMBER_LAST_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
				</td>
				<td class="row3">
					{L_PRIVATE_MESSAGE}
				</td>
				<td class="row3">
					{L_WEB_SITE}
				</td>
							
			</tr>
			
			# START all.member #
			<tr> 
				<td class="row2" style="text-align:center;padding:4px 0px;">
					<a href="member{all.member.U_MEMBER_ID}">{all.member.PSEUDO}</a>
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					{all.member.MAIL}
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					{all.member.DATE}
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					{all.member.MSG}
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					{all.member.LOCAL}
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					{all.member.LAST_CONNECT}
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					<a href="pm{all.member.U_MEMBER_PM}"><img src="../templates/{THEME}/images/{LANG}/pm.png" alt="{L_PRIVATE_MESSAGE}" /></a>
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					{all.member.WEB}
				</td>
			</tr>
			# END all.member #

			<tr>
				<td colspan="8" class="row1">
					<span style="float:left;">{PAGINATION}</span>
				</td>
			</tr>
		</table>
		# END all #



		# START profil #
		<div class="fieldset_content">
			<fieldset>
				<legend>{L_PROFIL}</legend>
				# START profil.edit #
				<dl>
					<dt>{L_PROFIL_EDIT}</dt>
					<dd><a href="{profil.edit.U_MEMBER_SCRIPT}" title="{L_PROFIL_EDIT}"><img src="../templates/{profil.edit.THEME}/images/{LANG}/edit.png" alt="{L_PROFIL_EDIT}" /></a></dd>
				</dl>
				# END profil.edit #				
				<dl>
					<dt>{L_PSEUDO}</dt>
					<dd>{profil.USER_NAME}</dd>
				</dl>
				<dl>
					<dt>{L_AVATAR}</dt>
					<dd>{profil.USER_AVATAR}</dd>
				</dl>
				<dl>
					<dt>{L_STATUT}</dt>
					<dd>{profil.STATUT}</dd>
				</dl>
				<dl>
					<dt>{L_GROUPS}</dt>
					<dd>
						# START profil.groups #
						{profil.groups.USER_GROUP}
						# END profil.groups #
					</dd>
				</dl>
				<dl>
					<dt>{L_REGISTERED}</dt>
					<dd>{profil.DATE}</dd>
				</dl>
				<dl>
					<dt>{L_NBR_MESSAGE}</dt>
					<dd>{profil.USER_MSG}</dd>
				</dl>
				<dl>
					<dt>{L_LAST_CONNECT}</dt>
					<dd>{profil.LAST_CONNECT}</dd>
				</dl>
				<dl>
					<dt>{L_WEB_SITE}</dt>
					<dd>{profil.WEB}</dd>
				</dl>
				<dl>
					<dt>{L_LOCALISATION}</dt>
					<dd>{profil.LOCAL}</dd>
				</dl>
				<dl>
					<dt>{L_JOB}</dt>
					<dd>{profil.OCCUPATION}</dd>
				</dl>
				<dl>
					<dt>{L_HOBBIES}</dt>
					<dd>{profil.HOBBIES}</dd>
				</dl>
				<dl>
					<dt>{L_SEX}</dt>
					<dd>{profil.USER_SEX}</dd>
				</dl>
				<dl>
					<dt>{L_AGE}</dt>
					<dd>{profil.USER_AGE}</dd>
				</dl>
				<dl>
					<dt>{L_BIOGRAPHY}</dt>
					<dd><div>{profil.USER_DESC}</div></dd>
				</dl>
			</fieldset>
			
			<fieldset>
				<legend>{L_CONTACT}</legend>
				<dl>
					<dt>{L_MAIL}</dt>
					<dd>{profil.MAIL}</dd>
				</dl>
				<dl>
					<dt>{L_PRIVATE_MESSAGE}</dt>
					<dd><a href="pm{profil.U_MEMBER_PM}"><img src="../templates/{THEME}/images/{LANG}/pm.png" alt="{L_PRIVATE_MESSAGE}" /></a></dd>
				</dl>
				<dl>
					<dt>MSN</dt>
					<dd>{profil.USER_MSN}</dd>
				</dl>
				<dl>
					<dt>Yahoo</dt>
					<dd>{profil.USER_YAHOO}</dd>
				</dl>
			</fieldset>
			
			# START profil.miscellaneous #
			<fieldset>
				<legend>{L_MISCELLANEOUS}</legend>						
				# START profil.miscellaneous.list #
				<dl>
					<dt>{profil.miscellaneous.list.NAME}<br /><span>{profil.miscellaneous.list.DESC}</span></dt>
					<dd>{profil.miscellaneous.list.FIELD}</dd>
				</dl>
				# END profil.miscellaneous.list #	
			</fieldset>
			# END profil.miscellaneous #
		</div>		
		# END profil #

		

		# START group #
		<table class="module_table" style="width:70%;">	
			<tr>
				<td style="vertical-align:top;" class="row2">
					<form action="member.php{SID}" method="post">
						{L_SELECT_GROUP}: <select name="show_group" style="text-align:center;" onchange="document.location = {U_SELECT_SHOW_GROUP};">  
							<option value="0" selected="selected">-- {L_LIST} --</option>
							# START group.select #
								{group.select.OPTION}
							# END group.select #
						</select>
						&nbsp;&nbsp;{group.ADMIN_GROUPS}
						<noscript>
							<input type="submit" name="valid" value="{L_SEARCH}" class="submit" />
						</noscript>
					</form>				
				</td>
			</tr>
		</table>	

		<br /><br />

		<table class="module_table" style="width: 70%;text-align:center;">
			<tr>
				<th colspan="3">
					{group.GROUP_NAME}
				</th>
			</tr>
			<tr>
				<td class="row3" colspan="3" style="text-align:left;">
					<a href="member.php{SID}">{L_BACK}</a>
				</td>
			</tr>
			<tr>
				<td class="row3" style="font-weight: bold;width: 120px;">
					{L_AVATAR}
				</td>
				<td class="row3" style="font-weight: bold;">
					{L_LOGIN}
				</td>
				<td class="row3" style="font-weight: bold;">
					{L_STATUT}
				</td>
			</tr>
			
			# START group.list #
			<tr>
				<td class="row1">
					{group.list.USER_AVATAR}
				</td>
				<td class="row1">
					{group.list.U_MEMBER}
				</td>
				<td class="row1">
					{group.list.USER_RANK}
				</td>
			</tr>	
			# END group.list #
		</table>
		# END group #
