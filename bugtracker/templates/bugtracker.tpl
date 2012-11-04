<script type="text/javascript">
	<!--
	function Confirm() {
		return confirm("{L_CONFIRM_DEL_BUG}");
	}
	
	function hidediv(id) {
		//safe function to hide an element with a specified id
		if (document.getElementById) { // DOM3 = IE5, NS6
			document.getElementById(id).style.display = 'none';
		}
		else {
			if (document.layers) { // Netscape 4
				document.id.display = 'none';
			}
			else { // IE 4
				document.all.id.style.display = 'none';
			}
		}
	}

	function showdiv(id) {
		//safe function to show an element with a specified id
		if (document.getElementById) { // DOM3 = IE5, NS6
			document.getElementById(id).style.display = 'block';
		}
		else {
			if (document.layers) { // Netscape 4
				document.id.display = 'block';
			}
			else { // IE 4
				document.all.id.style.display = 'block';
			}
		}
	}
	-->
</script>
# START list #
	<div class="module_position">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<ul style="list-style-type:none;">
				<li class="bt_current">
					<a href="{U_BUG_LIST}">{L_UNSOLVED}</a> 
				</li>
				<li class="bt_no_current">
					<a href="{U_BUG_SOLVED}">{L_SOLVED}</a>
				</li>
				# IF C_ROADMAP #
				<li class="bt_no_current">
					<a href="{U_BUG_ROADMAP}">{L_ROADMAP}</a>
				</li>
				# ENDIF #
				<li class="bt_no_current">
					<a href="{U_BUG_STATS}">{L_STATS}</a>
				</li>
				# IF C_ADD #
				<li class="bt_add">
					<a href="{U_BUG_ADD}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" alt="" title="{L_ADD}" class="valign_middle" /></a>
				</li>
				# ENDIF #
			</ul>
		</div>
		<div class="module_contents">
			# INCLUDE message_helper #
			
			<table  class="module_table">
				<tr style="text-align:center;">
					<th style="width:52px;text-align:center;">
						{L_ID}<br />
						<a href="{U_BUG_ID_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_ID_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					<th style="width:52px;text-align:center;">
						{L_TITLE}<br />
						<a href="{U_BUG_TITLE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_TITLE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# IF C_DISPLAY_TYPES #
					<th style="width:66px;text-align:center;">
						{L_TYPE}<br />
						<a href="{U_BUG_TYPE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_TYPE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# ENDIF #
					# IF C_DISPLAY_SEVERITIES #
					<th style="width:70px;text-align:center;">
						{L_SEVERITY}<br />
						<a href="{U_BUG_SEVERITY_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_SEVERITY_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# ENDIF #
					<th style="width:60px;text-align:center;">
						{L_STATUS}<br />
						<a href="{U_BUG_STATUS_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_STATUS_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# IF C_COM #
					<th style="width:84px;text-align:center;">
						{L_COMMENTS}<br />
						<a href="{U_BUG_COMMENTS_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_COMMENTS_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# ENDIF #
					<th style="width:63px;text-align:center;">
						{L_DATE}<br />
						<a href="{U_BUG_DATE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_DATE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# IF C_IS_ADMIN #
					<th style="width:56px;text-align:center;">
						{L_ACTIONS}<br /><br />
					</th>
					# ENDIF #
				</tr>
				# IF C_NO_BUGS #
				<tr style="text-align:center;"> 
					<td colspan="{NO_BUGS_COLSPAN}" class="row2">
						{L_NO_BUG}
					</td>
				</tr>
				# ELSE #
				# START list.bug #
				<tr style="text-align:center;"> 
					<td class="row2" {list.bug.LINE_COLOR}>
						<a href="{list.bug.U_BUG_VIEW}">\#{list.bug.ID}</a>
					</td>
					<td class="row2" {list.bug.LINE_COLOR}>
						{list.bug.TITLE}
					</td>
					# IF C_DISPLAY_TYPES #
					<td class="row2" {list.bug.LINE_COLOR}>
						{list.bug.TYPE}
					</td>
					# ENDIF #
					# IF C_DISPLAY_SEVERITIES #
					<td class="row2" {list.bug.SEVERITY_COLOR}> 
						<b>{list.bug.SEVERITY}</b>
					</td>
					# ENDIF #
					<td class="row2" {list.bug.LINE_COLOR}> 
						{list.bug.STATUS}
					</td>
					# IF C_COM #
					<td class="row2" {list.bug.LINE_COLOR}> 
						{list.bug.COMMENTS}
					</td>
					# ENDIF #
					<td class="row2" {list.bug.LINE_COLOR}>
						{list.bug.DATE}
					</td>
					# IF C_IS_ADMIN #
					<td class="row2" {list.bug.LINE_COLOR}> 
						<a href="{list.bug.U_BUG_REJECT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/unvisible.png" alt="{L_REJECT}" title="{L_REJECT}" /></a>
						<a href="{list.bug.U_BUG_EDIT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
						<a href="{list.bug.U_BUG_HISTORY}"><img src="{PATH_TO_ROOT}/bugtracker/templates/images/history.png" alt="{L_HISTORY}" title="{L_HISTORY}" /></a>
						<a href="{list.bug.U_BUG_DELETE}" onclick="javascript:return Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
					# ENDIF #
				</tr>
				# END list.bug #
				# ENDIF #
			</table>

			<br /><br />
			<p style="text-align: center;">{PAGINATION}</p>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
			<div style="float:left" class="text_small"></div>
			<div style="float:right" class="text_small"></div>
		</div>
	</div>
