		<script type="text/javascript">
		<!--
		function check_form_conf(){
			if(document.getElementById('server_name').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_SERV']) ? $this->_var['L_REQUIRE_SERV'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('site_name').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_NAME']) ? $this->_var['L_REQUIRE_NAME'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('site_cookie').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_COOKIE_NAME']) ? $this->_var['L_REQUIRE_COOKIE_NAME'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('site_session').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_SESSION_TIME']) ? $this->_var['L_REQUIRE_SESSION_TIME'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('site_session_invit').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_SESSION_INVIT']) ? $this->_var['L_REQUIRE_SESSION_INVIT'] : ''; ?>");
				return false;
		    }
			return true;
		}
						
		function Confirm_unlock() {
			return confirm("<?php echo isset($this->_var['L_CONFIRM_UNLOCK_ADMIN']) ? $this->_var['L_CONFIRM_UNLOCK_ADMIN'] : ''; ?>");
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
		
		<form action="admin_config.php?adv=1" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
			<fieldset> 
				<legend><?php echo isset($this->_var['L_CONFIG_ADVANCED']) ? $this->_var['L_CONFIG_ADVANCED'] : ''; ?></legend>
				<dl>
					<dt><label for="server_name">* <?php echo isset($this->_var['L_SERV_NAME']) ? $this->_var['L_SERV_NAME'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_SERV_NAME_EXPLAIN']) ? $this->_var['L_SERV_NAME_EXPLAIN'] : ''; ?></span></dt>
					<dd><label><input type="text" maxlength="255" size="40" id="server_name" name="server_name" value="<?php echo isset($this->_var['SERVER_NAME']) ? $this->_var['SERVER_NAME'] : ''; ?>" class="text" /></label></dd>
				</dl>
				<dl>
					<dt><label for="server_path">* <?php echo isset($this->_var['L_SERV_PATH']) ? $this->_var['L_SERV_PATH'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_SERV_PATH_EXPLAIN']) ? $this->_var['L_SERV_PATH_EXPLAIN'] : ''; ?></span></dt>
					<dd><label><input type="text" maxlength="255" size="20" id="server_path" name="server_path" value="<?php echo isset($this->_var['SERVER_PATH']) ? $this->_var['SERVER_PATH'] : ''; ?>" class="text" /></label></dd>
				</dl>
			</fieldset> 
			
			<fieldset>  
				<legend><?php echo isset($this->_var['L_REWRITE']) ? $this->_var['L_REWRITE'] : ''; ?></legend> 
				<p style="text-align:left;"><?php echo isset($this->_var['L_EXPLAIN_REWRITE']) ? $this->_var['L_EXPLAIN_REWRITE'] : ''; ?></p>		
				<br />
				<p style="text-align:center;">			
					<?php echo isset($this->_var['L_REWRITE_SERVER']) ? $this->_var['L_REWRITE_SERVER'] : ''; ?>
					<?php echo isset($this->_var['CHECK_REWRITE']) ? $this->_var['CHECK_REWRITE'] : ''; ?>
				</p>					
				<p style="text-align:center;">
					<label><input type="radio" name="rewrite_engine" value="1" <?php echo isset($this->_var['CHECKED']) ? $this->_var['CHECKED'] : ''; ?> /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?></label>
					&nbsp;&nbsp; 
					<label><input type="radio" name="rewrite_engine" value="0" <?php echo isset($this->_var['UNCHECKED']) ? $this->_var['UNCHECKED'] : ''; ?> /> <?php echo isset($this->_var['L_UNACTIVE']) ? $this->_var['L_UNACTIVE'] : ''; ?></label>
				</p>
			</fieldset> 
			
			<fieldset>  
				<legend><?php echo isset($this->_var['L_USER_CONNEXION']) ? $this->_var['L_USER_CONNEXION'] : ''; ?></legend> 
				<dl>
					<dt><label for="site_cookie">* <?php echo isset($this->_var['L_COOKIE_NAME']) ? $this->_var['L_COOKIE_NAME'] : ''; ?></label></dt>
					<dd>
						<label><input type="text" size="20" maxlength="150" id="site_cookie" name="site_cookie" value="<?php echo isset($this->_var['SITE_COOKIE']) ? $this->_var['SITE_COOKIE'] : ''; ?>" class="text" /></label>
					</dd>
				</dl>
				<dl>
					<dt><label for="site_session">* <?php echo isset($this->_var['L_SESSION_TIME']) ? $this->_var['L_SESSION_TIME'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_SESSION_TIME_EXPLAIN']) ? $this->_var['L_SESSION_TIME_EXPLAIN'] : ''; ?></span></dt>
					<dd><label><input type="text" size="4" id="site_session" name="site_session" value="<?php echo isset($this->_var['SITE_SESSION']) ? $this->_var['SITE_SESSION'] : ''; ?>" class="text" /> <?php echo isset($this->_var['L_SECONDS']) ? $this->_var['L_SECONDS'] : ''; ?></label></dd>
				</dl>
				<dl>
					<dt><label for="site_session_invit">* <?php echo isset($this->_var['L_SESSION_INVIT']) ? $this->_var['L_SESSION_INVIT'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_SESSION_INVIT_EXPLAIN']) ? $this->_var['L_SESSION_INVIT_EXPLAIN'] : ''; ?></span></dt>
					<dd><label><input type="text" size="4" id="site_session_invit" name="site_session_invit" value="<?php echo isset($this->_var['SITE_SESSION_VISIT']) ? $this->_var['SITE_SESSION_VISIT'] : ''; ?>" class="text" /> <?php echo isset($this->_var['L_SECONDS']) ? $this->_var['L_SECONDS'] : ''; ?></label></dd>
				</dl>
			</fieldset>
			
			<fieldset>  
				<legend><?php echo isset($this->_var['L_MISC']) ? $this->_var['L_MISC'] : ''; ?></legend>
				<dl>
					<dt><label for="timezone"><?php echo isset($this->_var['L_TIMEZONE_CHOOSE']) ? $this->_var['L_TIMEZONE_CHOOSE'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_TIMEZONE_CHOOSE_EXPLAIN']) ? $this->_var['L_TIMEZONE_CHOOSE_EXPLAIN'] : ''; ?></span></dt>
					<dd>
						<label>
							<select name="timezone" id="timezone">	
								<?php echo isset($this->_var['SELECT_TIMEZONE']) ? $this->_var['SELECT_TIMEZONE'] : ''; ?>						
							</select>
						</label>
					</dd>
				</dl>
				<dl>
					<dt><label for="ob_gzhandler"><?php echo isset($this->_var['L_ACTIV_GZHANDLER']) ? $this->_var['L_ACTIV_GZHANDLER'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_ACTIV_GZHANDLER_EXPLAIN']) ? $this->_var['L_ACTIV_GZHANDLER_EXPLAIN'] : ''; ?></span></dt>
					<dd>
						<label><input type="radio" <?php echo isset($this->_var['GZHANDLER_ENABLED']) ? $this->_var['GZHANDLER_ENABLED'] : ''; ?> name="ob_gzhandler" id="ob_gzhandler" value="1" <?php echo isset($this->_var['GZ_DISABLED']) ? $this->_var['GZ_DISABLED'] : ''; ?> /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?></label>
						&nbsp;&nbsp;
						<label><input type="radio" <?php echo isset($this->_var['GZHANDLER_DISABLED']) ? $this->_var['GZHANDLER_DISABLED'] : ''; ?> name="ob_gzhandler" value="0" <?php echo isset($this->_var['GZ_DISABLED']) ? $this->_var['GZ_DISABLED'] : ''; ?> /> <?php echo isset($this->_var['L_UNACTIVE']) ? $this->_var['L_UNACTIVE'] : ''; ?></label>
					</dd>
				</dl>
				<dl>
					<dt><label for="sign"><?php echo isset($this->_var['L_UNLOCK_ADMIN']) ? $this->_var['L_UNLOCK_ADMIN'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_UNLOCK_ADMIN_EXPLAIN']) ? $this->_var['L_UNLOCK_ADMIN_EXPLAIN'] : ''; ?></span></dt>
					<dd><label><a href="admin_config.php?unlock=1" onClick="javascript:return Confirm_unlock();"><?php echo isset($this->_var['L_UNLOCK_LINK']) ? $this->_var['L_UNLOCK_LINK'] : ''; ?></a></label></dd>
				</dl>
			</fieldset> 
			
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
				<input type="submit" name="advanced" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
				&nbsp;&nbsp; 
				<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
			</fieldset>		
		</form>	
	</div>