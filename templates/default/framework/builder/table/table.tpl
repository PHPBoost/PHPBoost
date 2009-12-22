# IF C_FILTERS #
<div id="filters">
	<form action="">
		# START filter #
		<div>
			<input type="text" name="" value="" />
			<input type="submit" value="submit" />
		</div>
		# ENDIF #
	</form>
</div>
# ENDIF #
<table
	class="module_table # IF C_CSS_CLASSES #{CSS_CLASSES}# ENDIF #"
	# IF C_CSS_STYLE # style="{CSS_STYLE}"# ENDIF #>
	# IF C_CAPTION #<caption>{CAPTION}</caption># ENDIF #
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
	<tbody>
		# START row #
		<tr
		# IF row.C_CSS_CLASSES # class="{row.CSS_CLASSES}"# ENDIF #
		# IF row.C_CSS_STYLE # style="{row.CSS_STYLE}"# ENDIF #>
			# START row.cell #
			<td
			# IF row.cell.C_CSS_CLASSES # class="{row.cell.CSS_CLASSES}"# ENDIF #
			# IF row.cell.C_CSS_STYLE # style="{row.cell.CSS_STYLE}"# ENDIF #>
				{row.cell.VALUE}
			</td>
			# END row.cell #
		</tr>
		# END row #
	</tbody>
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
</table>