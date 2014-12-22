		<script>
		<!--
			function check_form_conf(){
				if(document.getElementById('forum_name').value == "") {
					alert("{L_REQUIRE_NAME}");
					return false;
				}
				if(document.getElementById('number_topics_per_page').value == "") {
					alert("{L_REQUIRE_TOPIC_P}");
					return false;
				}
				if(document.getElementById('number_messages_per_page').value == "") {
					alert("{L_REQUIRE_NBR_MSG_P}");
					return false;
				}
				if(document.getElementById('read_messages_storage_duration').value == "") {
					alert("{L_REQUIRE_TIME_NEW_MSG}");
					return false;
				}
				if(document.getElementById('max_topic_number_in_favorite').value == "") {
					alert("{L_REQUIRE_TOPIC_TRACK_MAX}");
					return false;
				}
				
				return true;
			}
		-->
		</script>

		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_FORUM_MANAGEMENT}</li>
				<li>
					<a href="admin_forum.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum.php" class="quick-link">{L_CAT_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_forum_add.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_add.php" class="quick-link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_forum_config.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_config.php" class="quick-link">{L_FORUM_CONFIG}</a>
				</li>
				<li>
					<a href="admin_forum_groups.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_groups.php" class="quick-link">{L_FORUM_GROUPS}</a>
				</li>
				<li>
					<a href="admin_ranks.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ranks.png" alt="" /></a>
					<br />
					<a href="admin_ranks.php" class="quick-link">{L_FORUM_RANKS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_ranks_add.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ranks.png" alt="" /></a>
					<br />
					<a href="admin_ranks_add.php" class="quick-link">{L_FORUM_ADD_RANKS}</a>
				</li>
			</ul>
		</div>

		<div id="admin-contents">
		
			# INCLUDE message_helper #
			
			<form action="admin_forum_config.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_FORUM_CONFIG}</legend>
					<div class="form-element">
						<label for="forum_name">* {L_FORUM_NAME}</label>
						<div class="form-field"><label><input type="text" maxlength="255" size="40" id="forum_name" name="forum_name" value="{FORUM_NAME}"></label></div>
					</div>
					<div class="form-element">
						<label for="number_topics_per_page">* {L_NBR_TOPIC_P} <span class="field-description">{L_NBR_TOPIC_P_EXPLAIN}</span></label>
						<div class="form-field"><label><input type="text" maxlength="3" size="3" id="number_topics_per_page" name="number_topics_per_page" value="{NUMBER_TOPICS_PER_PAGE}"></label></div>
					</div>
					<div class="form-element">
						<label for="number_messages_per_page">* {L_NBR_MSG_P} <span class="field-description">{L_NBR_MSG_P_EXPLAIN}</span></label>
						<div class="form-field"><label><input type="text" size="3" maxlength="3" id="number_messages_per_page" name="number_messages_per_page" value="{NUMBER_MESSAGES_PER_PAGE}"></label></div>
					</div>
					<div class="form-element">
						<label for="read_messages_storage_duration">* {L_TIME_NEW_MSG} <span class="field-description">{L_TIME_NEW_MSG_EXPLAIN}</span></label>
						<div class="form-field"><label><input type="text" size="4" maxlength="6" id="read_messages_storage_duration" name="read_messages_storage_duration" value="{READ_MESSAGES_STORAGE_DURATION}"> {L_DAYS}</label></div>
					</div>
					<div class="form-element">
						<label for="max_topic_number_in_favorite">* {L_TOPIC_TRACK_MAX} <span class="field-description">{L_TOPIC_TRACK_MAX_EXPLAIN}</span></label>
						<div class="form-field"><label><input type="text" size="4" maxlength="6" id="max_topic_number_in_favorite" name="max_topic_number_in_favorite" value="{MAX_TOPIC_NUMBER_IN_FAVORITE}"></label></div>
					</div>
					<div class="form-element">
						<label for="edit_mark_enabled">{L_EDIT_MARK}</label>
						<div class="form-field">
							<label><input type="checkbox" name="edit_mark_enabled"# IF C_EDIT_MARK_ENABLED # checked="checked"# ENDIF #></label>
						</div>
					</div>
					<div class="form-element">
						<label for="connexion_form_displayed">{L_DISPLAY_CONNEXION}</label>
						<div class="form-field">
							<label><input type="checkbox" name="connexion_form_displayed"# IF C_CONNEXION_FORM_DISPLAYED # checked="checked"# ENDIF #></label>
						</div>
					</div>
					<div class="form-element">
						<label for="left_column_disabled">{L_NO_LEFT_COLUMN}</label>
						<div class="form-field">
							<label><input type="checkbox" name="left_column_disabled"# IF C_LEFT_COLUMN_DISABLED # checked="checked"# ENDIF #></label>
						</div>
					</div>
					<div class="form-element">
						<label for="right_column_disabled">{L_NO_RIGHT_COLUMN}</label>
						<div class="form-field">
							<label><input type="checkbox" name="right_column_disabled"# IF C_RIGHT_COLUMN_DISABLED # checked="checked"# ENDIF #></label>
						</div>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{L_ACTIV_DISPLAY_MSG}</legend>
					<div class="form-element">
						<label for="message_before_topic_title_displayed">{L_ACTIV_DISPLAY_MSG}</label>
						<div class="form-field">
							<label><input type="checkbox" name="message_before_topic_title_displayed"# IF C_MESSAGE_BEFORE_TOPIC_TITLE_DISPLAYED # checked="checked"# ENDIF #></label>
						</div>
					</div>
					<div class="form-element">
						<label for="message_before_topic_title">{L_DISPLAY_MSG}</label>
						<div class="form-field">
							<label><input type="text" size="25" name="message_before_topic_title" id="message_before_topic_title" value="{MESSAGE_BEFORE_TOPIC_TITLE}"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="message_when_topic_is_unsolved">{L_EXPLAIN_DISPLAY_MSG} <span class="field-description">{L_EXPLAIN_DISPLAY_MSG_EXPLAIN}</span></label>
						<div class="form-field">
							<label><input type="text" size="40" name="message_when_topic_is_unsolved" id="message_when_topic_is_unsolved" value="{MESSAGE_WHEN_TOPIC_IS_UNSOLVED}"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="message_when_topic_is_solved">{L_EXPLAIN_DISPLAY_MSG_BIS} <span class="field-description">{L_EXPLAIN_DISPLAY_MSG_BIS_EXPLAIN}</span></label>
						<div class="form-field">
							<label><input type="text" size="40" name="message_when_topic_is_solved" id="message_when_topic_is_solved" value="{MESSAGE_WHEN_TOPIC_IS_SOLVED}"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="message_before_topic_title_icon_displayed">{L_ICON_DISPLAY_MSG} <span class="field-description"><i class="fa fa-msg-display"></i> / <i class="fa fa-msg-not-display"></i></span></label>
						<div class="form-field">
							<label><input type="checkbox" name="message_before_topic_title_icon_displayed"# IF C_MESSAGE_BEFORE_TOPIC_TITLE_ICON_DISPLAYED # checked="checked"# ENDIF #></label>
						</div>
					</div>
				</fieldset>
					
				<fieldset class="fieldset-submit">
				<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true" class="submit">{L_UPDATE}</button>
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>

			<form action="admin_forum_config.php?upd=1&amp;token={TOKEN}" name="form" method="post" class="fieldset-content">
				<fieldset>
					<legend>{L_UPDATE_DATA_CACHED}</legend>
					<p class="center">
						<a href="admin_forum_config.php?upd=1" title="{L_UPDATE_DATA_CACHED}">
							<i class="fa fa-refresh fa-2x"></i>
						</a>
						<br />
						<a href="admin_forum_config.php?upd=1">{L_UPDATE_DATA_CACHED}</a>
					</p>
				</fieldset>
			</form>
		</div>
		