# END list #

# START solved #
	<div class="module_position">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<ul style="list-style-type:none;">
				<li class="bt_no_current">
					<a href="{U_BUG_LIST}">{L_UNSOLVED}</a> 
				</li>
				<li class="bt_current">
					<a href="{U_BUG_SOLVED}">{L_SOLVED}</a>
				</li>
				# IF C_ROADMAP #
				<li class="bt_no_current">
					<a href="{U_BUG_ROADMAP}">{L_ROADMAP}</a>
				</li>
				# ENDIF #
				<li class="bt_no_current">
					<a href="{U_BUG_STATS}">{L_STATS}</a>
				</li>
				# IF C_ADD #
				<li class="bt_add">
					<a href="{U_BUG_ADD}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" alt="" title="{L_ADD}" class="valign_middle" /></a>
				</li>
				# ENDIF #
			</ul>
		</div>
		<div class="module_contents">
			# INCLUDE message_helper #
			
			<table  class="module_table">
				<tr style="text-align:center;">
					<th style="width:45px;text-align:center;">
						{L_ID}<br />
						<a href="{U_BUG_ID_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_ID_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					<th style="text-align:center;">
						{L_TITLE}<br />
						<a href="{U_BUG_TITLE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_TITLE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# IF C_DISPLAY_TYPES #
					<th style="width:66px;text-align:center;">
						{L_TYPE}<br />
						<a href="{U_BUG_TYPE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_TYPE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# ENDIF #
					# IF C_DISPLAY_SEVERITIES #
					<th style="width:70px;text-align:center;">
						{L_SEVERITY}<br />
						<a href="{U_BUG_SEVERITY_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_SEVERITY_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# ENDIF #
					<th style="width:60px;text-align:center;">
						{L_STATUS}<br />
						<a href="{U_BUG_STATUS_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_STATUS_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# IF C_COM #
					<th style="width:84px;text-align:center;">
						{L_COMMENTS}<br />
						<a href="{U_BUG_COMMENTS_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_COMMENTS_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# ENDIF #
					<th style="width:63px;text-align:center;">
						{L_DATE}<br />
						<a href="{U_BUG_DATE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_DATE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# IF C_IS_ADMIN #
					<th style="width:56px;text-align:center;">
						{L_ACTIONS}<br /><br />
					</th>
					# ENDIF #
				</tr>
				# IF C_NO_BUGS #
				<tr style="text-align:center;"> 
					<td colspan="{NO_BUGS_COLSPAN}" class="row2">
						{L_NO_SOLVED_BUG}
					</td>
				</tr>
				# ELSE #
					# START solved.bugclosed #
					<tr style="text-align:center;"> 
						<td class="row2" {solved.bugclosed.LINE_COLOR}>
							<a href="{solved.bugclosed.U_BUG_VIEW}">\#{solved.bugclosed.ID}</a>
						</td>
						<td class="row2" {solved.bugclosed.LINE_COLOR}>
							{solved.bugclosed.TITLE}
						</td>
						# IF C_DISPLAY_TYPES #
						<td class="row2" {solved.bugclosed.LINE_COLOR}>
							{solved.bugclosed.TYPE}
						</td>
						# ENDIF #
						# IF C_DISPLAY_SEVERITIES #
						<td class="row2" {solved.bugclosed.LINE_COLOR}> 
							<b>{solved.bugclosed.SEVERITY}</b>
						</td>
						# ENDIF #
						<td class="row2" {solved.bugclosed.LINE_COLOR}> 
							{solved.bugclosed.STATUS}
						</td>
						# IF C_COM #
						<td class="row2" {solved.bugclosed.LINE_COLOR}>
							{solved.bugclosed.COMMENTS}
						</td>
						# ENDIF #
						<td class="row2" {solved.bugclosed.LINE_COLOR}>
							{solved.bugclosed.DATE}
						</td>
						# IF C_IS_ADMIN #
						<td class="row2" {solved.bugclosed.LINE_COLOR}> 
							<a href="{solved.bugclosed.U_BUG_REOPEN}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/visible.png" alt="{L_REOPEN}" title="{L_REOPEN}" /></a>
							<a href="{solved.bugclosed.U_BUG_EDIT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
							<a href="{solved.bugclosed.U_BUG_HISTORY}"><img src="{PATH_TO_ROOT}/bugtracker/templates/images/history.png" alt="{L_HISTORY}" title="{L_HISTORY}" /></a>
							<a href="{solved.bugclosed.U_BUG_DELETE}" onclick="javascript:return Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
						</td>
						# ENDIF #
					</tr>
					# END solved.bugclosed #
				# ENDIF #
			</table>
			<br /><br />
			<p style="text-align: center;">{PAGINATION}</p>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom" style="text-align:center;">
			<div style="float:left" class="text_small"></div>
			<div style="float:right" class="text_small"></div>
		</div>
		
	</div>
