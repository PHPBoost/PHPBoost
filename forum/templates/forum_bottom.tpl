		<div class="module_position" style="margin-top:15px;">
			<div class="forum_links" style="border-bottom:none;">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a> 
				</span>
				<span style="float:right;">
					<img src="{MODULE_DATA_PATH}/images/favorite_mini.png" alt="" class="valign_middle" /> {U_TOPIC_TRACK}
					<img src="{MODULE_DATA_PATH}/images/last_mini.png" alt="" class="valign_middle" /> {U_LAST_MSG_READ}
					<img src="{MODULE_DATA_PATH}/images/new_mini.png" alt="" class="valign_middle" /> {U_MSG_NOT_READ}
					<img src="{MODULE_DATA_PATH}/images/read_mini.png" alt="" class="valign_middle" /> {U_MSG_SET_VIEW}
				</span>
				<div class="spacer"></div>
			</div>
			
			<div class="forum_online">
				# IF USERS_ONLINE #
				<span style="float:left;">
					{TOTAL_ONLINE} {L_USER} {L_ONLINE} :: {ADMIN} {L_ADMIN}, {MODO} {L_MODO}, {MEMBER} {L_MEMBER} {L_AND} {GUEST} {L_GUEST}
					<br />
					{L_MEMBER} {L_ONLINE}: {USERS_ONLINE}
				</span>
				<span style="float:right;text-align:right">
					# IF SELECT_CAT #
					<form action="{U_CHANGE_CAT}" method="post">
						<select name="change_cat" onchange="document.location = {U_ONCHANGE};">
							{SELECT_CAT}
						</select>
						<noscript>
							<input type="submit" name="valid_change_cat" value="Go" class="submit" />
						</noscript>
					</form>
					# ENDIF #
						
					# IF C_MASS_MODO_CHECK #
					<form action="action.php{SID}">
						{L_FOR_SELECTION}: 
						<select name="massive_action_type">
							<option value="change">{L_CHANGE_STATUT_TO}</option>
							<option value="changebis">{L_CHANGE_STATUT_TO_DEFAULT}</option>
							<option value="move">{L_MOVE_TO}</option>
							<option value="lock">{L_LOCK}</option>
							<option value="unlock">{L_UNLOCK}</option>
							<option value="del">{L_DELETE}</option>
						</select>
						<input type="submit" value="{L_GO}" name="valid" class="submit" />
					</form>
					# ENDIF #
				</span>
				<div class="spacer"></div>
				# ENDIF #
			
				# IF C_TOTAL_POST #
				<div style="margin-top:6px;">
					<span style="float:left;">
						{L_TOTAL_POST}: <strong>{NBR_MSG}</strong> {L_MESSAGE} {L_DISTRIBUTED} <strong>{NBR_TOPIC}</strong> {L_TOPIC}
					</span>
					<span style="float:right;">
						<a href="stats.php{SID}">{L_STATS}</a> <a href="stats.php{SID}"><img src="{MODULE_DATA_PATH}/images/stats.png" alt="" class="valign_middle" /></a>
					</span>
					<div class="spacer"></div>
				</div>
				# ENDIF #
			</div>
		</div>
