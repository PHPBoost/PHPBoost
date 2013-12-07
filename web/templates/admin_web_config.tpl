		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('nbr_web_max').value == "") {
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

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_WEB_MANAGEMENT}</li>
				<li>
					<a href="admin_web_cat.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php" class="quick_link">{L_WEB_CAT}</a>
				</li>
				<li>
					<a href="admin_web_cat.php#add_cat"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php#add_cat" class="quick_link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_web.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web.php" class="quick_link">{L_WEB_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_web_add.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_add.php" class="quick_link">{L_WEB_ADD}</a>
				</li>
				<li>
					<a href="admin_web_config.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_config.php" class="quick_link">{L_WEB_CONFIG}</a>
				</li>
			</ul>
		</div> 
		
		<div id="admin_contents">							
			<form action="admin_web_config.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend>{L_WEB_CONFIG}</legend>
					<div class="form-element">
						<label for="nbr_web_max">* {L_NBR_WEB_MAX}</label>
						<div class="form-field"><label><input type="text" size="3" maxlength="3" id="nbr_web_max" name="nbr_web_max" value="{NBR_WEB_MAX}"></label></div>
					</div>
					<div class="form-element">
						<label for="nbr_cat_max">* {L_NBR_CAT_MAX}</label>
						<div class="form-field"><label><input type="text" size="3" maxlength="3" id="nbr_cat_max" name="nbr_cat_max" value="{NBR_CAT_MAX}"></label></div>
					</div>
					<div class="form-element">
						<label for="nbr_column">* {L_NBR_COLUMN_MAX}</label>
						<div class="form-field"><label><input type="text" size="3" maxlength="3" id="nbr_column" name="nbr_column" value="{NBR_COLUMN}"></label></div>
					</div>
					<div class="form-element">
						<label for="note_max">* {L_NOTE_MAX}</label>
						<div class="form-field"><label><input type="text" size="2" maxlength="2" id="note_max" name="note_max" value="{NOTE_MAX}"></label></div>
					</div>
				</fieldset>
				<fieldset>
					<legend>
						{L_AUTHORIZATIONS}
					</legend>
					<div class="form-element">
						
							<label>
								{L_READ_AUTHORIZATION}
							</label>
						
						<div class="form-field">
							{READ_AUTHORIZATION}
						</div>
					</div>
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					&nbsp;&nbsp; 
					<button type="reset" value="true">{L_RESET}</button>				
				</fieldset>	
			</form>
		</div>