		<script type="text/javascript">
		<!--
		function check_form_conf(){
			if(document.getElementById('mail').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_VALID_MAIL']) ? $this->_var['L_REQUIRE_VALID_MAIL'] : ''; ?>");
				return false;
		    }
			
			return true;
		}

		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_CONFIG']) ? $this->_var['L_CONFIG'] : ''; ?></li>
				<li>
					<a href="admin_config.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/configuration.png" alt="" /></a>
					<br />
					<a href="admin_config.php" class="quick_link"><?php echo isset($this->_var['L_CONFIG_MAIN']) ? $this->_var['L_CONFIG_MAIN'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_config.php?adv=1"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/configuration.png" alt="" /></a>
					<br />
					<a href="admin_config.php?adv=1" class="quick_link"><?php echo isset($this->_var['L_CONFIG_ADVANCED']) ? $this->_var['L_CONFIG_ADVANCED'] : ''; ?></a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">

			<?php if( !isset($this->_block['error_handler']) || !is_array($this->_block['error_handler']) ) $this->_block['error_handler'] = array();
foreach($this->_block['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$this->_block['error_handler'][$error_handler_key]; ?>
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
					<br />	
				</div>	
			</div>
			<?php } ?>
				
			<script type="text/javascript">
			<!--	
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
				
			<form action="admin_config.php" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset> 
					<legend><?php echo isset($this->_var['L_CONFIG_MAIN']) ? $this->_var['L_CONFIG_MAIN'] : ''; ?></legend>
					<dl>
						<dt><label for="site_name"><?php echo isset($this->_var['L_SITE_NAME']) ? $this->_var['L_SITE_NAME'] : ''; ?></label></dt>
						<dd><label><input type="text" size="40" maxlength="100" id="site_name" name="site_name" value="<?php echo isset($this->_var['SITE_NAME']) ? $this->_var['SITE_NAME'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="site_desc"><?php echo isset($this->_var['L_SITE_DESC']) ? $this->_var['L_SITE_DESC'] : ''; ?></label></dt>
						<dd><label><textarea type="text" class="post" rows="3" cols="37" name="site_desc" id="site_desc"><?php echo isset($this->_var['SITE_DESCRIPTION']) ? $this->_var['SITE_DESCRIPTION'] : ''; ?></textarea></label></dd>
					</dl>
					<dl>
						<dt><label for="site_keyword"><?php echo isset($this->_var['L_SITE_KEYWORDS']) ? $this->_var['L_SITE_KEYWORDS'] : ''; ?></label></dt>
						<dd><label><textarea type="text" class="post" rows="3" cols="37" name="site_keyword" id="site_keyword"><?php echo isset($this->_var['SITE_KEYWORD']) ? $this->_var['SITE_KEYWORD'] : ''; ?></textarea></label></dd>
					</dl> 
					<dl>
						<dt><label for="site_lang">* <?php echo isset($this->_var['L_DEFAULT_LANGUAGES']) ? $this->_var['L_DEFAULT_LANGUAGES'] : ''; ?></label></dt>
						<dd>
							<label><select name="lang" id="site_lang" onchange="change_img_lang('img_lang', this.options[this.selectedIndex].value)">				
							<?php if( !isset($this->_block['select_lang']) || !is_array($this->_block['select_lang']) ) $this->_block['select_lang'] = array();
foreach($this->_block['select_lang'] as $select_lang_key => $select_lang_value) {
$_tmpb_select_lang = &$this->_block['select_lang'][$select_lang_key]; ?>				
								<?php echo isset($_tmpb_select_lang['LANG']) ? $_tmpb_select_lang['LANG'] : ''; ?>				
							<?php } ?>				
							</select> <img id="img_lang" src="<?php echo isset($this->_var['IMG_LANG_IDENTIFIER']) ? $this->_var['IMG_LANG_IDENTIFIER'] : ''; ?>" alt="" class="valign_middle" /></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="default_theme">* <?php echo isset($this->_var['L_DEFAULT_THEME']) ? $this->_var['L_DEFAULT_THEME'] : ''; ?></label></dt>
						<dd><label>
							<select name="theme" id="default_theme" onChange="change_img_theme('img_theme', this.options[selectedIndex].value)">						
							<?php if( !isset($this->_block['select']) || !is_array($this->_block['select']) ) $this->_block['select'] = array();
foreach($this->_block['select'] as $select_key => $select_value) {
$_tmpb_select = &$this->_block['select'][$select_key]; ?>				
								<?php echo isset($_tmpb_select['THEME']) ? $_tmpb_select['THEME'] : ''; ?>				
							<?php } ?>				
							</select>
							<img id="img_theme" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/theme.jpg" alt="" style="vertical-align:top" />
						</label></dd>
					</dl>
					<dl>
						<dt><label for="default_editor">* <?php echo isset($this->_var['L_DEFAULT_EDITOR']) ? $this->_var['L_DEFAULT_EDITOR'] : ''; ?></label></dt>
						<dd><label>
							<select name="editor" id="default_editor">						
							<?php if( !isset($this->_block['select_editor']) || !is_array($this->_block['select_editor']) ) $this->_block['select_editor'] = array();
foreach($this->_block['select_editor'] as $select_editor_key => $select_editor_value) {
$_tmpb_select_editor = &$this->_block['select_editor'][$select_editor_key]; ?>				
								<?php echo isset($_tmpb_select_editor['EDITOR']) ? $_tmpb_select_editor['EDITOR'] : ''; ?>				
							<?php } ?>				
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="start_page">* <?php echo isset($this->_var['L_START_PAGE']) ? $this->_var['L_START_PAGE'] : ''; ?></label></dt>
						<dd><label>
							<select name="start_page" id="start_page">		
								<?php echo isset($this->_var['SELECT_PAGE']) ? $this->_var['SELECT_PAGE'] : ''; ?>			
							</select> 
						</label>
						<label><?php echo isset($this->_var['L_OTHER']) ? $this->_var['L_OTHER'] : ''; ?> <input type="text" maxlength="255" size="20" id="start_page2" name="start_page2" class="text" value="<?php echo isset($this->_var['START_PAGE']) ? $this->_var['START_PAGE'] : ''; ?>" /></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="count"><?php echo isset($this->_var['L_COMPT']) ? $this->_var['L_COMPT'] : ''; ?></label></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['COMPTEUR_ENABLED']) ? $this->_var['COMPTEUR_ENABLED'] : ''; ?> name="compteur" id="count" value="1" /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?></label>
							&nbsp;&nbsp;
							<label><input type="radio" <?php echo isset($this->_var['COMPTEUR_DISABLED']) ? $this->_var['COMPTEUR_DISABLED'] : ''; ?> name="compteur" value="0" /> <?php echo isset($this->_var['L_UNACTIVE']) ? $this->_var['L_UNACTIVE'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="bench"><?php echo isset($this->_var['L_BENCH']) ? $this->_var['L_BENCH'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_BENCH_EXPLAIN']) ? $this->_var['L_BENCH_EXPLAIN'] : ''; ?></span></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['BENCH_ENABLED']) ? $this->_var['BENCH_ENABLED'] : ''; ?> name="bench" id="bench" value="1" /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?></label>
							&nbsp;&nbsp;
							<label><input type="radio" <?php echo isset($this->_var['BENCH_DISABLED']) ? $this->_var['BENCH_DISABLED'] : ''; ?> name="bench" value="0" /> <?php echo isset($this->_var['L_UNACTIVE']) ? $this->_var['L_UNACTIVE'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="theme_author"><?php echo isset($this->_var['L_THEME_AUTHOR']) ? $this->_var['L_THEME_AUTHOR'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_THEME_AUTHOR_EXPLAIN']) ? $this->_var['L_THEME_AUTHOR_EXPLAIN'] : ''; ?></span></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['THEME_AUTHOR_ENABLED']) ? $this->_var['THEME_AUTHOR_ENABLED'] : ''; ?> name="theme_author" id="theme_author" value="1" /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?></label>
							&nbsp;&nbsp;
							<label><input type="radio" <?php echo isset($this->_var['THEME_AUTHOR_DISABLED']) ? $this->_var['THEME_AUTHOR_DISABLED'] : ''; ?> name="theme_author" value="0" /> <?php echo isset($this->_var['L_UNACTIVE']) ? $this->_var['L_UNACTIVE'] : ''; ?></label>
						</dd>
					</dl>
				</fieldset> 
				<fieldset>  
					<legend><?php echo isset($this->_var['L_POST_MANAGEMENT']) ? $this->_var['L_POST_MANAGEMENT'] : ''; ?></legend> 
					<dl>
						<dt><label for="pm_max"><?php echo isset($this->_var['L_PM_MAX']) ? $this->_var['L_PM_MAX'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_PM_MAX_EXPLAIN']) ? $this->_var['L_PM_MAX_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="2" name="pm_max" id="pm_max" value="<?php echo isset($this->_var['PM_MAX']) ? $this->_var['PM_MAX'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="anti_flood"><?php echo isset($this->_var['L_ANTI_FLOOD']) ? $this->_var['L_ANTI_FLOOD'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_ANTI_FLOOD_EXPLAIN']) ? $this->_var['L_ANTI_FLOOD_EXPLAIN'] : ''; ?></span></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['FLOOD_ENABLED']) ? $this->_var['FLOOD_ENABLED'] : ''; ?> name="anti_flood" id="anti_flood" value="1" /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?>&nbsp;&nbsp;</label>
							<label><input type="radio" <?php echo isset($this->_var['FLOOD_DISABLED']) ? $this->_var['FLOOD_DISABLED'] : ''; ?> name="anti_flood" value="0" /> <?php echo isset($this->_var['L_UNACTIVE']) ? $this->_var['L_UNACTIVE'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="delay_flood"><?php echo isset($this->_var['L_INT_FLOOD']) ? $this->_var['L_INT_FLOOD'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_INT_FLOOD_EXPLAIN']) ? $this->_var['L_INT_FLOOD_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="3" maxlength="9" name="delay_flood" id="delay_flood" value="<?php echo isset($this->_var['DELAY_FLOOD']) ? $this->_var['DELAY_FLOOD'] : ''; ?>" class="text" /> <?php echo isset($this->_var['L_SECONDS']) ? $this->_var['L_SECONDS'] : ''; ?></label></dd>
					</dl>
				</fieldset>
				
				<fieldset>  
					<legend>
						<?php echo isset($this->_var['L_EMAIL_MANAGEMENT']) ? $this->_var['L_EMAIL_MANAGEMENT'] : ''; ?>
					</legend>
					<dl>
						<dt><label for="mail">* <?php echo isset($this->_var['L_EMAIL_ADMIN']) ? $this->_var['L_EMAIL_ADMIN'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_EMAIL_ADMIN_EXPLAIN']) ? $this->_var['L_EMAIL_ADMIN_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" maxlength="255" size="40" id="mail" name="mail" value="<?php echo isset($this->_var['MAIL']) ? $this->_var['MAIL'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="activ_mail"><?php echo isset($this->_var['L_EMAIL_ADMIN_STATUS']) ? $this->_var['L_EMAIL_ADMIN_STATUS'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_EMAIL_ADMIN_STATUS_EXPLAIN']) ? $this->_var['L_EMAIL_ADMIN_STATUS_EXPLAIN'] : ''; ?></span></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['MAIL_ENABLED']) ? $this->_var['MAIL_ENABLED'] : ''; ?> name="activ_mail" id="activ_mail" value="1" /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?>&nbsp;&nbsp;</label>
							<label><input type="radio" <?php echo isset($this->_var['MAIL_DISABLED']) ? $this->_var['MAIL_DISABLED'] : ''; ?> name="activ_mail" value="0" /> <?php echo isset($this->_var['L_UNACTIVE']) ? $this->_var['L_UNACTIVE'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="sign"><?php echo isset($this->_var['L_EMAIL_ADMIN_SIGN']) ? $this->_var['L_EMAIL_ADMIN_SIGN'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_EMAIL_ADMIN_SIGN_EXPLAIN']) ? $this->_var['L_EMAIL_ADMIN_SIGN_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><textarea type="text" class="post" rows="3" cols="37" name="sign" id="sign"><?php echo isset($this->_var['SIGN']) ? $this->_var['SIGN'] : ''; ?></textarea></label></dd>
					</dl>
				</fieldset> 

				
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
					<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
				</fieldset>
			</form>
		</div>