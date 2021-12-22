# IF C_SUSCRIBE #
	<nav id="cssmenu-newsletter-actions" class="cssmenu cssmenu-group">
		<ul>
			<li>
				<a href="${relative_url(NewsletterUrlBuilder::subscribe())}" class="cssmenu-title offload">
					<i class="fa fa-sign-in-alt" aria-hidden="true"></i>
					<span>{@newsletter.subscribe.item}</span>
				</a>
			</li>
			<li>
				<a href="${relative_url(NewsletterUrlBuilder::unsubscribe())}" class="cssmenu-title offload">
					<i class="fa fa-sign-out-alt" aria-hidden="true"></i>
					<span>{@newsletter.unsubscribe.item}</span>
				</a>
			</li>
		</ul>
	</nav>
	<script>
		jQuery("#cssmenu-newsletter-actions").menumaker({
			title: "{@common.options}",
			format: "multitoggle",
			breakpoint: 768
		});
	</script>
# ENDIF #

# IF NOT C_STREAMS #
	<div class="message-helper bgc notice">{@common.no.item.now}</div>
# ELSE #
	<div class="responsive-table">
		<table class="table">
			<thead>
				<tr>
					<th>
						{@common.name}
					</th>
					<th>
						{@common.description}
					</th>
					<th>
						{@newsletter.archives}
					</th>
					<th>
						{@newsletter.subscribers.list}
					</th>
					<th class="col-small" aria-label="{@form.thumbnail}">
						<i class="far fa-fw fa-image hidden-small-screens" aria-hidden="true"></i>
						<span class="hidden-large-screens">{@form.thumbnail}</span>
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
							# IF streams_list.C_VIEW_ARCHIVES #<a class="offload" href="{streams_list.U_VIEW_ARCHIVES}">{@newsletter.see.archives}</a># ELSE #{@warning.auth}# ENDIF #
						</td>
						<td>
							# IF streams_list.C_VIEW_SUBSCRIBERS #<a class="offload" href="{streams_list.U_VIEW_SUBSCRIBERS}">{@newsletter.see.subscribers.list}</a> ({streams_list.SUBSCRIBERS_NUMBER})# ELSE #{@warning.auth}# ENDIF #
						</td>
						<td>
							# IF streams_list.C_THUMBNAIL #<img src="{streams_list.U_THUMBNAIL}" alt="{streams_list.NAME}" /># ENDIF #
						</td>
					</tr>
				# END streams_list #
			</tbody>
			# IF C_PAGINATION #
				# INCLUDE PAGINATION #
			# ENDIF #
		</table>

	</div>
# ENDIF #
