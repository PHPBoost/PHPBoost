# IF C_SUSCRIBE #
<nav id="cssmenu-newsletter-actions" class="cssmenu cssmenu-group">
	<ul>
		<li>
			<a href="${relative_url(NewsletterUrlBuilder::subscribe())}" class="cssmenu-title">{@newsletter.subscribe_newsletters}</a>
		</li>
		<li>
			<a href="${relative_url(NewsletterUrlBuilder::unsubscribe())}" class="cssmenu-title">{@newsletter.unsubscribe_newsletters}</a>
		</li>
	</ul>
</nav>
<script>
	jQuery("#cssmenu-newsletter-actions").menumaker({
		title: "${LangLoader::get_message('form.options', 'common')}",
		format: "multitoggle",
		breakpoint: 768
	});
</script>
<div class="spacer"></div>
# ENDIF #

<table class="table">
	<thead>
		<tr>
			<th>
				${LangLoader::get_message('form.name', 'common')}
			</th>
			<th>
				${LangLoader::get_message('form.description', 'common')}
			</th>
			<th>
				{@newsletter.archives}
			</th>
			<th>
				{@newsletter.subscribers}
			</th>
			<th>
			</th>
		</tr>
	</thead>
	<tbody>
	# IF C_STREAMS #
		# START streams_list #
		<tr>
			<td>
				{streams_list.NAME}
			</td>
			<td>
				{streams_list.DESCRIPTION}
			</td>
			<td>
				# IF streams_list.C_VIEW_ARCHIVES #<a href="{streams_list.U_VIEW_ARCHIVES}">{@newsletter.view_archives}</a># ELSE #${LangLoader::get_message('error.auth', 'status-messages-common')}# ENDIF #
			</td>
			<td>
				# IF streams_list.C_VIEW_SUBSCRIBERS #<a href="{streams_list.U_VIEW_SUBSCRIBERS}">{@newsletter.view_subscribers}</a> ({streams_list.SUBSCRIBERS_NUMBER})# ELSE #${LangLoader::get_message('error.auth', 'status-messages-common')}# ENDIF #
			</td>
			<td>
				<img src="{streams_list.IMAGE}" alt="{streams_list.NAME}" />
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
	# IF C_PAGINATION #
	<tfoot>
		<tr>
			<td colspan="5">
				# INCLUDE PAGINATION #
			</td>
		</tr>
	</tfoot>
	# ENDIF #
</table>
