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
			
			<form method="post" action="admin_forum.php?del={IDCAT}&amp;token={TOKEN}" onsubmit="javascript:return check_form_select();" class="fieldset_content">
				# START topics #
				<fieldset>
					<legend>{topics.L_KEEP}</legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<i class="icon-notice"></i> &nbsp;{topics.L_EXPLAIN_CAT}
						<br />	
						<br />	
					</div>
					<br />	
					<div class="form-element">
						<label for="t_to">{topics.L_MOVE_TOPICS}</label>
						<div class="form-field"><label>
							<select id="t_to" name="t_to">
								{topics.FORUMS}
							</select>
						</label></div>
					</div>
				</fieldset>			
				# END topics #
				
				# START subforums #
				<fieldset>
					<legend>{subforums.L_KEEP}</legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<i class="icon-notice"></i> &nbsp;{subforums.L_EXPLAIN_CAT}
						<br />	
						<br />	
					</div>
					<br />	
					<div class="form-element">
						<label for="f_to">{subforums.L_MOVE_FORUMS}</label>
						<div class="form-field"><label>
							<select id="f_to" name="f_to">
								{subforums.FORUMS}
							</select>
						</label></div>
					</div>
				</fieldset>			
				# END subforums #
				
				<fieldset>
					<legend>{L_DEL_ALL}</legend>
					<div class="form-element">
						<label for="del_conf">{L_DEL_FORUM_CONTENTS}</label>
						<div class="form-field"><label><input type="checkbox" name="del_conf" id="del_conf"></label></div>
					</div>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<button type="submit" name="del_cat" value="true">{L_SUBMIT}</button>
				</fieldset>
			</form>
		</div>
		