<script type="text/javascript">
<!--
function Confirm_del_comment() {
	return confirm("{L_CONFIRM_DELETE}");
}
-->
</script>
# START comments #
	<div id="com{comments.ID_COMMENT}" class="comment" itemscope="itemscope" itemtype="http://schema.org/Comments">
		<div class="comment-user_infos">
			<div id="comment-pseudo">
				# IF comments.C_VISITOR #
					<span itemprop="author">{comments.PSEUDO}</span>
				# ELSE #
					<a itemprop="author" href="{comments.U_PROFILE}" class="{comments.LEVEL_CLASS}" # IF comments.C_GROUP_COLOR # style="color:{comments.GROUP_COLOR}" # ENDIF #>
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
					<a itemprop="url" href="\#com{comments.ID_COMMENT}">\#{comments.ID_COMMENT}</a>
					# IF comments.C_MODERATOR #
						<a href="{comments.U_EDIT}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" class="valign_middle" />
						</a> 
						<a href="{comments.U_DELETE}" onclick="javascript:return Confirm_del_comment();">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" class="valign_middle" />
						</a>
					# ENDIF #
				</div>
				<span itemprop="datePublished" content="{comments.DATE_ISO}">{comments.DATE}</span>
			</div>
			<div class="comment-message">
				<div itemprop="text" class="message-containt" class="content">{comments.MESSAGE}</div>
				# IF comments.C_VIEW_TOPIC #
					<div class="view-topic">
						<a href="{comments.U_TOPIC}&refresh_all=1\#com{comments.ID_COMMENT}">
						{L_VIEW_TOPIC}
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/right.png" class="valign_middle">
					</a>
					</div>
				# ENDIF #
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