# END solved #

# START roadmap #
	<div class="module_position">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<ul style="list-style-type:none;">
				<li class="bt_no_current">
					<a href="{U_BUG_LIST}">{L_UNSOLVED}</a> 
				</li>
				<li class="bt_no_current">
					<a href="{U_BUG_SOLVED}">{L_SOLVED}</a>
				</li>
				<li class="bt_current">
					<a href="{U_BUG_ROADMAP}">{L_ROADMAP}</a>
				</li>
				<li class="bt_no_current">
					<a href="{U_BUG_STATS}">{L_STATS}</a>
				</li>
				# IF C_ADD #
				<li class="bt_add">
					<a href="{U_BUG_ADD}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" alt="" title="{L_ADD}" class="valign_middle" /></a>
				</li>
				# ENDIF #
			</ul>
		</div>
		<div class="module_contents">
			# INCLUDE message_helper #
			
			# IF C_DISPLAY_VERSIONS #
			<form action="{U_FORM}" name="form" method="post" class="fieldset_content" id="form">
				<fieldset>
					<legend>{L_CHOOSE_VERSION}</legend>
					<dl>
						<dt><label for="roadmap_version">{L_VERSION}</label></dt>
						<dd><label>
							<select id="roadmap_version" name="roadmap_version">
							# START select_version #
								{select_version.VERSION}
							# END select_version #
							</select>
						</label>
						&nbsp;&nbsp;
						<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
						</dd>
					</dl>
				</fieldset>	
			</form>
			# ENDIF #
			
			<table  class="module_table">
				<tr style="text-align:center;">
					<th style="width:52px;text-align:center;">
						{L_ID}<br />
						<a href="{U_BUG_ID_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_ID_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					<th style="width:52px;text-align:center;">
						{L_TITLE}<br />
						<a href="{U_BUG_TITLE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_TITLE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# IF C_DISPLAY_TYPES #
					<th style="width:66px;text-align:center;">
						{L_TYPE}<br />
						<a href="{U_BUG_TYPE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_TYPE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# ENDIF #
					# IF C_DISPLAY_SEVERITIES #
					<th style="width:70px;text-align:center;">
						{L_SEVERITY}<br />
						<a href="{U_BUG_SEVERITY_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_SEVERITY_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# ENDIF #
					<th style="width:60px;text-align:center;">
						{L_STATUS}<br />
						<a href="{U_BUG_STATUS_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_STATUS_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# IF C_COM #
					<th style="width:84px;text-align:center;">
						{L_COMMENTS}<br />
						<a href="{U_BUG_COMMENTS_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_COMMENTS_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# ENDIF #
					<th style="width:63px;text-align:center;">
						{L_DATE}<br />
						<a href="{U_BUG_DATE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						<a href="{U_BUG_DATE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					<th style="width:56px;text-align:center;">
						{L_HISTORY}<br /><br />
					</th>
				</tr>
				# IF C_NO_BUGS #
				<tr style="text-align:center;"> 
					<td colspan="{NO_BUGS_COLSPAN}" class="row2">
						{L_NO_BUG_FIXED}
					</td>
				</tr>
				# ELSE #
				# START roadmap.bug #
				<tr style="text-align:center;"> 
					<td class="row2" {roadmap.bug.LINE_COLOR}>
						<a href="{roadmap.bug.U_BUG_VIEW}" style="text-decoration:line-through;">\#{roadmap.bug.ID}</a>
					</td>
					<td class="row2" {roadmap.bug.LINE_COLOR}>
						{roadmap.bug.TITLE}
					</td>
					# IF C_DISPLAY_TYPES #
					<td class="row2" {roadmap.bug.LINE_COLOR}>
						{roadmap.bug.TYPE}
					</td>
					# ENDIF #
					# IF C_DISPLAY_SEVERITIES #
					<td class="row2" {roadmap.bug.LINE_COLOR}> 
						<b>{roadmap.bug.SEVERITY}</b>
					</td>
					# ENDIF #
					<td class="row2" {roadmap.bug.LINE_COLOR}> 
						{roadmap.bug.STATUS}
					</td>
					# IF C_COM #
					<td class="row2" {roadmap.bug.LINE_COLOR}> 
						{roadmap.bug.COMMENTS}
					</td>
					# ENDIF #
					<td class="row2" {roadmap.bug.LINE_COLOR}>
						{roadmap.bug.DATE}
					</td>
					<td class="row2" {roadmap.bug.LINE_COLOR}>
						<a href="{roadmap.bug.U_BUG_HISTORY}"><img src="{PATH_TO_ROOT}/bugtracker/templates/images/history.png" alt="{L_HISTORY}" title="{L_HISTORY}" /></a>
					</td>
				</tr>
				# END roadmap.bug #
				# ENDIF #
			</table>
			<br /><br />
			<p style="text-align: center;">{PAGINATION}</p>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom" style="text-align:center;">
			<div style="float:left" class="text_small"></div>
			<div style="float:right" class="text_small"></div>
		</div>
		
	</div>
# END roadmap #

# START stats #
	<div class="module_position">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<ul style="list-style-type:none;">
				<li class="bt_no_current">
					<a href="{U_BUG_LIST}">{L_UNSOLVED}</a> 
				</li>
				<li class="bt_no_current">
					<a href="{U_BUG_SOLVED}">{L_SOLVED}</a>
				</li>
				# IF C_ROADMAP #
				<li class="bt_no_current">
					<a href="{U_BUG_ROADMAP}">{L_ROADMAP}</a>
				</li>
				# ENDIF #
				<li class="bt_current">
					<a href="{U_BUG_STATS}">{L_STATS}</a>
				</li>
				# IF C_ADD #
				<li class="bt_add">
					<a href="{U_BUG_ADD}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" alt="" title="{L_ADD}" class="valign_middle" /></a>
				</li>
				# ENDIF #
			</ul>
		</div>
		<div class="module_contents">
			# INCLUDE message_helper #
			
			<table  class="module_table">
				<tr style="text-align:center;">
					<th style="width:45px;text-align:center;">
						{L_STATUS}
					</th>
					<th style="text-align:center;">
						{L_NUMBER}
					</th>
				</tr>
				# IF C_NO_BUGS #
				<tr style="text-align:center;"> 
					<td colspan="2" class="row2">
						{L_NO_BUG}
					</td>
				</tr>
				# ELSE #
					# START stats.status #
					<tr style="text-align:center;"> 
						<td class="row2"> 
							<b>{stats.status.NAME}</b>
						</td>
						<td class="row2"> 
							{stats.status.NUMBER}
						</td>
					</tr>
					# END stats.status #
				# ENDIF #
			</table>
			<br /><br />
			# IF C_DISPLAY_VERSIONS #
			<table  class="module_table">
				<tr style="text-align:center;">
					<th style="width:45px;text-align:center;">
						{L_VERSION}
					</th>
					<th style="text-align:center;">
						{L_NUMBER_CORRECTED}
					</th>
				</tr>
				# IF C_NO_FIXED_BUGS #
				<tr style="text-align:center;"> 
					<td colspan="2" class="row2">
						{L_NO_BUG_SOLVED}
					</td>
				</tr>
				# ELSE #
					# START stats.fixed_version #
					<tr style="text-align:center;"> 
						<td class="row2"> 
							<b>{stats.fixed_version.NAME}</b>
						</td>
						<td class="row2"> 
							{stats.fixed_version.NUMBER}
						</td>
					</tr>
					# END stats.fixed_version #
				# ENDIF #
			</table>
			<br /><br />
			# ENDIF #
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
						{L_NUMBER}
					</td>
				</tr>
				# IF C_NO_BUGS_NOT_REJECTED #
				<tr style="text-align:center;"> 
					<td colspan="3" class="row2">
						{L_NO_BUG}
					</td>
				</tr>
				# ELSE #
					# START stats.top_poster #
					<tr>
						<td class="row2" style="text-align:center;">
							{stats.top_poster.ID}
						</td>
						<td class="row2" style="text-align:center;">
							<a href="{stats.top_poster.U_USER_PROFILE}">{stats.top_poster.LOGIN}</a>
						</td>
						<td class="row2" style="text-align:center;">
							{stats.top_poster.USER_BUGS}
						</td>
					</tr>
					# END stats.top_poster #
				# ENDIF #
			</table>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom" style="text-align:center;">
			<div style="float:left" class="text_small"></div>
			<div style="float:right" class="text_small"></div>
		</div>
	</div>
# END stats #

# START add #
	<script type="text/javascript">
	<!--
	function check_form()
	{
		# IF C_BBCODE_TINYMCE_MODE #
		tinyMCE.triggerSave();
		# ENDIF #

		if(document.getElementById('title').value == "") {
			alert("{L_REQUIRE_TITLE}");
			return false;
		}
		if(document.getElementById('contents').value == "") {
			alert("{L_REQUIRE_TEXT}");
			return false;
		}
		# IF C_TYPE_MANDATORY #
		if(document.getElementById('type').value == "") {
			alert("{L_REQUIRE_TYPE}");
			return false;
		}
		# ENDIF #
		# IF C_CATEGORY_MANDATORY #
		if(document.getElementById('category').value == "") {
			alert("{L_REQUIRE_CATEGORY}");
			return false;
		}
		# ENDIF #
		# IF C_SEVERITY_MANDATORY #
		if(document.getElementById('severity').value == "") {
			alert("{L_REQUIRE_SEVERITY}");
			return false;
		}
		# ENDIF #
		# IF C_PRIORITY_MANDATORY #
		if(document.getElementById('priority').value == "") {
			alert("{L_REQUIRE_PRIORITY}");
			return false;
		}
		# ENDIF #
		# IF C_DETECTED_IN_MANDATORY #
		if(document.getElementById('detected_in').value == "") {
			alert("{L_REQUIRE_DETECTED_IN}");
			return false;
		}
		# ENDIF #
		return true;
	}

	function bbcode_page()
	{
		var page = prompt("{L_PAGE_PROMPT}");
		if( page != null && page != '' )
			insertbbcode('[page]' + page, '[/page]', 'contents');
	}
	-->
	</script>
		# INCLUDE message_helper #
		
		<form action="{U_FORM}" name="form" method="post" onsubmit="return check_form();" class="fieldset_content" id="form">
			<fieldset>
				<legend>{L_ADD_BUG}</legend>
				<p>{L_REQUIRE}</p>
				<dl>
					<dt><label for="title">* {L_TITLE}</label></dt>
					<dd><label><input type="text" size="50" maxlength="200" id="title" name="title" class="text" /></label></dd>
				</dl>
				<label for="contents">* {L_CONTENT}</label>
				<br /><span>{L_CONTENT_EXPLAIN}</span>
				<div style="position:relative;display:none;" id="loading_previewcontents">
					<div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading.gif" alt="" /></div>
				</div>
				<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_previewcontents"></div>
				{CONTENTS_KERNEL_EDITOR}
				<label><textarea rows="20" cols="86" id="contents" name="contents">{CONTENTS}</textarea></label>
				<div style="text-align:center;"><input type="button" value="{L_PREVIEW}" onclick="XMLHttpRequest_preview('contents');" class="submit" /></div>
				<br />
				# IF C_DISPLAY_TYPES #
				<dl>
					<dt><label for="type"># IF C_TYPE_MANDATORY #* # ENDIF #{L_TYPE}</label></dt>
					<dd><label>
						<select id="type" name="type">
						# START select_type #
							{select_type.TYPE}
						# END select_type #
						</select>
					</label></dd>
				</dl>
				# ENDIF #
				# IF C_DISPLAY_CATEGORIES #
				<dl>
					<dt><label for="category"># IF C_CATEGORY_MANDATORY #* # ENDIF #{L_CATEGORY}</label></dt>
					<dd><label>
						<select id="category" name="category">
						# START select_category #
							{select_category.CATEGORY}
						# END select_category #
						</select>
					</label></dd>
				</dl>
				# ENDIF #
				# IF C_DISPLAY_ADVANCED #
				# IF C_DISPLAY_SEVERITIES #
				<dl>
					<dt><label for="severity"># IF C_SEVERITY_MANDATORY #* # ENDIF #{L_SEVERITY}</label></dt>
					<dd><label>
						<select id="severity" name="severity">
						# START select_severity #
							{select_severity.SEVERITY}
						# END select_severity #
						</select>
					</label></dd>
				</dl>
				# ENDIF #
				# IF C_DISPLAY_PRIORITIES #
				<dl>
					<dt><label for="priority"># IF C_PRIORITY_MANDATORY #* # ENDIF #{L_PRIORITY}</label></dt>
					<dd><label>
						<select id="priority" name="priority">
						# START select_priority #
							{select_priority.PRIORITY}
						# END select_priority #
						</select>
					</label></dd>
				</dl>
				# ENDIF #
				# ENDIF #
				# IF C_DISPLAY_VERSIONS #
				<dl>
					<dt><label for="detected_in"># IF C_DETECTED_IN_MANDATORY #* # ENDIF #{L_DETECTED_IN}</label></dt>
					<dd><label>
						<select id="detected_in" name="detected_in">
						# START select_detected_in #
							{select_detected_in.VERSION}
						# END select_detected_in #
						</select>
					</label></dd>
				</dl>
				# ENDIF #
				<dl>
					<dt><label for="reproductible">{L_REPRODUCTIBLE}</label></dt>
					<dd>
						<label><input type="radio" {REPRODUCTIBLE_ENABLED} name="reproductible" id="reproductible" onclick="javascript:showdiv('reproduction');" value="1" /> {L_YES}</label>
						&nbsp;&nbsp;
						<label><input type="radio" {REPRODUCTIBLE_DISABLED} name="reproductible" onclick="javascript:hidediv('reproduction');" value="0" /> {L_NO}</label>
					</dd>
				</dl>
				<div id="reproduction">
					<label for="reproduction_method">{L_REPRODUCTION_METHOD}</label>
					<div style="position:relative;display:none;" id="loading_previewreproduction_method">
						<div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading.gif" alt="" /></div>
					</div>
					<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_previewreproduction_method"></div>
					{METHOD_KERNEL_EDITOR}
					<label><textarea rows="20" cols="86" id="reproduction_method" name="reproduction_method">{REPRODUCTION_METHOD}</textarea></label>
					<div style="text-align:center;"><input type="button" value="{L_PREVIEW}" onclick="XMLHttpRequest_preview('reproduction_method');" class="submit" /></div>
					<br />
				</div>
				<br />
			</fieldset>	
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="valid_add" value="{L_SUBMIT}" class="submit" />
				&nbsp;
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>	
		</form>
# END add #

# START edit #
	<script type="text/javascript">
	<!--
	function check_form()
	{
		# IF C_BBCODE_TINYMCE_MODE #
		tinyMCE.triggerSave();
		# ENDIF #

		if(document.getElementById('title').value == "") {
			alert("{L_REQUIRE_TITLE}");
			return false;
		}
		if(document.getElementById('contents').value == "") {
			alert("{L_REQUIRE_CONTENT}");
			return false;
		}
		# IF C_TYPE_MANDATORY #
		if(document.getElementById('type').value == "") {
			alert("{L_REQUIRE_TYPE}");
			return false;
		}
		# ENDIF #
		# IF C_CATEGORY_MANDATORY #
		if(document.getElementById('category').value == "") {
			alert("{L_REQUIRE_CATEGORY}");
			return false;
		}
		# ENDIF #
		# IF C_SEVERITY_MANDATORY #
		if(document.getElementById('severity').value == "") {
			alert("{L_REQUIRE_SEVERITY}");
			return false;
		}
		# ENDIF #
		# IF C_PRIORITY_MANDATORY #
		if(document.getElementById('priority').value == "") {
			alert("{L_REQUIRE_PRIORITY}");
			return false;
		}
		# ENDIF #
		# IF C_DETECTED_IN_MANDATORY #
		if(document.getElementById('detected_in').value == "") {
			alert("{L_REQUIRE_DETECTED_IN}");
			return false;
		}
		# ENDIF #
		return true;
	}

	function bbcode_page()
	{
		var page = prompt("{L_PAGE_PROMPT}");
		if( page != null && page != '' )
			insertbbcode('[page]' + page, '[/page]', 'contents');
	}
	
	# IF edit.C_IS_ASSIGNED #
	function XMLHttpRequest_search()
	{
		var login = document.getElementById("login").value;
		if( login != "" )
		{
			if( document.getElementById('loading_members') )
				document.getElementById('loading_members').innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
			
			data = 'login=' + login;
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/kernel/framework/ajax/member_xmlhttprequest.php?token={TOKEN}&insert_member=1');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{
					document.getElementById("xmlhttprequest_result_search").innerHTML = xhr_object.responseText;
					show_div("xmlhttprequest_result_search");
					if( document.getElementById('loading_members') )
						document.getElementById('loading_members').innerHTML = '';
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
				{	
					if( document.getElementById('loading_members') )
						document.getElementById('loading_members').innerHTML = '';
				}
			}
			xmlhttprequest_sender(xhr_object, data);
		}	
		else
			alert("{L_REQUIRE_LOGIN}");
	}
	# ENDIF #
	-->
	</script>

		# INCLUDE message_helper #
		
		<form action="{U_FORM}" name="form" method="post" onsubmit="return check_form();" class="fieldset_content" id="form">
			# IF edit.C_IS_ADMIN #
			<fieldset>
			<legend>{L_BUG_TREATMENT}</legend>
				<dl>
					<dt>
						<label for="status">{L_FIXED}</label><br />
					</dt>
					<dd>
						<span><input type="radio" name="status" value="fixed" # IF edit.C_IS_FIXED #checked=checked# ENDIF # /> {L_YES}</span>
						<span style="margin-left:30px;"><input type="radio" name="status" value="{edit.STATUS}" # IF NOT edit.C_IS_FIXED #checked=checked# ENDIF # /> {L_NO}</span>
					</dd>
				</dl>
				<dl>
					<dt><label for="assigned_to">{L_ASSIGNED_TO}</label><br /><span>{L_JOKER}</span></dt>
					<dd>
						<input type="text" size="20" maxlength="25" id="login" value="{edit.ASSIGNED_TO}" name="assigned_to" class="text" /> 
						<span id="loading_members"></span>
						<script type="text/javascript">
						<!--
							document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search();" type="button" class="submit">');
						-->
						</script>
						<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
					</dd>
				</dl>
				# IF C_DISPLAY_VERSIONS #
				<dl>
					<dt><label for="fixed_in"># IF C_ROADMAP #* # ENDIF #{L_FIXED_IN}</label></dt>
					<dd><label>
						<select id="fixed_in" name="fixed_in">
						# START edit.select_fixed_in #
							{edit.select_fixed_in.VERSION}
						# END edit.select_fixed_in #
						</select>
					</label></dd>
				</dl>
				# ENDIF #
			</fieldset>
			# ENDIF #
			
			<fieldset>
			<legend>{L_BUG_INFOS}</legend>
				<p>{L_REQUIRE}</p>
				# IF edit.C_IS_ADMIN #
				<dl>
					<dt><label for="author">{L_AUTHOR}</label></dt>
					<dd><label>{edit.AUTHOR}</label></dd>
				</dl>
				# ENDIF #
				<dl>
					<dt><label for="title">* {L_TITLE}</label></dt>
					<dd><label><input type="text" size="50" maxlength="200" id="title" name="title" value="{edit.TITLE}" class="text" /></label></dd>
				</dl>
				<label for="contents">* {L_CONTENT}</label>
				<br /><span>{L_CONTENT_EXPLAIN}</span>
				<div style="position:relative;display:none;" id="loading_previewcontents">
					<div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading.gif" alt="" /></div>
				</div>
				<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_previewcontents"></div>
				{CONTENTS_KERNEL_EDITOR}
				<label><textarea rows="20" cols="86" id="contents" name="contents">{edit.CONTENTS}</textarea></label>
				<div style="text-align:center;"><input type="button" value="{L_PREVIEW}" onclick="XMLHttpRequest_preview('contents');" class="submit" /></div>
				<br />
				# IF C_DISPLAY_TYPES #
				<dl>
					<dt><label for="type"># IF C_TYPE_MANDATORY #* # ENDIF #{L_TYPE}</label></dt>
					<dd><label>
						<select id="type" name="type">
						# START edit.select_type #
							{edit.select_type.TYPE}
						# END edit.select_type #
						</select>
					</label></dd>
				</dl>
				# ENDIF #
				# IF C_DISPLAY_CATEGORIES #
				<dl>
					<dt><label for="category"># IF C_CATEGORY_MANDATORY #* # ENDIF #{L_CATEGORY}</label></dt>
					<dd><label>
						<select id="category" name="category">
						# START edit.select_category #
							{edit.select_category.CATEGORY}
						# END edit.select_category #
						</select>
					</label></dd>
				</dl>
				# ENDIF #
				# IF C_DISPLAY_ADVANCED #
				# IF C_DISPLAY_SEVERITIES #
				<dl>
					<dt><label for="severity"># IF C_SEVERITY_MANDATORY #* # ENDIF #{L_SEVERITY}</label></dt>
					<dd><label>
						<select id="severity" name="severity">
						# START edit.select_severity #
							{edit.select_severity.SEVERITY}
						# END edit.select_severity #
						</select>
					</label></dd>
				</dl>
				# ENDIF #
				# IF C_DISPLAY_PRIORITIES #
				<dl>
					<dt><label for="priority"># IF C_PRIORITY_MANDATORY #* # ENDIF #{L_PRIORITY}</label></dt>
					<dd><label>
						<select id="priority" name="priority">
						# START edit.select_priority #
							{edit.select_priority.PRIORITY}
						# END edit.select_priority #
						</select>
					</label></dd>
				</dl>
				# ENDIF #
				# ENDIF #
				# IF C_DISPLAY_VERSIONS_DETECTED_IN #
				<dl>
					<dt><label for="detected_in"># IF C_DETECTED_IN_MANDATORY #* # ENDIF #{L_DETECTED_IN}</label></dt>
					<dd><label>
						<select id="detected_in" name="detected_in">
						# START edit.select_detected_in #
							{edit.select_detected_in.VERSION}
						# END edit.select_detected_in #
						</select>
					</label></dd>
				</dl>
				# ENDIF #
				<dl>
					<dt><label for="reproductible">{L_REPRODUCTIBLE}</label></dt>
					<dd>
						<label><input type="radio" {edit.REPRODUCTIBLE_ENABLED} name="reproductible" id="reproductible" onclick="javascript:showdiv('reproduction');" value="1" /> {L_YES}</label>
						&nbsp;&nbsp;
						<label><input type="radio" {edit.REPRODUCTIBLE_DISABLED} name="reproductible" onclick="javascript:hidediv('reproduction');" value="0" /> {L_NO}</label>
					</dd>
				</dl>
				<div id="reproduction" # IF NOT C_REPRODUCTIBLE #style="display:none;"# ENDIF #>
					<label for="reproduction_method">{L_REPRODUCTION_METHOD}</label>
					<div style="position:relative;display:none;" id="loading_previewreproduction_method">
						<div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading.gif" alt="" /></div>
					</div>
					<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_previewreproduction_method"></div>
					{METHOD_KERNEL_EDITOR}
					<label><textarea rows="20" cols="86" id="reproduction_method" name="reproduction_method">{edit.REPRODUCTION_METHOD}</textarea></label>
					<div style="text-align:center;"><input type="button" value="{L_PREVIEW}" onclick="XMLHttpRequest_preview('reproduction_method');" class="submit" /></div>
					<br />
				</div>
				<br />
			</fieldset>	
			
			<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
				<input type="hidden" name="id" value="{edit.ID}" />
				# IF C_BACK #<input type="hidden" name="back" value="{BACK}" /># ENDIF #
				# IF NOT C_DISPLAY_ADVANCED #
					<input type="hidden" name="severity" value="{edit.SEVERITY}" />
					<input type="hidden" name="priority" value="{edit.PRIORITY}" />
				# ENDIF #
				<input type="submit" name="valid_edit" value="{L_UPDATE}" class="submit" />
				&nbsp;
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>
		</form>
		</div>
