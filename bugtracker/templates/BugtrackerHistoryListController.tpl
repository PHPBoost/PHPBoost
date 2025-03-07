# IF C_HISTORY #
	<div class="responsive-table">
		<table class="table">
			<thead>
				<tr>
					<th>
						{@bugtracker.updated.by}
					</th>
					<th>
						{@bugtracker.updated.field}
					</th>
					<th>
						{@common.before}
					</th>
					<th>
						{@common.after}
					</th>
					<th>
						{@bugtracker.update.date}
					</th>
					<th>
						{@common.comment}
					</th>
				</tr>
			</thead>
			<tbody>
				# START history #
				<tr>
					<td>
						# IF history.C_UPDATER_EXIST #<a href="{history.U_UPDATER_PROFILE}" class="{history.UPDATER_LEVEL_CLASS} offload" # IF history.C_UPDATER_GROUP_COLOR # style="color:{history.UPDATER_GROUP_COLOR}" # ENDIF #>{history.UPDATER}</a># ELSE #{history.UPDATER}# ENDIF #
					</td>
					<td>
						{history.UPDATED_FIELD}
					</td>
					<td>
						{history.OLD_VALUE}
					</td>
					<td>
						{history.NEW_VALUE}
					</td>
					<td>
						{history.UPDATE_DATE_FULL}
					</td>
					<td>
						{history.COMMENT}
					</td>
				</tr>
				# END history #
			</tbody>
			# IF C_PAGINATION #
			<tfoot>
				<tr>
					<td colspan="6"># INCLUDE PAGINATION #</td>
				</tr>
			</tfoot>
			# ENDIF #
		</table>
	</div>
# ELSE #
	<div class="message-helper bgc notice">{@common.no.item.now}</div>
# ENDIF #
