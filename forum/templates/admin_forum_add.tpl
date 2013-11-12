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
				<li>
					<a href="admin_ranks.php"><img src="templates/images/ranks.png" alt="" /></a>
					<br />
					<a href="admin_ranks.php" class="quick_link">{L_FORUM_RANKS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_ranks_add.php"><img src="templates/images/ranks.png" alt="" /></a>
					<br />
					<a href="admin_ranks_add.php" class="quick_link">{L_FORUM_ADD_RANKS}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
					
			# INCLUDE message_helper #
				
			<form action="admin_forum_add.php?token={TOKEN}" method="post" onsubmit="return check_form_list();" class="fieldset_content">
				<fieldset>
					<legend>{L_ADD_CAT}</legend>
					<p>{L_REQUIRE}</p>
					<div class="form-element">
						<label for="type">* {L_TYPE}</label>
						<div class="form-field"><label>
							<select name="type" id="type" onchange="change_type(this.options[this.selectedIndex].value)">
								<option value="1">{L_CATEGORY}</option>
								<option value="2">{L_FORUM}</option>
								<option value="3">{L_LINK}</option>
							</select>
						</label></div>
					</div>
					<div class="form-element">
						<label for="name">* {L_NAME}</label>
						<div class="form-field"><label><input type="text" maxlength="100" size="35" id="name" name="name"></label></div>
					</div>
					<div class="form-element" id="forum_category">
						<label for="category">* {L_PARENT_CATEGORY}</label>
						<div class="form-field"><label>
							<select name="category" id="category">
								{CATEGORIES}
							</select>
						</label></div>
					</div>
					<div class="form-element" id="forum_url">
						<label for="desc">* {L_URL}</label><br /><span>{L_URL_EXPLAIN}</span>
						<div class="form-field"><label><input type="text" maxlength="150" size="55" name="url" id="url" value="http://www.phpboost.com"></label></div>
					</div>
					<div class="form-element">
						<label for="desc">{L_DESC}</label>
						<div class="form-field"><label><input type="text" maxlength="150" size="55" name="desc" id="desc"></label></div>
					</div>
					<div class="form-element">
						<label for="aprob">{L_APROB}</label>
						<div class="form-field">
							<label><input type="radio" name="aprob" id="aprob" checked="checked" value="1"> {L_YES}</label>
							<label><input type="radio" name="aprob" value="0"> {L_NO}</label>
						</div>
					</div>
					<div class="form-element" id="forum_status">
						<label for="status">{L_STATUS}</label>
						<div class="form-field">
							<label><input type="radio" name="status" id="status" checked="checked" value="1"> {L_UNLOCK}</label>
							<label><input type="radio" name="status" value="0"> {L_LOCK}</label>
						</div>
					</div>
					<div class="form-element">
						<label>{L_AUTH_READ}</label>
						<div class="form-field">{AUTH_READ}</div>
					</div>
					<div class="form-element" id="write_auth">
						<label>{L_AUTH_WRITE}</label>
						<div class="form-field">{AUTH_WRITE}</div>
					</div>
					<div class="form-element" id="edit_auth">
						<label>{L_AUTH_EDIT}</label>
						<div class="form-field">{AUTH_EDIT}</div>
					</div>
				</fieldset>
								
				<fieldset class="fieldset_submit">
				<legend>{L_ADD}</legend>
					<button type="submit" name="add" value="true">{L_ADD}</button>
					&nbsp;&nbsp; 
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
			<script type="text/javascript">
			<!--
				change_type(1);
			-->
			</script>
		</div>
			