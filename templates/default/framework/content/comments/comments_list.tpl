# START comments #
<div class="msg_position">
	<div class="msg_container">
		<div class="msg_top_row">
			<div class="msg_pseudo_mbr">
			</div>
		</div>
		<div class="msg_contents_container">
			<div class="msg_info_mbr">
			</div>
			# IF comments.C_MODERATOR #
				<a href="{comments.U_EDIT}">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" class="valign_middle" />
				</a> 
				<a href="{comments.U_DELETE}">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" class="valign_middle" />
				</a>
			# ENDIF #
			<div class="msg_contents">
				<div class="msg_contents_overflow">
					{comments.MESSAGE}
				</div>
				<button type="submit" onclick="CommentsService.positive_vote(${escapejs(MODULE_ID)}, ${escapejs(ID_IN_MODULE)}, ${escapejs(TOPIC_IDENTIFIER)}, ${escapejs(comments.COMMENT_ID)});" class="submit">+1</button>
				<button type="submit" onclick="CommentsService.negative_vote(${escapejs(MODULE_ID)}, ${escapejs(ID_IN_MODULE)}, ${escapejs(TOPIC_IDENTIFIER)}, ${escapejs(comments.COMMENT_ID)});" class="submit">-1</button>
			</div>
		</div>
	</div>	
	<div class="msg_sign">				
		<div class="msg_sign_overflow">
		</div>				
		<hr />
		<div style="float:right;font-size:10px;">
		</div>
	</div>	
</div>
# END comments #