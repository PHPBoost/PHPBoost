		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_CONTENT_CONFIG}</li>
				<li>
					<a href="admin_content_config.php"><img src="../templates/{THEME}/images/admin/content.png" alt="" /></a>
					<br />
					<a href="admin_content_config.php" class="quick_link">{L_CONTENT_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<form action="admin_content_config.php" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend>{L_LANGAGE_CONFIG}</legend>
					<dl> 
						<dt><label for="com_auth">* {L_DEFAULT_LANGAGE}</label></dt>
						<dd>
							<select name="com_auth" id="com_auth">
								<option name="bbcode">BBCode</option>
								<option name="tinymce">TinyMCE</option>
							</select>
						</dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="submit" value="{L_UPDATE}" class="submit" />
					<input type="reset" value="{L_RESET}" class="reset" />					
				</fieldset>	
			</form>
		</div>
		