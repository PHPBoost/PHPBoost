		<script type="text/javascript">
		<!--
			function check_form_rank_add()
			{	
				if(document.getElementById('name').value == "") {
					alert("{L_REQUIRE_RANK_NAME}");
					return false;
			    }
				if(document.getElementById('msg').value == "") {
					alert("{L_REQUIRE_NBR_MSG_RANK}");
					return false;
			    }
				return true;
			}

			function img_change(id, url)
			{
				if( document.getElementById(id) && url != '' )
				{	
					document.getElementById(id).style.display = 'inline';
					document.getElementById(id).src = url;
				}
				else
					document.getElementById(id).style.display = 'none';
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
			
			<form action="admin_ranks_add.php?token={TOKEN}" method="post" enctype="multipart/form-data" class="fieldset_content">				
				<fieldset>
				<legend>{L_UPLOAD_RANKS}</legend>						
					<dl>
						<dt><label for="upload_ranks">{L_UPLOAD_RANKS}</label><br />{L_UPLOAD_FORMAT}</dt>
						<dd><label>
							<input type="hidden" name="max_file_size" value="2000000">
							<input type="file" id="upload_ranks" name="upload_ranks" size="30" class="file">
						</label></dd>
					</dl>
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend>{L_UPLOAD}</legend>
					<button type="submit" name="" value="true">{L_UPLOAD}</button>
				</fieldset>
			</form>

			<form action="admin_ranks_add.php?token={TOKEN}" method="post" onsubmit="return check_form_rank_add();" class="fieldset_content">	
				<fieldset>
					<legend>{L_ADD_RANKS}</legend>
					<dl>
						<dt><label for="name">* {L_RANK_NAME}</label></dt>
						<dd><label><input type="text" maxlength="30" size="20" id="name" name="name" class="text"></label></dd>
					</dl>
					<dl>
						<dt><label for="msg">* {L_NBR_MSG}</label></dt>
						<dd><label><input type="text" maxlength="6" size="6" id="msg" name="msg" class="text"></label></dd>
					</dl>
					<dl>
						<dt><label for="icon">{L_IMG_ASSOC}</label></dt>
						<dd><label>
							<select name="icon" id="icon" onchange="img_change('img_icon', '{PATH_TO_ROOT}/forum/templates/images/ranks/' + this.options[selectedIndex].value)">
								{RANK_OPTIONS}
							</select>
							<img src="{PATH_TO_ROOT}/forum/templates/images/ranks/rank_0.png" id="img_icon" alt="" style="display:none;" />
						</label></dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="idc" value="{NEXT_ID}">
					<button type="submit" name="add" value="true">{L_ADD}</button>
					&nbsp;&nbsp; 
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
		</div>
			