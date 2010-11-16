		# IF C_ERROR_HANDLER #
			<span id="errorh"></span>
			<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
				<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
			</div>
		# ENDIF #
			
		<table class="module_table">
			<tr>
				<th colspan="2">
					{L_FAVORITES}
				</th>
			</tr>			
			<tr>
				<td class="row1" style="text-align:center;">
					{L_TITLE}
				</td>
				<td class="row2" style="text-align:center; width:100px;">
					{L_UNTRACK}
				</td>
			</tr>
			# START no_favorite #
			<tr>
				<td colspan="2" class="row2" style="text-align:center;">
					<br />
					<div class="notice">{no_favorite.L_NO_FAVORITE}</div>
				</td>
			</tr>
			# END no_favorite #	
			# START list #
			<tr>
				<td class="row1" style="text-align:center;">
					<a href="{list.U_ARTICLE}">{list.ARTICLE}</a>
				</td>
				<td class="row2" style="text-align:center;">
					{list.ACTIONS}
				</td>
			</tr>
			# END list #
		</table>
		