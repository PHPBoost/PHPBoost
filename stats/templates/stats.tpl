		<table class="module_table">
			<tr>
				<th id="stats" colspan="5">
					{L_STATS}
				</th>
			</tr>
			<tr style="text-align:center;">
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats{U_STATS_SITE}#stats"><img src="../templates/{THEME}/images/stats/site.png" alt="" /></a>
					<br /><a href="stats{U_STATS_SITE}#stats">{L_SITE}</a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats{U_STATS_USERS}#stats"><img src="../templates/{THEME}/images/upload/member.png" alt="" /></a>
					<br /><a href="stats{U_STATS_USERS}#stats">{L_USERS}</a>
				</td>
				<td style="width:20%;" class="row2">
					<a href="stats{U_STATS_VISIT}#stats"><img src="../templates/{THEME}/images/stats/visitors.png" alt="" /></a>
					<br /><a href="stats{U_STATS_VISIT}#stats">{L_VISITS}</a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats{U_STATS_PAGES}#stats"><img src="../templates/{THEME}/images/stats/pages.png" alt="" /></a>
					<br /><a href="stats{U_STATS_PAGES}#stats">{L_PAGES}</a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats{U_STATS_BROWSER}#stats"><img src="../templates/{THEME}/images/stats/browsers.png" alt="" /></a>
					<br /><a href="stats{U_STATS_BROWSER}#stats">{L_BROWSERS}</a>
				</td>
			</tr>
			<tr style="text-align:center;">				
				<td style="width:20%;" class="row2">	
					<a href="stats{U_STATS_OS}#stats"><img src="../templates/{THEME}/images/stats/os.png" alt="" /></a>
					<br /><a href="stats{U_STATS_OS}#stats">{L_OS}</a>		
				</td>		
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats{U_STATS_LANG}#stats"><img src="../templates/{THEME}/images/stats/countries.png" alt="" /></a>
					<br /><a href="stats{U_STATS_LANG}#stats">{L_LANG}</a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats{U_STATS_REFERER}#stats"><img src="../templates/{THEME}/images/stats/referer.png" alt="" /></a>
					<br /><a href="stats{U_STATS_REFERER}#stats">{L_REFERER}</a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats{U_STATS_KEYWORD}#stats"><img src="../templates/{THEME}/images/stats/keyword.png" alt="" /></a>
					<br /><a href="stats{U_STATS_KEYWORD}#stats">{L_KEYWORD}</a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					&nbsp;
				</td>
			</tr>
		</table>
		
		<br /><br />
		
		# IF C_STATS_SITE #
		<table class="module_table">
			<tr>
				<th colspan="3">
					{L_SITE}
				</th>
			</tr>
			<tr>
				<td class="row2">
					{L_START}: <strong>{START}</strong>
				</td>		
			</tr>
			<tr>
				<td class="row2">
					{L_VERSION} PHPBoost: <strong>{VERSION}</strong>
				</td>		
			</tr>	
		</table>
		# ENDIF #
		
		
		# IF C_STATS_USERS #
		<table class="module_table">
			<tr>
				<th colspan="2">	
					{L_USERS}
				</th>
			</tr>
			<tr>
				<td class="row1" style="text-align:center;width:25%;">
					{L_USERS}
				</td>
				<td class="row2">
					{USERS}
				</td>
			 </tr>
			<tr>
				<td class="row1" style="text-align:center;width:50%;">
					{L_LAST_USER}
				</td>
				<td class="row2">
					<a href="../member/member{U_LAST_USER_ID}">{LAST_USER}</a>
				</td>
			</tr>
		</table>
		<br /><br />
		<table class="module_table">
			<tr>
				<th colspan="2">	
					{L_TEMPLATES}
				</th>
			</tr>
			<tr>
				<td style="width:50%;padding-top:30px;vertical-align:top" class="row1">
					<table class="module_table">						
						<tr>
							<td style="text-align:center;" class="row1">			
								{L_TEMPLATES} 
							</td>
							<td style="width:10px;" class="row1">			
								{L_COLORS}
							</td>
							<td style="text-align:center;" class="row1">
								{L_USERS}
							</td>				
						</tr>						
						# START templates #	
						<tr>
							<td style="text-align:center;" class="row2">			
								{templates.THEME} <span class="text_small">({templates.PERCENT}%)</span>
							</td>							
							<td style="text-align:center;" class="row2">			
								<div style="margin:auto;width:10px;margin:auto;height:10px;background:{templates.COLOR};border:1px solid black;"></div>
							</td>
							<td style="text-align:center;" class="row2">
								{templates.NBR_THEME}
							</td>				
						</tr>
						# END templates #		
					</table>
				</td>
				<td style="text-align:center;padding-top:30px;vertical-align:top" class="row1">
					{GRAPH_RESULT_THEME}
				</td>
			</tr>
		</table>
		<br /><br />
		<table class="module_table">
			<tr>
				<th colspan="2">	
					{L_SEX}
				</th>
			</tr>
			<tr>
				<td style="width:50%;padding-top:30px;vertical-align:top" class="row1">
					<table class="module_table">						
						<tr>
							<td style="text-align:center;" class="row1">			
								{L_SEX} 
							</td>
							<td style="width:10px;" class="row1">			
								{L_COLORS}
							</td>
							<td style="text-align:center;" class="row1">
								{L_USERS}
							</td>				
						</tr>						
						# START sex #	
						<tr>
							<td style="text-align:center;" class="row2">			
								{sex.SEX} <span class="text_small">({sex.PERCENT}%)</span>
							</td>							
							<td style="text-align:center;" class="row2">			
								<div style="margin:auto;width:10px;margin:auto;height:10px;background:{sex.COLOR};border:1px solid black;"></div>
							</td>
							<td style="text-align:center;" class="row2">
								{sex.NBR_MBR}
							</td>				
						</tr>
						# END sex #		
					</table>
				</td>
				<td style="text-align:center;padding-top:30px;vertical-align:top" class="row1">
					{GRAPH_RESULT_SEX}
				</td>
			</tr>
		</table>
		<br /><br />
		<table class="module_table">
			<tr>
				<th colspan="3">	
					{L_TOP_TEN_POSTERS}
				</th>
			</tr>
			<tr>
				<td class="row1" style="text-align:center;">
					N&deg;
				</td>
				<td class="row1" style="text-align:center;">
					{L_PSEUDO}
				</td>
				<td class="row1" style="text-align:center;">
					{L_MSG}
				</td>
			</tr>			
			# START top_poster #			
			<tr>
				<td class="row2" style="text-align:center;">
					{top_poster.ID}
				</td>
				<td class="row2" style="text-align:center;">
					<a href="../member/member{top_poster.U_USER_ID}">{top_poster.LOGIN}</a>
				</td>
				<td class="row2" style="text-align:center;">
					{top_poster.USER_POST}
				</td>
			</tr>			
			# END top_poster #
		</table>
		# ENDIF #
		
		
		# IF C_STATS_VISIT #
		<form action="stats.php#stats" method="get">
			<table class="module_table">
				<tr>
					<th>
						{L_VISITORS} {MONTH} {U_YEAR}
					</th>
				</tr>
				<tr>
					<td class="row2" style="text-align:center;">
						<div style="width:50%;text-align:center;margin:auto">
							<p class="text_strong">{L_TOTAL}: {VISIT_TOTAL} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {L_TODAY}: {VISIT_DAY}</p>
							<a href="stats{U_PREVIOUS_LINK}#stats">&laquo;</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
							<input type="hidden" name="{TYPE}" value="1" />
							<input type="submit" name="date" value="{L_SUBMIT}" class="submit" />
							&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="stats{U_NEXT_LINK}#stats">&raquo;</a>				
						</div>
						<br />
						# IF C_STATS_NO_GD #
						<br />
						<table class="module_table" style="width:400px;margin:auto;">
							<tr>
								<td style="background-color: #000000;width:1px;"></td>
								<td style="height:200px;width:10px;vertical-align:top;text-align:center;font-size:9px;">
									{MAX_NBR}
								</td>
									
								# START values #								
								<td style="height:200px;width:10px;vertical-align:bottom;">
									<table class="module_table" style="width:14px;margin:auto;">
										# START values.head #
										<tr>
											<td style="margin-left:2px;width:10px;height:4px;background-image: url(../templates/{THEME}/images/stats2.png); background-repeat:no-repeat;">
											</td>
										</tr>
										# END values.head #
										<tr>
											<td style="margin-left:2px;width:10px;height:{values.HEIGHT}px;background-image: url(../templates/{THEME}/images/stats.png);background-repeat:repeat-y;padding:0px">
											</td>
										</tr>
									</table>
								</td>	
								# END values #
								
								# START end_td #							
									{end_td.END_TD}							
								# END end_td #
							</tr>
							<tr>
								<td style="background-color: #000000;width:1px"></td>
								<td style="width:10px;font-size:9px;">
									0
								</td>								
								# START legend #								
								<td style="text-align:center;width:13px;font-size:9px;">
									{legend.LEGEND}
								</td>								
								# END legend #								
							</tr>
							<tr>
								<td style="height:1px;background-color: #000000;" colspan="{COLSPAN}"></td>
							</tr>
						</table>
						<br />
						# ENDIF #
						
						{GRAPH_RESULT}
					</td>
				</tr>
				<tr>
					<td class="row3" style="text-align:center;" colspan="{COLSPAN}">
						{L_TOTAL}: {SUM_NBR}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{L_AVERAGE}: {MOY_NBR}
					</td>
				</tr>
				<tr>
					<td class="row3" style="text-align:center;">
						{U_VISITS_MORE}
					</td>
				</tr>
			</table>
		</form>	
		<br /><br />
		<table class="module_table" style="width:300px;">
			<tr>
				<th style="width:50%">
					{L_DAY}
				</th>
				<th style="width:50%">
					{L_VISITS_DAY}
				</th>
			</tr>			
			# START value #
			<tr>
				<td class="row3" style="font-size:10px;width:50%">
					{value.U_DETAILS}
				</td>
				<td class="row3" style="font-size:10px;width:50%">
					{value.NBR}
				</td>
			</tr>		
			# END value #
		</table>
		# ENDIF #


		# IF C_STATS_BROWSERS #
		<table class="module_table">
			<tr>
				<th colspan="2">
					{L_BROWSERS}
				</th>
			</tr>
			<tr>
				<td class="row2" style="width:50%;padding-top:30px;vertical-align:top">
					<table class="module_table">
						# START list #			
						<tr style="height:35px;">
							<td style="width:35px;text-align:center;" class="row2">			
								{list.IMG}
							</td>
							<td style="width:10px;text-align:center;" class="row2">			
								<div style="width:10px;height:10px;margin:auto;background:{list.COLOR};border:1px solid black;"></div>
							</td>
							<td style="width:50px;" class="row2">
								{list.L_NAME} <span class="text_small">({list.PERCENT}%)</span>
							</td>				
						</tr>
						# END list #
					</table>
				</td>
				<td class="row2" style="text-align:center;padding-top:30px;vertical-align:top">
					{GRAPH_RESULT}
				</td>
			</tr>
		</table>
		# ENDIF #


		# IF C_STATS_OS #
		<table class="module_table">
			<tr>
				<th colspan="2">
					{L_OS}
				</th>
			</tr>
			<tr style="height:35px;">
				<td class="row2" style="width:50%;padding-top:30px;vertical-align:top">
					<table class="module_table">
						# START list #			
						<tr style="height:35px;">
							<td style="width:35px; text-align:center;" class="row2">			
								{list.IMG}
							</td>
							<td style="width:10px; text-align:center;" class="row2">			
								<div style="width:10px;height:10px;margin:auto;background:{list.COLOR};border:1px solid black;"></div>
							</td>
							<td style="width:50px;" class="row2">
								{list.L_NAME} <span class="text_small">({list.PERCENT}%)</span>
							</td>				
						</tr>
						# END list #
					</table>
				</td>
				<td class="row2" style="text-align:center;padding-top:30px;vertical-align:top">
					{GRAPH_RESULT}
				</td>
			</tr>
		</table>
		# ENDIF #

				
		# IF C_STATS_LANG #
		<table class="module_table">
			<tr>
				<th colspan="2">
					{L_LANG}
				</th>
			</tr>
			<tr style="height:35px;">
				<td class="row2" style="width:50%;padding-top:30px;vertical-align:top">
					<table class="module_table">
						# START list #			
						<tr style="height:35px;">
							<td style="width:35px;text-align:center;" class="row2">			
								{list.IMG}
							</td>
							<td style="width:10px;text-align:center;" class="row2">			
								<div style="width:10px;margin:auto;height:10px;background:{list.COLOR};border:1px solid black;"></div>
							</td>
							<td style="width:50px;" class="row2">
								{list.L_NAME} <span class="text_small">({list.PERCENT}%)</span>
							</td>				
						</tr>
						# END list #
					</table>
				</td>
				<td class="row2" style="text-align:center;padding-top:30px;vertical-align:top">
					{GRAPH_RESULT}
				</td>
			</tr>
			<tr>
				<td class="row2" colspan="2" style="text-align:center;">
					{L_LANG_ALL}
				</td>
			</tr>
		</table>
		# ENDIF #

		
		# IF C_STATS_REFERER #
		<script type="text/javascript">
		<!--
		function XMLHttpRequest_referer(divid)
		{
			if( document.getElementById('url' + divid).style.display == 'table' )
			{
				display_div_auto('url' + divid, 'table');
				document.getElementById('img_url' + divid).src = '../templates/{THEME}/images/upload/plus.png';
			}
			else
			{
				var xhr_object = null;
				var filename = '../kernel/framework/ajax/stats_xmlhttprequest.php?stats_referer=1&id=' + divid;
				var data = null;
				
				if(window.XMLHttpRequest) // Firefox
				   xhr_object = new XMLHttpRequest();
				else if(window.ActiveXObject) // Internet Explorer
				   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
				else // XMLHttpRequest non support? par le navigateur
					return;
				
				document.getElementById('load' + divid).innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
				
				xhr_object.open("POST", filename, true);
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
					{	
						display_div_auto('url' + divid, 'table');
						document.getElementById('url' + divid).innerHTML = xhr_object.responseText;
						document.getElementById('load' + divid).innerHTML = '';
						document.getElementById('img_url' + divid).src = '../templates/{THEME}/images/upload/minus.png';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
						document.getElementById('load' + divid).innerHTML = '';
				}
				xmlhttprequest_sender(xhr_object, null);
			}
		}
		-->
		</script>
		
		<table class="module_table">
			<tr>
				<th>			
					{L_REFERER}
				</th>
				<th style="width:100px;">
					{L_TOTAL_VISIT}
				</th>
				<th style="width:100px;">
					{L_AVERAGE_VISIT}
				</th>
				<th style="width:90px;">
					{L_LAST_UPDATE}
				</th>	
				<th style="width:93px;">
					{L_TREND}
				</th>
			</tr>
			# START referer_list #	
			<tr>
				<td class="row2" style="padding:0px;margin:0px;" colspan="5">			
					<table style="width:100%;border:none;border-collapse:collapse;">
						<tr>
							<td style="text-align:center;">		
								{referer_list.IMG_MORE} <span class="text_small">({referer_list.NBR_LINKS})</span> <a href="{referer_list.URL}">{referer_list.URL}</a>	<span id="load{referer_list.ID}"></span>	 			
							</td>
							<td style="width:112px;text-align:center;">
								{referer_list.TOTAL_VISIT}
							</td>
							<td style="width:112px;text-align:center;">
								{referer_list.AVERAGE_VISIT}
							</td>
							<td style="width:102px;text-align:center;">
								{referer_list.LAST_UPDATE}
							</td>
							<td style="width:105px;">
								{referer_list.TREND}
							</td>
						</tr>
					</table>
					<div id="url{referer_list.ID}" style="display:none;width:100%;"></div>					
				</td>	
			</tr>
			# END referer_list #
			<tr>
				<td class="row3" colspan="5" style="text-align:center;">
					{PAGINATION}&nbsp;
				</td>
			</tr>
		</table>
		# ENDIF #
		
		
		# IF C_STATS_KEYWORD #
		<script type="text/javascript">
		<!--
		function XMLHttpRequest_referer(divid)
		{
			if( document.getElementById('url' + divid).style.display == 'table' )
			{
				display_div_auto('url' + divid, 'table');
				document.getElementById('img_url' + divid).src = '../templates/{THEME}/images/upload/plus.png';
			}
			else
			{
				document.getElementById('load' + divid).innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
				var xhr_object = xmlhttprequest_init('../kernel/framework/ajax/stats_xmlhttprequest.php?token={TOKEN}&stats_keyword=1&id=' + divid);
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
					{	
						display_div_auto('url' + divid, 'table');
						document.getElementById('url' + divid).innerHTML = xhr_object.responseText;
						document.getElementById('load' + divid).innerHTML = '';
						document.getElementById('img_url' + divid).src = '../templates/{THEME}/images/upload/minus.png';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
						document.getElementById('load' + divid).innerHTML = '';
				}
				xmlhttprequest_sender(xhr_object, null);
			}
		}
		-->
		</script>
		
		<table class="module_table">
			<tr>
				<th>			
					{L_KEYWORD}
				</th>
				<th style="width:100px;">
					{L_TOTAL_VISIT}
				</th>
				<th style="width:100px;">
					{L_AVERAGE_VISIT}
				</th>
				<th style="width:90px;">
					{L_LAST_UPDATE}
				</th>	
				<th style="width:93px;">
					{L_TREND}
				</th>
			</tr>
			# START keyword_list #	
			<tr>
				<td class="row2" style="padding:0px;margin:0px;" colspan="5">			
					<table style="width:100%;border:none;border-collapse:collapse;">
						<tr>
							<td style="text-align:center;">		
								{keyword_list.IMG_MORE} <span class="text_small">({keyword_list.NBR_LINKS})</span> {keyword_list.KEYWORD}	<span id="load{keyword_list.ID}"></span>	 			
							</td>
							<td style="width:112px;text-align:center;">
								{keyword_list.TOTAL_VISIT}
							</td>
							<td style="width:112px;text-align:center;">
								{keyword_list.AVERAGE_VISIT}
							</td>
							<td style="width:102px;text-align:center;">
								{keyword_list.LAST_UPDATE}
							</td>
							<td style="width:105px;">
								{keyword_list.TREND}
							</td>
						</tr>
					</table>
					<div id="url{keyword_list.ID}" style="display:none;width:100%;"></div>					
				</td>	
			</tr>
			# END keyword_list #
			<tr>
				<td class="row3" colspan="5" style="text-align:center;">
					{PAGINATION}&nbsp;
				</td>
			</tr>
		</table>
		# ENDIF #
		