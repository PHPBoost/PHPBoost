		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('width_max').value == "") {
				alert("{L_REQUIRE_MAX_WIDTH}");
				return false;
			}
			if(document.getElementById('height_max').value == "") {
				alert("{L_REQUIRE_HEIGHT}");
				return false;
			}
			if(document.getElementById('weight_max').value == "") {
				alert("{L_REQUIRE_WEIGHT}");
				return false;
			}
			return true;
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
			<form action="admin_members_config.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend>{L_USERS_CONFIG}</legend>
					<dl>
						<dt><label for="activ_register">{L_ACTIV_REGISTER}</label></dt>
						<dd><label>
							<select name="activ_register" id="activ_register">							
								<option value="1" {ACTIV_REGISTER_ENABLED}>{L_YES}</option>
								<option value="0" {ACTIV_REGISTER_DISABLED}>{L_NO}</option>
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="activ_mbr">{L_ACTIV_MBR}</label></dt>
						<dd><label>
							<select name="activ_mbr" id="activ_mbr">							
								{ACTIV_MODE_OPTION}				
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="delay_unactiv_max">{L_DELAY_UNACTIV_MAX}</label><br /><span>{L_DELAY_UNACTIV_MAX_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="delay_unactiv_max" name="delay_unactiv_max" value="{DELAY_UNACTIV_MAX}" class="text" /> {L_DAYS}</label></dd>
					</dl>
					<dl>
						<dt><label for="verif_code">{L_VERIF_CODE}</label><br /><span>{L_VERIF_CODE_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" {VERIF_CODE_ENABLED} name="verif_code" id="verif_code" value="1" {GD_DISABLED} /> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {VERIF_CODE_DISABLED} name="verif_code" value="0" {GD_DISABLED} /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="verif_code_difficulty">{L_CAPTCHA_DIFFICULTY}</label></dt>
						<dd>
							<label>
								<select name="verif_code_difficulty" id="verif_code_difficulty">
									# START difficulty #
									<option value="{difficulty.VALUE}" {difficulty.SELECTED}>{difficulty.VALUE}</option>
									# END difficulty #
								</select>         
							</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="force_theme">{L_ALLOW_THEME_MBR}</label></dt>
						<dd>
							<label><input type="radio" {ALLOW_THEME_ENABLED} name="force_theme" id="force_theme" value="0" /> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {ALLOW_THEME_DISABLED} name="force_theme" value="1" /> {L_NO}</label>
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_AVATAR_MANAGEMENT}</legend>
					<dl>
						<dt><label for="activ_up_avatar">* {L_ACTIV_UP_AVATAR}</label></dt>
						<dd>
							<label><input type="radio" {AVATAR_UP_ENABLED} name="activ_up_avatar" id="activ_up_avatar" value="1" /> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {AVATAR_UP_DISABLED} name="activ_up_avatar" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="width_max">* {L_WIDTH_MAX_AVATAR}</label><br /><span>{L_WIDTH_MAX_AVATAR_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="width_max" name="width_max" value="{WIDTH_MAX}" class="text" /> {L_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="height_max">* {L_HEIGHT_MAX_AVATAR}</label><br /><span>{L_HEIGHT_MAX_AVATAR_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="height_max" name="height_max" value="{HEIGHT_MAX}" class="text" /> {L_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="weight_max">* {L_WEIGHT_MAX_AVATAR}</label><br /><span>{L_WEIGHT_MAX_AVATAR_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" maxlength="5" id="weight_max" name="weight_max" value="{WEIGHT_MAX}" class="text" /> {L_KB}</label></dd>
					</dl>
					<dl>
						<dt><label for="activ_avatar">{L_ACTIV_DEFAUT_AVATAR}</label><br /><span>{L_ACTIV_DEFAUT_AVATAR_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" {AVATAR_ENABLED} name="activ_avatar" id="activ_avatar" value="1" /> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {AVATAR_DISABLED} name="activ_avatar" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="avatar_url">{L_URL_DEFAUT_AVATAR}</label><br /><span>{L_URL_DEFAUT_AVATAR_EXPLAIN}</span></dt>
						<dd><label>
							<input type="text" maxlength="50" size="25" id="avatar_url" name="avatar_url" value="{AVATAR_URL}" class="text" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{AVATAR_URL}" alt="" style="vertical-align:top" />
						</label></dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_USERS_MSG}</legend>
					<label for="contents">* {L_CONTENTS}</label>
					{KERNEL_EDITOR}
					<textarea rows="20" cols="63" id="contents" name="contents">{CONTENTS}</textarea> 
				</fieldset>
							
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="msg_mbr" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>		
			</form>
		</div>
		