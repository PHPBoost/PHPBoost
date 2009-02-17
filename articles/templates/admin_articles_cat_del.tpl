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
			<form method="post" action="admin_articles_cat.php?del={IDCAT}&amp;token={TOKEN}" onsubmit="javascript:return check_form_select();" class="fieldset_content">
				# START articles #
				<fieldset>
					<legend>{articles.L_KEEP}</legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/{THEME}/images/important.png" alt="" style="float:left;padding-right:6px;" /> &nbsp;{articles.L_EXPLAIN_CAT}
						<br />	
					</div>
					<br />	
					<dl>
						<dt><label for="t_to">{articles.L_MOVE_TOPICS}</label></dt>
						<dd><label>
							<select id="t_to" name="t_to">
								{articles.CATEGORIES}
							</select>
						</label></dd>
					</dl>
				</fieldset>			
				# END articles #
				
				# START subcats #
				<fieldset>
					<legend>{subcats.L_KEEP}</legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/{THEME}/images/important.png" alt="" style="float:left;padding-right:6px;" /> &nbsp;{subcats.L_EXPLAIN_CAT}
						<br />	
					</div>
					<br />	
					<dl>
						<dt><label for="f_to">{subcats.L_MOVE_CATEGORIES}</label></dt>
						<dd><label>
							<select id="f_to" name="f_to">
								{subcats.CATEGORIES}
							</select>
						</label></dd>
					</dl>
				</fieldset>			
				# END subcats #
				
				<fieldset>
					<legend>{L_DEL_ALL}</legend>
					<dl>
						<dt><label for="del_conf">{L_DEL_ARTICLES_CONTENTS}</label></dt>
						<dd><label><input type="checkbox" name="del_conf" id="del_conf" /></label></dd>
					</dl>
				</fieldset>	
							
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="submit" name="del_cat" value="{L_SUBMIT}" class="submit" />			
				</fieldset>	
			</form>
		</div>
		