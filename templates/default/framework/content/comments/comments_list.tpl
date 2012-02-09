# START comments_list #
<div class="msg_position">
	<div class="msg_container">
		<div class="msg_top_row">
			<div class="msg_pseudo_mbr">
			</div>
		</div>
		<div class="msg_contents_container">
			<div class="msg_info_mbr">
			</div>
			<a href="{comments_list.EDIT_COMMENT}">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" class="valign_middle" />
			</a> 
			<a href="{comments_list.DELETE_COMMENT}">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" class="valign_middle" />
			</a>
			<div class="msg_contents">
				<div class="msg_contents_overflow">
					{comments_list.MESSAGE}
				</div>
				<button type="submit" onclick="CommentsService.positive_vote(${escapejs(MODULE_ID)}, ${escapejs(ID_IN_MODULE)}, ${escapejs(comments_list.COMMENT_ID)});" class="submit">+1</button>
				<button type="submit" onclick="CommentsService.negative_vote(${escapejs(MODULE_ID)}, ${escapejs(ID_IN_MODULE)}, ${escapejs(comments_list.COMMENT_ID)});" class="submit">-1</button>
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
# END comments_list #