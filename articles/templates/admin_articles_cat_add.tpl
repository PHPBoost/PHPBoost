		<script type="text/javascript">
		<!--
		function check_select_multiple(id, status)
		{
			var i;		
			for(i = -1; i <= 2; i++)
			{
				if( document.getElementById(id + 'r' + i) )
					document.getElementById(id + 'r' + i).selected = status;
			}				
			document.getElementById(id + 'r3').selected = true;
			
			for(i = 0; i < {NBR_GROUP}; i++)
			{	
				if( document.getElementById(id + 'g' + i) )
					document.getElementById(id + 'g' + i).selected = status;		
			}	
		}		
		function check_select_multiple_ranks(id, start)
		{
			var i;				
			for(i = start; i <= 2; i++)
			{	
				if( document.getElementById(id + i) )
					document.getElementById(id + i).selected = true;			
			}
		}
		function change_icon(img_path)
		{
			document.getElementById('icon_img').innerHTML = '<img src="' + img_path + '" alt="" class="valign_middle" />';
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
			
			# START error_handler #
				<span id="errorh"></span>
				<div class="{error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
					<br />	
				</div>
				<br />	
			# END error_handler #
				
			<form action="admin_articles_cat_add.php" method="post" onsubmit="return check_form_list();" class="fieldset_content">
				<fieldset>
					<legend>{L_ARTICLES_CAT_ADD}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="category">* {L_PARENT_CATEGORY}</label></dt>
						<dd><label>
							<select name="category" id="category" onchange="change_parent_cat(this.options[this.selectedIndex].value)">
								{CATEGORIES}
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="name">* {L_NAME}</label></dt>
						<dd><label><input type="text" maxlength="100" size="35" id="name" name="name" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="desc">{L_DESC}</label></dt>
						<dd><label><input type="text" maxlength="150" size="35" name="desc" id="desc" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="aprob">* {L_APROB}</label></dt>
						<dd>
							<label><input type="radio" name="aprob" id="aprob" checked="checked" value="1" /> {L_YES}</label>
							&nbsp;
							<label><input type="radio" name="aprob" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="icon">{L_ICON}</label><br /><span>{L_ICON_EXPLAIN}</span></dt>
						<dd>
							<label><select name="icon" id="icon" onchange="change_icon(this.options[this.selectedIndex].value)" onclick="change_icon(this.options[this.selectedIndex].value)">
								{IMG_LIST}
							</select></label>
							<span id="icon_img">{IMG_ICON}</span>
							<br />
							<label><span class="text_small">{L_OR_DIRECT_PATH}</span> <input type="text" class="text" name="icon_path" value="{IMG_PATH}" onblur="if( this.value != '' )change_icon(this.value)" /></label>
						</label></dd>
					</dl>
					<dl>
						<dt><label>{L_AUTH_READ}</label></dt>
						<dd><label>
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{AUTH_READ}
							<br />
							<a href="javascript:check_select_multiple('r', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('r', false);">{L_SELECT_NONE}</a>
						</label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_ADD}</legend>
					<input type="submit" name="add" value="{L_ADD}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>
		