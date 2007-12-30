		<link href="{MODULE_DATA_PATH}/forum.css" rel="stylesheet" type="text/css" media="screen, handheld">
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
			

			<script type="text/javascript">
			<!--
				function check_select_multiple(id, status)
				{
					var i;		
					for(i = -1; i <= 3; i++)
					{
						if( document.getElementById(id + 'r' + i) )
							document.getElementById(id + 'r' + i).selected = status;
					}
					for(i = 0; i < {NBR_GROUP}; i++)
					{	
						if( document.getElementById(id + 'g' + i) )
							document.getElementById(id + 'g' + i).selected = status;		
					}	
				}	
				function check_select_multiple_ranks(id, start)
				{
					var i;				
					for(i = start; i <= 3; i++)
					{	
						if( document.getElementById(id + i) )
							document.getElementById(id + i).selected = true;			
					}
				}		
			-->
			</script>
			<form action="admin_forum_groups.php" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_FORUM_GROUPS}</legend>
					<p>{EXPLAIN_FORUM_GROUPS}</p>
					<dl>
						<dt><label>{L_FLOOD}</label></dt>
						<dd><label>
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{FLOOD_AUTH}
							<br />
							<a href="javascript:check_select_multiple('1', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('1', false);">{L_SELECT_NONE}</a>
						</label></dd>
					</dl>
					<dl>
						<dt><label>{L_EDIT_MARK}</label></dt>
						<dd><label>
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{EDIT_MARK_AUTH}
							<br />
							<a href="javascript:check_select_multiple('2', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('2', false);">{L_SELECT_NONE}</a>
						</label></dd>
					</dl>
					<dl>
						<dt><label>{L_TRACK_TOPIC}</label></dt>
						<dd><label>
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{TRACK_TOPIC_AUTH}
							<br />
							<a href="javascript:check_select_multiple('3', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('3', false);">{L_SELECT_NONE}</a>
						</label></dd>
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
		