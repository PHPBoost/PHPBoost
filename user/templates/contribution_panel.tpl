# IF C_CONTRIBUTION_LIST #
	<section id="module-user-contribution-list">
		<header class="section-header">
			<h1>{@contribution.panel}</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<article class="user-contribution-item several-items">
					<header>
						<h2>{@contribution.contribute}</h2>
					</header>
					<div class="content">
						# IF C_CONTRIBUTION_MODULE #
							<p>{@contribution.contribute.in.modules}</p>
							<div class="cell-flex cell-tile cell-columns-4">
								# START module #
									<div class="cell">
										<div class="cell-body">
											<div class="cell-content align-center">
												<a class="offload" href="{module.U_MODULE_LINK}">
													# IF module.C_IMG #
														<img src="{module.U_IMG}" alt="{module.LINK_TITLE}" />
													# ELSE #
														# IF module.C_FA_ICON #
															<i class="{module.FA_ICON} fa-fw fa-2x"></i>
														# ELSE #
															# IF module.C_HEXA_ICON #
																<span class="bigger">{module.HEXA_ICON}</span>
															# ENDIF #
														# ENDIF #
													# ENDIF #
													<span class="d-block">{module.MODULE_NAME}</span>
												</a>
											</div>
										</div>
									</div>
								# END module #
							</div>
						# ELSE #
							<div class="message-helper bgc warning">{@contribution.no.module.to.contribute}</div>
						# ENDIF #
					</div>
				</article>
			</div>
		</div>
		<hr />
		<div class="sub-section">
			<div class="content-container">
				<article class="contribution-item several-items">
					<header>
						<h2>{@contribution.list}</h2>
					</header>

					# IF C_NO_CONTRIBUTION #
						<div class="content">
							<div class="message-helper bgc success">{@common.no.item.now}</div>
						</div>
					# ELSE #
						<div class="content">
							<div class="responsive-table">
								<table class="table">
									<thead>
										<tr>
											<th>
												<span class="html-table-header-sortable# IF C_ORDER_ENTITLED_ASC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_ENTITLED_ASC}" aria-label="{@common.sort.asc}">
														<i class="fa fa-caret-up" aria-hidden="true"></i>
													</a>
												</span>
												{@common.title}
												<span class="html-table-header-sortable# IF C_ORDER_ENTITLED_DESC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_ENTITLED_DESC}" aria-label="{@common.sort.desc}">
														<i class="fa fa-caret-down" aria-hidden="true"></i>
													</a>
												</span>
											</th>
											<th>
												<span class="html-table-header-sortable# IF C_ORDER_MODULE_ASC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_MODULE_ASC}" aria-label="{@common.sort.asc}">
														<i class="fa fa-caret-up" aria-hidden="true"></i>
													</a>
												</span>
												{@common.module}
												<span class="html-table-header-sortable# IF C_ORDER_MODULE_DESC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_MODULE_DESC}" aria-label="{@common.sort.desc}">
														<i class="fa fa-caret-down" aria-hidden="true"></i>
													</a>
												</span>
											</th>
											<th>
												<span class="html-table-header-sortable# IF C_ORDER_STATUS_ASC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_STATUS_ASC}" aria-label="{@common.sort.asc}">
														<i class="fa fa-caret-up" aria-hidden="true"></i>
													</a>
												</span>
												{@common.status}
												<span class="html-table-header-sortable# IF C_ORDER_STATUS_DESC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_STATUS_DESC}" aria-label="{@common.sort.desc}">
														<i class="fa fa-caret-down" aria-hidden="true"></i>
													</a>
												</span>
											</th>
											<th>
												<span class="html-table-header-sortable# IF C_ORDER_CREATION_DATE_ASC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_CREATION_DATE_ASC}" aria-label="{@common.sort.asc}">
														<i class="fa fa-caret-up" aria-hidden="true"></i>
													</a>
												</span>
												{@common.creation.date}
												<span class="html-table-header-sortable# IF C_ORDER_CREATION_DATE_DESC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_CREATION_DATE_DESC}" aria-label="{@common.sort.desc}">
														<i class="fa fa-caret-down" aria-hidden="true"></i>
													</a>
												</span>
											</th>
											<th>
												<span class="html-table-header-sortable# IF C_ORDER_FIXING_DATE_ASC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_FIXING_DATE_ASC}" aria-label="{@common.sort.asc}">
														<i class="fa fa-caret-up" aria-hidden="true"></i>
													</a>
												</span>
												{@contribution.closing.date}
												<span class="html-table-header-sortable# IF C_ORDER_FIXING_DATE_DESC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_FIXING_DATE_DESC}" aria-label="{@common.sort.desc}">
														<i class="fa fa-caret-down" aria-hidden="true"></i>
													</a>
												</span>
											</th>
											<th>
												<span class="html-table-header-sortable# IF C_ORDER_AUTHOR_ASC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_AUTHOR_ASC}" aria-label="{@common.sort.asc}">
														<i class="fa fa-caret-up" aria-hidden="true"></i>
													</a>
												</span>
												{@common.author}
												<span class="html-table-header-sortable# IF C_ORDER_AUTHOR_DESC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_AUTHOR_DESC}" aria-label="{@common.sort.desc}">
														<i class="fa fa-caret-down" aria-hidden="true"></i>
													</a>
												</span>
											</th>
											<th>
												<span class="html-table-header-sortable# IF C_ORDER_REFEREE_ASC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_REFEREE_ASC}" aria-label="{@common.sort.asc}">
														<i class="fa fa-caret-up" aria-hidden="true"></i>
													</a>
												</span>
												{@user.referee}
												<span class="html-table-header-sortable# IF C_ORDER_REFEREE_DESC # sort-active# ENDIF #">
													<a class="offload" href="{U_ORDER_REFEREE_DESC}" aria-label="{@common.sort.desc}">
														<i class="fa fa-caret-down" aria-hidden="true"></i>
													</a>
												</span>
											</th>
										</tr>
									</thead>
									<tbody>
										# START contributions #
											<tr>
												<td>
													<a class="offload" href="{contributions.U_CONSULT}">{contributions.ENTITLED}</a>
												</td>
												<td >
													{contributions.MODULE}
												</td>
												# IF contributions.C_FIXED #
													<td class="bgc success">
														{contributions.STATUS}
													</td>
												# ELSE #
													# IF contributions.C_PROCESSING #
														<td class="bgc question">
															{contributions.STATUS}
														</td>
													# ELSE #
														<td class="bgc error">
															{contributions.STATUS}
														</td>
													# ENDIF #
												# ENDIF #
												<td >
													{contributions.CREATION_DATE_FULL}
												</td>
												<td >
													# IF contributions.C_FIXED #
														{contributions.FIXING_DATE_FULL}
													# ELSE #
														-
													# ENDIF #
												</td>
												<td >
													<a itemprop="author" href="{contributions.U_AUTHOR_PROFILE}" class="{contributions.AUTHOR_LEVEL_CLASS} offload" # IF contributions.C_AUTHOR_GROUP_COLOR # style="color:{contributions.AUTHOR_GROUP_COLOR}" # ENDIF #>{contributions.POSTER}</a>
												</td>
												<td >
													# IF contributions.C_FIXED #
														<a href="{contributions.U_REFEREE_PROFILE}" class="{contributions.REFEREE_LEVEL_CLASS} offload" # IF contributions.C_REFEREE_GROUP_COLOR # style="color:{contributions.REFEREE_GROUP_COLOR}" # ENDIF #>{contributions.FIXER}</a>
													# ELSE #
														-
													# ENDIF #
												</td>
											</tr>
										# END contributions #
									</tbody>
									# IF C_PAGINATION #
										<tfoot>
											<tr>
												<td colspan="7">
													# INCLUDE PAGINATION #
												</td>
											</tr>
										</tfoot>
									# ENDIF #
								</table>
							</div>
						</div>
					# ENDIF #
				</article>
			</div>
		</div>
		<footer></footer>
	</section>
