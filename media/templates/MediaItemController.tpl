<section id="module-media" class="category-{CATEGORY_ID} single-item">
	<header class="section-header">
		<div class="controls align-right">
			{@media.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1>
			{TITLE}
		</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<article id="media-item-{ID}" class="media-item# IF C_NEW_CONTENT # new-content# ENDIF #">
				<div class="flex-between">
					<div class="more">
						<span class="pinned" aria-label="{@common.author}">
							# IF C_AUTHOR_DISPLAYED #
								<i class="far fa-user"></i>
								# IF C_AUTHOR_EXISTS #
									<a itemprop="author" class="{AUTHOR_LEVEL_CLASS} offload"# IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}"# ENDIF # href="{U_AUTHOR_PROFILE}">
										{AUTHOR_DISPLAY_NAME}
									</a>
								# ELSE #
									<span class="visitor">{@user.guest}</span>
								# ENDIF #
							# ENDIF #
						</span>
						<span class="pinned" aria-label="{@common.creation.date}"><i class="far fa-calendar" aria-hidden="true"></i> {DATE}</span>
						<span class="pinned" aria-label="{@common.views.number}"><i class="far fa-eye" aria-hidden="true"></i> {VIEWS_NUMBER}</span>
						# IF C_ENABLED_COMMENTS #
							<span class="pinned" aria-label="{@common.comments}"><i class="far fa-comments" aria-hidden="true"></i> {COMMENTS_NUMBER}</span>
						# ENDIF #
						<div class="spacer"></div>
						# IF C_ENABLED_NOTATION #
							<div class="pinned">{KERNEL_NOTATION}</div>
						# ENDIF #
					</div>
					# IF C_CONTROLS #
						<div class="controls">
							<a class="offload" href="{U_STATUS}" aria-label="{@media.hide.item}"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
							<a class="offload" href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>
							<a href="{U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
						</div>
					# ENDIF #
				</div>
				<div class="content" itemprop="text">
					# INCLUDE MEDIA_FORMAT #
					{CONTENT}
				</div>
				<aside>${ContentSharingActionsMenuService::display()}</aside>
				# IF C_ENABLED_COMMENTS #
					<aside>{COMMENTS}</aside>
				# ENDIF #
			</article>
		</div>
	</div>
	<footer></footer>
</section>
