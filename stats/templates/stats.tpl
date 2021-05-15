<section id="module-stats">
	<header class="section-header">
		<h1>{@stats.module.title}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<nav id="menustats">
				<a href="#" class="js-menu-button" onclick="open_submenu('menustats');return false;" aria-label="${LangLoader::get_message('categories', 'categories-common')}">
					<i class="fa fa-fw fa-bars" aria-hidden="true"></i> ${LangLoader::get_message('category.categories', 'category-lang')}
				</a>
				<ul>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_SITE}#stats" aria-label="{@stats.website}">
							<i class="fa fa-fw fa-home" aria-hidden="true"></i> <span>{@stats.website}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_USERS}#stats" aria-label="{@user.members}">
							<i class="fa fa-fw fa-users" aria-hidden="true"></i> <span>{@user.members}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_VISIT}#stats" aria-label="{@user.guests}">
							<i class="fa fa-fw fa-eye" aria-hidden="true"></i> <span>{@user.guests}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_PAGES}#stats" aria-label="{@common.pages}">
							<i class="far fa-fw fa-file" aria-hidden="true"></i> <span>{@common.pages}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_BROWSER}#stats" aria-label="{@stats.browsers}">
							<i class="fa fa-fw fa-globe" aria-hidden="true"></i> <span>{@stats.browsers}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_OS}#stats" aria-label="{@stats.os}">
							<i class="fa fa-fw fa-laptop" aria-hidden="true"></i> <span>{@stats.os}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_LANG}#stats" aria-label="{@stats.countries}">
							<i class="fa fa-fw fa-flag" aria-hidden="true"></i> <span>{@stats.countries}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_REFERER}#stats" aria-label="{@stats.referers}">
							<i class="fa fa-fw fa-share-square" aria-hidden="true"></i> <span>{@stats.referers}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_KEYWORD}#stats" aria-label="{@common.keywords}">
							<i class="fa fa-fw fa-key" aria-hidden="true"></i> <span>{@common.keywords}</span>
						</a>
					</li>
					# IF IS_ADMIN #
						<li>
							<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_ROBOTS}#stats" aria-label="{@stats.robots}">
								<i class="fa fa-fw fa-search" aria-hidden="true"></i> <span>{@stats.robots}</span>
							</a>
						</li>
					# ENDIF #
				</ul>
			</nav>

			# IF C_STATS_SITE #
				<article class="content">
					<header>
						<h2>{@stats.website}</h2>
					</header>
					<table class="table">
						<thead>
							<tr>
								<th>
									{@stats.website.creation.date}
								</th>
								<th>
									{@stats.phpboost.version}
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									{START}
								</td>
								<td>
									{VERSION}
								</td>
							</tr>
						</tbody>
					</table>
				</article>
			# ENDIF #

			# IF C_STATS_USERS #
				<article class="content">
					<header>
						<h2>{@user.members}</h2>
					</header>
					<table class="table">
						<thead>
							<tr>
								<th>
									{@user.members}
								</th>
								<th>
									{@stats.last.member}
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									{USERS_NUMBER}
								</td>
								<td>
									<a href="{U_LAST_USER_PROFILE}" class="{LAST_USER_LEVEL_CLASS}" # IF C_LAST_USER_GROUP_COLOR # style="color:{LAST_USER_GROUP_COLOR}" # ENDIF #>{LAST_USER_DISPLAY_NAME}</a>
								</td>
							 </tr>
						</tbody>
					</table>

					<h3>{@common.themes}</h3>
					<div class="align-center">
						{GRAPH_RESULT_THEME}
					</div>
					<table class="table">
						<thead>
							<tr>
								<th>
									{@common.themes}
								</th>
								<th>
									{@common.percentage}
								</th>
								<th>
									{@common.colors}
								</th>
								<th>
									{@user.members}
								</th>
							</tr>
						</thead>
						<tbody>
							# START templates #
								<tr>
									<td>
										{templates.THEME}
									</td>
									<td>
										{templates.PERCENT}%
									</td>
									<td>
										<div class="stats-color-square" style="background-color: {templates.COLOR};"></div>
									</td>
									<td>
										{templates.NBR_THEME}
									</td>
								</tr>
							# END templates #
						</tbody>
					</table>

					# IF C_DISPLAY_SEX #
						<h3>{@user.sex}</h3>
						<div class="align-center">
							{GRAPH_RESULT_SEX}
						</div>
						<table class="table">
							<thead>
								<tr>
									<th>
										{@user.sex}
									</th>
									<th>
										{@common.colors}
									</th>
									<th>
										{@user.members}
									</th>
								</tr>
							</thead>
							<tbody>
								# START sex #
									<tr>
										<td>
											<div class="flex-between">{sex.SEX} <span>{sex.PERCENT}%</span></div>
										</td>
										<td>
											<div class="stats-color-square" style="background-color: {sex.COLOR};"></div>
										</td>
										<td>
											{sex.MEMBERS_NUMBER}
										</td>
									</tr>
								# END sex #
							</tbody>
						</table>
					# ENDIF #

					<h3>{@stats.top.10.contributors}</h3>
					<table class="table">
						<thead>
							<tr>
								<th>
									N&deg;
								</th>
								<th>
									{@user.display.name}
								</th>
								<th>
									{@common.messages}
								</th>
							</tr>
						</thead>
						<tbody>
							# START top_poster #
								<tr>
									<td>
										{top_poster.ID}
									</td>
									<td>
										<a href="{top_poster.U_USER_PROFILE}" class="{top_poster.USER_LEVEL_CLASS}" # IF top_poster.C_USER_GROUP_COLOR # style="color: {top_poster.USER_GROUP_COLOR}" # ENDIF #>{top_poster.LOGIN}</a>
									</td>
									<td>
										{top_poster.USER_POST}
									</td>
								</tr>
							# END top_poster #
						</tbody>
					</table>
				</article>
			# ENDIF #

			# IF C_STATS_VISIT #
				<form action="stats.php#stats" method="get">
					<article class="content">
						<header>
							<h2>{@user.guests}</h2>
						</header>
						<div class="cell-flex cell-tile">
							<div class="cell">
								<div class="cell-header">
									<span class="cell-name">{@common.total}: {VISIT_TOTAL}</span><span>{@date.today}: {VISIT_DAY}</span>
								</div>
								<div class="cell-form grouped-inputs">
									<a class="grouped-element" href="stats{U_PREVIOUS_LINK}#stats" aria-label="{@common.previous}"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
									# IF C_STATS_DAY #
										<select name="d">
											{STATS_DAY}
										</select>
									# ENDIF #
									# IF C_STATS_MONTH #
										<select name="m">
											{STATS_MONTH}
										</select>
									# ENDIF #
									# IF C_STATS_YEAR #
										<select name="y">
											{STATS_YEAR}
										</select>
									# ENDIF #
									<a class="grouped-element" href="stats{U_NEXT_LINK}#stats" aria-label="{@common.next}"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
								</div>
								<div class="cell-body">
									<div class="cell-content align-center">
										<input type="hidden" name="{TYPE}" value="1">
										<button type="submit" name="date" value="true" class="button submit">{@form.submit}</button>
										<input type="hidden" name="token" value="{TOKEN}">
									</div>
								</div>
								<div class="cell-list">
									<ul>
										# IF U_YEAR #<li>{MONTH} {U_YEAR}</li># ENDIF #
										<li class="li-stretch">
											<span>{@common.total}: {SUM_NBR}</span>
											<span>{@common.average}: {MOY_NBR}</span>
										</li>
									</ul>
								</div>
								# IF C_STATS_NO_GD #
									<div class="cell-table">
										<table class="table">
											<tbody>
												<tr>
													<td></td>
													<td>
														{MAX_NBR}
													</td>

													# START values #
														<td>
															<table>
																<tbody>
																	# START values.head #
																	<tr>
																		<td class="table-values-head">
																		</td>
																	</tr>
																	# END values.head #
																	<tr>
																		<td class="table-values" style="height: {values.HEIGHT}px;">
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													# END values #

													# START end_td #
														{end_td.END_TD}
													# END end_td #
												</tr>
												<tr>
													<td></td>
													<td>
														0
													</td>
													# START legend #
														<td>
															{legend.LEGEND}
														</td>
													# END legend #
												</tr>
												<tr>
													<td colspan="{COLSPAN}">{U_VISITS_MORE}</td>
												</tr>
											</tbody>
										</table>
									</div>
								# ELSE #
									<div class="cell-body">
										<div class="cell-thumbnail">{GRAPH_RESULT}</div>
										<div class="cell-conte align-center">{U_VISITS_MORE}</div>
									</div>
								# ENDIF #
							</div>
						</div>
						<table class="table">
							<thead>
								<tr>
									<th>
										{@date.date}
									</th>
									<th>
										{@user.guests}
									</th>
								</tr>
							</thead>
							<tbody>
								# START value #
									<tr>
										<td>
											{value.U_DETAILS}
										</td>
										<td>
											{value.NBR}
										</td>
									</tr>
								# END value #
							</tbody>
						</table>
					</article>
				</form>
			# ENDIF #

			# IF C_STATS_BROWSERS #
				<article class="content">
					<header>
						<h2>{@stats.browsers}</h2>
					</header>
					<div class="align-center">
						{GRAPH_RESULT}
					</div>
					<table class="table">
						<thead>
							<tr>
								<th>{@stats.browsers}</th>
								<th>{@common.colors}</th>
								<th>{@common.percentage}</th>
							</tr>
						</thead>
						<tbody>
							# START list #
								<tr>
									<td>
										{list.IMG}
									</td>
									<td>
										<div class="stats-color-square" style="background-color: {list.COLOR};"></div>
									</td>
									<td>
										{list.L_NAME} <span class="smaller">({list.PERCENT}%)</span>
									</td>
								</tr>
							# END list #
						</tbody>
					</table>
				</article>
			# ENDIF #

			# IF C_STATS_OS #
				<article class="content">
					<header>
						<h2>{@stats.os}</h2>
					</header>
					<div class="align-center">
						{GRAPH_RESULT}
					</div>
					<table class="table">
						<thead>
							<tr>
								<th>{@stats.os}</th>
								<th>{@common.colors}</th>
								<th>{@common.percentage}</th>
							</tr>
						</thead>
						<tbody>
							# START list #
								<tr>
									<td>
										{list.IMG}
									</td>
									<td>
										<div class="stats-color-square" style="background-color: {list.COLOR};"></div>
									</td>
									<td>
										{list.L_NAME} <span class="smaller">({list.PERCENT}%)</span>
									</td>
								</tr>
							# END list #
						</tbody>
					</table>
				</article>
			# ENDIF #

			# IF C_STATS_LANG #
				<article class="content">
					<header>
						<h2>{@stats.countries}</h2>
					</header>
					<div class="align-center">
						{GRAPH_RESULT}
					</div>
					<table class="table">
						<thead>
							<tr>
								<th>{@stats.countries}</th>
								<th>{@common.colors}</th>
								<th>{@common.percentage}</th>
							</tr>
						</thead>
						<tbody>
							# START list #
								<tr>
									<td>
										{list.IMG}
									</td>
									<td>
										<div class="stats-color-square" style="background-color: {list.COLOR};"></div>
									</td>
									<td>
										{list.L_NAME} <span class="smaller">({list.PERCENT}%)</span>
									</td>
								</tr>
							# END list #
						</tbody>
					</table>
				</article>
			# ENDIF #

			# IF C_STATS_REFERER #
				<script>
					function XMLHttpRequest_referer(divid)
					{
						if ( document.getElementById('url' + divid).style.display != 'none' )
						{
							jQuery('#url' + divid).fadeToggle();
							document.getElementById('img-url-' + divid).className = 'far fa-plus-square';
						}
						else
						{
							var xhr_object = null;
							var filename = '{PATH_TO_ROOT}/stats/ajax/stats_xmlhttprequest.php?token={TOKEN}&stats_referer=1&id=' + divid;
							var data = null;

							if (window.XMLHttpRequest) // Firefox
							   xhr_object = new XMLHttpRequest();
							else if (window.ActiveXObject) // Internet Explorer
							   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
							else // XMLHttpRequest non support? par le navigateur
								return;

							document.getElementById('load' + divid).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

							xhr_object.open("POST", filename, true);
							xhr_object.onreadystatechange = function()
							{
								if ( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
								{
									jQuery('#url' + divid).fadeToggle();
									document.getElementById('url' + divid).innerHTML = xhr_object.responseText;
									document.getElementById('load' + divid).innerHTML = '';
									document.getElementById('img-url-' + divid).className = 'far fa-minus-square';
								}
								else if ( xhr_object.readyState == 4 && xhr_object.responseText == '' )
									document.getElementById('load' + divid).innerHTML = '';
							}
							xmlhttprequest_sender(xhr_object, null);
						}
					}
				</script>

				<article class="content">
					<header>
						<h2>{@stats.referers}</h2>
					</header>
					<table class="table">
						<thead>
							<tr>
								<th>
									{@stats.referers}
								</th>
								<th class="total-head">
									{@stats.total.visits}
								</th>
								<th class="average-head">
									{@stats.average.visits}
								</th>
								<th class="last-update-head">
									{@stats.last.visit.date}
								</th>
								<th class="trend-head">
									{@stats.trend}
								</th>
							</tr>
						</thead>
						<tbody>
							# START referer_list #
								<tr>
									<td>
										<a class="far fa-plus-square" style="cursor: pointer;" onclick="XMLHttpRequest_referer({referer_list.ID})" id="img-url-{referer_list.ID}"></a> <span class="smaller">({referer_list.NBR_LINKS})</span> <a href="{referer_list.URL}">{referer_list.URL}</a>	<span id="load{referer_list.ID}"></span>
									</td>
									<td>
										{referer_list.TOTAL_VISIT}
									</td>
									<td>
										{referer_list.AVERAGE_VISIT}
									</td>
									<td>
										{referer_list.LAST_UPDATE}
									</td>
									<td>
										{referer_list.TREND}
									</td>
								</tr>
								<tr>
									<td colspan="5" >
										<div id="url{referer_list.ID}" style="display: none;width: 100%;"></div>
									</td>
								</tr>
							# END referer_list #
							# IF NOT C_REFERERS #
								<tr>
									<td colspan="5">
										<div class="message-helper bgc notice">{@common.no.item.now}</div>
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
				</article>
			# ENDIF #

			# IF C_STATS_KEYWORD #
				<script>
					function XMLHttpRequest_referer(divid)
					{
						if ( document.getElementById('url' + divid).style.display != 'none' )
						{
							jQuery('#url' + divid).fadeToggle();
							document.getElementById('img-url-' + divid).className = 'far fa-plus-square';
						}
						else
						{
							document.getElementById('load' + divid).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
							var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/stats/ajax/stats_xmlhttprequest.php?token={TOKEN}&stats_keyword=1&id=' + divid);
							xhr_object.onreadystatechange = function()
							{
								if ( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
								{
									jQuery('#url' + divid).fadeToggle();
									document.getElementById('url' + divid).innerHTML = xhr_object.responseText;
									document.getElementById('load' + divid).innerHTML = '';
									document.getElementById('img-url-' + divid).className = 'far fa-minus-square';
								}
								else if ( xhr_object.readyState == 4 && xhr_object.responseText == '' )
									document.getElementById('load' + divid).innerHTML = '';
							}
							xmlhttprequest_sender(xhr_object, null);
						}
					}
				</script>
				<article class="content">
					<header>
						<h2>{@common.keywords}</h2>
					</header>
					<table class="table">
						<thead>
							<tr>
								<th>
									{@common.keywords}
								</th>
								<th class="total-head">
									{@stats.total.visits}
								</th>
								<th class="average-head">
									{@stats.average.visits}
								</th>
								<th class="last-update-head">
									{@stats.last.visit.date}
								</th>
								<th class="trend-head">
									{@stats.trend}
								</th>
							</tr>
						</thead>
						<tbody>
							# START keyword_list #
								<tr>
									<td>
										<a class="far fa-plus-square" style="cursor: pointer;" onclick="XMLHttpRequest_referer({keyword_list.ID})" id="img-url-{keyword_list.ID}"></a> <span class="smaller">({keyword_list.NBR_LINKS})</span> {keyword_list.KEYWORD} <span id="load{keyword_list.ID}"></span>
									</td>
									<td>
										{keyword_list.TOTAL_VISIT}
									</td>
									<td>
										{keyword_list.AVERAGE_VISIT}
									</td>
									<td>
										{keyword_list.LAST_UPDATE}
									</td>
									<td>
										{keyword_list.TREND}
									</td>
								</tr>
								<tr>
									<td colspan="5">
										<div id="url{keyword_list.ID}" style="display: none;width: 100%;"></div>
									</td>
								</tr>
							# END keyword_list #
							# IF NOT C_KEYWORDS #
								<tr>
									<td colspan="5">
										<div class="message-helper bgc notice">{@common.no.item.now}</div>
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
				</article>
			# ENDIF #

			# IF IS_ADMIN #
				# IF C_STATS_ROBOTS #
					<form action="stats.php?bot=1#stats" name="form" method="post" onsubmit="return check_form();">
						<article class="content">
							<header>
								<h2>{@stats.robots}</h2>
							</header>
							# IF C_ROBOTS_DATA #
								<div class="align-center">
									<img class="fieldset-img" src="display_stats.php?bot=1" alt="{@stats.robots}" />
								</div>
							# ENDIF #
							<table class="table">
								<thead>
									<tr>
										<th>
											{@stats.robots}
										</th>
										<th>
											{@common.colors}
										</th>
										<th>
											{@common.visits.number}
										</th>
										<th>
											{@stats.last.visit}
										</th>
									</tr>
								</thead>
								<tbody>
									# START list #
										<tr>
											<td>
												# IF list.C_BOT_DETAILS #<a href="{list.U_BOT_DETAILS}"># ENDIF #{list.L_NAME}# IF list.C_BOT_DETAILS #</a># ENDIF # <span class="smaller">({list.PERCENT}%)</span>
											</td>
											<td>
												<div class="stats-color-square" style="background-color: {list.COLOR};"></div>
											</td>
											<td>
												{list.VISITS_NUMBER}
											</td>
											<td>
												{list.LAST_SEEN}
											</td>
										</tr>
									# END list #
									# IF NOT C_ROBOTS_DATA #
										<tr>
											<td colspan="4">
												<div class="message-helper bgc notice">{@common.no.item.now}</div>
											</td>
										</tr>
									# ENDIF #
								</tbody>
							</table>
							# IF C_ROBOTS_DATA #
								<fieldset class="fieldset-submit">
									<legend>{@stats.erase.list}</legend>
									<div class="fieldset-inset">
										<button type="submit" name="erase" value="true" class="button submit">{@stats.erase.list}</button>
										<button type="submit" name="erase-occasional" value="true" class="button submit">{@stats.erase.occasional}</button>
										<input type="hidden" name="token" value="{TOKEN}">
									</div>
								</fieldset>
							# ENDIF #
						</article>
					</form>
				# ENDIF #
			# ENDIF #
		</div>
	</div>
	<footer></footer>
</section>
