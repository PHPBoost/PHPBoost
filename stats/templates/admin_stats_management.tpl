	<div id="admin-contents" style="margin-left:0;padding:10px">
		<table>
			<thead>
				<tr>
					<th colspan="5" style="width: 20%;">
						{L_STATS}
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="no-separator">
						<a href="admin_stats.php?site=1#stats"><img src="{PATH_TO_ROOT}/stats/templates/images/site.png" alt="" /></a>
						<br /><a href="admin_stats.php?site=1#stats">{L_SITE}</a>
					</td>
					<td class="no-separator">
						<a href="admin_stats.php?members=1#stats"><img src="{PATH_TO_ROOT}/stats/templates/images/member.png" alt="" /></a>
						<br /><a href="admin_stats.php?members=1#stats">{L_USERS}</a>
					</td>
					<td class="no-separator">
						<a href="admin_stats.php?visit=1#stats"><img src="{PATH_TO_ROOT}/stats/templates/images/visitors.png" alt="" /></a>
						<br /><a href="admin_stats.php?visit=1#stats">{L_VISITS}</a>
					</td>
					<td class="no-separator">
						<a href="admin_stats.php?pages=1#stats"><img src="{PATH_TO_ROOT}/stats/templates/images/pages.png" alt="" /></a>
						<br /><a href="admin_stats.php?pages=1#stats">{L_PAGES}</a>
					</td>
					<td class="no-separator">
						<a href="admin_stats.php?browser=1#stats"><img src="{PATH_TO_ROOT}/stats/templates/images/browsers.png" alt="" /></a>
						<br /><a href="admin_stats.php?browser=1#stats">{L_BROWSERS}</a>
					</td>
				</tr>
				<tr>
					<td class="no-separator">
						<a href="admin_stats.php?os=1#stats"><img src="{PATH_TO_ROOT}/stats/templates/images/os.png" alt="" /></a>
						<br /><a href="admin_stats.php?os=1#stats">{L_OS}</a>
					</td>
					<td class="no-separator">
						<a href="admin_stats.php?lang=1#stats"><img src="{PATH_TO_ROOT}/stats/templates/images/countries.png" alt="" /></a>
						<br /><a href="admin_stats.php?lang=1#stats">{L_LANG}</a>
					</td>
					<td class="no-separator">
						<a href="admin_stats.php?referer=1#stats"><img src="{PATH_TO_ROOT}/stats/templates/images/referer.png" alt="" /></a>
						<br /><a href="admin_stats.php?referer=1#stats">{L_REFERER}</a>
					</td>
					<td class="no-separator">
						<a href="admin_stats.php?keyword=1#stats"><img src="{PATH_TO_ROOT}/stats/templates/images/keyword.png" alt="" /></a>
						<br /><a href="admin_stats.php?keyword=1#stats">{L_KEYWORD}</a>
					</td>
					<td class="no-separator">
						<a href="admin_stats.php?bot=1#stats"><img src="{PATH_TO_ROOT}/stats/templates/images/robots.png" alt="" /></a>
						<br /><a href="admin_stats.php?bot=1#stats">{L_ROBOTS}</a>
					</td>
				</tr>
			</tbody>
		</table>
		
		<br /><br />
		
		# IF C_STATS_SITE #
		<table>
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
						{L_KERNEL_VERSION} : <strong>{VERSION}</strong>
					</td>
				</tr>
			</tbody>
		</table>
		# ENDIF #
		
		
		# IF C_STATS_USERS #
		<table>
			<thead>
				<tr>
					<th colspan="2">
						{L_USERS}
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						{L_USERS}
					</td>
					<td>
						{USERS}
					</td>
				 </tr>
				<tr>
					<td>
						{L_LAST_USER}
					</td>
					<td>
						<a href="{U_LAST_USER_PROFILE}" class="{LAST_USER_LEVEL_CLASS}" # IF C_LAST_USER_GROUP_COLOR # style="color:{LAST_USER_GROUP_COLOR}" # ENDIF #>{LAST_USER}</a>
					</td>
				</tr>
			</tbody>
		</table>
		<br /><br />
		<table>
			<thead>
				<tr>
					<th colspan="2">
						{L_TEMPLATES}
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<table>
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
										<div style="margin:auto;width:10px;margin:auto;height:10px;background:{templates.COLOR};border:1px solid black;"></div>
									</td>
									<td>
										{templates.NBR_THEME}
									</td>
								</tr>
								# END templates #
							</tbody>
						</table>
					</td>
					<td class="no-separator">
						<img src="display_stats.php?theme=1" alt="" />
					</td>
				</tr>
			</tbody>
		</table>
		<br /><br />
		<table>
			<thead>
				<tr>
					<th colspan="2">
						{L_SEX}
					</th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<td>
					<table>
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
									<div style="margin:auto;width:10px;margin:auto;height:10px;background:{sex.COLOR};border:1px solid black;"></div>
								</td>
								<td>
									{sex.NBR_MBR}
								</td>
							</tr>
							# END sex #
						</tbody>
					</table>
				</td>
				<td class="no-separator">
					{GRAPH_RESULT_SEX}
				</td>
			</tr>
			</tbody>
		</table>
		<br /><br />
		<table>
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
						<a href="{top_poster.U_USER_PROFILE}" class="{top_poster.USER_LEVEL_CLASS}" # IF top_poster.C_USER_GROUP_COLOR # style="color:{top_poster.USER_GROUP_COLOR}" # ENDIF #>{top_poster.LOGIN}</a>
					</td>
					<td>
						{top_poster.USER_POST}
					</td>
				</tr>
				# END top_poster #
			</tbody>
		</table>
		# ENDIF #
		
		
		# IF C_STATS_VISIT #
		<form action="admin_stats.php#stats" method="get">
			<table>
				<thead>
					<tr>
						<th>
							{L_VISITORS} {MONTH} {U_YEAR}
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<div style="width:50%;text-align:center;margin:auto">
								<p class="text-strong">{L_TOTAL}: {VISIT_TOTAL} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {L_TODAY}: {VISIT_DAY}</p>
								<a class="fa fa-arrow-left" href="admin_stats{U_PREVIOUS_LINK}#stats"></a>&nbsp;&nbsp;&nbsp;&nbsp;
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
								<input type="hidden" name="{TYPE}" value="1">
								<button type="submit" name="date" value="true">{L_SUBMIT}</button>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<a class="fa fa-arrow-right" href="admin_stats{U_NEXT_LINK}#stats"></a>
							</div>
							<br />
							# IF C_STATS_NO_GD #
							<br />
							<table>
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
													# START head #
													<tr>
														<td style="margin-left:2px;width:10px;height:4px;background-image: url({PATH_TO_ROOT}/stats/templates/images/stats2.png); background-repeat:no-repeat;">
														</td>
													</tr>
													# END head #
													<tr>
														<td style="margin-left:2px;width:10px;height:{values.HEIGHT}px;background-image: url({PATH_TO_ROOT}/stats/templates/images/stats.png);background-repeat:repeat-y;padding:0px">
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
							<br />
							# ENDIF #

							{GRAPH_RESULT}
						</td>
					</tr>
					<tr>
						<td colspan="{COLSPAN}">
							{L_TOTAL}: {SUM_NBR}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{L_AVERAGE}: {MOY_NBR}
						</td>
					</tr>
					<tr>
						<td>
							{U_VISITS_MORE}
						</td>
					</tr>
				</tbody>
			</table>
		</form>	
		<br /><br />
		<table>
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
		# ENDIF #


		# IF C_STATS_BROWSERS #
		<table>
			<thead>
				<tr>
					<th colspan="3">
						{L_BROWSERS}
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<table>
							<tbody>
								# START list #
								<tr>
									<td class="no-separator">
										{list.IMG}
									</td>
									<td class="no-separator">
										<div style="margin:auto;width:10px;height:10px;margin:auto;background:{list.COLOR};border:1px solid black;"></div>
									</td>
									<td class="no-separator">
										 {list.L_NAME} <span class="smaller">({list.PERCENT}%)</span>
									</td>
								</tr>
								# END list #
							</tbody>
						</table>
					</td>
					<td class="no-separator">
						{GRAPH_RESULT}
					</td>
				</tr>
			</tbody>
		</table>
		# ENDIF #


		# IF C_STATS_OS #
		<table>
			<thead>
				<tr>
					<th colspan="3">
						{L_OS}
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<table>
							<tbody>
								# START list #
								<tr>
									<td class="no-separator">
										{list.IMG}
									</td>
									<td class="no-separator">
										<div style="margin:auto;width:10px;height:10px;background:{list.COLOR};border:1px solid black;"></div>
									</td>
									<td class="no-separator">
										{list.L_NAME} <span class="smaller">({list.PERCENT}%)</span>
									</td>
								</tr>
								# END list #
							</tbody>
						</table>
					</td>
					<td class="no-separator">
						{GRAPH_RESULT}
					</td>
				</tr>
			</tbody>
		</table>
		# ENDIF #

		
		# IF C_STATS_LANG #
		<table>
			<thead>
				<tr>
					<th colspan="3">
						{L_LANG}
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<table>
							<tbody>
								# START list #
								<tr>
									<td class="no-separator">
										{list.IMG}
									</td>
									<td class="no-separator">
										<div style="margin:auto;width:10px;margin:auto;height:10px;background:{list.COLOR};border:1px solid black;"></div>
									</td>
									<td class="no-separator">
										{list.L_NAME} <span class="smaller">({list.PERCENT}%)</span>
									</td>
								</tr>
								# END list #
							</tbody>
						</table>
					</td>
					<td class="no-separator">
						{GRAPH_RESULT}
					</td>
				</tr>
			</tbody>
		</table>
		# ENDIF #
		

		# IF C_STATS_REFERER #
		<script>
		<!--
		function XMLHttpRequest_referer(divid)
		{
			if( document.getElementById('url' + divid).style.display != 'none' )
			{
				document.getElementById('img_url' + divid).className = 'fa fa-plus-square-o';
				display_div_auto('url' + divid, 'table');
			}
			else
			{
				var xhr_object = null;
				var filename = '{PATH_TO_ROOT}/stats/ajax/stats_xmlhttprequest.php?stats_referer=1&id=' + divid;
				var data = null;
				
				if(window.XMLHttpRequest) // Firefox
				   xhr_object = new XMLHttpRequest();
				else if(window.ActiveXObject) // Internet Explorer
				   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
				else // XMLHttpRequest non support� par le navigateur
					return;
				
				document.getElementById('load' + divid).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
				
				xhr_object.open("POST", filename, true);
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
					{
						display_div_auto('url' + divid, 'table');
						document.getElementById('url' + divid).innerHTML = xhr_object.responseText;
						document.getElementById('load' + divid).innerHTML = '';
						document.getElementById('img_url' + divid).className = 'fa fa-minus-square-o';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
						document.getElementById('load' + divid).innerHTML = '';
				}
				xmlhttprequest_sender(xhr_object, null);
			}
		}
		-->
		</script>
		
		<table>
			<thead>
				<tr>
					<th>
						{L_REFERER}
					</th>
					<th style="width:70px;">
						{L_TOTAL_VISIT}
					</th>
					<th style="width:60px;">
						{L_AVERAGE_VISIT}
					</th>
					<th style="width:96px;">
						{L_LAST_UPDATE}
					</th>
					<th style="width:100px;">
						{L_TREND}
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
				# START referer_list #
				<tr>
					<td colspan="5">
						<table>
							<tbody>
								<tr>
									<td>
										{referer_list.IMG_MORE} <span class="smaller">({referer_list.NBR_LINKS})</span> <a href="{referer_list.URL}">{referer_list.URL}</a> <span id="load{referer_list.ID}"></span>
									</td>
									<td style="width:70px;">
										{referer_list.TOTAL_VISIT}
									</td>
									<td style="width:60px;">
										{referer_list.AVERAGE_VISIT}
									</td>
									<td style="width:96px;">
										{referer_list.LAST_UPDATE}
									</td>
									<td style="width:95px;">
										{referer_list.TREND}
									</td>
								</tr>
							</tbody>
						</table>
						<div id="url{referer_list.ID}" style="display:none;width:100%;"></div>
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
		</table>
		# ENDIF #
		
		
		# IF C_STATS_KEYWORD #
		<script>
		<!--
		function XMLHttpRequest_referer(divid)
		{
			if( document.getElementById('url' + divid).style.display != 'none' )
			{
				document.getElementById('img_url' + divid).className = 'fa fa-plus-square-o';
				display_div_auto('url' + divid, 'table');
			}
			else
			{
				document.getElementById('load' + divid).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
				var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/stats/ajax/stats_xmlhttprequest.php?token={TOKEN}&stats_keyword=1&id=' + divid);
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
					{
						display_div_auto('url' + divid, 'table');
						document.getElementById('url' + divid).innerHTML = xhr_object.responseText;
						document.getElementById('load' + divid).innerHTML = '';
						document.getElementById('img_url' + divid).className = 'fa fa-minus-square-o';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
						document.getElementById('load' + divid).innerHTML = '';
				}
				xmlhttprequest_sender(xhr_object, null);
			}
		}
		-->
		</script>
		
		<table>
			<thead>
				<tr>
					<th>
						{L_KEYWORD}
					</th>
					<th style="width:70px;">
						{L_TOTAL_VISIT}
					</th>
					<th style="width:60px;">
						{L_AVERAGE_VISIT}
					</th>
					<th style="width:96px;">
						{L_LAST_UPDATE}
					</th>
					<th style="width:100px;">
						{L_TREND}
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
				# START keyword_list #
				<tr>
					<td colspan="5">
						<table>
							<tbody>
								<tr>
									<td>
										{keyword_list.IMG_MORE} <span class="smaller">({keyword_list.NBR_LINKS})</span> {keyword_list.KEYWORD} <span id="load{keyword_list.ID}"></span>
									</td>
									<td style="width:70px;">
										{keyword_list.TOTAL_VISIT}
									</td>
									<td style="width:60px;">
										{keyword_list.AVERAGE_VISIT}
									</td>
									<td style="width:96px;">
										{keyword_list.LAST_UPDATE}
									</td>
									<td style="width:95px;">
										{keyword_list.TREND}
									</td>
								</tr>
							</tbody>
						</table>
						<div id="url{keyword_list.ID}" style="display:none;width:100%;"></div>
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
		</table>
		# ENDIF #
		
		
		# IF C_STATS_ROBOTS #
		<form action="admin_stats.php?bot=1#stats" name="form" method="post" style="margin:auto;" onsubmit="return check_form();">
			<table>
				<thead>
					<tr> 
						<th colspan="2">
							{L_ROBOTS}
						</th>
					</tr>
				</thead>
				<tbody>
					<tr> 
						<td>
							<table>
								<thead>
									<tr>
										<th style="text-align:center">
											{L_ROBOTS} 
										</th>
										<th style="text-align:center">
											{L_COLORS}
										</th>
										<th style="text-align:center">
											{L_VIEW_NUMBER}
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
											<div style="margin:auto;width:10px;margin:auto;height:10px;background:{list.COLOR}"></div>
										</td>
										<td>
											{list.VIEWS}
										</td>
									</tr>
									# END list #
								</tbody>
							</table>
						</td>
						<td class="no-separator">
							<img src="display_stats.php?bot=1" alt="" />
						</td>
					</tr>
				</tbody>
			</table>
			<br /><br />
			<fieldset class="fieldset-submit">
				<legend>{L_ERASE_RAPPORT}</legend>
				<button type="submit" name="erase" value="true">{L_ERASE_RAPPORT}</button> 
			</fieldset>
		</form>
		# ENDIF #
		
		<br /><br />
		<form action="admin_stats.php?token={TOKEN}" method="post" class="fieldset-content">
				<fieldset>
					<legend>
						{L_AUTHORIZATIONS}
					</legend>
					<div class="form-element">
						
							<label>
								{L_READ_AUTHORIZATION}
							</label>
						
						<div class="form-field">
							{READ_AUTHORIZATION}
						</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
	</div>
	