		{JAVA} 

		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left" class="text_strong">
					<a href="articles.php{SID}">{L_ARTICLES_INDEX}</a> &raquo; {U_ARTICLES_CAT_LINKS} {ADD_ARTICLES}
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
			<div class="module_contents">
				# START cat #
				<table class="module_table">
					<tr>
						<th colspan="{COLSPAN}">
							{L_CATEGORIES} {cat.EDIT}
						</th>
					</tr>
					
					# START cat.list #
					{cat.list.TR_START}								
						<td class="row2" style="vertical-align:bottom;text-align:center;width:{COLUMN_WIDTH_CATS}%">
							{cat.list.ICON_CAT}
							<a href="articles{cat.list.U_CAT}">{cat.list.CAT}</a> {cat.list.EDIT}
							<br />
							<span class="text_small">{cat.list.DESC}</span> 
							<br />
							<span class="text_small">{cat.list.L_NBR_ARTICLES}</span> 
						</td>	
					{list.TR_END}
					# END cat.list #						
				
					# START cat.end_td #
						{cat.end_td.TD_END}
					{cat.end_td.TR_END}
					# END cat.end_td #
					
				</table>	
				# END cat #
				
				# START link #
				<br />
				<table class="module_table">
					<tr>
						<th colspan="6">
							{link.CAT} &nbsp;{link.EDIT}
						</th>	
					</tr>
					<tr>
						<td colspan="6" class="row3">
							{PAGINATION}
						</td>	
					</tr>
					<tr style="font-weight:bold;text-align: center;">
						<td class="row2">
							<a href="articles{U_ARTICLES_ALPHA_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_ARTICLES}
							<a href="articles{U_ARTICLES_ALPHA_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</td>
						<td class="row2">
							<a href="articles{U_ARTICLES_DATE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_DATE}					
							<a href="articles{U_ARTICLES_DATE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</td>
						<td class="row2">
							<a href="articles{U_ARTICLES_VIEW_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_VIEW}					
							<a href="articles{U_ARTICLES_VIEW_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</td>
						<td class="row2">
							<a href="articles{U_ARTICLES_NOTE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_NOTE}					
							<a href="articles{U_ARTICLES_NOTE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</td>
						<td class="row2">
							<a href="articles{U_ARTICLES_COM_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_COM}
							<a href="articles{U_ARTICLES_COM_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</td>
					</tr>
					# START link.articles #
					<tr>	
						<td class="row2" style="padding-left:25px">
							{link.articles.ICON} &nbsp;&nbsp;<a href="articles{link.articles.U_ARTICLES_LINK}">{link.articles.NAME}</a>
						</td>
						<td class="row2" style="text-align: center;">
							{link.articles.DATE}
						</td>
						<td class="row2" style="text-align: center;">
							{link.articles.COMPT} 
						</td>
						<td class="row2" style="text-align: center;">
							{link.articles.NOTE}
						</td>
						<td class="row2" style="text-align: center;">
							{link.articles.COM} 
						</td>
					</tr>
					# END link.articles #
					<tr>
						<td colspan="6" class="row3">
							{PAGINATION}
						</td>	
					</tr>
				</table>
				<br />
				# END link #
				
				<p style="text-align:center" class="text_small">
					{L_NO_ARTICLES} {L_TOTAL_ARTICLE}
				</p>
				&nbsp;
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left" class="text_strong">
					<a href="articles.php{SID}">{L_ARTICLES_INDEX}</a> &raquo; {U_ARTICLES_CAT_LINKS} {ADD_ARTICLES}
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
		</div>
		