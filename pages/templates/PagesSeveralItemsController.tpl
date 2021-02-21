<section id="module-pages">
	<header class="section-header">
		<div class="controls align-right">
			<a href="${relative_url(SyndicationUrlBuilder::rss('pages', CATEGORY_ID))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-fw fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CATEGORIES ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING_ITEMS #
				{@pending.items}
			# ELSE #
				# IF C_MEMBER_ITEMS #
					# IF C_MY_ITEMS #{@my.items}# ELSE #{@member.items} {MEMBER_NAME}# ENDIF #
				# ELSE #
					{@items}# IF C_CATEGORIES # - {CATEGORY_NAME}# ENDIF #
				# ENDIF #
			# ENDIF #
		</h1>
	</header>

	# IF C_CATEGORY_DESCRIPTION #
		<div class="sub-section">
			<div class="content-container">
				<div class="cat-description">
					# IF C_CATEGORY_THUMBNAIL #<img src="{U_CATEGORY_THUMBNAIL}" class="item-thumbnail" alt="{CATEGORY_NAME}"># ENDIF #
					{CATEGORY_DESCRIPTION}
				</div>
			</div>
		</div>
	# ENDIF #

	# IF NOT C_SUB_CATEGORIES #
		# IF C_NO_ITEM #
			<div class="sub-section">
				<div class="content-container">
					<div class="content">
						<div class="message-helper bgc notice align-center">
							${LangLoader::get_message('no_item_now', 'common')}
						</div>
					</div>
				</div>
			</div>
		# ENDIF #
	# ENDIF #
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				# IF C_CONTROLS #
					# IF C_SEVERAL_ITEMS #
						<div class="controls align-right">
							<a href="{U_REORDER_ITEMS}" aria-label="${LangLoader::get_message('reorder', 'common')}"><i class="fa fa-fw fa-exchange-alt"></i></a>
						</div>
					# ENDIF #
				# ENDIF #
				<div class="responsive-table">
					<table class="table-no-header">
						<tbody>
							# START items #
								<tr>
									<td class="align-left"><a href="{items.U_ITEM}"><span itemprop="name"><i class="far fa-fw fa-file-alt" aria-hidden="true"></i> {items.TITLE}</a></td>
									<td aria-label="${LangLoader::get_message('form.date.creation', 'common')}"><i class="far fa-calendar-plus" aria-hidden="true"></i> <time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT items.C_DIFFERED #{items.DATE}# ELSE #{items.DIFFERED_START_DATE}# ENDIF #</time></td>
									<td aria-label="${LangLoader::get_message('form.date.update', 'common')}">
										<i class="far fa-calendar-check" aria-hidden="true"></i>
										# IF items.C_HAS_UPDATE_DATE #
											<time datetime="{items.UPDATED_DATE_ISO8601}" itemprop="datePublished">{items.UPDATE_DATE}</time>
										# ELSE #
											<span>--</span>
										# ENDIF #
									</td>
									# IF NOT C_CATEGORIES #
										<td><a itemprop="about" href="{items.U_CATEGORY}"><i class="far fa-folder" aria-hidden="true"></i> {items.CATEGORY_NAME}</a></td>
									# ENDIF #
									# IF NOT C_MEMBER_ITEMS #
										<td>
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
										</td>
									# ENDIF #
									# IF C_VIEWS_NUMBER #
										<td>
											<span class="pinned" role="contentinfo" aria-label="{items.VIEWS_NUMBER} # IF items.C_SEVERAL_VIEWS #{@pages.views}# ELSE #{@pages.view}# ENDIF #"><i class="far fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span></td>
										</td>
									# ENDIF #
									# IF items.C_CONTROLS #
										<td>
											# IF items.C_EDIT #
												<a href="{items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-fw fa-edit" aria-hidden="true"></i></a>
											# ENDIF #
											# IF items.C_DELETE #
												<a href="{items.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-fw fa-trash-alt" aria-hidden="true"></i></a>
											# ENDIF #
										</td>
									# ENDIF #
								</tr>
							# END items #
							# IF C_SUB_CATEGORIES #
								# START sub_categories_list #
									<tr>
										<td colspan="4" class="align-left">
											<a href="{sub_categories_list.U_CATEGORY}"><i class="far fa-fw fa-folder"></i> {sub_categories_list.CATEGORY_NAME}</a>
										</td>
										# IF C_CONTROLS #
											<td>
												{sub_categories_list.ITEMS_NUMBER} # IF sub_categories_list.C_SEVERAL_ITEMS #{@items}# ELSE #{@item}# ENDIF #
											</td>
										# ENDIF #
									</tr>
								# END sub_categories_list #
							# ENDIF #
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<footer></footer>
</section>
