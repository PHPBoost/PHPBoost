<section id="module-media" class="category-{CATEGORY_ID}">
	<header class="setion-header">
		<div class="controls align-right">
			{@media.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1>
			{TITLE}
		</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<article id="media-item-{ID}" class="media-item single-item# IF C_NEW_CONTENT # new-content# ENDIF #">
				<div class="flex-between">
					<div class="more">
						<span class="pinned" aria-label="{@common.author}">
							# IF C_AUTHOR_EXISTS #
								<a class="{AUTHOR_LEVEL_CLASS}"# IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}"# ENDIF # href="{U_AUTHOR_PROFILE}">{AUTHOR_DISPLAY_NAME}</a>
							# ELSE #
								<span class="visitor">${LangLoader::get_message('user.guest', 'user-lang')}</span>
							# ENDIF #
						</span>
						<span class="pinned" aria-label="{@common.creation.date}"><i class="far fa-calendar"></i> {DATE}</span>
						<span class="pinned" aria-label="{@common.views.number}"><i class="far fa-eye"></i> {VIEWS_NUMBER}</span>
						# IF C_ENABLED_COMMENTS #
							<span class="pinned" aria-label="{@common.comments}"><i class="far fa-comments"></i> {COMMENTS_NUMBER}</span>
						# ENDIF #
						<div class="spacer"></div>
						# IF C_ENABLED_NOTATION #
							<div class="pinned">{KERNEL_NOTATION}</div>
						# ENDIF #
					</div>
					# IF C_CONTROLS #
						<div class="controls">
							<a href="{U_STATUS}" aria-label="{@media.hide.item}"><i class="fa fa-eye-slash"></i></a>
							<a href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-edit"></i></a>
							<a href="{U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-trash-alt"></i></a>
						</div>
					# ENDIF #
				</div>
				<div class="content" itemprop="text">
					{CONTENT}
					# INCLUDE MEDIA_FORMAT #
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
