		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('guestbook_auth').value == "") {
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
				<li class="title_menu">{L_GUESTBOOK}</li>
				<li>
					<a href="admin_guestbook.php"><img src="guestbook.png" alt="" /></a>
					<br />
					<a href="admin_guestbook.php" class="quick_link">{L_GUESTBOOK_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<form action="admin_guestbook.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend>{L_GUESTBOOK_CONFIG}</legend>
					<dl>
						<dt><label for="guestbook_auth">* {L_RANK}</label></dt>
						<dd><label>
							<select name="guestbook_auth" id="guestbook_auth">
								# START select_auth #
									{select_auth.RANK}
								# END select_auth #
							</select>
						</label></dd>
					</dl>	
					<dl>
						<dt><label for="contact_verifcode">{L_GUESTBOOK_VERIFCODE}</label><br /><span>{L_GUESTBOOK_VERIFCODE_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" {GUESTBOOK_VERIFCODE_ENABLED} name="guestbook_verifcode" id="guestbook_verifcode" value="1" />	{L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {GUESTBOOK_VERIFCODE_DISABLED} name="guestbook_verifcode" value="0" /> {L_NO}</label>
						</dd>
					</dl>	
					<dl>
						<dt><label for="guestbook_difficulty_verifcode">{L_CAPTCHA_DIFFICULTY}</label></dt>
						<dd>
							<label>
								<select name="guestbook_difficulty_verifcode" id="guestbook_difficulty_verifcode">
									# START difficulty #
									<option value="{difficulty.VALUE}" {difficulty.SELECTED}>{difficulty.VALUE}</option>
									# END difficulty #
								</select>         
							</label>
						</dd>
					</dl>
					<dl>
						<dt><label>* {L_FORBIDDEN_TAGS}</label></dt>
						<dd><label>
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							<select name="guestbook_forbidden_tags[]" id="guestbook_forbidden_tags" size="10" multiple="multiple">
								{TAGS}				
							</select>
							<br />
							<a class="small_link"href="javascript:check_select_multiple('tag', true);">{L_SELECT_ALL}</a>/<a class="small_link"href="javascript:check_select_multiple('tag', false);">{L_SELECT_NONE}</a>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="guestbook_max_link">* {L_MAX_LINK}</label><br /><span>{L_MAX_LINK_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="2" name="guestbook_max_link" id="guestbook_max_link" value="{MAX_LINK}" class="text" /></label></dd>
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
		