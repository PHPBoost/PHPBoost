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

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_ARTICLES_MANAGEMENT}</li>
				<li>
					<a href="admin_articles.php"><img src="articles.png" alt="" /></a>
					<br />
					<a href="admin_articles.php" class="quick_link">{L_ARTICLES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_articles_add.php"><img src="articles.png" alt="" /></a>
					<br />
					<a href="admin_articles_add.php" class="quick_link">{L_ARTICLES_ADD}</a>
				</li>
				<li>
					<a href="admin_articles_cat.php"><img src="articles.png" alt="" /></a>
					<br />
					<a href="admin_articles_cat.php" class="quick_link">{L_ARTICLES_CAT}</a>
				</li>
				<li>
					<a href="admin_articles_cat_add.php"><img src="articles.png" alt="" /></a>
					<br />
					<a href="admin_articles_cat_add.php" class="quick_link">{L_ARTICLES_CAT_ADD}</a>
				</li>
				<li>
					<a href="admin_articles_config.php"><img src="articles.png" alt="" /></a>
					<br />
					<a href="admin_articles_config.php" class="quick_link">{L_ARTICLES_CONFIG}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			<form action="admin_articles_config.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
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
		