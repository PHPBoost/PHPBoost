		<link href="{PICTURES_DATA_PATH}/forum.css" rel="stylesheet" type="text/css" media="screen, handheld">
		<script type="text/javascript">
		<!--
			function change_type(value)
			{			
				if( value == 3 ) //Lien
				{					
					document.getElementById('forum_category').style.display = 'block';
					document.getElementById('forum_url').style.display = 'block';
					document.getElementById('forum_status').style.display = 'none';
					document.getElementById('write_auth').style.display = 'none';
					document.getElementById('edit_auth').style.display = 'none';
				}
				else if( value == 1 ) //Cat�gorie
				{
					document.getElementById('forum_category').style.display = 'none';
					document.getElementById('forum_url').style.display = 'none';
					document.getElementById('forum_status').style.display = 'block';
					document.getElementById('write_auth').style.display = 'none';
					document.getElementById('edit_auth').style.display = 'none';
				}
				else //Forum
				{					
					document.getElementById('forum_category').style.display = 'block';
					document.getElementById('forum_url').style.display = 'none';
					document.getElementById('forum_status').style.display = 'block';
					document.getElementById('write_auth').style.display = 'block';
					document.getElementById('edit_auth').style.display = 'block';
				}
			}
		-->
		</script>
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_FORUM_MANAGEMENT}</li>
				<li>
					<a href="admin_forum.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum.php" class="quick_link">{L_CAT_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_forum_add.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_add.php" class="quick_link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_forum_config.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_config.php" class="quick_link">{L_FORUM_CONFIG}</a>
				</li>
				<li>
					<a href="admin_forum_groups.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_groups.php" class="quick_link">{L_FORUM_GROUPS}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
					
			# INCLUDE message_helper #
				
			<form action="admin_forum_add.php?token={TOKEN}" method="post" onsubmit="return check_form_list();" class="fieldset_content">
				<fieldset>
					<legend>{L_ADD_CAT}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="type">* {L_TYPE}</label></dt>
						<dd><label>
							<select name="type" id="type" onchange="change_type(this.options[this.selectedIndex].value)">
								<option value="1">{L_CATEGORY}</option>
								<option value="2">{L_FORUM}</option>
								<option value="3">{L_LINK}</option>
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="name">* {L_NAME}</label></dt>
						<dd><label><input type="text" maxlength="100" size="35" id="name" name="name" class="text" /></label></dd>
					</dl>
					<dl id="forum_category">
						<dt><label for="category">* {L_PARENT_CATEGORY}</label></dt>
						<dd><label>
							<select name="category" id="category">
								{CATEGORIES}
							</select>
						</label></dd>
					</dl>
					<dl id="forum_url">
						<dt><label for="desc">* {L_URL}</label><br /><span>{L_URL_EXPLAIN}</span></dt>
						<dd><label><input type="text" maxlength="150" size="55" name="url" id="url" value="http://www.phpboost.com" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="desc">{L_DESC}</label></dt>
						<dd><label><input type="text" maxlength="150" size="55" name="desc" id="desc" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="aprob">{L_APROB}</label></dt>
						<dd>
							<label><input type="radio" name="aprob" id="aprob" checked="checked" value="1" /> {L_YES}</label>
							<label><input type="radio" name="aprob" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl id="forum_status">
						<dt><label for="status">{L_STATUS}</label></dt>
						<dd>
							<label><input type="radio" name="status" id="status" checked="checked" value="1" /> {L_UNLOCK}</label>
							<label><input type="radio" name="status" value="0" /> {L_LOCK}</label>
						</dd>
					</dl>
					<dl>
						<dt><label>{L_AUTH_READ}</label></dt>
						<dd>{AUTH_READ}</dd>
					</dl>
					<dl id="write_auth">
						<dt><label>{L_AUTH_WRITE}</label></dt>
						<dd>{AUTH_WRITE}</dd>
					</dl>
					<dl id="edit_auth">
						<dt><label>{L_AUTH_EDIT}</label></dt>
						<dd>{AUTH_EDIT}</dd>
					</dl>
				</fieldset>
								
				<fieldset class="fieldset_submit">
				<legend>{L_ADD}</legend>
					<input type="submit" name="add" value="{L_ADD}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>
			<script type="text/javascript">
			<!--
				change_type(1);
			-->
			</script>
		</div>
			