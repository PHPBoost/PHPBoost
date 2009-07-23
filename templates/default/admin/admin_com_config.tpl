		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('com_auth').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('com_max').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			return true;
		}
		function check_select_multiple(id, status)
		{
			var i;
			for(i = 0; i < {NBR_TAGS}; i++)
			{
				if( document.getElementById(id + i) )
					document.getElementById(id + i).selected = status;
			}
		}
		-->
		</script>

		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_COM}</li>
				<li>
					<a href="admin_com.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/com.png" alt="" /></a>
					<br />
					<a href="admin_com.php" class="quick_link">{L_COM_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_com_config.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/com.png" alt="" /></a>
					<br />
					<a href="admin_com_config.php" class="quick_link">{L_COM_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
		
			<form action="admin_com_config.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend>{L_COM_CONFIG}</legend>
					<dl> 
						<dt><label for="com_auth">* {L_RANK}</label></dt>
						<dd>
							<label>
								<select name="com_auth" id="com_auth">
									{OPTIONS_RANK}
								</select>
							</label>
						</dd>
					</dl>
					<dl> 
						<dt><label for="com_popup">* {L_VIEW_COM}</label></dt>
						<dd>
							<label><input type="radio" {COM_ENABLED} name="com_popup" id="com_popup" value="0" /> {L_CURRENT_PAGE}</label>
							&nbsp;
							<label><input type="radio" {COM_DISABLED} name="com_popup" value="1" /> {L_NEW_PAGE}</label>
						</dd>
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
						<dt><label for="com_max">{L_COM_MAX}</label></dt>
						<dd><label><input type="text" size="3" id="com_max" name="com_max" value="{COM_MAX}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="forbidden_tags">{L_FORBIDDEN_TAGS}</label></dt>
						<dd>
							<label>
								<br />
								<select id="forbidden_tags" name="forbidden_tags[]" size="10" multiple="multiple">
								# START tag #
									# IF tag.C_ENABLED #
									<option id="tag{tag.IDENTIFIER}" selected="selected" value="{tag.CODE}">{tag.TAG_NAME}</option>
									# ELSE #
									<option id="tag{tag.IDENTIFIER}" value="{tag.CODE}">{tag.TAG_NAME}</option>
									# ENDIF #
								# END tags #
								</select>
								<br />
								<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
								<br />
								<a class="small_link" href="javascript:check_select_multiple('tag', true);">{L_SELECT_ALL}</a>/<a class="small_link" href="javascript:check_select_multiple('tag', false);">{L_SELECT_NONE}</a>
							</label>
						</dd>
					</dl> 
					<dl> 
						<dt><label for="max_link">{L_MAX_LINK}</label><br /><span>{L_MAX_LINK_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="2" id="max_link" name="max_link" value="{MAX_LINK}" class="text" /></label></dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					<input type="reset" value="{L_RESET}" class="reset" />					
				</fieldset>	
			</form>
		</div>
		