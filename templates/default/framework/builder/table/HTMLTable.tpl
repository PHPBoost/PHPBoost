# IF C_FILTERS #
<div id="filters_{TABLE_ID}" style="border:1px #aaa solid;">
   <script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/UrlSerializedParameterEncoder.js"></script>
	# INCLUDE filters #
</div>
# ENDIF #
<table
	# IF C_ID # id="{ID}"# ENDIF #
	# IF C_CSS_CLASSES # class="{CSS_CLASSES}"# ENDIF #
	# IF C_CSS_STYLE # style="{CSS_STYLE}"# ENDIF #>
	# IF C_CAPTION #
	<caption>
		<a href="{U_TABLE_DEFAULT_OPIONS}" title="${escape(CAPTION)}">${escape(CAPTION)}</a>
	</caption>
	# ENDIF #
	<thead>
		<tr>
			# START header_column #
			<th
			# IF header_column.C_CSS_CLASSES # class="{header_column.CSS_CLASSES}"# ENDIF #
			# IF header_column.C_CSS_STYLE # style="{header_column.CSS_STYLE}"# ENDIF #>
				
				# IF header_column.C_SORTABLE #
				<a href="{header_column.U_SORT_DESC}" title="{EL_DESCENDING}" class="fa fa-arrow-circle-up# IF header_column.C_SORT_DESC_SELECTED # table-arrow-color# ENDIF #"></a>
				# ENDIF #
				{header_column.NAME}
				# IF header_column.C_SORTABLE #
				<a href="{header_column.U_SORT_ASC}" title="{EL_ASCENDING}" class="fa fa-arrow-circle-down# IF header_column.C_SORT_ASC_SELECTED # table-arrow-color# ENDIF #"></a>
				# ENDIF #
			</th>
			# END header_column #
		</tr>
	</thead>
	
	# IF C_DISPLAY_FOOTER #
	<tfoot>
		<tr>
			<th colspan="{NUMBER_OF_COLUMNS}">
				<div style="float:left;">
					<span>
						{NUMBER_OF_ELEMENTS}
					</span>
				</div>
				# IF C_PAGINATION_ACTIVATED #
					# IF C_NB_ROWS_OPTIONS #
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
				# ENDIF #
			</th>
		</tr>
	</tfoot>
	# ENDIF #
	
	<tbody>
		# START row #
		<tr
		# IF row.C_ID # id="{row.ID}"# ENDIF #
		# IF row.C_CSS_CLASSES # class="{row.CSS_CLASSES}"# ENDIF #
		# IF row.C_CSS_STYLE # style="{row.CSS_STYLE}"# ENDIF #>
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
		# IF NOT C_HAS_ROWS #
		<tr> 
			<td colspan="{NUMBER_OF_COLUMNS}">
				${LangLoader::get_message('no_item_now', 'common')}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>
# IF C_FILTERS #
<script>
<!--
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
-->
</script>
# ENDIF #