# END edit #

# START view #
	<div class="module_position">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<div class="module_top_title">{L_VIEW_BUG} \#{view.ID}</div>
			<div class="module_top_com">
				# IF U_COM #
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png" alt="" />
					{U_COM}
				# ENDIF #
				# IF C_REOPEN_BUG #
					<a href="{U_BUG_REOPEN}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/visible.png" alt="{L_REOPEN}" title="{L_REOPEN}" />
					</a>
				# ENDIF #
				# IF C_REJECT_BUG #
					<a href="{U_BUG_REJECT}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/unvisible.png" alt="{L_REJECT}" title="{L_REJECT}" />
					</a>
				# ENDIF #
				# IF C_EDIT_BUG #
					<a href="{U_BUG_EDIT}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" />
					</a>
				# ENDIF #
				# IF C_HISTORY_BUG #
					<a href="{U_BUG_HISTORY}">
						<img src="{PATH_TO_ROOT}/bugtracker/templates/images/history.png" alt="{L_HISTORY}" title="{L_HISTORY}" />
					</a>
				# ENDIF #
				# IF C_DELETE_BUG #
					<a href="{U_BUG_DELETE}" onclick="javascript:return Confirm();">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" />
					</a>
				# ENDIF #
			</div>
			<div class="spacer"></div>
           	</div>
		<div class="module_contents">
			# INCLUDE message_helper #
			
			<fieldset>
				<legend>{L_BUG_TREATMENT_STATE}</legend>
				<br />
				<dl>
					<dt>{L_STATUS}</dt>
					<dd>{view.STATUS}</dd>
				</dl>
				<dl>
					<dt>{L_ASSIGNED_TO}</dt>
					<dd>{view.USER_ASSIGNED}</dd>
				</dl>
				# IF NOT C_EMPTY_VERSIONS #
				<dl>
					<dt>{L_FIXED_IN}</dt>
					<dd>{view.FIXED_IN}</dd>
				</dl>
				# ENDIF #
			</fieldset>
			
			<fieldset>
				<legend>{view.TITLE}</legend>
				<br />
				{view.CONTENTS}
				<br /><br />
			</fieldset>
			
			<fieldset>
				<legend>{L_BUG_INFOS}</legend>
				<br />
				# IF NOT C_EMPTY_TYPES #
				<dl>
					<dt>{L_TYPE}</dt>
					<dd>{view.TYPE}</dd>
				</dl>
				# ENDIF #
				# IF NOT C_EMPTY_CATEGORIES #
				<dl>
					<dt>{L_CATEGORY}</dt>
					<dd>{view.CATEGORY}</dd>
				</dl>
				# ENDIF #
				<dl>
					<dt>{L_SEVERITY}</dt>
					<dd>{view.SEVERITY}</dd>
				</dl>
				# IF NOT C_EMPTY_PRIORITIES #
				<dl>
					<dt>{L_PRIORITY}</dt>
					<dd>{view.PRIORITY}</dd>
				</dl>
				# ENDIF #
				# IF NOT C_EMPTY_VERSIONS #
				<dl>
					<dt>{L_DETECTED_IN}</dt>
					<dd>{view.DETECTED_IN}</dd>
				</dl>
				# ENDIF #
				<dl>
					<dt>{L_REPRODUCTIBLE}</dt>
					<dd>{view.REPRODUCTIBLE}</dd>
				</dl>
			</fieldset>
			
			# IF C_REPRODUCTIBLE #
			<fieldset>
				<legend>{L_REPRODUCTION_METHOD}</legend>
				<br />
				{view.REPRODUCTION_METHOD}
				<br /><br />
			</fieldset>
			# ENDIF #
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
			<div style="float:left" class="text_small">
				&nbsp;
			</div>
			<div style="float:right" class="text_small">
				{L_DETECTED_BY}: {view.AUTHOR}, {L_ON}: {view.SUBMIT_DATE}
			</div>
		</div>
	</div>

	<br /><br />
	{COMMENTS}
