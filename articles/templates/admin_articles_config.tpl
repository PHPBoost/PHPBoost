		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('nbr_articles_max').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('nbr_cat_max').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('nbr_column').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('note_max').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			return true;
		}
		-->
		</script>

		{ADMIN_MENU}

		<div id="admin_contents">
			<form action="admin_articles_config.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
						
				<fieldset>
					<legend>{L_ARTICLES_CONFIG}</legend>
					<dl>
						<dt><label for="nbr_articles_max">* {L_NBR_ARTICLES_MAX}</label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="nbr_articles_max" name="nbr_articles_max" value="{NBR_ARTICLES_MAX}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_cat_max">* {L_NBR_CAT_MAX}</label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="nbr_cat_max" name="nbr_cat_max" value="{NBR_CAT_MAX}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_column">* {L_NBR_COLUMN_MAX}</label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="nbr_column" name="nbr_column" value="{NBR_COLUMN}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="note_max">* {L_NOTE_MAX}</label></dt>
						<dd><label><input type="text" size="2" maxlength="2" id="note_max" name="note_max" value="{NOTE_MAX}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="note_max">* {L_USE_TAB}</label></dt>
						<dd><label><input type="radio" {TAB} name="tab" value="1" /> {L_ENABLED}</label>
									&nbsp;&nbsp; 
									<label><input type="radio" {NO_TAB} name="tab" value="0" />{L_DISABLED}</label></dd>	
					</dl>
				</fieldset>		
				<fieldset>
				<legend>{L_ARTICLES_MINI_CONFIG}</legend>
					<dl>
						<dt><label for="nbr_articles_max">* {L_NBR_ARTICLES_MINI}</label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="nbr_articles_mini" name="nbr_articles_mini" value="{NBR_ARTICLES_MINI}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_cat_max">* {L_MINI_TYPE}</label></dt>
						<dd><label>	<select name="mini_type" id="mini_type">
									<option value="view"{SELECTED_VIEW}>{L_ARTICLES_MOST_POPULAR}</option>
									<option value="date"{SELECTED_DATE}>{L_ARTICLES_BY_DATE}</option>
									<option value="com"{SELECTED_COM}>{L_ARTICLES_MORE_COM}</option>
									<option value="note"{SELECTED_NOTE}>{L_ARTICLES_BEST_NOTE}</option>
								</select>
							</label>
						</dd>
					</dl>
				</fieldset>							
				<fieldset>
					<legend>{L_GLOBAL_AUTH}</legend>
					<p>{L_GLOBAL_AUTH_EXPLAIN}</p>
					<dl>
						<dt>
							<label for="auth_read">{L_AUTH_READ}</label>
						</dt>
						<dd>
							{AUTH_READ}
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="auth_contribution">{L_AUTH_CONTRIBUTION}</label>
						</dt>
						<dd>
							{AUTH_CONTRIBUTION}
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="auth_write">{L_AUTH_WRITE}</label>
						</dt>
						<dd>
							{AUTH_WRITE}
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="auth_moderation">{L_AUTH_MODERATION}</label>
						</dt>
						<dd>
							{AUTH_MODERATION}
						</dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>

			<form action="admin_articles_config.php?token={TOKEN}" name="form" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_RECOUNT}</legend>
					<img src="../templates/{THEME}/images/admin/maintain.png" alt="" class="valign_middle" />
							{L_EXPLAIN_ARTICLES_COUNT}
							<br /><br />
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_RECOUNT}</legend>
					<input type="submit" name="articles_count" value="{L_RECOUNT}" class="submit" /> 				
				</fieldset>	
			</form>
		</div>
		