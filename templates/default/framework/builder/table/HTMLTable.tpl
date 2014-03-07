# IF C_FILTERS #
<div id="filters_{TABLE_ID}" style="border:1px #aaa solid;">
   <script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/UrlSerializedParameterEncoder.js"></script>
	# INCLUDE filters #
</div>
# ENDIF #
<table
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
				<a href="{header_column.U_SORT_DESC}" title="{EL_DESCENDING}" class="fa fa-table-sort-up"></a>
				# ENDIF #
				{header_column.NAME}
				# IF header_column.C_SORTABLE #
				<a href="{header_column.U_SORT_ASC}" title="{EL_ASCENDING}" class="fa fa-table-sort-down"></a>
				# ENDIF #
			</th>
			# END header_column #
		</tr>
	</thead>
	# IF C_PAGINATION_ACTIVATED #
	<tfoot>
	    <tr>
	      	<th colspan="{NUMBER_OF_COLUMNS}">
	      		<div style="float:left;">
					<span>
						{NUMBER_OF_ELEMENTS}
					</span>
      			</div>
      			# IF C_NB_ROWS_OPTIONS #
	      		<div style="float:right;padding:0 10px;">
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
	      		<div style="float:right;">
	      			# INCLUDE pagination #
      			</div>
			</th>
	    </tr>
    </tfoot>
    # ENDIF #
	<tbody>
		# START row #
		<tr
		# IF row.C_CSS_CLASSES # class="{row.CSS_CLASSES}"# ENDIF #
		# IF row.C_CSS_STYLE # style="{row.CSS_STYLE}"# ENDIF #>
			# START row.cell #
			<td
			# IF row.cell.C_COLSPAN #colspan="{row.cell.COLSPAN}"# ENDIF #
			# IF row.cell.C_CSS_CLASSES # class="{row.cell.CSS_CLASSES}"# ENDIF #
			# IF row.cell.C_CSS_STYLE # style="{row.cell.CSS_STYLE}"# ENDIF #>
				{row.cell.VALUE}
			</td>
			# END row.cell #
		</tr>
		# END row #
	</tbody>
</table>
# IF C_FILTERS #
<script>
<!--
function {SUBMIT_FUNCTION}() {
    var filters = new Array();
	var filtersObjects = new Array();
	# START filterElt #
	filtersObjects.push({
		formId: {filterElt.J_FORM_ID},
		tableId: {filterElt.J_TABLE_ID}
	});
	# END filterElt #
	for (var i = 0; i < filtersObjects.length; i++) {
		var filter = filtersObjects[i];
		var domFilter = $(filter.formId);
		if (domFilter) {
			var filterValue = $F(domFilter);
			filters[filter.tableId] = filterValue;
		} else {
			window.alert('element ' + filter.formId + ' not found');
		}
	}
    var serializer = new UrlSerializedParameterEncoder();
	var submitUrl = {J_SUBMIT_URL} + ',filters:{' + serializer.encode(filters) + '}';
	//window.alert('URL: ' + submitUrl);
	window.location = submitUrl;
    return false;
}
-->
</script>
# ENDIF #