# ENDIF #

# IF C_CONSULT_CONTRIBUTION #
	<section id="module-user-consult-contribution">
		<header class="section-header">
			# IF C_WRITE_AUTH #
				<div class="controls align-right">
					<a class="offload" href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
					<a href="{U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
				</div>
			# ENDIF #
			<h1>{ENTITLED}</h1>
		</header>
		# IF C_WRITE_AUTH #
			<div class="sub-section">
				<div class="content-container">
					<article class="contribution-item several-items">
						# IF C_UNPROCESSED_CONTRIBUTION #
							<div class="cell-flex cell-columns-3">
								<div class="cell">
									<div class="cell-body">
										<div class="cell-content align-center">
											<a class="offload" href="{FIXING_URL}">
												<i class="fa fa-wrench fa-2x"aria-hidden="true"></i>
												<span class="d-block">{@contribution.process}</span>
											</a>
										</div>
									</div>
								</div>
								<div class="cell">
									<div class="cell-body">
										<div class="cell-content align-center">
											<a class="offload" href="{U_EDIT}">
												<i class="fa fa-check fa-2x success"aria-hidden="true"></i>
												<span class="d-block">{@contribution.change.status}</span>
											</a>
										</div>
									</div>
								</div>
								<div class="cell">
									<div class="cell-body">
										<div class="cell-content align-center">
											<a href="{U_DELETE}" data-confirmation="delete-element">
												<i class="far fa-trash-alt fa-2x error"aria-hidden="true"></i>
												<span class="d-block">{@contribution.delete}</span>
											</a>
										</div>
									</div>
								</div>
							</div>
						# ENDIF #
					</article>
				</div>
			</div>
		# ENDIF #
		<div class="sub-section">
			<div class="content-container">
				<article class="user-contribution-item several-items">
					<header>
						<h2>{@contribution.details}</h2>
					</header>
					<div class="cell cell-tile">
						<div class="cell-list">
							<ul>
								<li class="li-stretch">
									<span class="text-strong">{@common.title}</span>
									<span>{ENTITLED}</span>
								</li>
								<li>
									<span class="text-strong">{@common.description}</span>
									<span>{DESCRIPTION}</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.status}</span>
									<span>{STATUS}</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@contribution.contributor}</span>
									<span><a href="{U_CONTRIBUTOR_PROFILE}" class="{CONTRIBUTOR_LEVEL_CLASS} offload" # IF C_CONTRIBUTOR_GROUP_COLOR # style="color:{CONTRIBUTOR_GROUP_COLOR}" # ENDIF #>{CONTRIBUTOR}</a></span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.creation.date}</span>
									<span>{CREATION_DATE}</span>
								</li>
								# IF C_CONTRIBUTION_FIXED #
									<li class="li-stretch">
										<span class="text-strong">{@user.referee}</span>
										<span><a href="{U_REFEREE_PROFILE}" class="{REFEREE_LEVEL_CLASS} offload" # IF C_REFEREE_GROUP_COLOR # style="color:{REFEREE_GROUP_COLOR}" # ENDIF #>{FIXER}</a></span>
									</li>
									<li class="li-stretch">
										<span class="text-strong">{@contribution.closing.date}</span>
										<span>{FIXING_DATE}</span>
									</li>
								# ENDIF #
								<li class="li-stretch">
									<span class="text-strong">{@common.module}</span>
									<span>{MODULE}</span>
								</li>
							</ul>
						</div>
					</div>

					{COMMENTS}

				</article>
			</div>
		</div>
		<footer></footer>
	</section>
