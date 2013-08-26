<script type="text/javascript">
<!--
function Confirm() {
	return confirm("{@guestbook.delete_message_confirm}");
}
-->
</script>

<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top">{@module_title}</div>
		# INCLUDE FORM #
		
		# IF C_PAGINATION #
			<div class="text_center"># INCLUDE PAGINATION #</div>
		# ENDIF #
		# START messages #
			<div id="m{messages.ID}" class="comment">
				<div class="comment-user_infos">
					<div id="comment-pseudo">
						# IF messages.C_VISITOR #
							<span class="text_italic"># IF messages.USER_PSEUDO #{messages.USER_PSEUDO}# ELSE #{L_GUEST}# ENDIF #</span>
						# ELSE #
							<a href="{messages.U_PROFILE}" class="{messages.USER_LEVEL_CLASS}" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>
								{messages.USER_PSEUDO}
							</a>
						# ENDIF #
					</div>
					# IF messages.C_AVATAR #<img src="{messages.U_AVATAR}" class="comment-avatar" /># ENDIF #
					# IF messages.C_USER_GROUPS #
						# START messages.user_groups #
							# IF messages.user_groups.C_GROUP_PICTURE #
							<img src="{PATH_TO_ROOT}/images/group/{messages.user_groups.GROUP_PICTURE}" alt="{messages.user_groups.GROUP_NAME}" title="{messages.user_groups.GROUP_NAME}"/>
							# ELSE #
							{L_GROUP}: {messages.user_groups.GROUP_NAME}
							# ENDIF #
						# END user_groups #
					# ENDIF #
				</div>
				<div class="comment-content">
					<div class="comment-date">
						<div style="float:right;">
							# IF messages.C_MODERATOR #
								<a href="{messages.U_EDIT}">
									<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" title="{L_EDIT}" class="valign_middle" />
								</a> 
								<a href="{messages.U_DELETE}" onclick="javascript:return Confirm();">
									<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" class="valign_middle" />
								</a>
							# ENDIF #
						</div>
						<a href="{messages.U_ANCHOR}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/ancre.png" alt="{messages.ID}" /></a> {L_ON} {messages.DATE}
					</div>
					<div class="comment-message">
						<div class="message-containt">{messages.CONTENTS}</div>
					</div>
				</div>
			</div>
		# END messages #
		# IF C_PAGINATION #
			<div class="text_center"># INCLUDE PAGINATION #</div>
		# ENDIF #
	
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom"></div>
</div>
