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
			
			<form method="post" action="admin_forum.php?del={IDCAT}&amp;token={TOKEN}" onsubmit="javascript:return check_form_select();" class="fieldset_content">
				# START topics #
				<fieldset>
					<legend>{topics.L_KEEP}</legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/important.png" alt="" style="float:left;padding-right:6px;" /> &nbsp;{topics.L_EXPLAIN_CAT}
						<br />	
						<br />	
					</div>
					<br />	
					<dl>
						<dt><label for="t_to">{topics.L_MOVE_TOPICS}</label></dt>
						<dd><label>
							<select id="t_to" name="t_to">
								{topics.FORUMS}
							</select>
						</label></dd>
					</dl>
				</fieldset>			
				# END topics #
				
				# START subforums #
				<fieldset>
					<legend>{subforums.L_KEEP}</legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/important.png" alt="" style="float:left;padding-right:6px;" /> &nbsp;{subforums.L_EXPLAIN_CAT}
						<br />	
						<br />	
					</div>
					<br />	
					<dl>
						<dt><label for="f_to">{subforums.L_MOVE_FORUMS}</label></dt>
						<dd><label>
							<select id="f_to" name="f_to">
								{subforums.FORUMS}
							</select>
						</label></dd>
					</dl>
				</fieldset>			
				# END subforums #
				
				<fieldset>
					<legend>{L_DEL_ALL}</legend>
					<dl>
						<dt><label for="del_conf">{L_DEL_FORUM_CONTENTS}</label></dt>
						<dd><label><input type="checkbox" name="del_conf" id="del_conf" /></label></dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="submit" name="del_cat" value="{L_SUBMIT}" class="submit" />
				</fieldset>
			</form>
		</div>
		