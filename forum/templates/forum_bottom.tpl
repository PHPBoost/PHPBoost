		<div class="module_position" style="margin-top:15px;margin-bottom:26px;">
			<div class="forum_links" style="border-bottom:none;">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a> 
				</span>
				<span style="float:right;">
					<img src="{MODULE_DATA_PATH}/images/favorite_mini.png" alt="" class="valign_middle" /> {U_TOPIC_TRACK} &bull;
					<img src="{MODULE_DATA_PATH}/images/last_mini.png" alt="" class="valign_middle" /> {U_LAST_MSG_READ} &bull;
					<img src="{MODULE_DATA_PATH}/images/new_mini.png" alt="" class="valign_middle" /> {U_MSG_NOT_READ} 
					
					# IF C_DISPLAY_UNREAD_DETAILS #
					<div style="position:relative;float:left;">
						<div style="position:absolute;z-index:100;float:left;margin-left:160px;display:none;" id="forum_blockforum_unread2">
							<div class="row2" style="width:408px;height:{MAX_UNREAD_HEIGHT}px;overflow:auto;padding:0px;" onmouseover="forum_hide_block('forum_unread2', 1);" onmouseout="forum_hide_block('forum_unread2', 0);">
								<table class="module_table" style="margin:2px;width:99%">
									# START forum_unread_list #
									<tr>
										<td class="text_small row2" style="padding:4px;width:100%">
											{forum_unread_list.U_TOPICS}
										</td>
										<td class="text_small row2" style="padding:4px;">
											[{forum_unread_list.LOGIN}]
										</td>
										<td class="text_small row2" style="padding:4px;white-space:nowrap">
											{forum_unread_list.DATE}
										</td>
									</tr>
									# END forum_unread_list #
								</table>
							</div>
						</div>
					</div>
					<a href="javascript:forum_display_block('forum_unread2');" onmouseover="forum_hide_block('forum_unread2', 1);" onmouseout="forum_hide_block('forum_unread2', 0);" class="bbcode_hover"><img src="../templates/{THEME}/images/upload/plus.png" alt="" class="valign_middle" /></a> 
					# ENDIF #
					
					&bull;
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