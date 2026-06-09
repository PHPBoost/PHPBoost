<div class="sub-section" style="order: {MODULE_POSITION};">
	<div class="content-container">
		<article id="{MODULE_NAME}# IF C_CATEGORY #_category# ENDIF #-panel">
			<header class="module-header flex-between">
				<h2>{L_MODULE_TITLE}# IF C_CATEGORY #: {L_CATEGORY_NAME}# ENDIF #</h2>
				<div class="controls align-right">
					<a class="offload" href="{PATH_TO_ROOT}/{MODULE_NAME}" aria-label="{@lobby.see.module}"><i class="fa fa-share-square" aria-hidden="true"></i></a>
				</div>
			</header>
			# IF C_NO_ITEM #
				<div class="content">
					<div class="message-helper bgc notice">
						{@common.no.item.now}
					</div>
				</div>
			# ELSE #
				# IF C_TABLE_VIEW #
					<div class="responsive-table">
						<table class="table">
							<thead>
								<tr>
									<th>{@common.name}</th>
									<th class="col-small" aria-label="{@common.creation.date}">
										<i class="fa fa-fw fa-clock" aria-hidden="true"></i>
										<span class="hidden-large-screens">{@common.creation.date}</span>
									</th>
									# IF C_VIEWS_NUMBER #
										<th class="col-small" aria-label="# IF C_VISIT #{@common.visits.number}# ELSE #{@common.views.number}# ENDIF #">
											<i class="fa fa-fw fa-# IF C_VISIT #share-square# ELSE #eye# ENDIF #" aria-hidden="true"></i>
											<span class="hidden-large-screens"># IF C_VISIT #{@common.visits.number}# ELSE #{@common.views.number}# ENDIF #</span>
										</th>
									# ENDIF #
									# IF C_DL_NUMBER #
										<th class="col-small" aria-label="{@common.downloads.number}">
											<i class="fa fa-fw fa-download" aria-hidden="true"></i>
											<span class="hidden-large-screens">{@common.downloads.number}</span>
										</th>
									# ENDIF #
									# IF C_VISIT #
										<th class="col-small" aria-label="{@common.link.to.website}">
											<i class="fa fa-fw fa-sign-in-alt" aria-hidden="true"></i>
											<span class="hidden-large-screens">{@common.link.to.website}</span>
										</th>
									# ENDIF #
								</tr>
							</thead>
							<tbody>
								# START items #
									<tr class="category-{items.CATEGORY_ID}# IF items.C_PRIME_ITEM # prime-item# ENDIF #">
										<td# IF C_VISIT # aria-label="{@common.see.details}"# ENDIF #><a class="offload" href="{items.U_ITEM}">{items.TITLE}</a></td>
										<td>
											# IF items.HAS_UPDATE_DATE #
												<time datetime="{items.UPDATE_DATE_ISO8601}" itemprop="dateModified">{items.UPDATE_DATE}</time>
											# ELSE #
												<time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.DATE}</time>
											# ENDIF #
										</td>
										# IF C_VIEWS_NUMBER #<td>{items.VIEWS_NUMBER}</td># ENDIF #
										# IF C_DL_NUMBER #<td>{items.DOWNLOADS_NUMBER}</td># ENDIF #
										# IF C_VISIT #<td aria-label="{@common.link.to.website}"><a class="offload" href="{items.U_VISIT}"><i class="fa fa-fw fa-sign-in-alt" aria-hidden="true"></i></a></td># ENDIF #
									</tr>
								# END items #
							</tbody>
						</table>
					</div>
				# ELSE #
					<div class="cell-flex # IF C_GRID_VIEW #cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">

						# START items #
							<div class="{MODULE_NAME}-item category-{items.CATEGORY_ID} cell# IF items.C_PRIME_ITEM # prime-item# ENDIF #" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
								<div class="cell-header">
									<h3 class="cell-name">
										<a class="offload" href="{items.U_ITEM}">{items.TITLE}</a>
									</h3>
								</div>
								<div class="cell-infos">
									<div class="more">
										# IF C_AUTHOR_DISPLAYED #
											<span class="pinned item-author">
												<i class="fa fa-fw fa-user" aria-hidden="true"></i>
												# IF items.C_AUTHOR_EXISTS #<a itemprop="author" class="{items.AUTHOR_LEVEL_CLASS} offload" href="{items.U_AUTHOR_PROFILE}"# IF items.C_AUTHOR_GROUP_COLOR # style="{items.AUTHOR_GROUP_COLOR}"# ENDIF #>{items.AUTHOR_DISPLAY_NAME}</a># ELSE #{items.AUTHOR_DISPLAY_NAME}# ENDIF #
											</span>
										# ENDIF #
										# IF NOT C_DATE #
											# IF items.C_HAS_UPDATE #
												<span class="pinned notice text-italic item-modified-date"><i class="far fa-fw fa-calendar-plus" aria-hidden="true"></i> <time datetime="{items.UPDATE_DATE_ISO8601}" itemprop="dateModified">{items.UPDATE_DATE}</time></span>
											# ELSE #
												<span class="pinned item-creation-date"><i class="far fa-fw fa-calendar-alt" aria-hidden="true"></i> <time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.DATE}</time></span>
											# ENDIF #
										# ENDIF #
										<span class="pinned item-category"><i class="far fa-fw fa-folder" aria-hidden="true"></i> <a class="offload" itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a></span>
										# IF C_VIEWS_NUMBER #<span class="pinned item-views-number" aria-label="{items.VIEWS_NUMBER} # IF items.C_SEVERAL_VIEWS #{@common.views}# ELSE #{@common.view}# ENDIF #"><i class="fa fa-fw fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span># ENDIF #
									</div>
									# IF items.C_CONTROLS #
										<span class="controls align-right">
											# IF items.C_EDIT #
												<a class="offload item-edit" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a>
											# ENDIF #
											# IF items.C_DELETE #
												<a class="item-delete" href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
											# ENDIF #
										</span>
									# ENDIF #
								</div>
								# IF NOT items.C_FULL_ITEM_DISPLAY #
									# IF items.C_HAS_THUMBNAIL #
										<div class="cell-thumbnail cell-landscape cell-center">
											<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" />
											<a href="{items.U_ITEM}" class="cell-thumbnail-caption offload">
												{@common.see.details}
											</a>
										</div>
									# ELSE #
										# IF items.C_HAS_PARTNER_THUMBNAIL #
											<div class="cell-thumbnail cell-landscape cell-center">
												<img src="{items.U_PARTNER_THUMBNAIL}" alt="{items.TITLE}" />
												<a href="{items.U_ITEM}" class="cell-thumbnail-caption offload">
													{@common.see.details}
												</a>
											</div>
										# ENDIF #
									# ENDIF #
								# ENDIF #
								<div class="cell-body">
									<div class="cell-content">
										# IF C_DATE #
											<div class="controls align-right"><i class="far fa-fw fa-calendar-alt"></i><span>{items.START_DATE}</span> - <span>{items.END_DATE}</span></div>
										# ENDIF #
										# IF items.C_FULL_ITEM_DISPLAY #
											# IF items.C_HAS_THUMBNAIL #
												<img class="item-thumbnail" itemprop="thumbnailUrl" src="{items.U_THUMBNAIL}" alt="{items.TITLE}" />
											# ENDIF #
											{items.CONTENT}
										# ELSE #
											{items.SUMMARY}# IF items.C_READ_MORE #... <a href="{items.U_ITEM}" class="read-more offload">[{@common.read.more}]</a># ENDIF #
										# ENDIF #
									</div>
								</div>

							</div>
						# END items #
					</div>
				# ENDIF #
			# ENDIF #
		</article>
	</div>
</div>
