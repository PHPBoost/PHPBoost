<script type="text/javascript">
<!--
function Confirm_del_comment() {
	return confirm("{L_CONFIRM_DELETE}");
}
-->
</script>
# START comments #
	<article id="com{comments.ID_COMMENT}" class="message" itemscope="itemscope" itemtype="http://schema.org/Comment">
		<div class="message-user_infos">
			<div class="message-pseudo">
				# IF comments.C_VISITOR #
					<span itemprop="author">{comments.PSEUDO}</span>
				# ELSE #
					<a itemprop="author" href="{comments.U_PROFILE}" class="{comments.LEVEL_CLASS}" # IF comments.C_GROUP_COLOR # style="color:{comments.GROUP_COLOR}" # ENDIF #>
						{comments.PSEUDO}
					</a>
				# ENDIF #
			</div>
			<div class="message-level">{comments.L_LEVEL}</div>
			<img src="{comments.U_AVATAR}" class="message-avatar" />
		</div>
		<div class="message-content">
			<div class="message-date">
				<span class="actions">
					<a itemprop="url" href="\#com{comments.ID_COMMENT}">\#{comments.ID_COMMENT}</a>
					# IF comments.C_MODERATOR #
						<a href="{comments.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="edit"></a>
						<a href="{comments.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="delete"></a>
					# ENDIF #
				</span>
				<span itemprop="datePublished" content="{comments.DATE_ISO8601}">{comments.DATE}</span>
			</div>
			<div class="message-message">
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
				<div id="message-rating">
					<div class="positive_vote_button">+</div>
					<div class="negative_vote_button">-</div>
				</div>
				 -->
			</div>
		</div>
	</article>
# END comments #	