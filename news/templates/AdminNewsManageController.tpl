<table>
	<thead>
		<tr> 
			<th></th>
			<th>
				{@news.form.name} 
			</th>
			<th>
				{@news.form.category}
			</th>
			<th>
				${LangLoader::get_message('pseudo', 'main')}
			</th>
			<th>
				{@news.form.date.creation}
			</th>
			<th>
				{@news.form.approbation}
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="6">
				# INCLUDE PAGINATION #
			</th>
		</tr>
	</tfoot>
	<tbody>
		# START news #
			<tr>
				<td> 
					<a href="{news.EDIT_LINK}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
					<a href="{news.DELETE_LINK}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete" data-confirmation="delete-element"></a>
				</td>
				<td>
					{news.NAME}
				</td>
				<td>
					{news.CATEGORY}
				</td>
				<td>
					{news.PSEUDO}
				</td>
				<td>
					{news.DATE}
				</td>
				<td>
					{news.STATUS}
				</td>
			</tr>
		# END news #
	</tbody>
</table>