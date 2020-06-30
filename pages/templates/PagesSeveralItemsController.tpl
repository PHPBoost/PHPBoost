<section id="module-pages">
	<header>
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('pages', ID_CATEGORY))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-fw fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CATEGORIES ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING_ITEMS #
				{@pending.items}
			# ELSE #
				{@items}
				# IF C_MEMBER_ITEMS # - ${LangLoader::get_message('my.items', 'user-common')}# ENDIF #
				# IF C_CATEGORIES # - {CATEGORY_NAME}# ENDIF #
			# ENDIF #
		</h1>
	</header>
		<div class="content">

			# IF C_CATEGORY_DESCRIPTION #
				<div class="cat-description">
					<img src="{U_CATEGORY_THUMBNAIL}" class="item-thumbnail" alt="{CATEGORY_NAME}">
					{CATEGORY_DESCRIPTION}
				</div>
			# ENDIF #

			# IF C_NO_ITEM #
				<div class="message-helper bgc notice align-center">
					${LangLoader::get_message('no_item_now', 'common')}
				</div>
			# ELSE #
				<table class="table-no-header">
					<tbody>
						# START items #
							<tr>
								<td aria-label="${LangLoader::get_message('form.date.creation', 'common')}" class="align-left"><a href="{items.U_ITEM}"><span itemprop="name"><i class="far fa-fw fa-file-alt" aria-hidden="true"></i> {items.TITLE}</a></td>
								<td aria-label="${LangLoader::get_message('form.date.creation', 'common')}"><i class="far fa-calendar-plus" aria-hidden="true"></i> <time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT items.C_DIFFERED #{items.DATE}# ELSE #{items.DIFFERED_START_DATE}# ENDIF #</time></td>
								<td aria-label="${LangLoader::get_message('form.date.update', 'common')}"><i class="far fa-calendar-check" aria-hidden="true"></i> <time datetime="{items.UPDATED_DATE_ISO8601}" itemprop="datePublished">{items.UPDATED_DATE}</time></td>
								<td># IF NOT C_CATEGORIES #<a itemprop="about" href="{items.U_CATEGORY}"><i class="far fa-folder" aria-hidden="true"></i> {items.CATEGORY_NAME}</a># ENDIF #</td>
								<td>
									# IF NOT C_MEMBER_ITEMS #
										<span class="pinned {items.USER_LEVEL_CLASS}"# IF items.C_USER_GROUP_COLOR # style="color:{items.USER_GROUP_COLOR};border-color:{items.USER_GROUP_COLOR};"# ENDIF #>
											# IF items.C_AUTHOR_CUSTOM_NAME #
												<i class="far fa-user" aria-hidden="true"></i> <span class="custom-author">{items.AUTHOR_CUSTOM_NAME}</span>
											# ELSE #
												# IF items.C_AUTHOR_EXIST #
													<a itemprop="author" class="{items.USER_LEVEL_CLASS}" href="{items.U_AUTHOR_PROFILE}"# IF items.C_USER_GROUP_COLOR # style="color:{items.USER_GROUP_COLOR}"# ENDIF #>
														<i class="far fa-user" aria-hidden="true"></i> {items.PSEUDO}
													</a>
												# ELSE #
													<i class="far fa-user" aria-hidden="true"></i> <span class="visitor">{items.PSEUDO}</span>
												# ENDIF #
											# ENDIF #
										</span>
									# ENDIF #
								</td>
								<td>
									<span class="pinned" role="contentinfo" aria-label="{items.VIEWS_NUMBER} # IF items.C_SEVERAL_VIEWS #{@pages.views}# ELSE #{@pages.view}# ENDIF #"><i class="far fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span></td>
								</td>
								<td>
									# IF items.C_CONTROLS #
										# IF items.C_EDIT #
											<a href="{items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-fw fa-edit" aria-hidden="true"></i></a>
										# ENDIF #
										# IF items.C_DELETE #
											<a href="{items.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-fw fa-trash-alt" aria-hidden="true"></i></a>
										# ENDIF #
									# ENDIF #
								</td>
							</tr>
						# END items #
						# IF C_SUB_CATEGORIES #
							# START sub_categories_list #
								<tr>
									<td colspan="5" class="align-left">
										<a href="{sub_categories_list.U_CATEGORY}"><i class="far fa-fw fa-folder"></i> {sub_categories_list.CATEGORY_NAME}</a>
									</td>
									<td colspan="2">
										{sub_categories_list.ITEMS_NUMBER} # IF sub_categories_list.C_SEVERAL_ITEMS #{@items}# ELSE #{@item}# ENDIF #
									</td>
								</tr>
							# END sub_categories_list #
						# ENDIF #
					</tbody>
				</table>
			# ENDIF #
		</div>

		<footer class="cell-footer">
			# START items #
				<meta itemprop="url" content="{items.U_ITEM}">
				<meta itemprop="description" content="${escape(items.DESCRIPTION)}"/>
				# IF C_COMMENTS_ENABLED #
					<meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
					<meta itemprop="interactionCount" content="{items.COMMENTS_NUMBER} UserComments">
				# ENDIF #
			# END items #
		</footer>
</section>
