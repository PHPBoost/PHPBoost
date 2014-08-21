# IF C_SUSCRIBE #
<menu class="dynamic-menu group center">
	<ul>
		<li>
			<a href="${relative_url(NewsletterUrlBuilder::subscribe())}">{@newsletter.subscribe_newsletters}</a> 
		</li>
		<li>
			<a href="${relative_url(NewsletterUrlBuilder::unsubscribe())}">{@newsletter.unsubscribe_newsletters}</a>
		</li>
	</ul>
</menu>

<div class="spacer">&nbsp;</div>
# ENDIF #

<table>
	<thead>
		<tr> 
			<th>
			</th>
			<th>
				${LangLoader::get_message('category.form.name', 'categories-common')}
			</th>
			<th>
				${LangLoader::get_message('category.form.description', 'categories-common')}
			</th>
			<th>
				{@newsletter.archives}
			</th>
			<th>
				{@newsletter.subscribers}
			</th>
		</tr>
	</thead>
	# IF C_PAGINATION #
	<tfoot>
		<tr>
			<th colspan="5">
				# INCLUDE PAGINATION #
			</th>
		</tr>
	</tfoot>
	# ENDIF #
	<tbody>
	# IF C_STREAMS #
		# START streams_list #
		<tr>
			<td> 
				<img src="{streams_list.IMAGE}" alt="" />
			</td>
			<td>
				{streams_list.NAME}
			</td>
			<td>
				{streams_list.DESCRIPTION}
			</td>
			<td>
				# IF streams_list.C_VIEW_ARCHIVES #<a href="{streams_list.U_VIEW_ARCHIVES}">{@newsletter.view_archives}</a># ELSE #{@newsletter.not_level}# ENDIF #
			</td>
			<td>
				# IF streams_list.C_VIEW_SUBSCRIBERS #<a href="{streams_list.U_VIEW_SUBSCRIBERS}">{@newsletter.view_subscribers}</a># ELSE #{@newsletter.not_level}# ENDIF #
			</td>
		</tr>
		# END streams_list #
	# ELSE #
		<tr>
			<td colspan="5">
				<span class="text-strong">{@newsletter.no_newsletters}</span>
			</td>
		</tr>
	# ENDIF #
	</tbody>
</table>