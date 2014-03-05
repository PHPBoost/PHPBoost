		# INCLUDE message_helper #
		
		# IF NO_FAVORITE #
			# START no_favorite #
				<div class="notice">{no_favorite.L_NO_FAVORITE}</div>
			# END no_favorite #	
		# ELSE #
		{L_FAVORITES}
			<table>
				<thead>
					<tr>
						<th>
							{L_TITLE}
						</th>
						<th>
							{L_UNTRACK}
						</th>
					</tr>
				</thead>
				<tbody>
					# START list #
					<tr>
						<td>
							<a href="{list.U_ARTICLE}">{list.ARTICLE}</a>
						</td>
						<td>
							{list.ACTIONS}
						</td>
					</tr>
					# END list #
				</tbody>
			</table>
		# ENDIF #