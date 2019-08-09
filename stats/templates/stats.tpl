	<section id="module-stats">

		<header>
			<h1>{L_STATS}</h1>
		</header>

		<nav id="menustats">
			<a href="" class="js-menu-button" onclick="open_submenu('menustats');return false;" aria-label="${LangLoader::get_message('categories', 'categories-common')}">
				<i class="fa fa-bars" aria-hidden="true"></i> ${LangLoader::get_message('categories', 'categories-common')}
			</a>
			<ul>
				<li>
					<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_SITE}#stats" aria-label="{L_SITE}">
						<i class="fa fa-home" aria-hidden="true"></i> <span>{L_SITE}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_USERS}#stats" aria-label="{L_USERS}">
						<i class="fa fa-users" aria-hidden="true"></i> <span>{L_USERS}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_VISIT}#stats" aria-label="{L_VISITS}">
						<i class="fa fa-eye" aria-hidden="true"></i> <span>{L_VISITS}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_PAGES}#stats" aria-label="{L_PAGES}">
						<i class="far fa-file" aria-hidden="true"></i> <span>{L_PAGES}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_BROWSER}#stats" aria-label="{L_BROWSERS}">
						<i class="fa fa-globe" aria-hidden="true"></i> <span>{L_BROWSERS}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_OS}#stats" aria-label="{L_OS}">
						<i class="fa fa-laptop" aria-hidden="true"></i> <span>{L_OS}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_LANG}#stats" aria-label="{L_LANG}">
						<i class="fa fa-flag" aria-hidden="true"></i> <span>{L_LANG}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_REFERER}#stats" aria-label="{L_REFERER}">
						<i class="fa fa-share-square" aria-hidden="true"></i> <span>{L_REFERER}</span>
					</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/stats/stats{U_STATS_KEYWORD}#stats" aria-label="{L_KEYWORD}">
						<i class="fa fa-key" aria-hidden="true"></i> <span>{L_KEYWORD}</span>
					</a>
				</li>
			</ul>
		</nav>

		# IF C_STATS_SITE #
		<table class="table">
			<thead>
				<tr>
					<th>
						{L_SITE}
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						{L_START}: <strong>{START}</strong>
					</td>
				</tr>
				<tr>
					<td>
						{L_VERSION} PHPBoost: <strong>{VERSION}</strong>
					</td>
				</tr>
			</tbody>
		</table>
		# ENDIF #

		# IF C_STATS_USERS #
		<article>
			<header>
				<h2>{L_USERS}</h2>
			</header>

			<table class="table">
				<thead>
					<tr>
						<th>
							{L_USERS}
						</th>
						<th>
							{L_LAST_USER}
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

			<div class="elements-container">
				<h3>{L_TEMPLATES}</h3>
				<div class="block">
					{GRAPH_RESULT_THEME}
				</div>
				<div class="block">
					<table class="table">
						<thead>
							<tr>
								<th>
									{L_TEMPLATES}
								</th>
								<th>
									{L_COLORS}
								</th>
								<th>
									{L_USERS}
								</th>
							</tr>
						</thead>
						<tbody>
							# START templates #
							<tr>
								<td>
									{templates.THEME} <span class="smaller">({templates.PERCENT}%)</span>
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
				</div>
			</div>
			# IF C_DISPLAY_SEX #
			<div class="spacer"></div>

			<div class="elements-container">
				<h3>{L_SEX}</h3>
				<div class="block">
					{GRAPH_RESULT_SEX}
				</div>
				<div class="block">
					<table class="table">
						<thead>
							<tr>
								<th>
									{L_SEX}
								</th>
								<th>
									{L_COLORS}
								</th>
								<th>
									{L_USERS}
								</th>
							</tr>
						</thead>
						<tbody>
							# START sex #
							<tr>
								<td>
									{sex.SEX} <span class="smaller">({sex.PERCENT}%)</span>
								</td>
								<td>
									<div class="stats-color-square" style="background-color: {sex.COLOR};"></div>
								</td>
								<td>
									{sex.NBR_MBR}
								</td>
							</tr>
							# END sex #
						</tbody>
					</table>
				</div>
			</div>
			# ENDIF #

			<div class="spacer"></div>

			<table class="table">
				<thead>
					<tr>
						<th colspan="3">
							{L_TOP_TEN_POSTERS}
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							N&deg;
						</td>
						<td>
							{L_PSEUDO}
						</td>
						<td>
							{L_MSG}
						</td>
					</tr>
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
			<article>
				<header>
					<h2>{L_VISITORS}</h2>
				</header>
				<div class="elements-container">
					<div class="block block-xlarge">
						# IF U_YEAR #<div class="date-selector-filter">{MONTH} {U_YEAR}</div># ENDIF #
						<div class="date-selector-container">
							<p class="text-strong">{L_TOTAL}: {VISIT_TOTAL} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {L_TODAY}: {VISIT_DAY}</p>
							<a href="stats{U_PREVIOUS_LINK}#stats" aria-label="${LangLoader::get_message('previous', 'common')}"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
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

							&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="stats{U_NEXT_LINK}#stats" aria-label="${LangLoader::get_message('next', 'common')}"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
							<p>
								<input type="hidden" name="{TYPE}" value="1">
								<input type="hidden" name="token" value="{TOKEN}">
								<button type="submit" name="date" value="true" class="submit">{L_SUBMIT}</button>
							</p>
						</div>
					</div>
					<div class="block">
						# IF C_STATS_NO_GD #
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
									<td colspan="{COLSPAN}"></td>
								</tr>
							</tbody>
						</table>
						# ENDIF #

						{GRAPH_RESULT}
						<p class="center">{L_TOTAL}: {SUM_NBR}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{L_AVERAGE}: {MOY_NBR}</p>
						<p class="center">{U_VISITS_MORE}</p>
					</div>
					<div class="block">
						<table class="table">
							<thead>
								<tr>
									<th>
										{L_DAY}
									</th>
									<th>
										{L_VISITS_DAY}
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
					</div>
				</div>
				<div class="spacer"></div>
			</article>
		</form>
		# ENDIF #

		# IF C_STATS_BROWSERS #
		<article>
			<header>
				<h2>{L_BROWSERS}</h2>
			</header>
			<div class="content elements-container">
				<div class="block">
					{GRAPH_RESULT}
				</div>
				<div class="block">
					<table class="table">
						<thead>
							<tr>
								<th>{L_BROWSERS}</th>
								<th>{L_COLORS}</th>
								<th>{L_PERCENTAGE}</th>
							</tr>
						</thead>
						<tbody>
							# START list #
							<tr>
								<td class="no-separator">
									{list.IMG}
								</td>
								<td class="no-separator">
									<div class="stats-color-square" style="background-color: {list.COLOR};"></div>
								</td>
								<td class="no-separator">
									{list.L_NAME} <span class="smaller">({list.PERCENT}%)</span>
								</td>
							</tr>
							# END list #
						</tbody>
					</table>
				</div>
			</div>
			<div class="spacer"></div>

		</article>
		# ENDIF #

		# IF C_STATS_OS #
		<article>
			<header>
				<h2>{L_OS}</h2>
			</header>
			<div class="elements-container">
				<div class="block">
					{GRAPH_RESULT}
				</div>
				<div class="block">
					<table class="table">
						<thead>
							<tr>
								<th>{L_OS}</th>
								<th>{L_COLORS}</th>
								<th>{L_PERCENTAGE}</th>
							</tr>
						</thead>
						<tbody>
							# START list #
							<tr>
								<td class="no-separator">
									{list.IMG}
								</td>
								<td class="no-separator">
									<div class="stats-color-square" style="background-color: {list.COLOR};"></div>
								</td>
								<td class="no-separator">
									{list.L_NAME} <span class="smaller">({list.PERCENT}%)</span>
								</td>
							</tr>
							# END list #
						</tbody>
					</table>
				</div>
			</div>
			<div class="spacer"></div>
		</article>

		# ENDIF #

		# IF C_STATS_LANG #
		<article>
			<header>
				<h2>{L_LANG}</h2>
			</header>
			<div class="elements-container">
				<div class="block">
					{GRAPH_RESULT}
				</div>
				<div class="block">
					<table class="table">
						<thead>
							<tr>
								<th>{L_LANG}</th>
								<th>{L_COLORS}</th>
								<th>{L_PERCENTAGE}</th>
							</tr>
						</thead>
						<tbody>
							# START list #
							<tr>
								<td class="no-separator">
									{list.IMG}
								</td>
								<td class="no-separator">
									<div class="stats-color-square" style="background-color: {list.COLOR};"></div>
								</td>
								<td class="no-separator">
									{list.L_NAME} <span class="smaller">({list.PERCENT}%)</span>
								</td>
							</tr>
							# END list #
						</tbody>
					</table>
				</div>
			</div>
			<div class="spacer"></div>
		</article>
		# ENDIF #

		# IF C_STATS_REFERER #
		<script>
		<!--
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
		-->
		</script>

		<article>
			<header>
				<h2>{L_REFERER}</h2>
			</header>
			<table class="table">
				<thead>
					<tr>
						<th>
							{L_REFERER}
						</th>
						<th class="total-head">
							{L_TOTAL_VISIT}
						</th>
						<th class="average-head">
							{L_AVERAGE_VISIT}
						</th>
						<th class="last-update-head">
							{L_LAST_UPDATE}
						</th>
						<th class="trend-head">
							{L_TREND}
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
						{L_NO_REFERER}
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
		<!--
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
		-->
		</script>

		<article>
			<header>
				<h2>{L_KEYWORD}</h2>
			</header>
			<table class="table">
				<thead>
					<tr>
						<th>
							{L_KEYWORD}
						</th>
						<th class="total-head">
							{L_TOTAL_VISIT}
						</th>
						<th class="average-head">
							{L_AVERAGE_VISIT}
						</th>
						<th class="last-update-head">
							{L_LAST_UPDATE}
						</th>
						<th class="trend-head">
							{L_TREND}
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
						{L_NO_KEYWORD}
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
	</section>
