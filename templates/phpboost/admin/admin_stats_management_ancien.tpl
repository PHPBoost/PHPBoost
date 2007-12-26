		<table class="module_table">
			<tr>
				<th id="stats" colspan="4">
					{L_STATS}
				</th>
			</tr>
			<tr style="text-align:center;">
				<td style="width:25%;padding:15px;" class="row1">
					<a href="admin_stats.php?site=1#stats"><img src="../templates/{THEME}/images/stats/site.png" alt="" /></a></a>
					<br /><a href="admin_stats.php?site=1#stats">{L_SITE}</a>
				</td>
				<td style="width:25%;padding:15px;" class="row1">
					<a href="admin_stats.php?members=1#stats"><img src="../templates/{THEME}/images/upload/member.png" alt="" /></a>
					<br /><a href="admin_stats.php?members=1#stats">{L_MEMBERS}</a>
				</td>
				<td style="width:25%;" class="row1">
					<a href="admin_stats.php?visit=1#stats"><img src="../templates/{THEME}/images/stats/visitors.png" alt="" /></a>
					<br /><a href="admin_stats.php?visit=1#stats">{L_VISITS}</a>
				</td>				
				<td style="width:25%;padding:15px;" class="row1">
					<a href="admin_stats.php?browser=1#stats"><img src="../templates/{THEME}/images/stats/browsers.png" alt="" /></a>
					<br /><a href="admin_stats.php?browser=1#stats">{L_BROWSERS}</a>
				</td>
			</tr>
			<tr style="text-align:center;">		
				<td style="width:25%;" class="row1">	
					<a href="admin_stats.php?os=1#stats"><img src="../templates/{THEME}/images/stats/os.png" alt="" /></a>
					<br /><a href="admin_stats.php?os=1#stats">{L_OS}</a>		
				</td>
				<td style="width:25%;padding:15px;" class="row1">
					<a href="admin_stats.php?lang=1#stats"><img src="../templates/{THEME}/images/stats/countries.png" alt="" /></a>
					<br /><a href="admin_stats.php?lang=1#stats">{L_LANG}</a>
				</td>
				<td style="width:25%;padding:15px;" class="row1">
					<a href="admin_stats.php?bot=1#stats"><img src="../templates/{THEME}/images/stats/robots.png" alt="" /></a>
					<br /><a href="admin_stats.php?bot=1#stats">{L_ROBOTS}</a>
				</td>
				<td style="width:25%;padding:15px;" class="row1">
					&nbsp;
				</td>
			</tr>
		</table>
		
		<br /><br />

		# START compteur_stats #
			<div class="module_table" style="width:50%;text-align:center;">
				<div class="row1">
					<span class="text_strong">{L_TOTAL}: {compteur_stats.COMPTEUR_TOTAL} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {L_TODAY}: {compteur_stats.COMPTEUR_DAY}</span>
				</div>
			</div>
			<br />
		# END compteur_stats #

		
		# START select_month #		
		<form action="admin_stats.php#stats" method="get">
		<div class="module_table" style="width:50%;text-align:center;">			
			<div class="row1">
				<br />
				<a href="admin_stats{select_month.U_PREVIOUS_MONTH}#stats">&laquo;</a>&nbsp;&nbsp;&nbsp;&nbsp;
				<select class="nav" name="m">
					# START months #
					<option value="{select_month.months.MONTH}" {select_month.months.SELECTED}>{select_month.months.L_MONTH}</option>
					# END months #
					</select>
				<select class="nav" name="y">
					# START years #
					<option value="{select_month.years.YEAR}" {select_month.years.SELECTED}>{select_month.years.YEAR}</option>
					 # END years #
				</select>
				<input type="hidden" name="visit" value="true" />
				<input type="submit" name="date" value="{L_SUBMIT}" class="submit" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="admin_stats{select_month.U_NEXT_MONTH}#stats">&raquo;</a>				
				<br /><br />
			</div>
		</div>
		</form>
		
		<br /><br />
		# END select_month #

		
		# START month #
		<table class="module_table">
			<tr>
				<th>
					{L_VISITORS} {month.MONTH} {month.U_YEAR}
				</th>
			</tr>
			<tr>
				<td class="row1" style="text-align:center;">
					<br />
					<table class="module_table" style="width:400px;margin:auto;">
						<tr>
							<td style="background-color: #000000;width:1px;"></td>
							<td style="height:200px;width:10px;vertical-align:top;text-align:center;font-size:9px;">
								{month.MAX_NBR}
							</td>
								
							# START days #								
							<td style="height:200px;width:10px;vertical-align:bottom;">
								<table class="module_table" style="width:14px;margin:auto;border:none">
									# START head #
									<tr>
										<td style="margin-left:2px;width:10px;height:4px;background-image: url(../templates/{THEME}/images/stats2.gif); background-repeat: no-repeat;">
										</td>
									</tr>
									# END head #
									<tr>
										<td style="margin-left:2px;width:10px;height:{month.days.HEIGHT}px;background-image: url(../templates/{THEME}/images/stats.gif);background-repeat:repeat-y;padding:0px">
										</td>
									</tr>
								</table>
							</td>	
							# END days #
							
							# START end_td #							
								{month.end_td.END_TD}							
							# END end_td #
						</tr>
						<tr>
							<td style="background-color: #000000;width:1px"></td>
							<td style="width:10px;font-size:9px;">
								0
							</td>
								
							# START days_of_month #								
							<td style="text-align:center;width:13px;font-size:9px;">
								{month.days_of_month.DAY}
							</td>								
							# END days_of_month #
								
						</tr>
						<tr>
							<td style="height:1px;background-color: #000000;" colspan="{month.COLSPAN}"></td>
						</tr>
						<tr>
							<td class="row2" style="text-align:center;" colspan="{month.COLSPAN}">
								{L_TOTAL}: {month.SUM_NBR}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{L_AVERAGE}:  {month.MOY_NBR}
							</td>
						</tr>
					</table>
					<br />
				</td>
			</tr>
			<tr>
				<td class="row3" style="text-align:center;">
					{month.U_VISITS_YEAR}
				</td>
			</tr>
		</table>

		<br /><br /><br />

		<table class="module_table" style="width: 300px;">
			<tr>
				<th style="width:50%">
					{L_DAY}
				</th>
				<th style="width:50%">
					{L_VISITS_DAY}
				</th>
			</tr>
			
			# START valeurs #
			<tr>
				<td class="row3" style="font-size:10px;width:50%">
					{month.valeurs.DAY}
				</td>
				<td class="row3" style="font-size:10px;width:50%">
					{month.valeurs.NBR}
				</td>
			</tr>		
			# END valeurs #
		</table>
		# END month #

		
		# START select_year #		
		<form action="admin_stats.php#stats" method="get">
		<div class="module_table" style="width:50%; text-align:center;">			
			<div class="row1">
				<br />
				<a href="admin_stats{select_year.U_PREVIOUS_YEAR}#stats">&laquo;</a>&nbsp;&nbsp;&nbsp;&nbsp;
				<select class="nav" name="year">
					# START years #
					<option value="{select_year.years.YEAR}" {select_year.years.SELECTED}>{select_year.years.YEAR}</option>
					 # END years #
				</select>
				<input name="date" value="{L_SUBMIT}" class="submit" type="submit" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="admin_stats{select_year.U_NEXT_YEAR}#stats">&raquo;</a>
				<br /><br />
			</div>
		</div>
		</form>
		
		<br /><br />
		# END select_year #


		# START year #
		<table class="module_table">
			<tr>
				<th>
					{L_VISITORS} {year.YEAR}
				</th>
			</tr>
			<tr>
				<td style="text-align:center;" class="row1">
					<br />
					<table class="module_table" style="width:300px;margin:auto;">
						<tr>
							<td style="background-color: #000000;width:1px;"></td>
							<td style="height:200px;width:10px;vertical-align:top;text-align:center;font-size:9px;">
								{year.MAX_NBR}
							</td>
								
							# START months #								
							<td style="height:200px;width:10px;vertical-align:bottom;">
								<table class="module_table" style="margin:auto;width:14px;">
									<tr>
										<td style="margin-left:2px; width:10px; height:4px; background-image: url(../templates/{THEME}/images/stats2.gif);background-repeat:no-repeat;">
										</td>
									</tr>
									<tr>
										<td style="margin-left:2px;width:10px;height:{year.months.HEIGHT}px;background-image: url(../templates/{THEME}/images/stats.gif);background-repeat:repeat-y;padding:0px">
										</td>
									</tr>
								</table>						
							</td>														
							# END months #	
							
							# START end_td #							
								{year.end_td.END_TD}							
							# END end_td #	
						</tr>
						<tr>
							<td style="width:1px;background-color: #000000;"></td>
							<td style="width:10px;font-size:9px;">
								0
							</td>
								
							# START months_of_year #								
							<td style="text-align:center;width:13px;font-size:9px;">
								{year.months_of_year.U_MONTH}
							</td>								
							# END months_of_year #
								
						</tr>
						<tr>
							<td style="height:1px;background-color: #000000;" colspan="14"></td>
						</tr>
						<tr>
							<td class="row2" style="text-align:center;" colspan="14">
								{L_TOTAL}: {year.SUM_NBR}&nbsp;&nbsp;&nbsp;{L_AVERAGE}:  {year.MOY_NBR}
							</td>
						</tr>
					</table>
					<br />
				</td>
			</tr>
		</table>

		<br /><br /><br />

		<table class="module_table" style="width: 300px;">
			<tr>
				<th style="width:50%">
					{L_MONTH}
				</th>
				<th style="width:50%">
					{L_VISITS_MONTH}
				</th>
			</tr>
			
			# START valeurs #
			<tr>
				<td class="row3" style="font-size:10px;width:50%">
					{year.valeurs.U_MONTH}
				</td>
				<td class="row3" style="font-size:10px;width:50%">
					{year.valeurs.NBR}
				</td>
			</tr>		
			# END valeurs #
		</table>
		# END year #


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
						# START browsers_list #			
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
						# END browsers_list #
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
						# START os_list #			
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
						# END os_list #
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
						# START lang_list #			
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
						# END lang_list #
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
						
						# START templates #	
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
						# END templates #		
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
						
						# START sex #	
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
						# END sex #	
						
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
					N°
				</td>
				<td class="row3" style="text-align:center;">
					{L_PSEUDO}
				</td>
				<td class="row3" style="text-align:center;">
					{L_MSG}
				</td>
			</tr>
			
			# START top_poster #			
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
			# END top_poster #
			
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
							
							# START list #	
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
							# END list #		
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
		