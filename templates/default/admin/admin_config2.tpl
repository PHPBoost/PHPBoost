		<script type="text/javascript">
		<!--
		function check_form_conf(){
			if(document.getElementById('server_name').value == "") {
				alert("{L_REQUIRE_SERV}");
				return false;
		    }
			if(document.getElementById('site_name').value == "") {
				alert("{L_REQUIRE_NAME}");
				return false;
		    }
			if(document.getElementById('site_cookie').value == "") {
				alert("{L_REQUIRE_COOKIE_NAME}");
				return false;
		    }
			if(document.getElementById('site_session').value == "") {
				alert("{L_REQUIRE_SESSION_TIME}");
				return false;
		    }
			if(document.getElementById('site_session_invit').value == "") {
				alert("{L_REQUIRE_SESSION_INVIT}");
				return false;
		    }
			return true;
		}
						
		function Confirm_unlock() {
			return confirm("{L_CONFIRM_UNLOCK_ADMIN}");
		}
		-->
		</script>
		
		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_CONFIG}</li>
				<li>
					<a href="admin_config.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/configuration.png" alt="" /></a>
					<br />
					<a href="admin_config.php" class="quick_link">{L_CONFIG_MAIN}</a>
				</li>
				<li>
					<a href="admin_config.php?adv=1"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/configuration.png" alt="" /></a>
					<br />
					<a href="admin_config.php?adv=1" class="quick_link">{L_CONFIG_ADVANCED}</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}{MAIL_CONFIG_URL}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/configuration.png" alt="" /></a>
					<br />
					<a href="{PATH_TO_ROOT}{MAIL_CONFIG_URL}" class="quick_link">{L_MAIL_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">	
	
		# IF C_ERROR_HANDLER #
		<div class="error_handler_position">
			<span id="errorh"></span>
			<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				<br />	
			</div>	
		</div>
		# ENDIF #
		
		<form action="admin_config.php?adv=1" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
			<fieldset> 
				<legend>{L_CONFIG_ADVANCED}</legend>
				<dl>
					<dt><label for="server_name">* {L_SERV_NAME}</label><br /><span>{L_SERV_NAME_EXPLAIN}</span></dt>
					<dd><label><input type="text" maxlength="255" size="40" id="server_name" name="server_name" value="{SERVER_NAME}" class="text" /></label></dd>
				</dl>
				<dl>
					<dt><label for="server_path">* {L_SERV_PATH}</label><br /><span>{L_SERV_PATH_EXPLAIN}</span></dt>
					<dd><label><input type="text" maxlength="255" size="20" id="server_path" name="server_path" value="{SERVER_PATH}" class="text" /></label></dd>
				</dl>
			</fieldset> 
			
			<fieldset>  
				<legend>{L_REWRITE}</legend> 
				<p style="text-align:left;">{L_EXPLAIN_REWRITE}</p>		
				<br />
				<p style="text-align:center;">			
					{L_REWRITE_SERVER}
					{CHECK_REWRITE}
				</p>					
				<p style="text-align:center;">
					<label><input type="radio" name="rewrite_engine" value="1" {CHECKED} /> {L_ACTIV}</label>
					&nbsp;&nbsp; 
					<label><input type="radio" name="rewrite_engine" value="0" {UNCHECKED} /> {L_UNACTIVE}</label>
				</p>
			</fieldset>
			
			<fieldset>
				<legend>
					{L_HTACCESS_MANUAL_CONTENT}
				</legend>
				<dl>
					<dt>
						<label for="htaccess_manual_content">
							{L_HTACCESS_MANUAL_CONTENT}
						</label>
						<br /><span>{L_HTACCESS_MANUAL_CONTENT_EXPLAIN}</span>
					</dt>
					<dd>
						<textarea name="htaccess_manual_content" rows="5" cols="5" style="font-family:'Courier new';">{HTACCESS_MANUAL_CONTENT}</textarea>
					</dd>
				</dl>
			</fieldset>
			
			<fieldset>  
				<legend>{L_USER_CONNEXION}</legend> 
				<dl>
					<dt><label for="site_cookie">* {L_COOKIE_NAME}</label></dt>
					<dd>
						<label><input type="text" size="20" maxlength="150" id="site_cookie" name="site_cookie" value="{SITE_COOKIE}" class="text" /></label>
					</dd>
				</dl>
				<dl>
					<dt><label for="site_session">* {L_SESSION_TIME}</label><br /><span>{L_SESSION_TIME_EXPLAIN}</span></dt>
					<dd><label><input type="text" size="4" id="site_session" name="site_session" value="{SITE_SESSION}" class="text" /> {L_SECONDS}</label></dd>
				</dl>
				<dl>
					<dt><label for="site_session_invit">* {L_SESSION_INVIT}</label><br /><span>{L_SESSION_INVIT_EXPLAIN}</span></dt>
					<dd><label><input type="text" size="4" id="site_session_invit" name="site_session_invit" value="{SITE_SESSION_VISIT}" class="text" /> {L_SECONDS}</label></dd>
				</dl>
			</fieldset>
			
			<fieldset>  
				<legend>{L_MISC}</legend>
				<dl>
					<dt><label for="timezone">{L_TIMEZONE_CHOOSE}</label><br /><span>{L_TIMEZONE_CHOOSE_EXPLAIN}</span></dt>
					<dd>
						<label>
							<select name="timezone" id="timezone">	
								{SELECT_TIMEZONE}						
							</select>
						</label>
					</dd>
				</dl>
				<dl>
					<dt><label for="ob_gzhandler">{L_ACTIV_GZHANDLER}</label><br /><span>{L_ACTIV_GZHANDLER_EXPLAIN}</span></dt>
					<dd>
						<label><input type="radio" {GZHANDLER_ENABLED} name="ob_gzhandler" id="ob_gzhandler" value="1" {GZ_DISABLED} /> {L_ACTIV}</label>
						&nbsp;&nbsp;
						<label><input type="radio" {GZHANDLER_DISABLED} name="ob_gzhandler" value="0" {GZ_DISABLED} /> {L_UNACTIVE}</label>
					</dd>
				</dl>
				<dl>
					<dt><label for="sign">{L_UNLOCK_ADMIN}</label><br /><span>{L_UNLOCK_ADMIN_EXPLAIN}</span></dt>
					<dd><label><a href="admin_config.php?unlock=1" onclick="javascript:return Confirm_unlock();">{L_UNLOCK_LINK}</a></label></dd>
				</dl>
	            <dl>
                        <dt><label for="debug">{L_DEBUG}</label><br /><span>{L_DEBUG_EXPLAIN}</span></dt>
                        <dd>
                            <label><input type="radio" {DEBUG_ENABLED} name="debug" id="debug" value="1" /> {L_ACTIV}</label>
                            &nbsp;&nbsp;
                            <label><input type="radio" {DEBUG_DISABLED} name="debug" value="0" /> {L_UNACTIVE}</label>
                        </dd>
                    </dl>
			</fieldset> 
			
			<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
				<input type="submit" name="advanced" value="{L_UPDATE}" class="submit" />
				&nbsp;&nbsp; 
				<input type="reset" value="{L_RESET}" class="reset" />
				<input type="hidden" name="token" value="{TOKEN}" />
			</fieldset>		
		</form>	
	</div>