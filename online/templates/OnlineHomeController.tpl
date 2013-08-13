<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top">
		<div class="module_top_title">{@online} </div>
	</div>
	<div class="module_contents">
		<table class="module_table">
			<tr>
				<th colspan="3">
					{@online}
				</th>
			</tr>
			<tr>
				<td class="row2" style="width:33%">
					{L_LOGIN}
				</td>
				<td class="row2" style="width:34%">
					{@online.location}
				</td>
				<td class="row2" style="width:33%">
					{@online.last_update}
				</td>
			</tr>
			# START users #
			<tr>
				<td class="row3">
					<a href="{users.U_PROFILE}" class="{users.LEVEL_CLASS}" # IF users.C_GROUP_COLOR # style="color:{users.GROUP_COLOR}" # ENDIF #>{users.PSEUDO}</a>
				</td>
				<td class="row3">
					<a href="{users.U_LOCATION}">{users.TITLE_LOCATION}</a>
				</td>
				<td class="row3">
					{users.LAST_UPDATE}
				</td>
			</tr>
			# END users #
			<tr>
				<td colspan="8" class="row1">
					<span style="float:left;">{L_PAGE} : # INCLUDE PAGINATION #</span>
				</td>
			</tr>
		</table>
	</div>
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom"></div>
</div>