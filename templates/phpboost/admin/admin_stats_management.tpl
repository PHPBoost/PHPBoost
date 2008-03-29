		<table class="module_table">
			<tr>
				<th id="stats" colspan="5">
					{L_STATS}
				</th>
			</tr>
			<tr style="text-align:center;">
				<td style="width:20%;padding:15px;" class="row1">
					<a href="admin_stats.php?site=1#stats"><img src="../templates/{THEME}/images/stats/site.png" alt="" /></a>
					<br /><a href="admin_stats.php?site=1#stats">{L_SITE}</a>
				</td>
				<td style="width:20%;padding:15px;" class="row1">
					<a href="admin_stats.php?members=1#stats"><img src="../templates/{THEME}/images/upload/member.png" alt="" /></a>
					<br /><a href="admin_stats.php?members=1#stats">{L_MEMBERS}</a>
				</td>
				<td style="width:20%;" class="row1">
					<a href="admin_stats.php?visit=1#stats"><img src="../templates/{THEME}/images/stats/visitors.png" alt="" /></a>
					<br /><a href="admin_stats.php?visit=1#stats">{L_VISITS}</a>
				</td>
				<td style="width:20%;padding:15px;" class="row1">
					<a href="admin_stats.php?pages=1#stats"><img src="../templates/{THEME}/images/stats/pages.png" alt="" /></a>
					<br /><a href="admin_stats.php?pages=1#stats">{L_PAGES}</a>
				</td>				
				<td style="width:20%;padding:15px;" class="row1">
					<a href="admin_stats.php?browser=1#stats"><img src="../templates/{THEME}/images/stats/browsers.png" alt="" /></a>
					<br /><a href="admin_stats.php?browser=1#stats">{L_BROWSERS}</a>
				</td>
			</tr>
			<tr style="text-align:center;">						
				<td style="width:20%;" class="row1">	
					<a href="admin_stats.php?os=1#stats"><img src="../templates/{THEME}/images/stats/os.png" alt="" /></a>
					<br /><a href="admin_stats.php?os=1#stats">{L_OS}</a>		
				</td>
				<td style="width:20%;padding:15px;" class="row1">
					<a href="admin_stats.php?lang=1#stats"><img src="../templates/{THEME}/images/stats/countries.png" alt="" /></a>
					<br /><a href="admin_stats.php?lang=1#stats">{L_LANG}</a>
				</td>				
				<td style="width:20%;padding:15px;" class="row1">
					<a href="admin_stats.php?referer=1#stats"><img src="../templates/{THEME}/images/stats/referer.png" alt="" /></a>
					<br /><a href="admin_stats.php?referer=1#stats">{L_REFERER}</a>
				</td>
				<td style="width:20%;padding:15px;" class="row1">
					<a href="admin_stats.php?keyword=1#stats"><img src="../templates/{THEME}/images/stats/keyword.png" alt="" /></a>
					<br /><a href="admin_stats.php?keyword=1#stats">{L_KEYWORD}</a>
				</td>
				<td style="width:20%;padding:15px;" class="row1">
					<a href="admin_stats.php?bot=1#stats"><img src="../templates/{THEME}/images/stats/robots.png" alt="" /></a>
					<br /><a href="admin_stats.php?bot=1#stats">{L_ROBOTS}</a>
				</td>	
			</tr>
		</table>
		
		<br /><br />

		# START visit #
		<form action="admin_stats.php#stats" method="get">
			<table class="module_table">
				<tr>
					<th>
						{L_VISITORS} {visit.MONTH} {visit.U_YEAR}
					</th>
				</tr>
				<tr>
					<td class="row2" style="text-align:center;">
						<div style="width:50%;text-align:center;margin:auto">
							<p class="text_strong">{L_TOTAL}: {visit.VISIT_TOTAL} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {L_TODAY}: {visit.VISIT_DAY}</p>
							<a href="admin_stats{visit.U_PREVIOUS_LINK}#stats">&laquo;</a>&nbsp;&nbsp;&nbsp;&nbsp;
							# START visit.days #
							<select name="d">
								{visit.days.DAY}
							</select>
							# END visit.days #
							# START visit.months #
							<select name="m">
								{visit.months.MONTH}
							</select>
							# END visit.months #
							# START visit.years #
							<select name="y">
								{visit.years.YEAR}
							</select>
							 # END visit.years #
							<input type="hidden" name="{visit.TYPE}" value="1" />
							<input type="submit" name="date" value="{L_SUBMIT}" class="submit" />
							&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="admin_stats{visit.U_NEXT_LINK}#stats">&raquo;</a>				
						</div>
						<br />
						# START visit.no_gd #
						<br />
						<table class="module_table" style="width:400px;margin:auto;">
							<tr>
								<td style="background-color: #000000;width:1px;"></td>
								<td style="height:200px;width:10px;vertical-align:top;text-align:center;font-size:9px;">
									{visit.MAX_NBR}
								</td>
									
								# START visit.no_gd.values #								
								<td style="height:200px;width:10px;vertical-align:bottom;">
									<table class="module_table" style="width:14px;margin:auto;">
										# START head #
										<tr>
											<td style="margin-left:2px;width:10px;height:4px;background-image: url(../templates/{THEME}/images/stats2.png); background-repeat:no-repeat;">
											</td>
										</tr>
										# END head #
										<tr>
											<td style="margin-left:2px;width:10px;height:{visit.no_gd.values.HEIGHT}px;background-image: url(../templates/{THEME}/images/stats.png);background-repeat:repeat-y;padding:0px">
											</td>
										</tr>
									</table>
								</td>	
								# END visit.no_gd.values #
								
								# START visit.no_gd.end_td #							
									{visit.no_gd.end_td.END_TD}							
								# END visit.no_gd.end_td #
							</tr>
							<tr>
								<td style="background-color: #000000;width:1px"></td>
								<td style="width:10px;font-size:9px;">
									0
								</td>								
								# START visit.no_gd.legend #								
								<td style="text-align:center;width:13px;font-size:9px;">
									{visit.no_gd.legend.LEGEND}
								</td>								
								# END visit.no_gd.legend #								
							</tr>
							<tr>
								<td style="height:1px;background-color: #000000;" colspan="{visit.COLSPAN}"></td>
							</tr>
						</table>
						<br />
						# END visit.no_gd #
						
						{GRAPH_RESULT}
					</td>
				</tr>
				<tr>
					<td class="row2" style="text-align:center;" colspan="{visit.COLSPAN}">
						{L_TOTAL}: {visit.SUM_NBR}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{L_AVERAGE}: {visit.MOY_NBR}
					</td>
				</tr>
				<tr>
					<td class="row2" style="text-align:center;">
						{visit.U_VISITS_MORE}
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
			# START visit.value #
			<tr>
				<td class="row3" style="font-size:10px;width:50%">
					{visit.value.U_DETAILS}
				</td>
				<td class="row3" style="font-size:10px;width:50%">
					{visit.value.NBR}
				</td>
			</tr>		
			# END visit.value #
		</table>
		# END visit #


		# START browsers #
		<table class="module_table">
			<tr>
				<th colspan="3">
					{L_BROWSERS}
				</th>
			</tr>
			<tr>
				<td class="row1" style="width:50%;padding-top:30px;vertical-align:top">
					<table class="module_table">
						# START browsers.browsers_list #			
						<tr style="height:35px;">
							<td style="text-align:center;" class="row2">	
								{browsers.browsers_list.IMG}
							</td>
							<td style="text-align:center;" class="row2">			
								<div style="margin:auto;width:10px;height:10px;margin:auto;background:{browsers.browsers_list.COLOR};border:1px solid black;"></div>
							</td>
							<td style="text-align:center;" class="row2">
								 {browsers.browsers_list.L_NAME} <span class="text_small">({browsers.browsers_list.PERCENT}%)</span>
							</td>				
						</tr>
						# END browsers.browsers_list #
					</table>
				</td>
				<td style="text-align:center;padding-top:30px;vertical-align:top" class="row1">
					{browsers.GRAPH_RESULT}
				</td>
			</tr>
		</table>
		# END browsers #


		# START os #
		<table class="module_table">
			<tr>
				<th colspan="3">
					{L_OS}
				</th>
			</tr>
			<tr style="height:35px;">
				<td class="row1" style="width:50%;padding-top:30px;vertical-align:top">
					<table class="module_table">
						# START os.os_list #			
						<tr style="height:35px;">
							<td style="text-align:center;" class="row2">		
								{os.os_list.IMG}
							</td>
							<td style="text-align:center;" class="row2">			
								<div style="margin:auto;width:10px;height:10px;background:{os.os_list.COLOR};border:1px solid black;"></div>
							</td>
							<td style="text-align:center;" class="row2">
								{os.os_list.L_NAME} <span class="text_small">({os.os_list.PERCENT}%)</span>
							</td>				
						</tr>
						# END os.os_list #
					</table>
				</td>
				<td style="text-align:center;padding-top:30px;vertical-align:top" class="row1">
					{os.GRAPH_RESULT}
				</td>
			</tr>
		</table>
		# END os #

		
		# START lang #
		<table class="module_table">
			<tr>
				<th colspan="3">
					{L_LANG}
				</th>
			</tr>
			<tr style="height:35px;">
				<td class="row1" style="width:50%;padding-top:30px;vertical-align:top">
					<table class="module_table">
						# START lang.lang_list #			
						<tr>
							<td style="text-align:center;" class="row2">			
								{lang.lang_list.IMG}
							</td>
							<td style="text-align:center;" class="row2">			
								<div style="margin:auto;width:10px;margin:auto;height:10px;background:{lang.lang_list.COLOR};border:1px solid black;"></div>
							</td>
							<td style="text-align:center;" class="row2">
								{lang.lang_list.L_NAME} <span class="text_small">({lang.lang_list.PERCENT}%)</span>
							</td>				
						</tr>
						# END lang.lang_list #
					</table>
				</td>
				<td style="text-align:center;padding-top:30px;vertical-align:top" class="row1">
					{lang.GRAPH_RESULT}
				</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:center;">
					{L_LANG_ALL}
				</td>
			</tr>
		</table>
		# END lang #

		
		# START site #
		<table class="module_table">
			<tr>
				<th colspan="3">
					{L_SITE}
				</th>
			</tr>
			<tr>
				<td class="row1">
					{L_START}: <strong>{site.START}</strong>
				</td>		
			</tr>
			<tr>
				<td class="row1">
					{L_VERSION} PHPBoost: <strong>{site.VERSION}</strong>
				</td>		
			</tr>	
		</table>
		# END site #

		# START referer #
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
				var filename = '../includes/xmlhttprequest.php?stats_referer=1&id=' + divid;
				var data = null;
				
				if(window.XMLHttpRequest) // Firefox
				   xhr_object = new XMLHttpRequest();
				else if(window.ActiveXObject) // Internet Explorer
				   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
				else // XMLHttpRequest non support� par le navigateur
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
			# START referer.referer_list #	
			<tr>
				<td class="row2" style="padding:0px;margin:0px;" colspan="5">			
					<table style="width:100%;border:none;border-collapse:collapse;">
						<tr>
							<td style="text-align:center;">		
								{referer.referer_list.IMG_MORE} <span class="text_small">({referer.referer_list.NBR_LINKS})</span> <a href="{referer.referer_list.URL}">{referer.referer_list.URL}</a>	<span id="load{referer.referer_list.ID}"></span>	 			
							</td>
							<td style="width:112px;text-align:center;">
								{referer.referer_list.TOTAL_VISIT}
							</td>
							<td style="width:112px;text-align:center;">
								{referer.referer_list.AVERAGE_VISIT}
							</td>
							<td style="width:102px;text-align:center;">
								{referer.referer_list.LAST_UPDATE}
							</td>
							<td style="width:105px;">
								{referer.referer_list.TREND}
							</td>
						</tr>
					</table>
					<div id="url{referer.referer_list.ID}" style="display:none;width:100%;"></div>					
				</td>	
			</tr>
			# END referer.referer_list #
			<tr>
				<td class="row3" colspan="5" style="text-align:center;">
					{PAGINATION}&nbsp;
				</td>
			</tr>
		</table>
		# END referer #
		
		# START keyword #
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
				var xhr_object = xmlhttprequest_init('../includes/xmlhttprequest.php?stats_keyword=1&id=' + divid);
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
			# START keyword.keyword_list #	
			<tr>
				<td class="row2" style="padding:0px;margin:0px;" colspan="5">			
					<table style="width:100%;border:none;border-collapse:collapse;">
						<tr>
							<td style="text-align:center;">		
								{keyword.keyword_list.IMG_MORE} <span class="text_small">({keyword.keyword_list.NBR_LINKS})</span> {keyword.keyword_list.KEYWORD}	<span id="load{keyword.keyword_list.ID}"></span>	 			
							</td>
							<td style="width:112px;text-align:center;">
								{keyword.keyword_list.TOTAL_VISIT}
							</td>
							<td style="width:112px;text-align:center;">
								{keyword.keyword_list.AVERAGE_VISIT}
							</td>
							<td style="width:102px;text-align:center;">
								{keyword.keyword_list.LAST_UPDATE}
							</td>
							<td style="width:105px;">
								{keyword.keyword_list.TREND}
							</td>
						</tr>
					</table>
					<div id="url{keyword.keyword_list.ID}" style="display:none;width:100%;"></div>					
				</td>	
			</tr>
			# END keyword.keyword_list #
			<tr>
				<td class="row3" colspan="5" style="text-align:center;">
					{PAGINATION}&nbsp;
				</td>
			</tr>
		</table>
		# END keyword #
		
		# START members #
		<table class="module_table">
			<tr>
				<th colspan="2">	
					{L_MEMBERS}
				</th>
			</tr>
			<tr>
				<td class="row1" style="text-align:center;width:25%;">
					{L_MEMBERS}
				</td>
				<td class="row2">
					{members.MEMBERS}
				</td>
			 </tr>
			<tr>
				<td class="row1" style="text-align:center;width:50%;">
					{L_LAST_MEMBER}
				</td>
				<td class="row2">
					<a href="../member/member{members.U_LAST_USER_ID}">{members.LAST_USER}</a>
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
								{L_MEMBERS}
							</td>				
						</tr>
						
						# START members.templates #	
						<tr>
							<td style="text-align:center;" class="row2">			
								{members.templates.THEME} <span class="text_small">({members.templates.PERCENT}%)</span>
							</td>							
							<td style="text-align:center;" class="row2">			
								<div style="margin:auto;width:10px;margin:auto;height:10px;background:{members.templates.COLOR};border:1px solid black;"></div>
							</td>
							<td style="text-align:center;" class="row2">
								{members.templates.NBR_THEME}
							</td>				
						</tr>
						# END members.templates #		
					</table>
				</td>
				<td style="text-align:center;padding-top:30px;vertical-align:top" class="row1">
					<img src="../includes/display_stats.php?theme=1" alt="" />
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
								{L_MEMBERS}
							</td>				
						</tr>
						
						# START members.sex #	
						<tr>
							<td style="text-align:center;" class="row2">			
								{members.sex.SEX} <span class="text_small">({members.sex.PERCENT}%)</span>
							</td>							
							<td style="text-align:center;" class="row2">			
								<div style="margin:auto;width:10px;margin:auto;height:10px;background:{members.sex.COLOR};border:1px solid black;"></div>
							</td>
							<td style="text-align:center;" class="row2">
								{members.sex.NBR_MBR}
							</td>				
						</tr>
						# END members.sex #	
						
					</table>
				</td>
				<td style="text-align:center;padding-top:30px;vertical-align:top" class="row1">
					{members.GRAPH_RESULT_SEX}
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
				<td class="row3" style="text-align:center;">
					N�
				</td>
				<td class="row3" style="text-align:center;">
					{L_PSEUDO}
				</td>
				<td class="row3" style="text-align:center;">
					{L_MSG}
				</td>
			</tr>
			
			# START members.top_poster #			
			<tr>
				<td class="row1" style="text-align:center;">
					{members.top_poster.ID}
				</td>
				<td class="row1" style="text-align:center;">
					<a href="../member/member{members.top_poster.U_MEMBER_ID}">{members.top_poster.LOGIN}</a>
				</td>
				<td class="row1" style="text-align:center;">
					{members.top_poster.USER_POST}
				</td>
			</tr>			
			# END members.top_poster #
			
		</table>
		# END members #

		
		# START robots #
		<form action="admin_stats.php?bot=1#stats" name="form" method="post" style="margin:auto;" onsubmit="return check_form();">
			<table class="module_table">
				<tr> 
					<th colspan="2">
						{L_ROBOTS}
					</th>
				</tr>
				<tr> 
					<td style="width:50%;padding-top:30px;vertical-align:top" class="row1">
						<table class="module_table">						
							<tr>
								<td style="text-align:center;" class="row1">			
									{L_VIEW_NUMBER}
								</td>
								<td style="text-align:center;" class="row1">			
									{L_LAST_UPDATE}
								</td>
								<td style="width:10px;" class="row1">			
									{L_COLORS}
								</td>
								<td style="text-align:center;" class="row1">
									{L_ROBOTS} 
								</td>				
							</tr>
							
							# START robots.list #	
							<tr style="height:35px;">
								<td style="text-align:center;" class="row2">			
									{robots.list.VIEWS}
								</td>
								<td style="text-align:center;" class="row2">			
									{robots.list.DATE}
								</td>
								
								<td style="text-align:center;" class="row2">			
									<div style="margin:auto;width:10px;margin:auto;height:10px;background:{robots.list.COLOR}"></div>
								</td>
								<td style="text-align:center;" class="row2">
									 {robots.list.L_NAME}  <span class="text_small">({robots.list.PERCENT}%)</span>
								</td>				
							</tr>
							# END robots.list #		
						</table>
					</td>
					<td style="text-align:center;padding-top:30px;vertical-align:top" class="row1">
						<img src="../includes/display_stats.php?bot=1" alt="" />
					</td>
				</tr>
			</table>
			<br /><br />
			<fieldset class="fieldset_submit">
				<legend>{L_ERASE_RAPPORT}</legend>
				<input type="submit" name="erase" value="{L_ERASE_RAPPORT}" class="reset" /> 
			</fieldset>
		</form>
		# END robots #
		