# ENDIF #

# IF C_EDIT_CONTRIBUTION #
	<section id="module-user-edit-contribution">
		<header class="section-header">
			<h1>{ENTITLED}</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<form action="contribution_panel.php" method="post">
						<fieldset>
							<legend>{@contribution.contributions}</legend>
							<div class="form-element">
								<label for="entitled">{@common.title}</label>
								<div class="form-field">
									<input type="text" name="entitled" id="entitled" value="{ENTITLED}">
								</div>
							</div>
							<div class="form-element form-element-textarea">
								<label for="contents">{@common.description}</label>
								<div class="form-field form-field-textarea bbcode-sidebar">
									{KERNEL_EDITOR}
									<textarea rows="15" id="contents" name="contents">{DESCRIPTION}</textarea>
								</div>
								<button type="button" class="button preview-button" name="preview" onclick="XMLHttpRequest_preview();">{@form.preview}</button>
							</div>
							<div class="form-element">
								<label for="status">{@common.status}</label>
								<div class="form-field"><select name="status" id="status">
										<option value="0"{EVENT_STATUS_UNREAD_SELECTED}>{@contribution.not.processed}</option>
										<option value="1"{EVENT_STATUS_BEING_PROCESSED_SELECTED}>{@contribution.in.progress}</option>
										<option value="2"{EVENT_STATUS_PROCESSED_SELECTED}>{@contribution.processed}</option>
									</select>
								</div>
							</div>
						</fieldset>
						<fieldset class="fieldset-submit">
							<legend class="sr-only">{@form.submit}</legend>
							<button type="submit" class="button submit" value="true">{@form.submit}</button>
							<input type="hidden" name="idedit" value="{CONTRIBUTION_ID}">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="reset" class="button reset-button">{@form.reset}</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# ENDIF #
