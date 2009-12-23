# IF C_FILTERS #
<div
	id="show_filters_{TABLE_ID}"
	onclick="toggleTableFilters();">
	SHOW_FILTERS
</div>
<div
	id="hide_filters_{TABLE_ID}"
	onclick="toggleTableFilters();"
	style="display:none;">
	HIDE_FILTERS
</div>
<div id="filters_{TABLE_ID}" style="display:none;">
	# INCLUDE filters #
</div>
# ENDIF #
<table
	class="module_table # IF C_CSS_CLASSES #{CSS_CLASSES}# ENDIF #"
	# IF C_CSS_STYLE # style="{CSS_STYLE}"# ENDIF #>
	# IF C_CAPTION #
	<caption>
		<a href="{U_TABLE_DEFAULT_OPIONS}" title="{E_CAPTION}">{E_CAPTION}</a>
	</caption>
	# ENDIF #
	<thead>
		<tr>
			# START header_column #
			<th
			# IF header_column.C_CSS_CLASSES # class="{header_column.CSS_CLASSES}"# ENDIF #
			# IF header_column.C_CSS_STYLE # style="{header_column.CSS_STYLE}"# ENDIF #>
				
				# IF header_column.C_SORTABLE #
				<a href="{header_column.U_SORT_DESC}" title="{EL_DESCENDING}">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="{EL_DESCENDING}" />
				</a>
				# ENDIF #
				{header_column.NAME}
				# IF header_column.C_SORTABLE #
				<a href="{header_column.U_SORT_ASC}" title="{EL_ASCENDING}">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="{EL_ASCENDING}" />
				</a>
				# ENDIF #
			</th>
			# END header_column #
		</tr>
	</thead>
	# IF C_PAGINATION_ACTIVATED #
	<tfoot>
	    <tr>
	      	<td colspan="{NUMBER_OF_COLUMNS}" class="row2">
	      		<div style="float:left;">
	      			{NUMBER_OF_ELEMENTS}
      			</div>
	      		<div style="float:right;">
	      			# INCLUDE pagination #
      			</div>
			</td>
	    </tr>
    </tfoot>
    # ENDIF #
	<tbody>
		# IF C_PAGINATION_ACTIVATED #
	    <tr>
	      	<td colspan="{NUMBER_OF_COLUMNS}" class="row2">
	      		<div style="float:left;">
	      			{NUMBER_OF_ELEMENTS}
      			</div>
	      		<div style="float:right;">
	      			# INCLUDE pagination #
      			</div>
			</td>
	    </tr>
	    # ENDIF #
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
<script type="text/javascript">
<!--
function toggleTableFilters() {
	Effect.toggle('show_filters_{TABLE_ID}', 'appear', { duration: 0 });
	Effect.toggle('hide_filters_{TABLE_ID}', 'appear', { duration: 0 });
	Effect.toggle('filters_{TABLE_ID}', 'appear', { duration: 0.5 });
}

function {SUBMIT_FUNCTION}() {
	submitUrl = {J_SUBMIT_URL};
	filtersList = '';
	# START filter #
	eltName = '{filter.NAME}';
	elt = $(eltName);
	if (elt) {
		window.alert(elt.id + ' = ' + elt.value);
		filtersList += 'equals-' + elt.name + '-' + elt.value + ',';
	} else {
		window.alert('element ' + eltName + ' not found');
	}
	# END filter #
	if (submitUrl.charAt(submitUrl.length - 1)) {
		submitUrl += ',';
	}
	window.alert('URL: ' + submitUrl + 'filters:{' + filtersList + '}');
	// window.location = submitUrl;
	return false;
}
-->
</script>
# ENDIF #