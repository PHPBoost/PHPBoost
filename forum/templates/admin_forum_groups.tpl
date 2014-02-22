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
			</ul>
		</div>

		<div id="admin_contents">
			<form action="admin_forum_groups.php?token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_FORUM_GROUPS}</legend>
					<p>{EXPLAIN_FORUM_GROUPS}</p>
					<dl>
						<dt><label>{L_FLOOD}</label></dt>
						<dd>{FLOOD_AUTH}</dd>
					</dl>
					<dl>
						<dt><label>{L_EDIT_MARK}</label></dt>
						<dd>{EDIT_MARK_AUTH}</dd>
					</dl>
					<dl>
						<dt><label>{L_TRACK_TOPIC}</label></dt>
						<dd>{TRACK_TOPIC_AUTH}</dd>
					</dl>
				</fieldset>
								
				<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>
		</div>
		