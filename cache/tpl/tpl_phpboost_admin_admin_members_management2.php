		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('name').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_PSEUDO']) ? $this->_var['L_REQUIRE_PSEUDO'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('mail').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_MAIL']) ? $this->_var['L_REQUIRE_MAIL'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('level').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_RANK']) ? $this->_var['L_REQUIRE_RANK'] : ''; ?>");
				return false;
		    }
			
			return true;
		}
		-->
		</script>

		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("<?php echo isset($this->_var['L_CONFIRM_DEL_MEMBER']) ? $this->_var['L_CONFIRM_DEL_MEMBER'] : ''; ?>");
		}
		-->
		</script>
	
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_MEMBERS_MANAGEMENT']) ? $this->_var['L_MEMBERS_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_members.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php" class="quick_link"><?php echo isset($this->_var['L_MEMBERS_MANAGEMENT']) ? $this->_var['L_MEMBERS_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_members.php?add=1"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php?add=1" class="quick_link"><?php echo isset($this->_var['L_MEMBERS_ADD']) ? $this->_var['L_MEMBERS_ADD'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_members_config.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_config.php" class="quick_link"><?php echo isset($this->_var['L_MEMBERS_CONFIG']) ? $this->_var['L_MEMBERS_CONFIG'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_members_punishment.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_punishment.php" class="quick_link"><?php echo isset($this->_var['L_MEMBERS_PUNISHMENT']) ? $this->_var['L_MEMBERS_PUNISHMENT'] : ''; ?></a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
	
			<?php if( !isset($this->_block['members_add']) || !is_array($this->_block['members_add']) ) $this->_block['members_add'] = array();
foreach($this->_block['members_add'] as $members_add_key => $members_add_value) {
$_tmpb_members_add = &$this->_block['members_add'][$members_add_key]; ?>
			
			<?php if( !isset($_tmpb_members_add['error_handler']) || !is_array($_tmpb_members_add['error_handler']) ) $_tmpb_members_add['error_handler'] = array();
foreach($_tmpb_members_add['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_members_add['error_handler'][$error_handler_key]; ?>
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
					<br />	
				</div>
			</div>
			<?php } ?>
			
			<form action="admin_members.php?add=1" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_MEMBERS_ADD']) ? $this->_var['L_MEMBERS_ADD'] : ''; ?></legend>
					<dl>
						<dt><label for="login2">* <?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?></label></dt>
						<dd><label><input type="text" maxlength="25" size="20" id="login2" name="login2" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="password2">* <?php echo isset($this->_var['L_PASSWORD']) ? $this->_var['L_PASSWORD'] : ''; ?></label></dt>
						<dd><label><input type="password" maxlength="30" size="20" id="password2" name="password2" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="password2_bis">* <?php echo isset($this->_var['L_PASSWORD_CONFIRM']) ? $this->_var['L_PASSWORD_CONFIRM'] : ''; ?></label></dt>
						<dd><label><input type="password" maxlength="30" size="20" id="password2_bis" name="password2_bis" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="mail2">* <?php echo isset($this->_var['L_MAIL']) ? $this->_var['L_MAIL'] : ''; ?></label></dt>
						<dd><label><input type="text" maxlength="50" size="30" id="mail2" name="mail2" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="level2">* <?php echo isset($this->_var['L_RANK']) ? $this->_var['L_RANK'] : ''; ?></label></dt>
						<dd><label>
							<select id="level2" name="level2">
								<option value="0" selected="selected"><?php echo isset($this->_var['L_MEMBER']) ? $this->_var['L_MEMBER'] : ''; ?></option>
								<option value="1"><?php echo isset($this->_var['L_MODO']) ? $this->_var['L_MODO'] : ''; ?></option>
								<option value="2"><?php echo isset($this->_var['L_ADMIN']) ? $this->_var['L_ADMIN'] : ''; ?></option>
							</select>
						</label></dd>
					</dl>
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_ADD']) ? $this->_var['L_ADD'] : ''; ?></legend>
					<input type="submit" name="add" value="<?php echo isset($this->_var['L_ADD']) ? $this->_var['L_ADD'] : ''; ?>" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />				
				</fieldset>	
			</form>
			
			<?php } ?>
			
			
			<?php if( !isset($this->_block['members_management']) || !is_array($this->_block['members_management']) ) $this->_block['members_management'] = array();
foreach($this->_block['members_management'] as $members_management_key => $members_management_value) {
$_tmpb_members_management = &$this->_block['members_management'][$members_management_key]; ?>
			
			<script type="text/javascript">
			<!--
			function check_select_multiple(id, status)
			{
				var i;
				
				for(i = 0; i < <?php echo isset($_tmpb_members_management['NBR_GROUP']) ? $_tmpb_members_management['NBR_GROUP'] : ''; ?>; i++)
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
			<script type="text/javascript" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.js"></script>
			
			<?php if( !isset($_tmpb_members_management['error_handler']) || !is_array($_tmpb_members_management['error_handler']) ) $_tmpb_members_management['error_handler'] = array();
foreach($_tmpb_members_management['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_members_management['error_handler'][$error_handler_key]; ?>
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
					<br />	
				</div>
			</div>
			<?php } ?>
			
			<form action="admin_members.php" enctype="multipart/form-data" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_MEMBERS_MANAGEMENT']) ? $this->_var['L_MEMBERS_MANAGEMENT'] : ''; ?></legend>
					<dl>
						<dt><label for="name">* <?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?></label></dt>
						<dd><label><input type="text" maxlength="25" size="25" id="name" name="name" value="<?php echo isset($this->_var['NAME']) ? $this->_var['NAME'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="pass">* <?php echo isset($this->_var['L_PASSWORD']) ? $this->_var['L_PASSWORD'] : ''; ?></label></dt>
						<dd><label><input type="password" maxlength="30" size="30" name="pass" id="pass" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="confirm_pass">* <?php echo isset($this->_var['L_CONFIRM_PASSWORD']) ? $this->_var['L_CONFIRM_PASSWORD'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_CONFIRM_PASSWORD_EXPLAIN']) ? $this->_var['L_CONFIRM_PASSWORD_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="password" maxlength="30" size="30" name="confirm_pass" id="confirm_pass" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="mail">* <?php echo isset($this->_var['L_MAIL']) ? $this->_var['L_MAIL'] : ''; ?></label></dt>
						<dd><label><input type="text" maxlength="50" size="50" id="mail" name="mail" value="<?php echo isset($this->_var['MAIL']) ? $this->_var['MAIL'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="user_aprob"><?php echo isset($this->_var['L_APROB']) ? $this->_var['L_APROB'] : ''; ?></label></dt>
						<dd><label>
							<select id="user_aprob" name="user_aprob">					
								<option value="0" <?php echo isset($this->_var['SELECT_UNAPROB']) ? $this->_var['SELECT_UNAPROB'] : ''; ?>><?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></option>
								<option value="1" <?php echo isset($this->_var['SELECT_APROB']) ? $this->_var['SELECT_APROB'] : ''; ?>><?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></option>
							</select>
						</label></dd>
					</dl>	
					<dl>
						<dt><label for="level"><?php echo isset($this->_var['L_RANK']) ? $this->_var['L_RANK'] : ''; ?></label></dt>
						<dd><label>
							<select id="level" name="level">					
								<?php if( !isset($_tmpb_members_management['select_rank']) || !is_array($_tmpb_members_management['select_rank']) ) $_tmpb_members_management['select_rank'] = array();
foreach($_tmpb_members_management['select_rank'] as $select_rank_key => $select_rank_value) {
$_tmpb_select_rank = &$_tmpb_members_management['select_rank'][$select_rank_key]; ?>	
									<?php echo isset($_tmpb_select_rank['RANK']) ? $_tmpb_select_rank['RANK'] : ''; ?>
								<?php } ?>						
							</select>
						</label></dd>
					</dl>	
					<dl>
						<dt><label for="user_group"><?php echo isset($this->_var['L_GROUP']) ? $this->_var['L_GROUP'] : ''; ?></label></dt>
						<dd><label>
							<select id="user_group" name="user_groups[]" size="6" multiple="multiple">
								<?php if( !isset($_tmpb_members_management['select_group']) || !is_array($_tmpb_members_management['select_group']) ) $_tmpb_members_management['select_group'] = array();
foreach($_tmpb_members_management['select_group'] as $select_group_key => $select_group_value) {
$_tmpb_select_group = &$_tmpb_members_management['select_group'][$select_group_key]; ?>	
									<?php echo isset($_tmpb_select_group['GROUP']) ? $_tmpb_select_group['GROUP'] : ''; ?>
								<?php } ?>						
							</select>
							<br />
							<a href="javascript:check_select_multiple('g', true);"><?php echo isset($this->_var['L_SELECT_ALL']) ? $this->_var['L_SELECT_ALL'] : ''; ?></a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('g', false);"><?php echo isset($this->_var['L_SELECT_NONE']) ? $this->_var['L_SELECT_NONE'] : ''; ?></a>
						</label></dd>
					</dl>	
					<dl>
						<dt><label for="user_lang"><?php echo isset($this->_var['L_LANG_CHOOSE']) ? $this->_var['L_LANG_CHOOSE'] : ''; ?></label></dt>
						<dd><label>
							<select name="user_lang" id="user_lang" onchange="change_img_lang('img_lang', this.options[this.selectedIndex].value)">						
								<?php if( !isset($_tmpb_members_management['select_lang']) || !is_array($_tmpb_members_management['select_lang']) ) $_tmpb_members_management['select_lang'] = array();
foreach($_tmpb_members_management['select_lang'] as $select_lang_key => $select_lang_value) {
$_tmpb_select_lang = &$_tmpb_members_management['select_lang'][$select_lang_key]; ?>						
								<?php echo isset($_tmpb_select_lang['LANG']) ? $_tmpb_select_lang['LANG'] : ''; ?>						
								<?php } ?>						
							</select> <img id="img_lang" src="<?php echo isset($this->_var['IMG_LANG_IDENTIFIER']) ? $this->_var['IMG_LANG_IDENTIFIER'] : ''; ?>" alt="" class="valign_middle" />
						</label></dd>
					</dl>	
				</fieldset>
				
				<fieldset>
				<legend><?php echo isset($this->_var['L_OPTIONS']) ? $this->_var['L_OPTIONS'] : ''; ?></legend>
					<dl>
						<dt><label for="user_theme"><?php echo isset($this->_var['L_THEME_CHOOSE']) ? $this->_var['L_THEME_CHOOSE'] : ''; ?></label></dt>
						<dd><label>
							<select name="user_theme" id="user_theme" onChange="change_img_theme('img_theme', this.options[selectedIndex].value)">						
								<?php if( !isset($_tmpb_members_management['select_theme']) || !is_array($_tmpb_members_management['select_theme']) ) $_tmpb_members_management['select_theme'] = array();
foreach($_tmpb_members_management['select_theme'] as $select_theme_key => $select_theme_value) {
$_tmpb_select_theme = &$_tmpb_members_management['select_theme'][$select_theme_key]; ?>						
								<?php echo isset($_tmpb_select_theme['THEME']) ? $_tmpb_select_theme['THEME'] : ''; ?>						
								<?php } ?>						
							</select>
							<img id="img_theme" src="../templates/<?php echo isset($this->_var['USER_THEME']) ? $this->_var['USER_THEME'] : ''; ?>/images/theme.jpg" alt="" style="vertical-align:top" />			
						</label></dd>
					</dl>
					<dl>
						<dt><label for="user_editor">* <?php echo isset($this->_var['L_EDITOR_CHOOSE']) ? $this->_var['L_EDITOR_CHOOSE'] : ''; ?></label></dt>
						<dd>
							<label>
								<select name="user_editor" id="user_editor">
									<?php if( !isset($_tmpb_members_management['select_editor']) || !is_array($_tmpb_members_management['select_editor']) ) $_tmpb_members_management['select_editor'] = array();
foreach($_tmpb_members_management['select_editor'] as $select_editor_key => $select_editor_value) {
$_tmpb_select_editor = &$_tmpb_members_management['select_editor'][$select_editor_key]; ?>
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
									<?php if( !isset($_tmpb_members_management['select_timezone']) || !is_array($_tmpb_members_management['select_timezone']) ) $_tmpb_members_management['select_timezone'] = array();
foreach($_tmpb_members_management['select_timezone'] as $select_timezone_key => $select_timezone_value) {
$_tmpb_select_timezone = &$_tmpb_members_management['select_timezone'][$select_timezone_key]; ?>
									<?php echo isset($_tmpb_select_timezone['SELECT_TIMEZONE']) ? $_tmpb_select_timezone['SELECT_TIMEZONE'] : ''; ?>
									<?php } ?>						
								</select>
							</label>
						</dd>			
					</dl>	
					<dl>
						<dt><label for="user_show_mail"><?php echo isset($this->_var['L_HIDE_MAIL']) ? $this->_var['L_HIDE_MAIL'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_HIDE_MAIL_EXPLAIN']) ? $this->_var['L_HIDE_MAIL_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="checkbox" class="text" name="user_show_mail" id="user_show_mail" <?php echo isset($this->_var['SHOW_MAIL_CHECKED']) ? $this->_var['SHOW_MAIL_CHECKED'] : ''; ?> /></label></dd>
					</dl>			
				</fieldset>	
				
				<fieldset>
					<legend><?php echo isset($this->_var['L_SANCTION']) ? $this->_var['L_SANCTION'] : ''; ?></legend>				
					<dl>
						<dt><label for="delete"><?php echo isset($this->_var['L_CONFIRM_DEL_MEMBER']) ? $this->_var['L_CONFIRM_DEL_MEMBER'] : ''; ?></label></dt>
						<dd><label><input type="checkbox" name="delete" id="delete" value="1" /> </label></dd>
					</dl>
					<dl>
						<dt><label for="user_ban"><?php echo isset($this->_var['L_BAN']) ? $this->_var['L_BAN'] : ''; ?></label></dt>
						<dd><label>
							<select name="user_ban" id="user_ban">					
								<?php if( !isset($_tmpb_members_management['select_ban']) || !is_array($_tmpb_members_management['select_ban']) ) $_tmpb_members_management['select_ban'] = array();
foreach($_tmpb_members_management['select_ban'] as $select_ban_key => $select_ban_value) {
$_tmpb_select_ban = &$_tmpb_members_management['select_ban'][$select_ban_key]; ?>	
								<?php echo isset($_tmpb_select_ban['TIME']) ? $_tmpb_select_ban['TIME'] : ''; ?>
								<?php } ?>						
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="user_readonly"><?php echo isset($this->_var['L_READONLY']) ? $this->_var['L_READONLY'] : ''; ?></label></dt>
						<dd><label>
							<select name="user_readonly" id="user_readonly">					
								<?php if( !isset($_tmpb_members_management['select_readonly']) || !is_array($_tmpb_members_management['select_readonly']) ) $_tmpb_members_management['select_readonly'] = array();
foreach($_tmpb_members_management['select_readonly'] as $select_readonly_key => $select_readonly_value) {
$_tmpb_select_readonly = &$_tmpb_members_management['select_readonly'][$select_readonly_key]; ?>	
								<?php echo isset($_tmpb_select_readonly['TIME']) ? $_tmpb_select_readonly['TIME'] : ''; ?>
								<?php } ?>						
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="user_warning"><?php echo isset($this->_var['L_WARNING']) ? $this->_var['L_WARNING'] : ''; ?></label></dt>
						<dd><label>
							<select name="user_warning" id="user_warning">					
								<?php if( !isset($_tmpb_members_management['select_warning']) || !is_array($_tmpb_members_management['select_warning']) ) $_tmpb_members_management['select_warning'] = array();
foreach($_tmpb_members_management['select_warning'] as $select_warning_key => $select_warning_value) {
$_tmpb_select_warning = &$_tmpb_members_management['select_warning'][$select_warning_key]; ?>	
								<?php echo isset($_tmpb_select_warning['LEVEL']) ? $_tmpb_select_warning['LEVEL'] : ''; ?>
								<?php } ?>						
							</select>
						</label></dd>
					</dl>
				</fieldset>	
					
				<fieldset>
					<legend><?php echo isset($this->_var['L_INFO']) ? $this->_var['L_INFO'] : ''; ?></legend>	
					<dl>
						<dt><label for="user_web"><?php echo isset($this->_var['L_WEBSITE']) ? $this->_var['L_WEBSITE'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_WEBSITE_EXPLAIN']) ? $this->_var['L_WEBSITE_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" maxlength="70" size="40" name="user_web" id="user_web" value="<?php echo isset($this->_var['WEB']) ? $this->_var['WEB'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="user_local"><?php echo isset($this->_var['L_LOCALISATION']) ? $this->_var['L_LOCALISATION'] : ''; ?></label></dt>
						<dd><label><input type="text" maxlength=40" size="40" name="user_local" id="user_local" value="<?php echo isset($this->_var['LOCAL']) ? $this->_var['LOCAL'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="user_occupation"><?php echo isset($this->_var['L_JOB']) ? $this->_var['L_JOB'] : ''; ?></label></dt>
						<dd><label><input type="text" maxlength=40" size="40" name="user_occupation" id="user_occupation" value="<?php echo isset($this->_var['OCCUPATION']) ? $this->_var['OCCUPATION'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="user_hobbies"><?php echo isset($this->_var['L_HOBBIES']) ? $this->_var['L_HOBBIES'] : ''; ?></label></dt>
						<dd><label><input type="text" maxlength=40" size="40" name="user_hobbies" id="user_hobbies" value="<?php echo isset($this->_var['HOBBIES']) ? $this->_var['HOBBIES'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="user_sex"><?php echo isset($this->_var['L_SEX']) ? $this->_var['L_SEX'] : ''; ?></label></dt>
						<dd><label>
							<select name="user_sex" id="user_sex" onChange="img_change_sex(this.options[selectedIndex].value)">
								<?php if( !isset($_tmpb_members_management['select_sex']) || !is_array($_tmpb_members_management['select_sex']) ) $_tmpb_members_management['select_sex'] = array();
foreach($_tmpb_members_management['select_sex'] as $select_sex_key => $select_sex_value) {
$_tmpb_select_sex = &$_tmpb_members_management['select_sex'][$select_sex_key]; ?>						
								<?php echo isset($_tmpb_select_sex['SEX']) ? $_tmpb_select_sex['SEX'] : ''; ?>						
								<?php } ?>
							</select>
							<span id="img_sex"><?php echo isset($this->_var['IMG_SEX']) ? $this->_var['IMG_SEX'] : ''; ?></span>
						</label></dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="user_born"><?php echo isset($this->_var['L_DATE_BIRTH']) ? $this->_var['L_DATE_BIRTH'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_VALID']) ? $this->_var['L_VALID'] : ''; ?></span></dt>
						<dd><label>
							<input size="10" maxlength="10" type="text" class="text" id="user_born" name="user_born" value="<?php echo isset($this->_var['BORN']) ? $this->_var['BORN'] : ''; ?>" /> 

							<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
								<div id="calendar" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
								</div>
							</div>
							<a onClick="xmlhttprequest_calendar('calendar', '?input_field=user_born&amp;field=calendar&amp;lyear=1&amp;d=<?php echo isset($this->_var['BORN_DAY']) ? $this->_var['BORN_DAY'] : ''; ?>&amp;m=<?php echo isset($this->_var['BORN_MONTH']) ? $this->_var['BORN_MONTH'] : ''; ?>&amp;y=<?php echo isset($this->_var['BORN_YEAR']) ? $this->_var['BORN_YEAR'] : ''; ?>');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="user_sign"><?php echo isset($this->_var['L_MEMBER_SIGN']) ? $this->_var['L_MEMBER_SIGN'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_MEMBER_SIGN_EXPLAIN']) ? $this->_var['L_MEMBER_SIGN_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><textarea type="text" class="post" rows="4" cols="27" name="user_sign" id="user_sign"><?php echo isset($this->_var['SIGN']) ? $this->_var['SIGN'] : ''; ?></textarea> </label></dd>
					</dl>
					<dl>
						<dt><label for="user_desc"><?php echo isset($this->_var['L_MEMBER_BIOGRAPHY']) ? $this->_var['L_MEMBER_BIOGRAPHY'] : ''; ?></label></dt>
						<dd><label><textarea type="text" class="post" rows="4" cols="27" name="user_desc" id="user_desc"><?php echo isset($this->_var['BIOGRAPHY']) ? $this->_var['BIOGRAPHY'] : ''; ?></textarea> </label></dd>
					</dl>
				</fieldset>	
					
				<fieldset>
					<legend><?php echo isset($this->_var['L_CONTACT']) ? $this->_var['L_CONTACT'] : ''; ?></legend>	
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
					<legend><?php echo isset($this->_var['L_AVATAR_GESTION']) ? $this->_var['L_AVATAR_GESTION'] : ''; ?></legend>	
					<dl>
						<dt><label><?php echo isset($this->_var['L_CURRENT_AVATAR']) ? $this->_var['L_CURRENT_AVATAR'] : ''; ?></label></dt>
						<dd><label><?php echo isset($this->_var['USER_AVATAR']) ? $this->_var['USER_AVATAR'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label><?php echo isset($this->_var['L_UPLOAD_AVATAR']) ? $this->_var['L_UPLOAD_AVATAR'] : ''; ?></label></dt>
						<dd><label>
							<?php echo isset($this->_var['L_WEIGHT_MAX']) ? $this->_var['L_WEIGHT_MAX'] : ''; ?>: <?php echo isset($this->_var['WEIGHT_MAX']) ? $this->_var['WEIGHT_MAX'] : ''; ?> ko
							<br />
							<?php echo isset($this->_var['L_HEIGHT_MAX']) ? $this->_var['L_HEIGHT_MAX'] : ''; ?>: <?php echo isset($this->_var['HEIGHT_MAX']) ? $this->_var['HEIGHT_MAX'] : ''; ?> px
							<br />
							<?php echo isset($this->_var['L_WIDTH_MAX']) ? $this->_var['L_WIDTH_MAX'] : ''; ?>: <?php echo isset($this->_var['WIDTH_MAX']) ? $this->_var['WIDTH_MAX'] : ''; ?> px
						</label></dd>
					</dl>
					<dl>
						<dt><label for="avatars"><?php echo isset($this->_var['L_UPLOAD_AVATAR']) ? $this->_var['L_UPLOAD_AVATAR'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_UPLOAD_AVATAR_WHERE']) ? $this->_var['L_UPLOAD_AVATAR_WHERE'] : ''; ?></span></dt>
						<dd><label>
							<input type="file" name="avatars" id="avatars" size="30" class="submit" />					
							<input type="hidden" name="max_file_size" value="2000000" />
						</label></dd>
					</dl>
					<dl>
						<dt><label for="avatar"><?php echo isset($this->_var['L_AVATAR_LINK']) ? $this->_var['L_AVATAR_LINK'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_AVATAR_LINK_WHERE']) ? $this->_var['L_AVATAR_LINK_WHERE'] : ''; ?></span></dt>
						<dd><label><input type="text" maxlength="40" size="40" name="avatar" id="avatar" class="text" /></label></dd>
					</dl>	
					<dl>
						<dt><label for="delete_avatar"><?php echo isset($this->_var['L_AVATAR_DEL']) ? $this->_var['L_AVATAR_DEL'] : ''; ?></label></dt>
						<dd><label><input type="checkbox" class="text" name="delete_avatar" id="delete_avatar" /></label></dd>
					</dl>
				</fieldset>
				
				<?php if( !isset($_tmpb_members_management['miscellaneous']) || !is_array($_tmpb_members_management['miscellaneous']) ) $_tmpb_members_management['miscellaneous'] = array();
foreach($_tmpb_members_management['miscellaneous'] as $miscellaneous_key => $miscellaneous_value) {
$_tmpb_miscellaneous = &$_tmpb_members_management['miscellaneous'][$miscellaneous_key]; ?>
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
					<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
					<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
					<input type="hidden" name="id" value="<?php echo isset($this->_var['IDMBR']) ? $this->_var['IDMBR'] : ''; ?>" />
					&nbsp;&nbsp; 
					<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
				</fieldset>
			</form>

			<?php } ?>
		</div>
		