# END view #

# START history #
	<div class="module_position">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<div class="module_top_title">
				{L_HISTORY_BUG} : \#{ID}
			</div>
		</div>
		<div class="module_contents">
			<table  class="module_table">
				<tr style="text-align:center;">
					<th>
						{L_UPDATER}
					</th>
					<th>
						{L_UPDATED_FIELD}
					</th>
					<th>
						{L_OLD_VALUE}
					</th>
					<th>
						{L_NEW_VALUE}
					</th>
					<th>
						{L_DATE}
					</th>
					<th>
						{L_COMMENT}
					</th>
				</tr>
				# IF C_NO_HISTORY #
				<tr style="text-align:center;"> 
					<td colspan="6" class="row2">
						{L_NO_HISTORY}
					</td>
				</tr>
				# ELSE #
				# START history.bug #
				<tr style="text-align:center;"> 
					<td class="row2"> 
						{history.bug.UPDATER}
					</td>
					<td class="row2"> 
						{history.bug.UPDATED_FIELD}
					</td>
					<td class="row2"> 
						{history.bug.OLD_VALUE}
					</td>
					<td class="row2"> 
						{history.bug.NEW_VALUE}
					</td>	
					<td class="row2">
						{history.bug.DATE}
					</td>
					<td class="row2">
						{history.bug.COMMENT}
					</td>
				</tr>
				# END history.bug #
				# ENDIF #
			</table>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
	</div>
# END history #
