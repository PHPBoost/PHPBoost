<section id="module-wiki" class="several-items">
	<header class="section-header">
		<h1>
			{@wiki.item.history}: 
			<span class="d-block small align-center">{ITEM_TITLE}</span>
		</h1>
	</header>

	<div class="sub-section">
		<div class="content-container">
			<div class="responsive-table">
				<table class="table large-table">
					<thead>
						<tr>
							<th>{@common.version}</th>
							<th>{@common.creation.date}</th>
							<th>{@common.author}</th>
							<th>{@wiki.change.reason}</th>
							# IF C_CONTROLS #
								<th>{@common.moderation}</th>
							# ENDIF #
						</tr>
					</thead>
					<tbody>
						# START items #
							<tr class="category-{items.CATEGORY_ID}# IF items.C_ACTIVE_CONTENT # active-content# ENDIF #">
								<td>
								# IF items.C_ACTIVE_CONTENT #
									<a class="offload" href="{items.U_ITEM}" itemprop="name">{@wiki.archived.item}</a>
								# ELSE #
									<a class="offload" href="{items.U_ARCHIVE}" itemprop="name">{@wiki.archived.item}</a>
								# ENDIF #
								</td>
								<td>
									<time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.UPDATE_DATE_FULL}</time>
								</td>
								<td>
                                    <a href="${relative_url(UserUrlBuilder::personnal_message())}">{items.CONTRIBUTOR_DISPLAY_NAME}</a>  
								</td>
								<td# IF NOT items.C_INIT ## IF items.C_CHANGE_REASON # aria-label="{items.CHANGE_REASON}"# ENDIF ## ENDIF #>
									# IF items.C_INIT #
										{@wiki.history.init}
									# ELSE #
										# IF items.C_CHANGE_REASON #<p class="ellipsis">{items.CHANGE_REASON}</p># ENDIF #
									# ENDIF #
								</td#>
								# IF C_CONTROLS #
									<td class="controls">
										# IF items.C_ACTIVE_CONTENT #
											<span class="small text-italic">{@wiki.current.version}</span>
										# ELSE #
											# IF C_RESTORE #<a class="offload" href="{items.U_RESTORE}" aria-label="{@wiki.restore.item}" data-confirmation="{@wiki.confirm.restore}"><i class="fa fa-fw fa-undo" aria-hidden="true"></i></a># ENDIF #
											# IF items.C_DELETE #
												<a href="{items.U_DELETE_CONTENT}" aria-label="{@wiki.delete.version}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
											# ENDIF #
										# ENDIF #
									</td>
								# ENDIF #
							</tr>
						# END items #
					</tbody>
				</table>
			</div>			
		</div>
	</div>
	<footer>
		# IF C_PAGINATION #<div class="sub-section"><div class="content-container"># INCLUDE PAGINATION #</div></div># ENDIF #
	</footer>
</section>
<script src="{PATH_TO_ROOT}/wiki/templates/js/wiki# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
