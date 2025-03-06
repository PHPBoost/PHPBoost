<section id="module-wiki">
	<header class="section-header">
		<h1>{@wiki.tracked.items}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			# INCLUDE MESSAGE_HELPER #
			# IF C_TRACKED_ITEMS #
				<table class="table">
					<thead>
						<tr>
							<th>
								{@common.title}
							</th>
							<th class="col-medium">
								{@wiki.untrack}
							</th>
						</tr>
					</thead>
					<tbody>
						# START list #
							<tr>
								<td>
									<a class="offload" href="{list.U_ITEM}">{list.TITLE}</a>
								</td>
								<td>
									<a href="{list.U_UNTRACK}" aria-label="{@wiki.untrack}" data-confirmation="{@wiki.confirm.untrack}">
										<i class="fa fa-heart-broken" aria-hidden="true"></i>
									</a>
								</td>
							</tr>
						# END list #
					</tbody>
				</table>
			# ELSE #
				<div class="content"><div class="message-helper bgc notice">{@wiki.no.tracked.items}</div></div>
			# ENDIF #
		</div>
	</div>
	<footer></footer>
</section>
