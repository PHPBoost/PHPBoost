<section id="module-media" class="category-{CATEGORY_ID}">
	<header class="setion-header">
		<div class="controls align-right">
			{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
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
						<span class="pinned"><i class="fa fa-user"></i> {AUTHOR_NAME}</span>
						<span class="pinned"><i class="far fa-calendar"></i> {DATE}</span>
						<span class="pinned" aria-label="${LangLoader::get_message('sort_by.views.number', 'common')}"><i class="fa fa-eye"></i> {VIEWS_NUMBER}</span>
						# IF C_ENABLED_COMMENTS #
							<span class="pinned"><i class="fa fa-comments"></i> {COMMENTS_NUMBER}</span>
						# ENDIF #
						<div class="spacer"></div>
						# IF C_ENABLED_NOTATION #
							<div class="pinned">{KERNEL_NOTATION}</div>
						# ENDIF #
					</div>
					# IF C_CONTROLS #
						<div class="controls">
							<a href="{U_INVISIBLE_MEDIA}" aria-label="{@media.hide.file}"><i class="fa fa-eye-slash"></i></a>
							<a href="{U_EDIT_MEDIA}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit"></i></a>
							<a href="{U_DELETE_MEDIA}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-trash-alt"></i></a>
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
