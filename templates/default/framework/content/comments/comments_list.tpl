# START comments #
	<article id="com{comments.ID_COMMENT}" class="comment-item several-items message-container message-small" itemscope="itemscope" itemtype="http://schema.org/Comment">
		<header class="message-header-container">
			# IF comments.C_AVATAR #<img src="{comments.U_AVATAR}" alt="${LangLoader::get_message('avatar', 'user-common')}" class="message-user-avatar" /># ENDIF #
			<div class="message-header-infos">
				<div class="message-user">
					<h3 class="message-user-pseudo">
						# IF comments.C_VISITOR #
							<span itemprop="author">{comments.PSEUDO}</span>
						# ELSE #
							<a href="{comments.U_PROFILE}" class="{comments.LEVEL_CLASS}" # IF comments.C_GROUP_COLOR # style="color:{comments.GROUP_COLOR}" # ENDIF # itemprop="author">
								{comments.PSEUDO}
							</a>
						# ENDIF #
					</h3>
					<div class="message-actions">
						# IF comments.C_MODERATOR #
							<input type="checkbox" class="multiple-checkbox" id="multiple-checkbox-{comments.COMMENT_NUMBER}" name="delete-checkbox-{comments.COMMENT_NUMBER}" onclick="delete_button_display({comments.TOTAL_COMMENTS_NUMBER});" />
							<a href="{comments.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
							<a href="{comments.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
						# ENDIF #
					</div>
				</div>
				<div class="message-infos">
					<time datetime="{comments.DATE_ISO8601}" itemprop="datePublished">{comments.DATE_FULL}</time>
					<a href="\#com{comments.ID_COMMENT}" class="hidden-small-screens" itemprop="url">\#{comments.ID_COMMENT}</a>
				</div>
			</div>
		</header>
		<div class="message-content# IF comments.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
			{comments.MESSAGE}
			# IF comments.C_VIEW_TOPIC #
				<div class="view-topic">
					<a href="{comments.U_TOPIC}\#com{comments.ID_COMMENT}">
						{L_VIEW_TOPIC}
						<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
					</a>
				</div>
			# ENDIF #
		</div>
		<footer class="message-footer-container# IF comments.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
			<div class="message-user-management">
				<span class="message-user-level small">{comments.L_LEVEL}</span>
			</div>
		</footer>
	</article>
# END comments #
