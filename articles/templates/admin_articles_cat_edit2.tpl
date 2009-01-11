		<link href="{MODULE_DATA_PATH}/forum.css" rel="stylesheet" type="text/css" media="screen, handheld">
		<script type="text/javascript">
		<!--
			function check_form_list()
			{
				if(document.getElementById('name').value == "") {
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
			
			# IF C_ERROR_HANDLER #
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
				<br />	
			# ENDIF #
				
			<form action="admin_articles_cat.php?token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_EDIT_CAT}</legend>
					<p><strong>{L_ROOT}</strong></p>
					<dl>
						<dt><label>{L_AUTH_READ}</label></dt>
						<dd><label>{AUTH_READ}</label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid_root" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>
		