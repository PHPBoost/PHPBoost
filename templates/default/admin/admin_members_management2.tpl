		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('name').value == "") {
				alert("{L_REQUIRE_PSEUDO}");
				return false;
		    }
			if(document.getElementById('mail').value == "") {
				alert("{L_REQUIRE_MAIL}");
				return false;
		    }
			if(document.getElementById('level').value == "") {
				alert("{L_REQUIRE_RANK}");
				return false;
		    }
			return true;
		}
		-->
		</script>

		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_CONFIRM_DEL_USER}");
		}
		-->
		</script>
	
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_USERS_MANAGEMENT}</li>
				<li>
					<a href="admin_members.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php" class="quick_link">{L_USERS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_members.php?add=1"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php?add=1" class="quick_link">{L_USERS_ADD}</a>
				</li>
				<li>
					<a href="admin_members_config.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_config.php" class="quick_link">{L_USERS_CONFIG}</a>
				</li>
				<li>
					<a href="admin_members_punishment.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_punishment.php" class="quick_link">{L_USERS_PUNISHMENT}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
	
			# IF C_USERS_ADD #
			
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />
				</div>
			</div>
			# ENDIF #
			<form action="admin_members.php?add=1&amp;token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_USERS_ADD}</legend>
					<dl>
						<dt><label for="login2">* {L_PSEUDO}</label></dt>
						<dd><label><input type="text" maxlength="25" size="20" id="login2" name="login2" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="password2">* {L_PASSWORD}</label></dt>
						<dd><label><input type="password" maxlength="30" size="20" id="password2" name="password2" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="password2_bis">* {L_PASSWORD_CONFIRM}</label></dt>
						<dd><label><input type="password" maxlength="30" size="20" id="password2_bis" name="password2_bis" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="mail2">* {L_MAIL}</label></dt>
						<dd><label><input type="text" maxlength="50" size="30" id="mail2" name="mail2" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="level2">* {L_RANK}</label></dt>
						<dd><label>
							<select id="level2" name="level2">
								<option value="0" selected="selected">{L_USER}</option>
								<option value="1">{L_MODO}</option>
								<option value="2">{L_ADMIN}</option>
							</select>
						</label></dd>
					</dl>
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend>{L_ADD}</legend>
					<input type="submit" name="add" value="{L_ADD}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
			
			# ENDIF #
			
			
			# IF C_USERS_MANAGEMENT #
			<script type="text/javascript">
			<!--
			function check_select_multiple(id, status)
			{
				for(var i = 0; i < {NBR_GROUP}; i++)
				{	
					if( document.getElementById(id + i) )
						document.getElementById(id + i).selected = status;			
				}
			}	 
			function img_change_sex(url)
			{
				if( document.getElementById('img_sex') )
				{
					var img_sex = '';
					if( url == 1 )
						img_sex = 'man.png';
					else if( url == 2 )
						img_sex = 'woman.png';
					document.getElementById('img_sex').innerHTML = (img_sex != '') ? '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/' + img_sex + '" alt="" />' : '';
				}
			}
			function change_img_theme(id, value)
			{
				if(document.images )
					document.images[id].src = "{PATH_TO_ROOT}/templates/" + value + "/theme/images/theme.jpg";
			}
		
			var array_identifier = new Array();
			{JS_LANG_IDENTIFIER}
			function change_img_lang(id, lang)
			{
				if( array_identifier[lang] && document.getElementById(id) ) 
					document.getElementById(id).src = '{PATH_TO_ROOT}/images/stats/countries/' + array_identifier[lang] + '.png';
			}
			-->
			</script>
			<script type="text/javascript">
			<!--
				var theme = '{THEME}';
			-->
			</script>
			<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/calendar.js"></script>
				
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #
			
			<form action="admin_members.php?token={TOKEN}" enctype="multipart/form-data" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_USERS_MANAGEMENT}</legend>
					<dl>
						<dt><label for="name">* {L_PSEUDO}</label></dt>
						<dd><label><input type="text" maxlength="25" size="25" id="name" name="name" value="{NAME}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="pass">* {L_PASSWORD}</label></dt>
						<dd><label><input type="password" maxlength="30" size="30" name="pass" id="pass" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="confirm_pass">* {L_CONFIRM_PASSWORD}</label><br /><span>{L_CONFIRM_PASSWORD_EXPLAIN}</span></dt>
						<dd><label><input type="password" maxlength="30" size="30" name="confirm_pass" id="confirm_pass" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="mail">* {L_MAIL}</label></dt>
						<dd><label><input type="text" maxlength="50" size="50" id="mail" name="mail" value="{MAIL}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="user_aprob">{L_APROB}</label></dt>
						<dd><label>
							<select id="user_aprob" name="user_aprob">					
								<option value="0" {SELECT_UNAPROB}>{L_NO}</option>
								<option value="1" {SELECT_APROB}>{L_YES}</option>
							</select>
						</label></dd>
					</dl>	
					<dl>
						<dt><label for="level">{L_RANK}</label></dt>
						<dd><label>
							<select id="level" name="level">					
								{RANKS_OPTIONS}				
							</select>
						</label></dd>
					</dl>	
					<dl>
						<dt><label for="user_group">{L_GROUP}</label></dt>
						<dd><label>
							<select id="user_group" name="user_groups[]" size="6" multiple="multiple">
								{GROUPS_OPTIONS}
							</select>
							<br />
							<a class="small_link" href="javascript:check_select_multiple('g', true);">{L_SELECT_ALL}</a>/<a class="small_link" href="javascript:check_select_multiple('g', false);">{L_SELECT_NONE}</a>
						</label></dd>
					</dl>	
					<dl>
						<dt><label for="user_lang">{L_LANG_CHOOSE}</label></dt>
						<dd><label>
							<select name="user_lang" id="user_lang" onchange="change_img_lang('img_lang', this.options[selectedIndex].value)">	
								# START select_lang #
									<option value="{select_lang.IDNAME}"{select_lang.SELECTED}>{select_lang.NAME}</option>
								# END select_lang #			
							</select> &nbsp;<img id="img_lang" src="{IMG_LANG_IDENTIFIER}" alt="" class="valign_middle" />
						</label></dd>
					</dl>	
				</fieldset>
				
				<fieldset>
				<legend>{L_OPTIONS}</legend>
					<dl>
						<dt><label for="user_theme">{L_THEME_CHOOSE}</label></dt>
						<dd><label>
							<select name="user_theme" id="user_theme" onchange="change_img_theme('img_theme', this.options[selectedIndex].value)">						
								# START select_theme #
									<option value="{select_theme.IDNAME}"{select_theme.SELECTED}>{select_theme.NAME}</option>
								# END select_theme #				
							</select>
							<img id="img_theme" src="{PATH_TO_ROOT}/templates/{USER_THEME}/theme/images/theme.jpg" alt="" style="vertical-align:top" />			
						</label></dd>
					</dl>
					<dl>
						<dt><label for="user_editor">* {L_EDITOR_CHOOSE}</label></dt>
						<dd>
							<label>
								<select name="user_editor" id="user_editor">
									{EDITOR_OPTIONS}
								</select>
							</label>
						</dd>
					</dl>	
					<dl>
						<dt><label for="user_timezone">{L_TIMEZONE_CHOOSE}</label><br /><span>{L_TIMEZONE_CHOOSE_EXPLAIN}</span></dt>
						<dd>
							<label>
								<select name="user_timezone" id="user_timezone">	
									{TIMEZONE_OPTIONS}
								</select>
							</label>
						</dd>			
					</dl>	
					<dl>
						<dt><label for="user_show_mail">{L_HIDE_MAIL}</label><br /><span>{L_HIDE_MAIL_EXPLAIN}</span></dt>
						<dd><label><input type="checkbox" class="text" name="user_show_mail" id="user_show_mail" {SHOW_MAIL_CHECKED} /></label></dd>
					</dl>			
				</fieldset>	
				
				<fieldset>
					<legend>{L_SANCTION}</legend>				
					<dl>
						<dt><label for="delete">{L_CONFIRM_DEL_USER}</label></dt>
						<dd><label><input type="checkbox" name="delete" id="delete" value="1" /> </label></dd>
					</dl>
					<dl>
						<dt><label for="user_ban">{L_BAN}</label></dt>
						<dd><label>
							<select name="user_ban" id="user_ban">					
								{BAN_OPTIONS}
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="user_readonly">{L_READONLY}</label></dt>
						<dd><label>
							<select name="user_readonly" id="user_readonly">					
								{READONLY_OPTIONS}
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="user_warning">{L_WARNING}</label></dt>
						<dd><label>
							<select name="user_warning" id="user_warning">					
								{WARNING_OPTIONS}
							</select>
						</label></dd>
					</dl>
				</fieldset>	
					
				<fieldset>
					<legend>{L_INFO}</legend>	
					<dl>
						<dt><label for="user_web">{L_WEBSITE}</label><br /><span>{L_WEBSITE_EXPLAIN}</span></dt>
						<dd><label><input type="text" maxlength="70" size="40" name="user_web" id="user_web" value="{WEB}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="user_local">{L_LOCALISATION}</label></dt>
						<dd><label><input type="text" maxlength=40" size="40" name="user_local" id="user_local" value="{LOCAL}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="user_occupation">{L_JOB}</label></dt>
						<dd><label><input type="text" maxlength=40" size="40" name="user_occupation" id="user_occupation" value="{OCCUPATION}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="user_hobbies">{L_HOBBIES}</label></dt>
						<dd><label><input type="text" maxlength=40" size="40" name="user_hobbies" id="user_hobbies" value="{HOBBIES}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="user_sex">{L_SEX}</label></dt>
						<dd><label>
							<select name="user_sex" id="user_sex" onchange="img_change_sex(this.options[selectedIndex].value)">
								{SEX_OPTIONS}						
							</select>
							<span id="img_sex">{IMG_SEX}</span>
						</label></dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="user_born">{L_DATE_BIRTH}</label><br /><span>{L_VALID}</span></dt>
						<dd><label>
							<input size="10" maxlength="10" type="text" class="text" id="user_born" name="user_born" value="{BORN}" /> 

							<div style="position:relative;z-index:100;top:26px;margin-left:25px;float:left;display:none;" id="calendar1">
								<div id="calendar" class="calendar_block" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
								</div>
							</div>
							<a onclick="xmlhttprequest_calendar('calendar', '?input_field=user_born&amp;field=calendar&amp;lyear=1&amp;d={BORN_DAY}&amp;m={BORN_MONTH}&amp;y={BORN_YEAR}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgcalendar" src="{PATH_TO_ROOT}/templates/{THEME}/images/calendar.png" alt="" /></a>
						</label></dd>
					</dl>
					<p><label for="user_sign">{L_USER_SIGN}</label><br /><span>{L_USER_SIGN_EXPLAIN}</span></p>
					<p>
						{USER_SIGN_EDITOR}
						<textarea class="post" rows="10" cols="27" name="user_sign" id="user_sign">{SIGN}</textarea>
					</p>
					<p><label for="user_desc">{L_USER_BIOGRAPHY}</label></p>
					<p>
						{USER_DESC_EDITOR}
						<textarea class="post" rows="10" cols="27" name="user_desc" id="user_desc">{BIOGRAPHY}</textarea>
					</p>
					<div class="spacer">&nbsp;</div>
				</fieldset>	
					
				<fieldset>
					<legend>{L_CONTACT}</legend>	
					<dl>
						<dt><label for="user_msn">MSN</label></dt>
						<dd><label><input type="text" size="25" id="user_msn" name="user_msn" value="" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="user_yahoo">Yahoo</label></dt>
						<dd><label><input type="text" size="25" id="user_yahoo" name="user_yahoo" value="" class="text" /></label></dd>
					</dl>							
				</fieldset>
				
				<fieldset>
					<legend>{L_AVATAR_GESTION}</legend>	
					<dl>
						<dt><label>{L_CURRENT_AVATAR}</label></dt>
						<dd><label>{USER_AVATAR}</label></dd>
					</dl>
					<dl>
						<dt><label>{L_UPLOAD_AVATAR}</label></dt>
						<dd><label>
							{L_WEIGHT_MAX}: {WEIGHT_MAX} ko
							<br />
							{L_HEIGHT_MAX}: {HEIGHT_MAX} px
							<br />
							{L_WIDTH_MAX}: {WIDTH_MAX} px
						</label></dd>
					</dl>
					<dl>
						<dt><label for="avatars">{L_UPLOAD_AVATAR}</label><br /><span>{L_UPLOAD_AVATAR_WHERE}</span></dt>
						<dd><label>
							<input type="file" name="avatars" id="avatars" size="30" class="file" />					
							<input type="hidden" name="max_file_size" value="2000000" />
						</label></dd>
					</dl>
					<dl>
						<dt><label for="avatar">{L_AVATAR_LINK}</label><br /><span>{L_AVATAR_LINK_WHERE}</span></dt>
						<dd><label><input type="text" maxlength="40" size="40" name="avatar" id="avatar" class="text" /></label></dd>
					</dl>	
					<dl>
						<dt><label for="delete_avatar">{L_AVATAR_DEL}</label></dt>
						<dd><label><input type="checkbox" class="text" name="delete_avatar" id="delete_avatar" /></label></dd>
					</dl>
				</fieldset>
				
				# IF C_MISCELLANEOUS #
				<fieldset>
					<legend>{L_MISCELLANEOUS}</legend>	
						
					# START list #
					<dl>
						<dt><label for="{list.ID}">{list.NAME}</label><br /><span>{list.DESC}</span></dt>
						<dd>{list.FIELD}</dd>
					</dl>
					# END list #	
				</fieldset>
				# ENDIF #					
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					<input type="hidden" name="id" value="{IDMBR}" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>
			# ENDIF #
		</div>
		