		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_CONFIRM_DEL_ENTRY}");
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
					<a href="admin_ranks.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ranks.png" alt="" /></a>
					<br />
					<a href="admin_ranks.php" class="quick_link">{L_FORUM_RANKS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_ranks_add.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ranks.png" alt="" /></a>
					<br />
					<a href="admin_ranks_add.php" class="quick_link">{L_FORUM_ADD_RANKS}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			<form action="admin_forum_groups.php?token={TOKEN}" method="post" class="fieldset-content">
				<fieldset>
					<legend>{L_FORUM_GROUPS}</legend>
					<p>{EXPLAIN_FORUM_GROUPS}</p>
					<div class="form-element">
						<label>{L_FLOOD}</label>
						<div class="form-field">{FLOOD_AUTH}</div>
					</div>
					<div class="form-element">
						<label>{L_EDIT_MARK}</label>
						<div class="form-field">{EDIT_MARK_AUTH}</div>
					</div>
					<div class="form-element">
						<label>{L_TRACK_TOPIC}</label>
						<div class="form-field">{TRACK_TOPIC_AUTH}</div>
					</div>
				</fieldset>
								
				<fieldset class="fieldset-submit">
				<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					&nbsp;&nbsp; 
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
		</div>
		