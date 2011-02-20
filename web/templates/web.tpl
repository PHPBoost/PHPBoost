		{JAVA} 

		# IF C_WEB_CAT #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<strong>{L_CATEGORIES}</strong> # IF C_IS_ADMIN # &nbsp;&nbsp;<a href="admin_web_cat.php" title=""><img src="../templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" /></a> # ENDIF #
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
			<div class="module_contents">
				# START cat_list #
				<div style="float:left;text-align:center;width:{cat_list.WIDTH}%;">
					{cat_list.U_IMG_CAT}
					<a href="../web/web{cat_list.U_WEB_CAT}">{cat_list.CAT}</a> <span class="text_small">({cat_list.TOTAL})</span><br />
					<span class="text_small">{cat_list.CONTENTS}</span>
					<br /><br /><br />
				</div>	
				# END cat_list #
				
				<div class="text_small" style="padding-top:10px;text-align:center;clear:both">
					{TOTAL_FILE} {L_HOW_LINK}
				</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
		</div>
		# ENDIF #

		
		
		# IF C_WEB_LINK #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<strong>{CAT_NAME}</strong>  # IF C_IS_ADMIN # &nbsp;&nbsp;<a href="admin_web_cat.php" title=""><img src="../templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" /></a> # ENDIF #
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
			<div class="module_contents">
				<table class="module_table">
					<tr>
						<th style="text-align:center;">
							<a href="web{U_WEB_ALPHA_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
							{L_LINK}
							<a href="web{U_WEB_ALPHA_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web{U_WEB_DATE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
							{L_DATE}					
							<a href="web{U_WEB_DATE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web{U_WEB_VIEW_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
							{L_VIEW}					
							<a href="web{U_WEB_VIEW_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web{U_WEB_NOTE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
							{L_NOTE}					
							<a href="web{U_WEB_NOTE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web{U_WEB_COM_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
							{L_COM}
							<a href="web{U_WEB_COM_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
						</th>
					</tr>
					# START web #
					<tr>	
						<td class="row2">
							&raquo; <a href="web{web.U_WEB_LINK}">{web.NAME}</a>
						</td>
						<td class="row2" style="text-align: center;">
							{web.DATE}
						</td>
						<td class="row2" style="text-align: center;">
							{web.COMPT} 
						</td>
						<td class="row2" style="text-align: center;">
							{web.NOTE}
						</td>
						<td class="row2" style="text-align: center;">
							{web.COM} 
						</td>
					</tr>
					# END web #
				</table>
				<p style="text-align:center;padding:6px;">{NO_CAT}</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left">
					<strong>{CAT_NAME}</strong>
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
		</div>		
		# ENDIF #

		

		# IF C_DISPLAY_WEB #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<strong>{NAME}</strong>
				</div>
				<div style="float:right">
					{COM} {EDIT}{DEL}
				</div>
			</div>
			<div class="module_contents">
				<p>					
					<strong>{L_DESC}:</strong> {CONTENTS}
					<br /><br />
					<strong>{L_CAT}:</strong> 
					
					<a href="../web/web{U_WEB_CAT}" title="{CAT}">{CAT}</a><br />
					
					<strong>{L_DATE}:</strong> {DATE}<br />						
					<strong>{L_VIEWS}:</strong> {COMPT} {L_TIMES}
					
					<span class="spacer">&nbsp;</span>
				</p>
				<p class="text_center">					
					<a href="{URL}" title="{NAME}" onclick="document.location = 'count.php?id={IDWEB}';"><img src="{PICTURES_DATA_PATH}/images/{LANG}/bouton_url.gif" alt="" /></a>
				</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom text_small">
				{KERNEL_NOTATION}
			</div>
		</div>
			
		<br /><br />
		{COMMENTS}
		# ENDIF #
		
		