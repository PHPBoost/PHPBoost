# START comments #
	<article id="com{comments.ID_COMMENT}" class="comment-item several-items message-container message-small" itemscope="itemscope" itemtype="https://schema.org/Comment">
		<header class="message-header-container">
			# IF comments.C_AVATAR #<img src="{comments.U_AVATAR}" alt="{@common.avatar}" class="message-user-avatar" /># ENDIF #
			<div class="message-header-infos">
				<div class="message-user-container">
					<h3 class="message-user-pseudo">
						# IF comments.C_VISITOR #
							<span itemprop="author">{comments.PSEUDO}</span>
						# ELSE #
							<a href="{comments.U_PROFILE}" class="{comments.LEVEL_CLASS} offload" # IF comments.C_GROUP_COLOR # style="color:{comments.GROUP_COLOR}" # ENDIF # itemprop="author">
								{comments.PSEUDO}
							</a>
						# ENDIF #
					</h3>
					<div class="controls message-user-infos-preview">
						{comments.L_LEVEL}
					</div>
				</div>
				<div class="message-infos">
					<time datetime="{comments.DATE_ISO8601}" itemprop="datePublished">{comments.DATE_FULL}</time>
					<div class="message-actions">
						# IF comments.C_MODERATOR #
							<label class="checkbox" for="multiple-checkbox-{comments.COMMENT_NUMBER}" aria-label="{@common.select.element}">
								<input type="checkbox" class="multiple-checkbox" id="multiple-checkbox-{comments.COMMENT_NUMBER}" name="delete-checkbox-{comments.COMMENT_NUMBER}" onclick="delete_button_display({comments.TOTAL_COMMENTS_NUMBER});" />
								<span>&nbsp;</span>
							</label>
							<a class="offload" href="{comments.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
							<a href="{comments.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
						# ENDIF #
						<a href="\#com{comments.ID_COMMENT}" class="hidden-small-screens" itemprop="url" aria-label="{@comment.link.to.anchor}">\#{comments.ID_COMMENT}</a>
						# IF comments.C_VIEW_TOPIC #
							<a class="offload" href="{comments.U_TOPIC}\#com{comments.ID_COMMENT}" aria-label="{L_VIEW_TOPIC}">
								<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
							</a>
						# ENDIF #
					</div>

				</div>
			</div>
		</header>
		<div class="message-content# IF comments.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
			{comments.MESSAGE}
		</div>
	</article>
# END comments #
