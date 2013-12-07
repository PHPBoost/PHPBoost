		<script type="text/javascript">
		<!--
			function check_form_conf(){
				if(document.getElementById('forum_name').value == "") {
					alert("{L_REQUIRE_NAME}");
					return false;
			    }
				if(document.getElementById('pagination_topic').value == "") {
					alert("{L_REQUIRE_TOPIC_P}");
					return false;
			    }
				if(document.getElementById('pagination_msg').value == "") {
					alert("{L_REQUIRE_NBR_MSG_P}");
					return false;
			    }
				if(document.getElementById('view_time').value == "") {
					alert("{L_REQUIRE_TIME_NEW_MSG}");
					return false;
			    }
				if(document.getElementById('topic_track').value == "") {
					alert("{L_REQUIRE_TOPIC_TRACK_MAX}");
					return false;
			    }
			
				return true;
			}
			
			function img_change(id, url)
			{
				if( document.images )
					document.images[id].src = "{PICTURES_DATA_PATH}/images/" + url;
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
			
			<form action="admin_forum_config.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend>{L_FORUM_CONFIG}</legend>
					<div class="form-element">
						<label for="forum_name">* {L_FORUM_NAME}</label>
						<div class="form-field"><label><input type="text" maxlength="255" size="40" id="forum_name" name="forum_name" value="{FORUM_NAME}"></label></div>
					</div>
					<div class="form-element">
						<label for="pagination_topic">* {L_NBR_TOPIC_P}</label><br /><span>{L_NBR_TOPIC_P_EXPLAIN}</span>
						<div class="form-field"><label><input type="text" maxlength="3" size="3" id="pagination_topic" name="pagination_topic" value="{PAGINATION_TOPIC}"></label></div>
					</div>
					<div class="form-element">
						<label for="pagination_msg">* {L_NBR_MSG_P}</label><br /><span>{L_NBR_MSG_P_EXPLAIN}</span>
						<div class="form-field"><label><input type="text" size="3" maxlength="3" id="pagination_msg" name="pagination_msg" value="{PAGINATION_MSG}"></label></div>
					</div>
					<div class="form-element">
						<label for="view_time">* {L_TIME_NEW_MSG}</label><br /><span>{L_TIME_NEW_MSG_EXPLAIN}</span>
						<div class="form-field"><label><input type="text" size="4" maxlength="6" id="view_time" name="view_time" value="{VIEW_TIME}"> {L_DAYS}</label></div>
					</div>
					<div class="form-element">
						<label for="topic_track">* {L_TOPIC_TRACK_MAX}</label><br /><span>{L_TOPIC_TRACK_MAX_EXPLAIN}</span>
						<div class="form-field"><label><input type="text" size="4" maxlength="6" id="topic_track" name="topic_track" value="{TOPIC_TRACK_MAX}"></label></div>
					</div>
					<div class="form-element">
						<label for="edit_mark">{L_EDIT_MARK}</label>
						<div class="form-field">
							<label><input type="radio" {EDIT_MARK_ENABLED} name="edit_mark" id="edit_mark" value="1"> {L_ACTIV}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {EDIT_MARK_DISABLED} name="edit_mark" value="0"> {L_UNACTIVE}</label>
						</div>
					</div>
					<div class="form-element">
						<label for="display_connexion">{L_DISPLAY_CONNEXION}</label>
						<div class="form-field">
							<label><input type="radio" {DISPLAY_CONNEXION_ENABLED} name="display_connexion" id="display_connexion" value="1"> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {DISPLAY_CONNEXION_DISABLED} name="display_connexion" value="0"> {L_NO}</label>
						</div>
					</div>
					<div class="form-element">
						<label for="no_left_column">{L_NO_LEFT_COLUMN}</label>
						<div class="form-field">
							<label><input type="radio" {NO_LEFT_COLUMN_ENABLED} name="no_left_column" id="no_left_column" value="1"> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {NO_LEFT_COLUMN_DISABLED} name="no_left_column" value="0"> {L_NO}</label>
						</div>
					</div>
					<div class="form-element">
						<label for="no_right_column">{L_NO_RIGHT_COLUMN}</label>
						<div class="form-field">
							<label><input type="radio" {NO_RIGHT_COLUMN_ENABLED} name="no_right_column" id="no_right_column" value="1"> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {NO_RIGHT_COLUMN_DISABLED} name="no_right_column" value="0"> {L_NO}</label>
						</div>
					</div>
				</fieldset>
										
				<fieldset>
					<legend>{L_ACTIV_DISPLAY_MSG}</legend>
					<div class="form-element">
						<label for="activ_display_msg">{L_ACTIV_DISPLAY_MSG}</label>
						<div class="form-field">
							<label><input type="radio" {DISPLAY_MSG_ENABLED} name="activ_display_msg" id="activ_display_msg" value="1"> {L_ACTIV}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {DISPLAY_MSG_DISABLED} name="activ_display_msg" value="0"> {L_UNACTIVE}</label>
						</div>
					</div>
					<div class="form-element">
						<label for="display_msg">{L_DISPLAY_MSG}</label>
						<div class="form-field">
							<label><input type="text" size="25" name="display_msg" id="display_msg" value="{DISPLAY_MSG}"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="explain_display_msg">{L_EXPLAIN_DISPLAY_MSG}</label><br /><span>{L_EXPLAIN_DISPLAY_MSG_EXPLAIN}</span>
						<div class="form-field">
							<label><input type="text" size="40" name="explain_display_msg" id="explain_display_msg" value="{EXPLAIN_DISPLAY_MSG}"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="explain_display_msg_bis">{L_EXPLAIN_DISPLAY_MSG_BIS}</label><br /><span>{L_EXPLAIN_DISPLAY_MSG_BIS_EXPLAIN}</span>
						<div class="form-field">
							<label><input type="text" size="40" name="explain_display_msg_bis" id="explain_display_msg_bis" value="{EXPLAIN_DISPLAY_MSG_BIS}"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="icon_activ_display_msg">{L_ICON_DISPLAY_MSG}</label>
						<div class="form-field">
							<label><input type="radio" {ICON_DISPLAY_MSG_ENABLED} name="icon_activ_display_msg" id="icon_activ_display_msg" value="1"> {L_ACTIV}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {ICON_DISPLAY_MSG_DISABLED} name="icon_activ_display_msg" value="0"> {L_UNACTIVE}</label>
							&nbsp; <img src="{PICTURES_DATA_PATH}/images/msg_display.png" name="icon" alt="" style="vertical-align:middle" /> / <img src="{PICTURES_DATA_PATH}/images/msg_display2.png" name="icon" alt="" style="vertical-align:middle" />
						</div>
					</div>
				</fieldset>
					
				<fieldset class="fieldset-submit">
				<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					&nbsp;&nbsp; 
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>

			<form action="admin_forum_config.php?upd=1&amp;token={TOKEN}" name="form" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_UPDATE_DATA_CACHED}</legend>
					<p style="text-align:center;">
						<a href="admin_forum_config.php?upd=1" title="{L_UPDATE_DATA_CACHED}">
							<i class="icon-refresh icon-2x"></i>
						</a>
						<br />
						<a href="admin_forum_config.php?upd=1">{L_UPDATE_DATA_CACHED}</a>
					</p>
				</fieldset>
			</form>
		</div>
		