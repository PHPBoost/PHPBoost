# IF C_LAYOUT #
	<section id="module-{MODULE_ID}">
		<header class="section-header">
			<h1>{LAYOUT_TITLE}</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
# ENDIF #
				# IF C_FILTERS #
					<div id="filters-{TABLE_ID}" class="html-table-filters">
						<a href="" class="filter-button button bgc link-color"><i class="fa fa-sliders-h" aria-hidden="true"></i> ${LangLoader::get_message('filter.items', 'common')}</a>
						<div class="filters-container">
							<i class="close-filters far fa-window-close" aria-hidden="true"></i> <span class="sr-only">${LangLoader::get_message('close', 'main')}</span>
							<script src="{PATH_TO_ROOT}/templates/__default__/plugins/UrlSerializedParameterEncoder.js"></script>
							# INCLUDE filters #
						</div>
					</div>
					<script>
						jQuery('.filter-button').opensubmenu({
							osmTarget: '#filters-{TABLE_ID}',
							osmCloseExcept: '#filters-{TABLE_ID} filters-container *',
							osmCloseButton: '.close-filters',
						});
					</script>
				# ENDIF #

				# IF NOT C_HAS_ROWS #
					<div class="message-helper bgc notice">
						${LangLoader::get_message('no_item_now', 'common')}
					</div>
				# ELSE #
					<form method="post" class="fieldset-content">
						<div class="responsive-table">
							<table
								# IF C_ID # id="{ID}"# ENDIF #
								class="table# IF C_CSS_CLASSES # {CSS_CLASSES}# ENDIF #"
								# IF C_CSS_STYLE # style="{CSS_STYLE}"# ENDIF #>
								# IF C_CAPTION #
									<caption>
										<a href="{U_TABLE_DEFAULT_OPIONS}" aria-label="${escape(CAPTION)}">${escape(CAPTION)}</a>
									</caption>
								# ENDIF #
								<thead>
									<tr>
										# IF C_MULTIPLE_DELETE_DISPLAYED #<th class="col-smaller"><span aria-label="${LangLoader::get_message('select.elements', 'common')}"><i class="far fa-square" aria-hidden="true"></i></span></th># ENDIF #
										# START header_column #
											<th
												# IF header_column.C_CSS_CLASSES # class="{header_column.CSS_CLASSES}"# ENDIF #
												# IF header_column.C_CSS_STYLE # style="{header_column.CSS_STYLE}"# ENDIF #>
												# IF header_column.C_SORTABLE #
													<span class="html-table-header-sortable# IF header_column.C_SORT_DESC_SELECTED # sort-active# ENDIF #">
														<a href="{header_column.U_SORT_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}">
															<i class="fa fa-caret-up" aria-hidden="true"></i>
														</a>
													</span>
												# ENDIF #
												<span class="html-table-header-name# IF header_column.C_SR_ONLY # sr-only# ENDIF #">{header_column.NAME}</span>
												# IF header_column.C_SORTABLE #
													<span class="html-table-header-sortable# IF header_column.C_SORT_ASC_SELECTED # sort-active# ENDIF #">
														<a href="{header_column.U_SORT_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}">
															<i class="fa fa-caret-down" aria-hidden="true"></i>
														</a>
													</span>
												# ENDIF #
											</th>
										# END header_column #
									</tr>
								</thead>

								<tbody>
									# START row #
									<tr
										# IF row.C_ID # id="{row.ID}"# ENDIF #
										# IF row.C_CSS_CLASSES # class="{row.CSS_CLASSES}"# ENDIF #
										# IF row.C_CSS_STYLE # style="{row.CSS_STYLE}"# ENDIF #>
										# IF C_MULTIPLE_DELETE_DISPLAYED #
											<td class="mini-checkbox">
												# IF row.C_DISPLAY_DELETE_INPUT #
													<label for="multiple-checkbox-{row.ELEMENT_NUMBER}" class="checkbox" aria-label="${LangLoader::get_message('select.element', 'common')}">
														<input type="checkbox" class="multiple-checkbox" id="multiple-checkbox-{row.ELEMENT_NUMBER}" name="delete-checkbox-{row.ELEMENT_NUMBER}" onclick="delete_button_display({ELEMENTS_NUMBER});" />
														<span>&nbsp;</span>
													</label>
												# ENDIF #
											</td>
										# ENDIF #
										# START row.cell #
											<td
												# IF row.cell.C_COLSPAN #colspan="{row.cell.COLSPAN}"# ENDIF #
												# IF row.cell.C_ID # id="{row.cell.ID}"# ENDIF #
												# IF row.cell.C_CSS_CLASSES # class="{row.cell.CSS_CLASSES}"# ENDIF #
												# IF row.cell.C_CSS_STYLE # style="{row.cell.CSS_STYLE}"# ENDIF #>
												{row.cell.VALUE}
											</td>
										# END row.cell #
									</tr>
									# END row #
								</tbody>
								# IF C_DISPLAY_FOOTER #
									<tfoot>
										<tr>
											<td colspan="{NUMBER_OF_COLUMNS}" class="html-table-footer# IF C_FOOTER_CSS_CLASSES # {FOOTER_CSS_CLASSES}# ENDIF #">
												<div class="flex-between">
													# IF C_MULTIPLE_DELETE_DISPLAYED #
														<div class="mini-checkbox">
															<label for="delete-all-checkbox" class="checkbox" aria-label="${LangLoader::get_message('select.all.elements', 'common')}">
																<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {ELEMENTS_NUMBER});">
																<span>&nbsp;</span>
															</label>
															<input type="hidden" name="token" value="{TOKEN}" />
															<button type="submit" id="delete-all-button" name="delete-selected-elements" value="true" class="button submit" data-confirmation="delete-element" disabled="disabled">${LangLoader::get_message('delete', 'common')}</button>
														</div>
													# ENDIF #
													<div class="html-table-elements-number">
														<span>{ELEMENTS_NUMBER_LABEL}</span>
													</div>
													# IF C_PAGINATION_ACTIVATED #
														# IF C_NB_ROWS_OPTIONS #
															<div class="flex-between">
																<div class="table-rows-options">
																	<select name="nbItemsPerPage" onchange="window.location=this.value">
																		# START nbItemsOption #
																		<option value="{nbItemsOption.URL}"
																			# IF nbItemsOption.C_SELECTED # selected="selected"# END IF #>
																			{nbItemsOption.VALUE}
																		</option>
																		# END nbItemsOption #
																	</select>
																</div>
														# END IF #
														<div class="table-pagination">
															# INCLUDE pagination #
														</div>
														# IF C_NB_ROWS_OPTIONS #
															</div>
														# ENDIF #
													# ENDIF #
												</div>
											</td>
										</tr>
									</tfoot>
								# ENDIF #
							</table>
						</div>
					</form>
				# ENDIF #
				# IF C_FILTERS #
					<script>
						function {SUBMIT_FUNCTION}() {
							var filters = new Array();
							var filtersObjects = new Array();
							var has_filter = false;
							# START filterElt #
							filtersObjects.push({formId: ${escapejs(filterElt.FORM_ID)}, tableId: ${escapejs(filterElt.TABLE_ID)}});
							# END filterElt #
							for (var i = 0; i < filtersObjects.length; i++) {
								var filter = filtersObjects[i];
								var domFilter = jQuery('#' + filter.formId);
								if (domFilter) {
									var filterValue = domFilter.val();
									if (filterValue) {
										filters[filter.tableId] = filterValue;
										has_filter = true;
									}
								} else {
									window.alert('element ' + filter.formId + ' not found');
								}
							}
							var serializer = new UrlSerializedParameterEncoder();
							var filtersUrl = has_filter ? ',filters:{' + serializer.encode(filters) + '}' : '';
							var submitUrl = ${escapejs(SUBMIT_URL)} + filtersUrl;
							window.location = submitUrl;
							return false;
						}
					</script>
				# ENDIF #
# IF C_LAYOUT #
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# ENDIF #
