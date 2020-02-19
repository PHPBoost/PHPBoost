	<section id="module-wiki-history" class="wiki-history">
		# IF C_ARTICLE #
			<header>
				<h1>{L_HISTORY} : <a href="{U_ARTICLE}">{TITLE}</a></h1>
			</header>
			<table class="table">
				<thead>
					<tr>
						<th>
							{L_VERSIONS}
						</th>
						<th>
							{L_DATE}
						</th>
						<th>
							{L_CHANGE_REASON}
						</th>
						<th>
							{L_AUTHOR}
						</th>
						<th>
							{L_ACTIONS}
						</th>
					</tr>
				</thead>
				<tbody>
					# START list #
					<tr>
						<td>
							<a href="{list.U_ARTICLE}">{list.TITLE}</a>
							<span>{list.CURRENT_RELEASE}</span>
						</td>
						<td>
							{list.DATE}
						</td>
						<td class="col-max">
							{list.CHANGE_REASON}
						</td>
						<td>
							{list.AUTHOR}
						</td>
						<td>
							{list.ACTIONS}
						</td>
					</tr>
					# END list #
				</tbody>
			</table>
		# ELSE #
			<header>
				<h1>{L_HISTORY}</h1>
			</header>

			<table class="table">
				<caption>{L_HISTORY}</caption>
				<thead>
					<tr>
						<th>
							<a href="{TOP_TITLE}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
							{L_TITLE}
							<a href="{BOTTOM_TITLE}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
						</th>
						<th>
							<a href="{TOP_DATE}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
							{L_DATE}
							<a href="{BOTTOM_DATE}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
						</th>
						<th>
							{L_AUTHOR}
						</th>
					</tr>
				</thead>
				<tbody>
					# START list #
					<tr>
						<td>
							<a href="{list.U_ARTICLE}">{list.TITLE}</a>
						</td>
						<td>
							{list.DATE}
						</td>
						<td>
							{list.AUTHOR}
						</td>
					</tr>
					# END list #
				</tbody>
				# IF C_PAGINATION #
				<tfoot>
					<tr>
						<td colspan="3">
							# INCLUDE PAGINATION #
						</td>
					</tr>
				</tfoot>
				# ENDIF #
			</table>
		# END IF #
		<footer></footer>
	</section>
