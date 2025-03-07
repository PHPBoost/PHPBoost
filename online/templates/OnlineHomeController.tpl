<section id="module-online">
	<header class="section-header">
		<h1>{@online.module.title}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<article class="online-item several-items">
				<div class="content responsive-table">
					<table class="table">
						<thead>
							<tr>
								<th>
									{@user.avatar}
								</th>
								<th>
									{@user.display.name}
								</th>
								<th>
									{@user.rank}
								</th>
								<th>
									{@common.location}
								</th>
								<th>
									{@user.last.connection}
								</th>
							</tr>
						</thead>
						<tbody>
							# START items #
								# IF C_USERS #
									<tr>
										<td>
											# IF items.C_AVATAR #<img src="{items.U_AVATAR}" class="online-avatar" alt="{@user.avatar}" /># ENDIF #
										</td>
										<td>
											# IF items.C_ROBOT #
												<span class="{items.LEVEL_CLASS}">{items.PSEUDO}</span>
											# ELSE #
												<a href="{items.U_PROFILE}" class="{items.LEVEL_CLASS} offload" # IF items.C_GROUP_COLOR # style="color:{items.GROUP_COLOR}" # ENDIF #>{items.PSEUDO}</a>
											# ENDIF #
										</td>
										<td>{items.LEVEL}</td>
										<td>
											<a class="offload" href="{items.U_LOCATION}">{items.LOCATION_TITLE}</a>
										</td>
										<td>
											{items.DATE_AGO}
										</td>
									</tr>
								# ELSE #
									<tr>
										<td colspan="3">
											{@common.no.item.now}
										</td>
									</tr>
								# ENDIF #
							# END items #
						</tbody>
						# IF C_PAGINATION #
							<tfoot>
								<tr>
									<td colspan="3"># INCLUDE PAGINATION #</td>
								</tr>
							</tfoot>
						# ENDIF #
					</table>
				</div>
			</article>

		</div>
	</div>
	<footer></footer>
</section>
