		<?php if( !isset($this->_block['confirm']) || !is_array($this->_block['confirm']) ) $this->_block['confirm'] = array();
foreach($this->_block['confirm'] as $confirm_key => $confirm_value) {
$_tmpb_confirm = &$this->_block['confirm'][$confirm_key]; ?>

		<form action="" method="post">
			<table class="module_table">
				<tr>
					<th colspan="2">
						<?php echo isset($this->_var['L_REGISTER']) ? $this->_var['L_REGISTER'] : ''; ?>
					</th>
				</tr>
				<tr>
					<td colspan="2" class="row3" style="text-align:center;">
						<span class="text_strong"><?php echo isset($this->_var['L_REGISTRATION_TERMS']) ? $this->_var['L_REGISTRATION_TERMS'] : ''; ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="row2">
						<?php echo isset($this->_var['MSG_REGISTER']) ? $this->_var['MSG_REGISTER'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="row3" style="text-align:center;">
						<label><input type="checkbox" name="confirm" value="true" /> <?php echo isset($this->_var['L_ACCEPT']) ? $this->_var['L_ACCEPT'] : ''; ?></label>
					</td>	
				</tr>	
			</table>
			<br /><br />
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
				<input type="submit" name="register" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
			</fieldset>
		</form>

		<?php } ?>


		<?php if( !isset($this->_block['activ']) || !is_array($this->_block['activ']) ) $this->_block['activ'] = array();
foreach($this->_block['activ'] as $activ_key => $activ_value) {
$_tmpb_activ = &$this->_block['activ'][$activ_key]; ?>

		<form action="" method="post">
		<table class="module_table">
			<tr>
				<th colspan="2">
					<?php echo isset($this->_var['L_REGISTER']) ? $this->_var['L_REGISTER'] : ''; ?>
				</th>
			</tr>
			<tr>
				<td colspan="2" class="row3" style="text-align:center;">
					<br /><br />
					<span class="text_strong"><?php echo isset($this->_var['L_ACTIVATION_REPORT']) ? $this->_var['L_ACTIVATION_REPORT'] : ''; ?></span>
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

		<?php } ?>


		<?php if( !isset($this->_block['register']) || !is_array($this->_block['register']) ) $this->_block['register'] = array();
foreach($this->_block['register'] as $register_key => $register_value) {
$_tmpb_register = &$this->_block['register'][$register_key]; ?>

		<script type="text/javascript">
		<!--
		function check_form()
		{
			if(document.getElementById('mail').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_MAIL']) ? $this->_var['L_REQUIRE_MAIL'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('log').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_PSEUDO']) ? $this->_var['L_REQUIRE_PSEUDO'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('pass').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_PASSWORD']) ? $this->_var['L_REQUIRE_PASSWORD'] : ''; ?>");
				return false;
		    }
			if( document.getElementById('pass_bis').value == "" ) {
				alert("<?php echo isset($this->_var['L_REQUIRE_PASSWORD']) ? $this->_var['L_REQUIRE_PASSWORD'] : ''; ?>");
				return false;
		    }
			<?php echo isset($this->_var['L_REQUIRE_VERIF_CODE']) ? $this->_var['L_REQUIRE_VERIF_CODE'] : ''; ?>
			
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
		function refresh_img()
		{
			if ( typeof this.img_id == 'undefined' )
				this.img_id = 0;
			else
				this.img_id++;
			
			var xhr_object = null;
			var data = null;
			var filename = "verif_code.php";
			
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
					document.getElementById('verif_code_img').src = 'verif_code.php?new=' + img_id;	
				}
			}

			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(data);
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

		<?php if( !isset($_tmpb_register['error_handler']) || !is_array($_tmpb_register['error_handler']) ) $_tmpb_register['error_handler'] = array();
foreach($_tmpb_register['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_register['error_handler'][$error_handler_key]; ?>
		<span id="errorh"></span>
		<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
			<br />	
		</div>
		<br />		
		<?php } ?>
		
		<script type="text/javascript" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.js"></script>
		<form action="../member/register_valid.php" enctype="multipart/form-data" method="post" onsubmit="return check_form();" class="fieldset_content">
			<fieldset>
				<legend><?php echo isset($this->_var['L_REGISTER']) ? $this->_var['L_REGISTER'] : ''; ?></legend>
				<p>
					<?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?>
					
					<?php if( !isset($_tmpb_register['activ_mbr']) || !is_array($_tmpb_register['activ_mbr']) ) $_tmpb_register['activ_mbr'] = array();
foreach($_tmpb_register['activ_mbr'] as $activ_mbr_key => $activ_mbr_value) {
$_tmpb_activ_mbr = &$_tmpb_register['activ_mbr'][$activ_mbr_key]; ?>
					<br />
					<strong><?php echo isset($_tmpb_activ_mbr['L_ACTIV_MBR']) ? $_tmpb_activ_mbr['L_ACTIV_MBR'] : ''; ?></strong>
					<?php } ?>
				</p>
				
				<dl>
					<dt><label for="log">* <?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_PSEUDO_HOW']) ? $this->_var['L_PSEUDO_HOW'] : ''; ?></span></dt>
					<dd><label><input size="25" type="text" class="text" name="log" id="log" maxlength="25" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="mail">* <?php echo isset($this->_var['L_MAIL']) ? $this->_var['L_MAIL'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_VALID']) ? $this->_var['L_VALID'] : ''; ?></span></dt>
					<dd><label><input size="30" type="text" class="text" name="mail" id="mail" maxlength="50" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="pass">* <?php echo isset($this->_var['L_PASSWORD']) ? $this->_var['L_PASSWORD'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_PASSWORD_HOW']) ? $this->_var['L_PASSWORD_HOW'] : ''; ?></span></dt>
					<dd><label><input size="30" type="password" class="text" name="pass" id="pass" maxlength="30" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="pass_bis">* <?php echo isset($this->_var['L_CONFIRM_PASSWORD']) ? $this->_var['L_CONFIRM_PASSWORD'] : ''; ?></label></dt>
					<dd><label><input size="30" type="password" class="text" name="pass_bis" id="pass_bis" maxlength="30" /></label></dd>			
				</dl>
				<?php if( !isset($_tmpb_register['verif_code']) || !is_array($_tmpb_register['verif_code']) ) $_tmpb_register['verif_code'] = array();
foreach($_tmpb_register['verif_code'] as $verif_code_key => $verif_code_value) {
$_tmpb_verif_code = &$_tmpb_register['verif_code'][$verif_code_key]; ?>
				<dl>
					<dt><label for="verif_code">* <?php echo isset($this->_var['L_VERIF_CODE']) ? $this->_var['L_VERIF_CODE'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_VERIF_CODE_EXPLAIN']) ? $this->_var['L_VERIF_CODE_EXPLAIN'] : ''; ?></span></dt>
					<dd><label>
						<img src="verif_code.php" id="verif_code_img" alt="" style="padding:2px;" />
						<br />
						<input size="30" type="text" class="text" name="verif_code" id="verif_code" />
						<a href="javascript:refresh_img()"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/refresh.png" alt="" class="valign_middle" /></a>
					</label></dd>			
				</dl>
				<?php } ?>
				<dl>
					<dt><label for="user_lang">* <?php echo isset($this->_var['L_LANG_CHOOSE']) ? $this->_var['L_LANG_CHOOSE'] : ''; ?></label></dt>
					<dd>
						<label>
							<select name="user_lang" id="user_lang" onchange="change_img_lang('img_lang', this.options[this.selectedIndex].value)">						
								<?php if( !isset($_tmpb_register['select']) || !is_array($_tmpb_register['select']) ) $_tmpb_register['select'] = array();
foreach($_tmpb_register['select'] as $select_key => $select_value) {
$_tmpb_select = &$_tmpb_register['select'][$select_key]; ?>						
								<?php echo isset($_tmpb_select['LANG']) ? $_tmpb_select['LANG'] : ''; ?>						
								<?php } ?>						
							</select>
							<img id="img_lang" src="<?php echo isset($this->_var['IMG_LANG_IDENTIFIER']) ? $this->_var['IMG_LANG_IDENTIFIER'] : ''; ?>" alt="" class="valign_middle" />
						</label>
					</dd>			
				</dl>
			</fieldset>
				
			<fieldset>
				<legend><?php echo isset($this->_var['L_OPTIONS']) ? $this->_var['L_OPTIONS'] : ''; ?></legend>					
				<dl>
					<dt><label for="user_theme"><?php echo isset($this->_var['L_THEME_CHOOSE']) ? $this->_var['L_THEME_CHOOSE'] : ''; ?></label></dt>
					<dd>
						<label>
							<select name="user_theme" id="user_theme" onChange="change_img_theme('img_theme', this.options[selectedIndex].value)">			
								<?php if( !isset($_tmpb_register['select_theme']) || !is_array($_tmpb_register['select_theme']) ) $_tmpb_register['select_theme'] = array();
foreach($_tmpb_register['select_theme'] as $select_theme_key => $select_theme_value) {
$_tmpb_select_theme = &$_tmpb_register['select_theme'][$select_theme_key]; ?>						
								<?php echo isset($_tmpb_select_theme['THEME']) ? $_tmpb_select_theme['THEME'] : ''; ?>						
								<?php } ?>						
							</select>
							<img id="img_theme" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/theme.jpg" alt="" style="vertical-align:top" />
						</label>
					</dd>			
				</dl>
				<dl>
					<dt><label for="user_editor"><?php echo isset($this->_var['L_EDITOR_CHOOSE']) ? $this->_var['L_EDITOR_CHOOSE'] : ''; ?></label></dt>
					<dd>
						<label>
							<select name="user_editor" id="user_editor">	
								<?php echo isset($this->_var['SELECT_EDITORS']) ? $this->_var['SELECT_EDITORS'] : ''; ?>						
							</select>
						</label>
					</dd>			
				</dl>
				<dl>
					<dt><label for="user_timezone"><?php echo isset($this->_var['L_TIMEZONE_CHOOSE']) ? $this->_var['L_TIMEZONE_CHOOSE'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_TIMEZONE_CHOOSE_EXPLAIN']) ? $this->_var['L_TIMEZONE_CHOOSE_EXPLAIN'] : ''; ?></span></dt>
					<dd>
						<label>
							<select name="user_timezone" id="user_timezone">	
								<?php echo isset($this->_var['SELECT_TIMEZONE']) ? $this->_var['SELECT_TIMEZONE'] : ''; ?>						
							</select>
						</label>
					</dd>			
				</dl>
				<dl>
					<dt><label for="user_show_mail"><?php echo isset($this->_var['L_HIDE_MAIL']) ? $this->_var['L_HIDE_MAIL'] : ''; ?></label></dt>
					<dd><label><input type="checkbox" class="text" name="user_show_mail" id="user_show_mail" /></label></dd>			
				</dl>
			</fieldset>	

			<fieldset>
				<legend><?php echo isset($this->_var['L_INFO']) ? $this->_var['L_INFO'] : ''; ?></legend>			
				<dl>
					<dt><label for="user_web"><?php echo isset($this->_var['L_WEB_SITE']) ? $this->_var['L_WEB_SITE'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_VALID']) ? $this->_var['L_VALID'] : ''; ?></span></dt>
					<dd><label><input size="30" type="text" class="text" name="user_web" id="user_web" maxlength="70" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="user_local"><?php echo isset($this->_var['L_LOCALISATION']) ? $this->_var['L_LOCALISATION'] : ''; ?></label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_local" id="user_local" maxlength="25" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="user_occupation"><?php echo isset($this->_var['L_JOB']) ? $this->_var['L_JOB'] : ''; ?></label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_occupation" id="user_occupation" maxlength="50" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="user_hobbies"><?php echo isset($this->_var['L_HOBBIES']) ? $this->_var['L_HOBBIES'] : ''; ?></label></dt>
					<dd><label><input size="30" type="text" class="text" name="user_hobbies" id="user_hobbies" maxlength="50" /></label></dd>			
				</dl>
				<dl>
					<dt><label for="user_sex"><?php echo isset($this->_var['L_SEX']) ? $this->_var['L_SEX'] : ''; ?></label></dt>
					<dd><label>
						<select name="user_sex" id="user_sex" onchange="img_sex(this.options[selectedIndex].value)">
							<option selected="seleted" value="0">--</option>
							<option value="1"><?php echo isset($this->_var['L_MALE']) ? $this->_var['L_MALE'] : ''; ?></option>
							<option value="2"><?php echo isset($this->_var['L_FEMALE']) ? $this->_var['L_FEMALE'] : ''; ?></option>
						</select>
						<span id="img_sex"></span>
					</label></dd>			
				</dl>
				<dl class="overflow_visible">
					<dt><label for="user_born"><?php echo isset($this->_var['L_DATE_OF_BIRTH']) ? $this->_var['L_DATE_OF_BIRTH'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_VALID']) ? $this->_var['L_VALID'] : ''; ?></span></dt>
					<dd><label>
						<input size="10" maxlength="10" type="text" class="text" id="user_born" name="user_born" /> 
						
						<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
							<div id="calendar" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);"></div>
						</div>
						<a onClick="xmlhttprequest_calendar('calendar', '?input_field=user_born&amp;field=calendar&amp;lyear=1');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a>
					</label></dd>			
				</dl>
				<dl>
					<dt><label for="user_sign"><?php echo isset($this->_var['L_SIGN']) ? $this->_var['L_SIGN'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_SIGN_WHERE']) ? $this->_var['L_SIGN_WHERE'] : ''; ?></span></dt>
					<dd><label><textarea class="post" rows="4" cols="27" name="user_sign" id="user_sign"></textarea> </label></dd>			
				</dl>
			</fieldset>
			
			<fieldset>
				<legend><?php echo isset($this->_var['L_CONTACT']) ? $this->_var['L_CONTACT'] : ''; ?></legend>			
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
				<legend><?php echo isset($this->_var['L_AVATAR_MANAGEMENT']) ? $this->_var['L_AVATAR_MANAGEMENT'] : ''; ?></legend>		
				<?php if( !isset($_tmpb_register['upload_avatar']) || !is_array($_tmpb_register['upload_avatar']) ) $_tmpb_register['upload_avatar'] = array();
foreach($_tmpb_register['upload_avatar'] as $upload_avatar_key => $upload_avatar_value) {
$_tmpb_upload_avatar = &$_tmpb_register['upload_avatar'][$upload_avatar_key]; ?>
				<dl>
					<dt><label for="avatars"><?php echo isset($this->_var['L_UPLOAD_AVATAR']) ? $this->_var['L_UPLOAD_AVATAR'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_UPLOAD_AVATAR_WHERE']) ? $this->_var['L_UPLOAD_AVATAR_WHERE'] : ''; ?></span></dt>
					<dd><label>
						<input type="file" name="avatars" id="avatars" size="30" class="text" />					
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
					<dt><label for="user_avatar"><?php echo isset($this->_var['L_AVATAR_LINK']) ? $this->_var['L_AVATAR_LINK'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_AVATAR_LINK_WHERE']) ? $this->_var['L_AVATAR_LINK_WHERE'] : ''; ?></span></dt>
					<dd><label><input type="text" name="user_avatar" id="user_avatar" size="30" /></label></dd>			
				</dl>
			</fieldset>

			<?php if( !isset($_tmpb_register['miscellaneous']) || !is_array($_tmpb_register['miscellaneous']) ) $_tmpb_register['miscellaneous'] = array();
foreach($_tmpb_register['miscellaneous'] as $miscellaneous_key => $miscellaneous_value) {
$_tmpb_miscellaneous = &$_tmpb_register['miscellaneous'][$miscellaneous_key]; ?>
			<fieldset>
				<legend><?php echo isset($this->_var['L_MISCELLANEOUS']) ? $this->_var['L_MISCELLANEOUS'] : ''; ?></legend>	
					
				<?php if( !isset($_tmpb_miscellaneous['list']) || !is_array($_tmpb_miscellaneous['list']) ) $_tmpb_miscellaneous['list'] = array();
foreach($_tmpb_miscellaneous['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_miscellaneous['list'][$list_key]; ?>
				<dl>
					<dt><label for="<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>"><?php echo isset($_tmpb_list['NAME']) ? $_tmpb_list['NAME'] : ''; ?></label><br /><span><?php echo isset($_tmpb_list['DESC']) ? $_tmpb_list['DESC'] : ''; ?></span></dt>
					<dd><?php echo isset($_tmpb_list['FIELD']) ? $_tmpb_list['FIELD'] : ''; ?></dd>
				</dl>
				<?php } ?>	
			</fieldset>
			<?php } ?>	

			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
				<input type="submit" name="register_valid" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />	
			</fieldset>
		</form>

		<?php } ?>
		