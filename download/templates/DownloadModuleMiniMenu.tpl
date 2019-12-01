<div class="cell-table">
	<table id="table-mini-download">
		<thead>
			<tr>
				<th><i class="fa # IF C_SORT_BY_DATE #fa-calendar-alt# ELSE #fa-trophy# ENDIF #" aria-hidden="true"></i><span class="sr-only"># IF C_SORT_BY_DATE #{@download.sort.date}# ELSE #{@download.sort.trophy}# ENDIF #</span></th>
				<th>${LangLoader::get_message('form.name', 'common')}</th>
				# IF NOT C_SORT_BY_DATE #
				<th><i class="# IF C_SORT_BY_NUMBER_DOWNLOADS #fa fa-download# ELSE #far fa-star# ENDIF #" aria-hidden="true"></i><span class="sr-only"># IF C_SORT_BY_NUMBER_DOWNLOADS #{@download.sort.dls}# ELSE #{@download.sort.notes}# ENDIF #</span></th>
				# ENDIF #
			</tr>
		</thead>
		<tbody>
			# IF C_FILES #
				# START downloadfiles #
					<tr>
						<td># IF C_SORT_BY_DATE #<time datetime="{downloadfiles.DATE_ISO8601}">{downloadfiles.DATE_DAY_MONTH}</time># ELSE #{downloadfiles.DISPLAYED_POSITION}# ENDIF #</td>
						<td # IF C_SORT_BY_NOTATION #class="mini-download-table-name"# ENDIF #>
							<a href="{downloadfiles.U_LINK}">
								{downloadfiles.NAME}
							</a>
							<div class="category-folder">
								<i class="far fa-folder"></i> {downloadfiles.CATEGORY_NAME}
							</div>
						</td>
						# IF NOT C_SORT_BY_DATE #
							<td># IF C_SORT_BY_NUMBER_DOWNLOADS #{downloadfiles.NUMBER_DOWNLOADS}# ELSE #{downloadfiles.STATIC_NOTATION}# ENDIF #</td>
						# ENDIF #
					</tr>
				# END downloadfiles #
			# ELSE #
				<tr>
					<td colspan="# IF C_SORT_BY_DATE #2# ELSE #3# ENDIF #">${LangLoader::get_message('no_item_now', 'common')}</td>
				</tr>
			# ENDIF #
		</tbody>
	</table>
</div>


<script>
	jQuery('#table-mini-download').basictable();
</script>
