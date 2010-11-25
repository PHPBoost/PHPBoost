		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('shoutbox_max_msg').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('shoutbox_auth').value == "") {
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
				<li class="title_menu">{L_SHOUTBOX}</li>
				<li>
					<a href="admin_shoutbox.php"><img src="shoutbox.png" alt="" /></a>
					<br />
					<a href="admin_shoutbox.php" class="quick_link">{L_SHOUTBOX_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
		
			<form action="admin_shoutbox.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend>{L_SHOUTBOX_CONFIG}</legend>
					<dl>
						<dt>
							<label for="shoutbox_auth">{L_AUTH_READ}</label>
						</dt>
						<dd>
							{AUTH_READ}
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="shoutbox_auth">{L_AUTH_WRITE}</label>
						</dt>
						<dd>
							{AUTH_WRITE}
						</dd>
					</dl>
					<hr><br>
					<dl>
						<dt><label for="shoutbox_refresh_delay">* {L_SHOUTBOX_REFRESH_DELAY}</label><br /><span>{L_SHOUTBOX_REFRESH_DELAY_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" id="shoutbox_refresh_delay" name="shoutbox_refresh_delay" value="{SHOUTBOX_REFRESH_DELAY}" class="text" /> {L_MINUTES}</label></dd>
					</dl>
					<dl>
						<dt><label for="shoutbox_max_msg">* {L_SHOUTBOX_MAX_MSG}</label><br /><span>{L_SHOUTBOX_MAX_MSG_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" id="shoutbox_max_msg" name="shoutbox_max_msg" value="{SHOUTBOX_MAX_MSG}" class="text" /></label></dd>
					</dl>					
					<dl>
						<dt><label>* {L_FORBIDDEN_TAGS}</label></dt>
						<dd><label>
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							<select name="shoutbox_forbidden_tags[]" size="10" multiple="multiple">
								# START forbidden_tags #
									{forbidden_tags.TAGS}
								# END forbidden_tags #						
							</select>
							<br />
							<a class="small_link" href="javascript:check_select_multiple('tag', true);">{L_SELECT_ALL}</a>/<a class="small_link" href="javascript:check_select_multiple('tag', false);">{L_SELECT_NONE}</a>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="shoutbox_max_link">* {L_MAX_LINK}</label><br /><span>{L_MAX_LINK_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="2" name="shoutbox_max_link" id="shoutbox_max_link" value="{MAX_LINK}" class="text" /></label></dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />			
				</fieldset>
			</form>
		</div>
		