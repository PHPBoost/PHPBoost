# START comments #
	<div id="com{comments.ID_COMMENT}" class="comment">
		<div class="comment-user_infos">
			<div id="comment-pseudo">
				# IF comments.C_VISITOR #
					{comments.PSEUDO}
				# ELSE #
					<a href="{comments.U_PROFILE}" class="{comments.LEVEL_CLASS}" # IF comments.C_GROUP_COLOR # style="color:{comments.GROUP_COLOR}" # ENDIF #>
						{comments.PSEUDO}
					</a>
				# ENDIF #
			</div>
			<div class="comment-level">{comments.L_LEVEL}</div>
			<img src="{comments.U_AVATAR}" class="comment-avatar" />
		</div>
		<div class="comment-content">
			<div class="comment-date">
				<div style="float:right;">
					<a href="\#com{comments.ID_COMMENT}">\#{comments.ID_COMMENT}</a>
					# IF comments.C_MODERATOR #
						<a href="{comments.U_EDIT}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" class="valign_middle" />
						</a> 
						<a href="{comments.U_DELETE}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" class="valign_middle" />
						</a>
					# ENDIF #
				</div>
				{comments.DATE}
			</div>
			<div class="comment-message" >
				<div class="message-containt">{comments.MESSAGE}</div>
				<!-- 
				<div id="comment-rating">
					<div class="positive_vote_button">+</div>
					<div class="negative_vote_button">-</div>
				</div>
				 -->
			</div>
		</div>
	</div>
# END comments #	