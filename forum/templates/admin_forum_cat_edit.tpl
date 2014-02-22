		<link href="{PICTURES_DATA_PATH}/forum.css" rel="stylesheet" type="text/css" media="screen, handheld">
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
			
			var disabled = {DISABLED};				
			function check_select_multiple_ranks(id, start)
			{
				if( !disabled || id == '1r' )			
				{
					id_select = id.replace(/(.*)r/g, '$1');
					check_select_multiple(id_select, false);
					var i;
					for(i = start; i <= 3; i++)
					{
						if( document.getElementById(id + i) )
							document.getElementById(id + i).selected = true;
					}
				}
			}
			function change_type(value)
			{			
				if( value == 3 ) //Lien
				{					
					document.getElementById('forum_url').style.display = 'block';
					document.getElementById('forum_status').style.display = 'none';
					document.getElementById('write_auth').style.display = 'none';
					document.getElementById('edit_auth').style.display = 'none';
				}
				else if( value == 1 ) //Catégorie
				{
					document.getElementById('forum_url').style.display = 'none';
					document.getElementById('forum_status').style.display = 'block';
					document.getElementById('write_auth').style.display = 'none';
					document.getElementById('edit_auth').style.display = 'none';
				}
				else //Forum
				{					
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
				
			<form action="admin_forum.php?id={ID}&amp;token={TOKEN}" method="post" onsubmit="return check_form_list();" class="fieldset_content">
				<fieldset>
					<legend>{L_EDIT_CAT}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="name">* {L_NAME}</label></dt>
						<dd><label><input type="text" maxlength="100" size="35" id="name" name="name" value="{NAME}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="category">* {L_PARENT_CATEGORY}</label></dt>
						<dd><label>
							<select name="category" id="category">
								{CATEGORIES}
							</select>
						</label></dd>
					</dl>
					<dl id="forum_url">
						<dt><label for="desc">* {L_URL}</label><br /><span>{L_URL_EXPLAIN}</span></dt>
						<dd><label><input type="text" maxlength="150" size="55" name="url" id="url" value="{URL}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="desc">{L_DESC}</label></dt>
						<dd><label><input type="text" maxlength="150" size="55" name="desc" id="desc" value="{DESC}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="aprob">{L_APROB}</label></dt>
						<dd>
							<label><input type="radio" name="aprob" id="aprob" {CHECKED_APROB} value="1" /> {L_YES}</label>
							<label><input type="radio" name="aprob" {UNCHECKED_APROB} value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl id="forum_status">
						<dt><label for="status">{L_STATUS}</label></dt>
						<dd>
							<label><input type="radio" name="status" id="status" {CHECKED_STATUS} value="1" /> {L_UNLOCK}</label>
							<label><input type="radio" name="status" {UNCHECKED_STATUS} value="0" /> {L_LOCK}</label>
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
				<legend>{L_UPDATE}</legend>
					<input type="hidden" name="type" value="{TYPE}" class="submit" />
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>
			<script type="text/javascript">
			<!--
				change_type({TYPE});
			-->
			</script>
		</div>
