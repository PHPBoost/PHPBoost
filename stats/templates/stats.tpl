	<section id="module-stats">

		<header class="section-header">
			<h1>{@stats.module.title}</h1>
		</header>
		<div class="sub-section">
			<nav id="menustats">
				<a href="#" class="js-menu-button" onclick="open_submenu('menustats');return false;" aria-label="${LangLoader::get_message('categories', 'categories-common')}">
					<i class="fa fa-fw fa-bars" aria-hidden="true"></i> ${LangLoader::get_message('categories', 'categories-common')}
				</a>
				<ul>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_SITE}#stats" aria-label="{@site}">
							<i class="fa fa-fw fa-home" aria-hidden="true"></i> <span>{@site}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_USERS}#stats" aria-label="{@member_s}">
							<i class="fa fa-fw fa-users" aria-hidden="true"></i> <span>{@member_s}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_VISIT}#stats" aria-label="{@guest_s}">
							<i class="fa fa-fw fa-eye" aria-hidden="true"></i> <span>{@guest_s}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_PAGES}#stats" aria-label="{@page.s}">
							<i class="far fa-fw fa-file" aria-hidden="true"></i> <span>{@page.s}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_BROWSER}#stats" aria-label="{@browser.s}">
							<i class="fa fa-fw fa-globe" aria-hidden="true"></i> <span>{@browser.s}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_OS}#stats" aria-label="{@os}">
							<i class="fa fa-fw fa-laptop" aria-hidden="true"></i> <span>{@os}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_LANG}#stats" aria-label="{@stat.lang}">
							<i class="fa fa-fw fa-flag" aria-hidden="true"></i> <span>{@stat.lang}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_REFERER}#stats" aria-label="{@referer.s}">
							<i class="fa fa-fw fa-share-square" aria-hidden="true"></i> <span>{@referer.s}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_KEYWORD}#stats" aria-label="{@keyword.s}">
							<i class="fa fa-fw fa-key" aria-hidden="true"></i> <span>{@keyword.s}</span>
						</a>
					</li>
					<li>
						<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_ROBOTS}#stats" aria-label="{@robot_s}">
							<i class="fa fa-fw fa-search" aria-hidden="true"></i> <span>{@robot_s}</span>
						</a>
					</li>
				</ul>
			</nav>

			# IF C_STATS_SITE #
				<article class="content">
					<header>
						<h2>{@site}</h2>
					</header>
					<table class="table">
						<thead>
							<tr>
								<th>
									{@start}
								</th>
								<th>
									{@version} PHPBoost
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
						<h2>{@member_s}</h2>
					</header>
					<table class="table">
						<thead>
							<tr>
								<th>
									{@member_s}
								</th>
								<th>
									{@last.member}
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									{USERS}
								</td>
								<td>
									<a href="{U_LAST_USER_PROFILE}" class="{LAST_USER_LEVEL_CLASS}" # IF C_LAST_USER_GROUP_COLOR # style="color:{LAST_USER_GROUP_COLOR}" # ENDIF #>{LAST_USER}</a>
								</td>
							 </tr>
						</tbody>
					</table>

					<h3>{@theme_s}</h3>
					<div class="align-center">
						{GRAPH_RESULT_THEME}
					</div>
					<table class="table">
						<thead>
							<tr>
								<th>
									{@theme_s}
								</th>
								<th>
									{@colors}
								</th>
								<th>
									{@member_s}
								</th>
							</tr>
						</thead>
						<tbody>
							# START templates #
								<tr>
									<td>
										<div class="flex-between">{templates.THEME} <span>{templates.PERCENT}%</span></div>
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
						<h3>{@sex}</h3>
						<div class="align-center">
							{GRAPH_RESULT_SEX}
						</div>
						<table class="table">
							<thead>
								<tr>
									<th>
										{@sex}
									</th>
									<th>
										{@colors}
									</th>
									<th>
										{@member_s}
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

					<h3>{@top.10.posters}</h3>
					<table class="table">
						<thead>
							<tr>
								<th>
									N&deg;
								</th>
								<th>
									${LangLoader::get_message('display_name', 'user-common')}
								</th>
								<th>
									{@message_s}
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
							<h2>{@guest_s}</h2>
						</header>
						<div class="cell-flex cell-tile">
							<div class="cell">
								<div class="cell-header">
									<span class="cell-name">{@total}: {VISIT_TOTAL}</span><span>{@today}: {VISIT_DAY}</span>
								</div>
								<div class="cell-form grouped-inputs">
									<a class="grouped-element" href="stats{U_PREVIOUS_LINK}#stats" aria-label="${LangLoader::get_message('previous', 'common')}"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
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
									<a class="grouped-element" href="stats{U_NEXT_LINK}#stats" aria-label="${LangLoader::get_message('next', 'common')}"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
								</div>
								<div class="cell-body">
									<div class="cell-content align-center">
										<input type="hidden" name="{TYPE}" value="1">
										<button type="submit" name="date" value="true" class="button submit">{@submit}</button>
										<input type="hidden" name="token" value="{TOKEN}">
									</div>
								</div>
								<div class="cell-list">
									<ul>
										# IF U_YEAR #<li>{MONTH} {U_YEAR}</li># ENDIF #
										<li class="li-stretch">
											<span>{@total}: {SUM_NBR}</span>
											<span>{@average}: {MOY_NBR}</span>
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
										{@date}
									</th>
									<th>
										{@guest_s}
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
						<h2>{@browser.s}</h2>
					</header>
					<div class="align-center">
						{GRAPH_RESULT}
					</div>
					<table class="table">
						<thead>
							<tr>
								<th>{@browser.s}</th>
								<th>{@colors}</th>
								<th>{@percentage}</th>
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
						<h2>{@os}</h2>
					</header>
					<div class="align-center">
						{GRAPH_RESULT}
					</div>
					<table class="table">
						<thead>
							<tr>
								<th>{@os}</th>
								<th>{@colors}</th>
								<th>{@percentage}</th>
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
						<h2>{@stat.lang}</h2>
					</header>
					<div class="align-center">
						{GRAPH_RESULT}
					</div>
					<table class="table">
						<thead>
							<tr>
								<th>{@stat.lang}</th>
								<th>{@colors}</th>
								<th>{@percentage}</th>
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
						<h2>{@referer.s}</h2>
					</header>
					<table class="table">
						<thead>
							<tr>
								<th>
									{@referer.s}
								</th>
								<th class="total-head">
									{@total.visit}
								</th>
								<th class="average-head">
									{@average.visit}
								</th>
								<th class="last-update-head">
									{@last.update}
								</th>
								<th class="trend-head">
									{@trend}
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
								{@no.referer}
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
						<h2>{@keyword.s}</h2>
					</header>
					<table class="table">
						<thead>
							<tr>
								<th>
									{@keyword.s}
								</th>
								<th class="total-head">
									{@total.visit}
								</th>
								<th class="average-head">
									{@average.visit}
								</th>
								<th class="last-update-head">
									{@last.update}
								</th>
								<th class="trend-head">
									{@trend}
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
								{@no.keyword}
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

			# IF C_STATS_ROBOTS #
				<form action="stats.php?bot=1#stats" name="form" method="post" onsubmit="return check_form();">
					<article class="content">
						<header>
							<h2>{@robot_s}</h2>
						</header>
						# IF C_ROBOTS_DATA #
							<div class="align-center">
								<img class="fieldset-img" src="display_stats.php?bot=1" alt="{@robot_s}" />
							</div>
						# ENDIF #
						<table class="table">
							<thead>
								<tr>
									<th>
										{@robot_s}
									</th>
									<th>
										{@colors}
									</th>
									<th>
										{@number.r.visit}
									</th>
								</tr>
							</thead>
							<tbody>
								# START list #
									<tr>
										<td>
											 {list.L_NAME}  <span class="smaller">({list.PERCENT}%)</span>
										</td>
										<td>
											<div class="stats-color-square" style="background-color: {list.COLOR};"></div>
										</td>
										<td>
											{list.VIEWS}
										</td>
									</tr>
								# END list #
								# IF NOT C_ROBOTS_DATA #
								<tr>
									<td colspan="3">
									${LangLoader::get_message('no_item_now', 'common')}
									</td>
								</tr>
								# ENDIF #
							</tbody>
						</table>
						# IF C_ROBOTS_DATA #
						<fieldset class="fieldset-submit">
							<legend>{@erase.rapport}</legend>
							<div class="fieldset-inset">
								<button type="submit" name="erase" value="true" class="button submit">{@erase.rapport}</button>
								<input type="hidden" name="token" value="{TOKEN}">
							</div>
						</fieldset>
						# ENDIF #
					</article>
				</form>
			# ENDIF #
		</div>
		<footer></footer>
	</section>
