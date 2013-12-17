		# INCLUDE admin_download_menu #
		
		<div id="admin_contents">
			# INCLUDE message_helper #

			<table>
				<thead>
					<tr> 
						<th>{L_CATS_MANAGEMENT}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td> 
							{CATEGORIES}
						</td>
					</tr>
				</tbody>
			</table>
			
			<div style="text-align:center; margin:30px 20px;">
				<a href="{U_RECOUNT_SUBFILES}" title="{L_RECOUNT_SUBFILES}">
					<i class="icon-refresh icon-2x"></i>
				</a>
				<br />
				<a href="{U_RECOUNT_SUBFILES}">{L_RECOUNT_SUBFILES}</a>
			</div>
		</div>