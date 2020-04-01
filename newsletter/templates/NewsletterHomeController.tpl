# IF C_SUSCRIBE #
	<nav id="cssmenu-newsletter-actions" class="cssmenu cssmenu-group">
		<ul>
			<li>
				<a href="${relative_url(NewsletterUrlBuilder::subscribe())}" class="cssmenu-title">
					<i class="fa fa-sign-in-alt" aria-hidden="true"></i>
					<span>{@newsletter.subscribe_newsletters}</span>
				</a>
			</li>
			<li>
				<a href="${relative_url(NewsletterUrlBuilder::unsubscribe())}" class="cssmenu-title">
					<i class="fa fa-sign-out-alt" aria-hidden="true"></i>
					<span>{@newsletter.unsubscribe_newsletters}</span>
				</a>
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
# ENDIF #

# IF NOT C_STREAMS #
	<div class="message-helper bgc notice">{@newsletter.no_newsletters}</div>
# ELSE #
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
				<th class="col-small" aria-label="${LangLoader::get_message('form.thumbnail', 'common')}">
					<i class="far fa-fw fa-image hidden-small-screens" aria-hidden="true"></i>
					<span class="hidden-large-screens">${LangLoader::get_message('form.thumbnail', 'common')}</span>
				</th>
			</tr>
		</thead>
		<tbody>
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
						# IF streams_list.C_THUMBNAIL #<img src="{streams_list.U_THUMBNAIL}" alt="{streams_list.NAME}" /># ENDIF #
					</td>
				</tr>
			# END streams_list #
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
# ENDIF #
