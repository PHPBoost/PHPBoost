 # INCLUDE admin_download_menu #

<div id="admin_contents">

	<table class="module_table">
		<tr style="text-align: center;">
			<th style="width: 35%">{L_TITLE}</th>
			<th>{L_SIZE}</th>
			<th>{L_CATEGORY}</th>
			<th>{L_DATE}</th>
			<th>{L_APROB}</th>
			<th>{L_UPDATE}</th>
			<th>{L_DELETE}</th>
		</tr>

		# START list #

		<tr style="text-align: center;">
			<td class="row2"><a href="{list.U_FILE}">{list.TITLE}</a>
			</td>
			<td class="row2">{list.SIZE}</td>
			<td class="row2">{list.CAT}</td>
			<td class="row2">{list.DATE}</td>
			<td class="row2">{list.APROBATION} <br /> <span class="text_small">{list.VISIBLE}</span>
			</td>
			<td class="row2"><a href="{list.U_EDIT_FILE}"><img
					src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png"
					alt="{L_UPDATE}" title="{L_UPDATE}" /> </a>
			</td>
			<td class="row2"><a href="{list.U_DEL_FILE}"
				onclick="return confirm('{L_CONFIRM_DELETE}');"><img
					src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png"
					alt="{L_DELETE}" title="{L_DELETE}" /> </a>
			</td>
		</tr>
		# END list #

	</table>

	<br /> <br />
	<p style="text-align: center;">{PAGINATION}</p>
</div>
