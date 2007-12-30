		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('mail').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_MAIL']) ? $this->_var['L_REQUIRE_MAIL'] : ''; ?>");
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
				document.getElementById('img_sex').innerHTML = (img_sex != '') ? '<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/' + img_sex + '" alt="" />' : '';
			}
		}
		function change_img_theme(id, value)
		{
			if(document.images )
				document.images[id].src = "../templates/" + value + "/images/theme.jpg";
		}
		
		var array_identifier = new Array();
		<?php echo isset($this->_var['JS_LANG_IDENTIFIER']) ? $this->_var['JS_LANG_IDENTIFIER'] : ''; ?>
		function change_img_lang(id, lang)
		{
			if( array_identifier[lang] && document.getElementById(id) ) 
				document.getElementById(id).src = '../images/stats/countries/' + array_identifier[lang] + '.png';
		}
		-->
		</script>

		<?php if( !isset($this->_block['update']) || !is_array($this->_block['update']) ) $this->_block['update'] = array();
foreach($this->_block['update'] as $update_key => $update_value) {
$_tmpb_update = &$this->_block['update'][$update_key]; ?>

		<?php if( !isset($_tmpb_update['error_handler']) || !is_array($_tmpb_update['error_handler']) ) $_tmpb_update['error_handler'] = array();
foreach($_tmpb_update['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_update['error_handler'][$error_handler_key]; ?>
		<span id="errorh"></span>
		<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
			<br />	
		</div>
		<br />		
		<?php } ?>
		
		<script type="text/javascript" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.js"></script>
		<form action="member<?php echo isset($_tmpb_update['U_MEMBER_ACTION_UPDATE']) ? $_tmpb_update['U_MEMBER_ACTION_UPDATE'] : ''; ?>" enctype="multipart/form-data" method="post" onsubmit="return check_form();" class="fieldset_content">
			<fieldset>
				<legend><?php echo isset($this->_var['L_PROFIL_EDIT']) ? $this->_var['L_PROFIL_EDIT'] : ''; ?></legend>
				<dl>
					<dt><label for="mail">* <?php echo isset($this->_var['L_MAIL']) ? $this->_var['L_MAIL'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_VALID']) ? $this->_var['L_VALID'] : ''; ?></span></dt>
					<dd><label><input type="text" maxlength="50" size="30" id="mail" name="mail" value="<?php echo isset($_tmpb_update['MAIL']) ? $_tmpb_update['MAIL'] : ''; ?>" class="text" /></label></dd>
				</dl>
				<dl>
					<dt><label for="pass_old">(*) <?php echo isset($this->_var['L_PREVIOUS_PASS']) ? $this->_var['L_PREVIOUS_PASS'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_EDIT_JUST_IF_MODIF']) ? $this->_var['L_EDIT_JUST_IF_MODIF'] : ''; ?></span></dt>
					<dd><label><input size="30" type="password" class="text" name="pass_old" id="pass_old" maxlength="30" /></label></dd>
				</dl>
				<dl>
					<dt><label for="pass">(*) <?php echo isset($this->_var['L_NEW_PASS']) ? $this->_var['L_NEW_PASS'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_EDIT_JUST_IF_MODIF']) ? $this->_var['L_EDIT_JUST_IF_MODIF'] : ''; ?></span></dt>
					<dd><label><input size="30" type="password" class="text" name="pass" id="pass" maxlength="30" /></label></dd>
				</dl>
				<dl>
					<dt><label for="pass_bis">(*) <?php echo isset($this->_var['L_CONFIRM_PASS']) ? $this->_var['L_CONFIRM_PASS'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_EDIT_JUST_IF_MODIF']) ? $this->_var['L_EDIT_JUST_IF_MODIF'] : ''; ?></span></dt>
					<dd><label><input size="30" type="password" class="text" name="pass_bis" id="pass_bis" maxlength="30" /></label></dd>
				</dl>
				<dl>
					<dt><label for="del_member"><?php echo isset($this->_var['L_DEL_MEMBER']) ? $this->_var['L_DEL_MEMBER'] : ''; ?></label></dt>
					<dd><label><input size="30" type="checkbox" name="del_member" id="del_member" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_lang">* <?php echo isset($this->_var['L_LANG_CHOOSE']) ? $this->_var['L_LANG_CHOOSE'] : ''; ?></label></dt>
					<dd>
						<label>
							<select name="user_lang" id="user_lang" onchange="change_img_lang('img_lang', this.options[this.selectedIndex].value)">	
								<?php if( !isset($_tmpb_update['select_lang']) || !is_array($_tmpb_update['select_lang']) ) $_tmpb_update['select_lang'] = array();
foreach($_tmpb_update['select_lang'] as $select_lang_key => $select_lang_value) {
$_tmpb_select_lang = &$_tmpb_update['select_lang'][$select_lang_key]; ?>
								<?php echo isset($_tmpb_select_lang['LANG']) ? $_tmpb_select_lang['LANG'] : ''; ?>
								<?php } ?>
							</select> <img id="img_lang" src="<?php echo isset($this->_var['IMG_LANG_IDENTIFIER']) ? $this->_var['IMG_LANG_IDENTIFIER'] : ''; ?>" alt="" class="valign_middle" />
						</label>
					</dd>
				</dl>
			</fieldset>
				
			<fieldset>
				<legend><?php echo isset($this->_var['L_OPTIONS']) ? $this->_var['L_OPTIONS'] : ''; ?></legend>
				<dl>
					<dt><label for="user_theme">* <?php echo isset($this->_var['L_THEME_CHOOSE']) ? $this->_var['L_THEME_CHOOSE'] : ''; ?></label></dt>
					<dd>
						<label>
							<select name="user_theme" id="user_theme" onChange="change_img_theme('img_theme', this.options[selectedIndex].value)">
								<?php if( !isset($_tmpb_update['select_theme']) || !is_array($_tmpb_update['select_theme']) ) $_tmpb_update['select_theme'] = array();
foreach($_tmpb_update['select_theme'] as $select_theme_key => $select_theme_value) {
$_tmpb_select_theme = &$_tmpb_update['select_theme'][$select_theme_key]; ?>
								<?php echo isset($_tmpb_select_theme['THEME']) ? $_tmpb_select_theme['THEME'] : ''; ?>
								<?php } ?>
							</select>
							<img id="img_theme" src="../templates/<?php echo isset($_tmpb_update['USER_THEME']) ? $_tmpb_update['USER_THEME'] : ''; ?>/images/theme.jpg" alt="" style="vertical-align:top" />
						</label>
					</dd>
				</dl>
				<dl>
					<dt><label for="user_editor">* <?php echo isset($this->_var['L_EDITOR_CHOOSE']) ? $this->_var['L_EDITOR_CHOOSE'] : ''; ?></label></dt>
					<dd>
						<label>
							<select name="user_editor" id="user_editor">
								<?php if( !isset($_tmpb_update['select_editor']) || !is_array($_tmpb_update['select_editor']) ) $_tmpb_update['select_editor'] = array();
foreach($_tmpb_update['select_editor'] as $select_editor_key => $select_editor_value) {
$_tmpb_select_editor = &$_tmpb_update['select_editor'][$select_editor_key]; ?>
								<?php echo isset($_tmpb_select_editor['SELECT_EDITORS']) ? $_tmpb_select_editor['SELECT_EDITORS'] : ''; ?>
								<?php } ?>
							</select>
						</label>
					</dd>
				</dl>
				<dl>
					<dt><label for="user_timezone"><?php echo isset($this->_var['L_TIMEZONE_CHOOSE']) ? $this->_var['L_TIMEZONE_CHOOSE'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_TIMEZONE_CHOOSE_EXPLAIN']) ? $this->_var['L_TIMEZONE_CHOOSE_EXPLAIN'] : ''; ?></span></dt>
					<dd>
						<label>
							<select name="user_timezone" id="user_timezone">	
								<?php if( !isset($_tmpb_update['select_timezone']) || !is_array($_tmpb_update['select_timezone']) ) $_tmpb_update['select_timezone'] = array();
foreach($_tmpb_update['select_timezone'] as $select_timezone_key => $select_timezone_value) {
$_tmpb_select_timezone = &$_tmpb_update['select_timezone'][$select_timezone_key]; ?>
								<?php echo isset($_tmpb_select_timezone['SELECT_TIMEZONE']) ? $_tmpb_select_timezone['SELECT_TIMEZONE'] : ''; ?>
								<?php } ?>						
							</select>
						</label>
					</dd>			
				</dl>
				<dl>
					<dt><label for="user_show_mail"><?php echo isset($this->_var['L_HIDE_MAIL']) ? $this->_var['L_HIDE_MAIL'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_HIDE_MAIL_WHO']) ? $this->_var['L_HIDE_MAIL_WHO'] : ''; ?></span></dt>
					<dd><label><input type="checkbox" <?php echo isset($_tmpb_update['SHOW_MAIL_CHECKED']) ? $_tmpb_update['SHOW_MAIL_CHECKED'] : ''; ?> name="user_show_mail" id="user_show_mail" /></label></dd>
				</dl>
			</fieldset>	
			
			<fieldset>
				<legend><?php echo isset($this->_var['L_INFO']) ? $this->_var['L_INFO'] : ''; ?></legend>
				<dl>
					<dt><label for="user_web"><?php echo isset($this->_var['L_SITE_WEB']) ? $this->_var['L_SITE_WEB'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_VALID']) ? $this->_var['L_VALID'] : ''; ?></span></dt>
					<dd><label><input size="30" type="text" class="text" name="user_web" id="user_web" value="<?php echo isset($_tmpb_update['WEB']) ? $_tmpb_update['WEB'] : ''; ?>" maxlength="70" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_local"><?php echo isset($this->_var['L_LOCALISATION']) ? $this->_var['L_LOCALISATION'] : ''; ?></label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_local" id="user_local" value="<?php echo isset($_tmpb_update['LOCAL']) ? $_tmpb_update['LOCAL'] : ''; ?>" maxlength="25" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_occupation"><?php echo isset($this->_var['L_JOB']) ? $this->_var['L_JOB'] : ''; ?></label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_occupation" id="user_occupation" value="<?php echo isset($_tmpb_update['OCCUPATION']) ? $_tmpb_update['OCCUPATION'] : ''; ?>" maxlength="50" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_hobbies"><?php echo isset($this->_var['L_HOBBIES']) ? $this->_var['L_HOBBIES'] : ''; ?></label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_hobbies" id="user_hobbies" value="<?php echo isset($_tmpb_update['HOBBIES']) ? $_tmpb_update['HOBBIES'] : ''; ?>" maxlength="50" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_sex"><?php echo isset($this->_var['L_SEX']) ? $this->_var['L_SEX'] : ''; ?></label></dt>
					<dd><label>
						<select name="user_sex" id="user_sex" onchange="img_sex(this.options[selectedIndex].value)">
							<?php if( !isset($_tmpb_update['select_sex']) || !is_array($_tmpb_update['select_sex']) ) $_tmpb_update['select_sex'] = array();
foreach($_tmpb_update['select_sex'] as $select_sex_key => $select_sex_value) {
$_tmpb_select_sex = &$_tmpb_update['select_sex'][$select_sex_key]; ?>							
							<?php echo isset($_tmpb_select_sex['SEX']) ? $_tmpb_select_sex['SEX'] : ''; ?>							
							<?php } ?>
						</select>
						<span id="img_sex"><?php echo isset($_tmpb_update['USER_SEX']) ? $_tmpb_update['USER_SEX'] : ''; ?></span>
					</label></dd>
				</dl>
				<dl class="overflow_visible">
					<dt><label for="user_born"><?php echo isset($this->_var['L_DATE_OF_BIRTH']) ? $this->_var['L_DATE_OF_BIRTH'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_DATE_FORMAT']) ? $this->_var['L_DATE_FORMAT'] : ''; ?></span></dt>
					<dd><label>
						<input size="10" maxlength="10" type="text" class="text" id="user_born" name="user_born" value="<?php echo isset($_tmpb_update['USER_BORN']) ? $_tmpb_update['USER_BORN'] : ''; ?>" /> 

						<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
							<div id="calendar" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('calendar', '?input_field=user_born&amp;field=calendar&amp;lyear=1&amp;d=<?php echo isset($_tmpb_update['BORN_DAY']) ? $_tmpb_update['BORN_DAY'] : ''; ?>&amp;m=<?php echo isset($_tmpb_update['BORN_MONTH']) ? $_tmpb_update['BORN_MONTH'] : ''; ?>&amp;y=<?php echo isset($_tmpb_update['BORN_YEAR']) ? $_tmpb_update['BORN_YEAR'] : ''; ?>');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a>
					</label></dd>
				</dl>
				<dl>
					<dt><label for="user_sign"><?php echo isset($this->_var['L_SIGN']) ? $this->_var['L_SIGN'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_SIGN_WHERE']) ? $this->_var['L_SIGN_WHERE'] : ''; ?></span></dt>
					<dd><label><textarea class="post" rows="4" cols="27" name="user_sign" id="user_sign"><?php echo isset($_tmpb_update['USER_SIGN']) ? $_tmpb_update['USER_SIGN'] : ''; ?></textarea></label></dd>
				</dl>
				<dl>
					<dt><label for="user_desc"><?php echo isset($this->_var['L_BIOGRAPHY']) ? $this->_var['L_BIOGRAPHY'] : ''; ?></label></dt>
					<dd><label><textarea class="post" rows="6" cols="27" id="user_desc" name="user_desc"><?php echo isset($_tmpb_update['USER_DESC']) ? $_tmpb_update['USER_DESC'] : ''; ?></textarea> </label></dd>
				</dl>
			</fieldset>
				
			<fieldset>
				<legend><?php echo isset($this->_var['L_CONTACT']) ? $this->_var['L_CONTACT'] : ''; ?></legend>
				<dl>
					<dt><label for="user_msn">MSN</label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_msn" id="user_msn" value="<?php echo isset($_tmpb_update['USER_MSN']) ? $_tmpb_update['USER_MSN'] : ''; ?>" maxlength="50" /></label></dd>
				</dl>
				<dl>
					<dt><label for="user_yahoo">Yahoo</label></dt>
					<dd><input size="30" type="text" class="text" name="user_yahoo" id="user_yahoo" value="<?php echo isset($_tmpb_update['USER_YAHOO']) ? $_tmpb_update['USER_YAHOO'] : ''; ?>" maxlength="50" /></label></dd>
				</dl>
			</fieldset>	
				
			<fieldset>
				<legend><?php echo isset($this->_var['L_AVATAR_MANAGEMENT']) ? $this->_var['L_AVATAR_MANAGEMENT'] : ''; ?></legend>
				<dl>
					<dt><label><?php echo isset($this->_var['L_CURRENT_AVATAR']) ? $this->_var['L_CURRENT_AVATAR'] : ''; ?></label></dt>
					<dd><?php echo isset($_tmpb_update['USER_AVATAR']) ? $_tmpb_update['USER_AVATAR'] : ''; ?></label></dd>
				</dl>	
				
				<?php if( !isset($_tmpb_update['upload_avatar']) || !is_array($_tmpb_update['upload_avatar']) ) $_tmpb_update['upload_avatar'] = array();
foreach($_tmpb_update['upload_avatar'] as $upload_avatar_key => $upload_avatar_value) {
$_tmpb_upload_avatar = &$_tmpb_update['upload_avatar'][$upload_avatar_key]; ?>
				<dl>
					<dt><label for="avatars"><?php echo isset($this->_var['L_UPLOAD_AVATAR']) ? $this->_var['L_UPLOAD_AVATAR'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_UPLOAD_AVATAR_WHERE']) ? $this->_var['L_UPLOAD_AVATAR_WHERE'] : ''; ?></span></dt>
					<dd><label>
						<input type="file" name="avatars" id="avatars" size="30" class="submit" />					
						<input type="hidden" name="max_file_size" value="2000000" />
						<br />
						<?php echo isset($this->_var['L_WEIGHT_MAX']) ? $this->_var['L_WEIGHT_MAX'] : ''; ?>: <?php echo isset($_tmpb_upload_avatar['WEIGHT_MAX']) ? $_tmpb_upload_avatar['WEIGHT_MAX'] : ''; ?> ko
						<br />
						<?php echo isset($this->_var['L_HEIGHT_MAX']) ? $this->_var['L_HEIGHT_MAX'] : ''; ?>: <?php echo isset($_tmpb_upload_avatar['HEIGHT_MAX']) ? $_tmpb_upload_avatar['HEIGHT_MAX'] : ''; ?> pixels
						<br />
						<?php echo isset($this->_var['L_WIDTH_MAX']) ? $this->_var['L_WIDTH_MAX'] : ''; ?>: <?php echo isset($_tmpb_upload_avatar['WIDTH_MAX']) ? $_tmpb_upload_avatar['WIDTH_MAX'] : ''; ?> pixels
					</label></dd>
				</dl>
				<?php } ?>
				
				<dl>
					<dt><label for="avatar"><?php echo isset($this->_var['L_AVATAR_LINK']) ? $this->_var['L_AVATAR_LINK'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_AVATAR_LINK_WHERE']) ? $this->_var['L_AVATAR_LINK_WHERE'] : ''; ?></span></dt>
					<dd><label><input type="text" name="avatar" id="avatar" size="30" class="text" /></label></dd>
				</dl>
				<dl>
					<dt><label for="delete_avatar"><?php echo isset($this->_var['L_AVATAR_DEL']) ? $this->_var['L_AVATAR_DEL'] : ''; ?></label></dt>
					<dd><label><input type="checkbox" name="delete_avatar" id="delete_avatar" /></label></dd>
				</dl>
			</fieldset>
			
			<?php if( !isset($_tmpb_update['miscellaneous']) || !is_array($_tmpb_update['miscellaneous']) ) $_tmpb_update['miscellaneous'] = array();
foreach($_tmpb_update['miscellaneous'] as $miscellaneous_key => $miscellaneous_value) {
$_tmpb_miscellaneous = &$_tmpb_update['miscellaneous'][$miscellaneous_key]; ?>
			<fieldset>
				<legend><?php echo isset($this->_var['L_MISCELLANEOUS']) ? $this->_var['L_MISCELLANEOUS'] : ''; ?></legend>	
					
				<?php if( !isset($_tmpb_miscellaneous['list']) || !is_array($_tmpb_miscellaneous['list']) ) $_tmpb_miscellaneous['list'] = array();
foreach($_tmpb_miscellaneous['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_miscellaneous['list'][$list_key]; ?>
				<dl>
					<dt><label for="<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>"><?php echo isset($_tmpb_list['NAME']) ? $_tmpb_list['NAME'] : ''; ?></label><br /><span><?php echo isset($_tmpb_list['DESC']) ? $_tmpb_list['DESC'] : ''; ?></span></dt>
					<dd><label><?php echo isset($_tmpb_list['FIELD']) ? $_tmpb_list['FIELD'] : ''; ?></label></dd>
				</dl>
				<?php } ?>	
			</fieldset>
			<?php } ?>	

			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
				<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />	
				&nbsp;&nbsp; 
				<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
			</fieldset>
		</form>

		<?php } ?>



		<?php if( !isset($this->_block['msg_mbr']) || !is_array($this->_block['msg_mbr']) ) $this->_block['msg_mbr'] = array();
foreach($this->_block['msg_mbr'] as $msg_mbr_key => $msg_mbr_value) {
$_tmpb_msg_mbr = &$this->_block['msg_mbr'][$msg_mbr_key]; ?>

		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><strong><?php echo isset($this->_var['L_PROFIL']) ? $this->_var['L_PROFIL'] : ''; ?></strong></div>
			<div class="module_contents">
				<p style="text-align:center;" class="text_strong"><?php echo isset($this->_var['L_WELCOME']) ? $this->_var['L_WELCOME'] : ''; echo ' '; echo isset($_tmpb_msg_mbr['USER_NAME']) ? $_tmpb_msg_mbr['USER_NAME'] : ''; ?></p>
				<table class="module_table">
					<tr>
						<td class="row2" style="text-align:center;width:33%">
							<a href="member<?php echo isset($_tmpb_msg_mbr['U_MEMBER_ID']) ? $_tmpb_msg_mbr['U_MEMBER_ID'] : ''; ?>"><?php echo isset($this->_var['L_PROFIL_EDIT']) ? $this->_var['L_PROFIL_EDIT'] : ''; ?></a>
							<br /><br />
							<a href="member<?php echo isset($_tmpb_msg_mbr['U_MEMBER_ID']) ? $_tmpb_msg_mbr['U_MEMBER_ID'] : ''; ?>" title="">
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/member.png" alt="<?php echo isset($this->_var['L_PROFIL_EDIT']) ? $this->_var['L_PROFIL_EDIT'] : ''; ?>" title="<?php echo isset($this->_var['L_PROFIL_EDIT']) ? $this->_var['L_PROFIL_EDIT'] : ''; ?>" />
							</a>
						</td>
						<td class="row2" style="text-align:center;width:34%">
							<a href="pm<?php echo isset($_tmpb_msg_mbr['U_MEMBER_PM']) ? $_tmpb_msg_mbr['U_MEMBER_PM'] : ''; ?>"><?php echo isset($_tmpb_msg_mbr['PM']) ? $_tmpb_msg_mbr['PM'] : ''; echo ' '; echo isset($this->_var['L_PRIVATE_MESSAGE']) ? $this->_var['L_PRIVATE_MESSAGE'] : ''; ?></a> <br /><br />
							<a href="pm<?php echo isset($_tmpb_msg_mbr['U_MEMBER_PM']) ? $_tmpb_msg_mbr['U_MEMBER_PM'] : ''; ?>">
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_msg_mbr['IMG_PM']) ? $_tmpb_msg_mbr['IMG_PM'] : ''; ?>" alt="<?php echo isset($this->_var['L_PRIVATE_MESSAGE']) ? $this->_var['L_PRIVATE_MESSAGE'] : ''; ?>" title="<?php echo isset($this->_var['L_PRIVATE_MESSAGE']) ? $this->_var['L_PRIVATE_MESSAGE'] : ''; ?>" />
							</a>
						</td>
						<?php if( !isset($_tmpb_msg_mbr['files_management']) || !is_array($_tmpb_msg_mbr['files_management']) ) $_tmpb_msg_mbr['files_management'] = array();
foreach($_tmpb_msg_mbr['files_management'] as $files_management_key => $files_management_value) {
$_tmpb_files_management = &$_tmpb_msg_mbr['files_management'][$files_management_key]; ?>
						<td class="row2" style="text-align:center;width:33%">
							<a href="upload.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_FILES_MANAGEMENT']) ? $this->_var['L_FILES_MANAGEMENT'] : ''; ?></a> <br /><br />
							<a href="upload.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>">
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/files_add.png" alt="<?php echo isset($this->_var['L_FILES_MANAGEMENT']) ? $this->_var['L_FILES_MANAGEMENT'] : ''; ?>" title="<?php echo isset($this->_var['L_FILES_MANAGEMENT']) ? $this->_var['L_FILES_MANAGEMENT'] : ''; ?>" />
							</a>
						</td>				
						<?php } ?>
					</tr>
				</table>
				<br /><br />
				<?php echo isset($_tmpb_msg_mbr['MSG_MBR']) ? $_tmpb_msg_mbr['MSG_MBR'] : ''; ?>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		<?php } ?>



		<?php if( !isset($this->_block['all']) || !is_array($this->_block['all']) ) $this->_block['all'] = array();
foreach($this->_block['all'] as $all_key => $all_value) {
$_tmpb_all = &$this->_block['all'][$all_key]; ?>

		<script type="text/javascript">
		<!--

		function XMLHttpRequest_search()
		{
			var xhr_object = null;
			var data = null;
			var filename = "../includes/xmlhttprequest.php?member=1";
			var login = document.getElementById("login_mbr").value;
			var data = null;
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
			
			if( login != "" )
			{
				data = "login=" + login;
			   
				xhr_object.open("POST", filename, true);

				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 ) 
					{
						document.getElementById("xmlhttprequest_result_search").innerHTML = xhr_object.responseText;
						hide_div("xmlhttprequest_result_search");
					}
				}

				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

				xhr_object.send(data);
			}	
			else
			{
				alert("<?php echo isset($this->_var['L_REQUIRE_LOGIN']) ? $this->_var['L_REQUIRE_LOGIN'] : ''; ?>");
			}		
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
					<form action="../member/member.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>" method="post">
						<?php echo isset($this->_var['L_SELECT_GROUP']) ? $this->_var['L_SELECT_GROUP'] : ''; ?>: <select name="show_group" style="text-align:center;" onchange="document.location = <?php echo isset($this->_var['U_SELECT_SHOW_GROUP']) ? $this->_var['U_SELECT_SHOW_GROUP'] : ''; ?>;">
							<option value="0" selected="selected">-- <?php echo isset($this->_var['L_LIST']) ? $this->_var['L_LIST'] : ''; ?> --</option>
							<?php if( !isset($_tmpb_all['group']) || !is_array($_tmpb_all['group']) ) $_tmpb_all['group'] = array();
foreach($_tmpb_all['group'] as $group_key => $group_value) {
$_tmpb_group = &$_tmpb_all['group'][$group_key]; ?>
								<?php echo isset($_tmpb_group['OPTION']) ? $_tmpb_group['OPTION'] : ''; ?>
							<?php } ?>
						</select>
						
						<noscript>
							<input type="submit" name="valid" value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" class="submit" />
						</noscript>
					</form>				
				</td>

				<td style="vertical-align:top;" class="row2">
					<table>
						<tr>
							<td style="vertical-align: top">
								<form action="member.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>" method="post">
									<?php echo isset($this->_var['L_SEARCH_MEMBER']) ? $this->_var['L_SEARCH_MEMBER'] : ''; ?>: <input type="text" size="20" maxlenght="25" id="login_mbr" value="<?php echo isset($_tmpb_all['LOGIN']) ? $_tmpb_all['LOGIN'] : ''; ?>" name="login_mbr" class="text" />
								
									<script type="text/javascript">
									<!--								
										document.write('<input value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">');
									-->
									</script>
									
									<noscript>
										<input type="submit" name="search_member" value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" class="submit" />
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
					<?php echo isset($this->_var['L_PROFIL']) ? $this->_var['L_PROFIL'] : ''; ?>
				</th>
			</tr>
			<tr>
				<td colspan="8" class="row1">
					<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>&nbsp;
				</td>
			</tr>	
			<tr style="font-weight:bold;text-align: center;">
				<td class="row3">
					<a href="member<?php echo isset($this->_var['U_MEMBER_ALPHA_TOP']) ? $this->_var['U_MEMBER_ALPHA_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
					<?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?> 
					<a href="member<?php echo isset($this->_var['U_MEMBER_ALPHA_BOTTOM']) ? $this->_var['U_MEMBER_ALPHA_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
				</td>
				<td class="row3">
					<?php echo isset($this->_var['L_MAIL']) ? $this->_var['L_MAIL'] : ''; ?>
				</td>
				<td class="row3">
					<a href="member<?php echo isset($this->_var['U_MEMBER_TIME_TOP']) ? $this->_var['U_MEMBER_TIME_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
					<?php echo isset($this->_var['L_REGISTERED']) ? $this->_var['L_REGISTERED'] : ''; ?>
					<a href="member<?php echo isset($this->_var['U_MEMBER_TIME_BOTTOM']) ? $this->_var['U_MEMBER_TIME_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
				</td>
				<td class="row3">
					<a href="member<?php echo isset($this->_var['U_MEMBER_MSG_TOP']) ? $this->_var['U_MEMBER_MSG_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
					<?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?>
					<a href="member<?php echo isset($this->_var['U_MEMBER_MSG_BOTTOM']) ? $this->_var['U_MEMBER_MSG_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>	
				</td>
				<td class="row3">
					<?php echo isset($this->_var['L_LOCALISATION']) ? $this->_var['L_LOCALISATION'] : ''; ?>
				</td>
				<td class="row3">
					<a href="member<?php echo isset($this->_var['U_MEMBER_LAST_TOP']) ? $this->_var['U_MEMBER_LAST_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
					<?php echo isset($this->_var['L_LAST_CONNECT']) ? $this->_var['L_LAST_CONNECT'] : ''; ?>
					<a href="member<?php echo isset($this->_var['U_MEMBER_LAST_BOTTOM']) ? $this->_var['U_MEMBER_LAST_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
				</td>
				<td class="row3">
					<?php echo isset($this->_var['L_PRIVATE_MESSAGE']) ? $this->_var['L_PRIVATE_MESSAGE'] : ''; ?>
				</td>
				<td class="row3">
					<?php echo isset($this->_var['L_WEB_SITE']) ? $this->_var['L_WEB_SITE'] : ''; ?>
				</td>
							
			</tr>
			
			<?php if( !isset($_tmpb_all['member']) || !is_array($_tmpb_all['member']) ) $_tmpb_all['member'] = array();
foreach($_tmpb_all['member'] as $member_key => $member_value) {
$_tmpb_member = &$_tmpb_all['member'][$member_key]; ?>
			<tr> 
				<td class="row2" style="text-align:center;padding:4px 0px;">
					<a href="member<?php echo isset($_tmpb_member['U_MEMBER_ID']) ? $_tmpb_member['U_MEMBER_ID'] : ''; ?>"><?php echo isset($_tmpb_member['PSEUDO']) ? $_tmpb_member['PSEUDO'] : ''; ?></a>
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					<?php echo isset($_tmpb_member['MAIL']) ? $_tmpb_member['MAIL'] : ''; ?>
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					<?php echo isset($_tmpb_member['DATE']) ? $_tmpb_member['DATE'] : ''; ?>
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					<?php echo isset($_tmpb_member['MSG']) ? $_tmpb_member['MSG'] : ''; ?>
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					<?php echo isset($_tmpb_member['LOCAL']) ? $_tmpb_member['LOCAL'] : ''; ?>
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					<?php echo isset($_tmpb_member['LAST_CONNECT']) ? $_tmpb_member['LAST_CONNECT'] : ''; ?>
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					<a href="pm<?php echo isset($_tmpb_member['U_MEMBER_PM']) ? $_tmpb_member['U_MEMBER_PM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/pm.png" alt="<?php echo isset($this->_var['L_PRIVATE_MESSAGE']) ? $this->_var['L_PRIVATE_MESSAGE'] : ''; ?>" /></a>
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;"> 
					<?php echo isset($_tmpb_member['WEB']) ? $_tmpb_member['WEB'] : ''; ?>
				</td>
			</tr>
			<?php } ?>

			<tr>
				<td colspan="8" class="row1">
					<span style="float:left;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></span>
				</td>
			</tr>
		</table>
		<?php } ?>



		<?php if( !isset($this->_block['profil']) || !is_array($this->_block['profil']) ) $this->_block['profil'] = array();
foreach($this->_block['profil'] as $profil_key => $profil_value) {
$_tmpb_profil = &$this->_block['profil'][$profil_key]; ?>
		<div class="fieldset_content">
			<fieldset>
				<legend><?php echo isset($this->_var['L_PROFIL']) ? $this->_var['L_PROFIL'] : ''; ?></legend>
				<?php if( !isset($_tmpb_profil['edit']) || !is_array($_tmpb_profil['edit']) ) $_tmpb_profil['edit'] = array();
foreach($_tmpb_profil['edit'] as $edit_key => $edit_value) {
$_tmpb_edit = &$_tmpb_profil['edit'][$edit_key]; ?>
				<dl>
					<dt><?php echo isset($this->_var['L_PROFIL_EDIT']) ? $this->_var['L_PROFIL_EDIT'] : ''; ?></dt>
					<dd><a href="<?php echo isset($_tmpb_edit['U_MEMBER_SCRIPT']) ? $_tmpb_edit['U_MEMBER_SCRIPT'] : ''; ?>" title="<?php echo isset($this->_var['L_PROFIL_EDIT']) ? $this->_var['L_PROFIL_EDIT'] : ''; ?>"><img src="../templates/<?php echo isset($_tmpb_edit['THEME']) ? $_tmpb_edit['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="<?php echo isset($this->_var['L_PROFIL_EDIT']) ? $this->_var['L_PROFIL_EDIT'] : ''; ?>" /></a></dd>
				</dl>
				<?php } ?>				
				<dl>
					<dt><?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['USER_NAME']) ? $_tmpb_profil['USER_NAME'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_AVATAR']) ? $this->_var['L_AVATAR'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['USER_AVATAR']) ? $_tmpb_profil['USER_AVATAR'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_STATUT']) ? $this->_var['L_STATUT'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['STATUT']) ? $_tmpb_profil['STATUT'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_GROUPS']) ? $this->_var['L_GROUPS'] : ''; ?></dt>
					<dd>
						<?php if( !isset($_tmpb_profil['groups']) || !is_array($_tmpb_profil['groups']) ) $_tmpb_profil['groups'] = array();
foreach($_tmpb_profil['groups'] as $groups_key => $groups_value) {
$_tmpb_groups = &$_tmpb_profil['groups'][$groups_key]; ?>
						<?php echo isset($_tmpb_groups['USER_GROUP']) ? $_tmpb_groups['USER_GROUP'] : ''; ?>
						<?php } ?>
					</dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_REGISTERED']) ? $this->_var['L_REGISTERED'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['DATE']) ? $_tmpb_profil['DATE'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_NBR_MESSAGE']) ? $this->_var['L_NBR_MESSAGE'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['USER_MSG']) ? $_tmpb_profil['USER_MSG'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_LAST_CONNECT']) ? $this->_var['L_LAST_CONNECT'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['LAST_CONNECT']) ? $_tmpb_profil['LAST_CONNECT'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_WEB_SITE']) ? $this->_var['L_WEB_SITE'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['WEB']) ? $_tmpb_profil['WEB'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_LOCALISATION']) ? $this->_var['L_LOCALISATION'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['LOCAL']) ? $_tmpb_profil['LOCAL'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_JOB']) ? $this->_var['L_JOB'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['OCCUPATION']) ? $_tmpb_profil['OCCUPATION'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_HOBBIES']) ? $this->_var['L_HOBBIES'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['HOBBIES']) ? $_tmpb_profil['HOBBIES'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_SEX']) ? $this->_var['L_SEX'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['USER_SEX']) ? $_tmpb_profil['USER_SEX'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_AGE']) ? $this->_var['L_AGE'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['USER_AGE']) ? $_tmpb_profil['USER_AGE'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_BIOGRAPHY']) ? $this->_var['L_BIOGRAPHY'] : ''; ?></dt>
					<dd><div><?php echo isset($_tmpb_profil['USER_DESC']) ? $_tmpb_profil['USER_DESC'] : ''; ?></div></dd>
				</dl>
			</fieldset>
			
			<fieldset>
				<legend><?php echo isset($this->_var['L_CONTACT']) ? $this->_var['L_CONTACT'] : ''; ?></legend>
				<dl>
					<dt><?php echo isset($this->_var['L_MAIL']) ? $this->_var['L_MAIL'] : ''; ?></dt>
					<dd><?php echo isset($_tmpb_profil['MAIL']) ? $_tmpb_profil['MAIL'] : ''; ?></dd>
				</dl>
				<dl>
					<dt><?php echo isset($this->_var['L_PRIVATE_MESSAGE']) ? $this->_var['L_PRIVATE_MESSAGE'] : ''; ?></dt>
					<dd><a href="pm<?php echo isset($_tmpb_profil['U_MEMBER_PM']) ? $_tmpb_profil['U_MEMBER_PM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/pm.png" alt="<?php echo isset($this->_var['L_PRIVATE_MESSAGE']) ? $this->_var['L_PRIVATE_MESSAGE'] : ''; ?>" /></a></dd>
				</dl>
				<dl>
					<dt>MSN</dt>
					<dd><?php echo isset($_tmpb_profil['USER_MSN']) ? $_tmpb_profil['USER_MSN'] : ''; ?></dd>
				</dl>
				<dl>
					<dt>Yahoo</dt>
					<dd><?php echo isset($_tmpb_profil['USER_YAHOO']) ? $_tmpb_profil['USER_YAHOO'] : ''; ?></dd>
				</dl>
			</fieldset>
			
			<?php if( !isset($_tmpb_profil['miscellaneous']) || !is_array($_tmpb_profil['miscellaneous']) ) $_tmpb_profil['miscellaneous'] = array();
foreach($_tmpb_profil['miscellaneous'] as $miscellaneous_key => $miscellaneous_value) {
$_tmpb_miscellaneous = &$_tmpb_profil['miscellaneous'][$miscellaneous_key]; ?>
			<fieldset>
				<legend><?php echo isset($this->_var['L_MISCELLANEOUS']) ? $this->_var['L_MISCELLANEOUS'] : ''; ?></legend>						
				<?php if( !isset($_tmpb_miscellaneous['list']) || !is_array($_tmpb_miscellaneous['list']) ) $_tmpb_miscellaneous['list'] = array();
foreach($_tmpb_miscellaneous['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_miscellaneous['list'][$list_key]; ?>
				<dl>
					<dt><?php echo isset($_tmpb_list['NAME']) ? $_tmpb_list['NAME'] : ''; ?><br /><span><?php echo isset($_tmpb_list['DESC']) ? $_tmpb_list['DESC'] : ''; ?></span></dt>
					<dd><?php echo isset($_tmpb_list['FIELD']) ? $_tmpb_list['FIELD'] : ''; ?></dd>
				</dl>
				<?php } ?>	
			</fieldset>
			<?php } ?>
		</div>		
		<?php } ?>

		

		<?php if( !isset($this->_block['group']) || !is_array($this->_block['group']) ) $this->_block['group'] = array();
foreach($this->_block['group'] as $group_key => $group_value) {
$_tmpb_group = &$this->_block['group'][$group_key]; ?>
		<table class="module_table" style="width:70%;">	
			<tr>
				<td style="vertical-align:top;" class="row2">
					<form action="member.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>" method="post">
						<?php echo isset($this->_var['L_SELECT_GROUP']) ? $this->_var['L_SELECT_GROUP'] : ''; ?>: <select name="show_group" style="text-align:center;" onchange="document.location = <?php echo isset($this->_var['U_SELECT_SHOW_GROUP']) ? $this->_var['U_SELECT_SHOW_GROUP'] : ''; ?>;">  
							<option value="0" selected="selected">-- <?php echo isset($this->_var['L_LIST']) ? $this->_var['L_LIST'] : ''; ?> --</option>
							<?php if( !isset($_tmpb_group['select']) || !is_array($_tmpb_group['select']) ) $_tmpb_group['select'] = array();
foreach($_tmpb_group['select'] as $select_key => $select_value) {
$_tmpb_select = &$_tmpb_group['select'][$select_key]; ?>
								<?php echo isset($_tmpb_select['OPTION']) ? $_tmpb_select['OPTION'] : ''; ?>
							<?php } ?>
						</select>
						&nbsp;&nbsp;<?php echo isset($_tmpb_group['ADMIN_GROUPS']) ? $_tmpb_group['ADMIN_GROUPS'] : ''; ?>
						<noscript>
							<input type="submit" name="valid" value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" class="submit" />
						</noscript>
					</form>				
				</td>
			</tr>
		</table>	

		<br /><br />

		<table class="module_table" style="width: 70%;text-align:center;">
			<tr>
				<th colspan="3">
					<?php echo isset($_tmpb_group['GROUP_NAME']) ? $_tmpb_group['GROUP_NAME'] : ''; ?>
				</th>
			</tr>
			<tr>
				<td class="row3" colspan="3" style="text-align:left;">
					<a href="member.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_BACK']) ? $this->_var['L_BACK'] : ''; ?></a>
				</td>
			</tr>
			<tr>
				<td class="row3" style="font-weight: bold;width: 120px;">
					<?php echo isset($this->_var['L_AVATAR']) ? $this->_var['L_AVATAR'] : ''; ?>
				</td>
				<td class="row3" style="font-weight: bold;">
					<?php echo isset($this->_var['L_LOGIN']) ? $this->_var['L_LOGIN'] : ''; ?>
				</td>
				<td class="row3" style="font-weight: bold;">
					<?php echo isset($this->_var['L_STATUT']) ? $this->_var['L_STATUT'] : ''; ?>
				</td>
			</tr>
			
			<?php if( !isset($_tmpb_group['list']) || !is_array($_tmpb_group['list']) ) $_tmpb_group['list'] = array();
foreach($_tmpb_group['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_group['list'][$list_key]; ?>
			<tr>
				<td class="row1">
					<?php echo isset($_tmpb_list['USER_AVATAR']) ? $_tmpb_list['USER_AVATAR'] : ''; ?>
				</td>
				<td class="row1">
					<?php echo isset($_tmpb_list['U_MEMBER']) ? $_tmpb_list['U_MEMBER'] : ''; ?>
				</td>
				<td class="row1">
					<?php echo isset($_tmpb_list['USER_RANK']) ? $_tmpb_list['USER_RANK'] : ''; ?>
				</td>
			</tr>	
			<?php } ?>
		</table>
		<?php } ?>
