<section id="module-wiki-history" class="wiki-history">
	# IF C_ITEM #
		<header class="section-header">
			<h1>{@wiki.history} : <a class="offload" href="{U_ITEM}">{TITLE}</a></h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<div class="responsive-table">
						<table class="table">
							<thead>
								<tr>
									<th>
										{@wiki.versions}
									</th>
									<th>
										{@wiki.version.date}
									</th>
									<th>
										{@common.author}
									</th>
									<th>
										{@wiki.changing.reason}
									</th>
									<th class="col-medium">
										{@common.actions}
									</th>
								</tr>
							</thead>
							<tbody>
								# START list #
									<tr# IF list.C_CURRENT_VERSION # class="bgc visitor"# ENDIF #>
										<td>
											<a class="offload" href="{list.U_ARTICLE}">{@wiki.consult}</a>
										</td>
										<td>
											{list.DATE}
										</td>
										<td>
											{list.AUTHOR}
										</td>
										<td>
											{list.L_CHANGE_REASON}
										</td>
										<td>
											# IF list.C_ACTIONS #
												# IF list.C_RESTORE #
													<a class="offload" href="{list.U_RESTORE}" aria-label="{@wiki.restore.version}"><i class="fa fa-fw fa-undo" aria-hidden="true"></i></a>
												# ENDIF #
												# IF list.C_DELETE #
													<a href="{list.U_DELETE}" aria-label="{@common.delete}" data-confirmation="{@wiki.confirm.delete.archive}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
												# ENDIF #
											# ELSE #
												<span class="d-block small text-italic">{@wiki.current.version}</span>
											# ENDIF #
										</td>
									</tr>
								# END list #
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	# ELSE #
		<header class="section-header">
			<h1>{@wiki.history}</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<div class="responsive-table">
						<table class="table">
							<thead>
								<tr>
									<th>
										<a href="{TOP_TITLE}" aria-label="{@common.sort.asc}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
										{@common.title}
										<a href="{BOTTOM_TITLE}" aria-label="{@common.sort.desc}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
									</th>
									<th>
										<a href="{TOP_DATE}" aria-label="{@common.sort.asc}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
										{@common.last.update}
										<a href="{BOTTOM_DATE}" aria-label="{@common.sort.desc}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
									</th>
									<th>
										{@common.author}
									</th>
								</tr>
							</thead>
							<tbody>
								# START list #
									<tr>
										<td>
											<a class="offload" href="{list.U_ITEM}" aria-label="{@common.see.details}">{list.TITLE}</a>
										</td>
										<td>
											{list.LAST_UPDATE}
										</td>
										<td>
											# IF list.C_AUTHOR_EXISTS #
												<a itemprop="author" href="{list.U_AUTHOR_PROFILE}" class="{list.AUTHOR_LEVEL_CLASS} offload" # IF list.C_AUTHOR_GROUP_COLOR # style="color: {list.AUTHOR_GROUP_COLOR};" # ENDIF #>{list.AUTHOR_DISPLAY_NAME}</a>
											# ELSE #
												<span aria-label="{list.AUTHOR_IP}">{@user.guest}</span>
											# ENDIF #
										</td>
									</tr>
								# END list #
							</tbody>
							# IF C_PAGINATION #
								<tfoot>
									<tr>
										<td colspan="3">
											# INCLUDE PAGINATION #
										</td>
									</tr>
								</tfoot>
							# ENDIF #
						</table>
					</div>
				</div>
			</div>
		</div>
	# END IF #
	<footer></footer>
</section>
