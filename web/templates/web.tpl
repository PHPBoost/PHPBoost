		{JAVA} 

		# START cat #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<strong>{cat.L_CATEGORIES}</strong> {cat.EDIT}
				</div>
				<div style="float:right">
					{cat.PAGINATION}
				</div>
			</div>
			<div class="module_contents">
				# START cat.web #
				<div style="float:left;text-align:center;width:{cat.web.WIDTH}%;height:80px;">
					{cat.web.U_IMG_CAT}
					<a href="../web/web{cat.web.U_WEB_CAT}">{cat.web.CAT}</a> ({cat.web.TOTAL})<br />
					<span class="text_small">{cat.web.CONTENTS}</span>
					<br /><br /><br />
				</div>	
				# END cat.web #
				
				<div class="text_small" style="text-align:center;clear:both">
					{cat.TOTAL_FILE} {cat.L_HOW_LINK}
				</div>
				<div class="spacer">&nbsp;</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:right">
					{cat.PAGINATION}
				</div>
			</div>
		</div>
		# END cat #

		
		
		# START link #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<strong>{link.CAT_NAME}</strong>
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
			<div class="module_contents">
				<table class="module_table">
					<tr>
						<th style="text-align:center;">
							<a href="web{U_WEB_ALPHA_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_LINK}
							<a href="web{U_WEB_ALPHA_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web{U_WEB_DATE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_DATE}					
							<a href="web{U_WEB_DATE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web{U_WEB_VIEW_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_VIEW}					
							<a href="web{U_WEB_VIEW_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web{U_WEB_NOTE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_NOTE}					
							<a href="web{U_WEB_NOTE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web{U_WEB_COM_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_COM}
							<a href="web{U_WEB_COM_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</th>
					</tr>
					# START link.web #
					<tr>	
						<td class="row2">
							&raquo; <a href="web{link.web.U_WEB_LINK}">{link.web.NAME}</a>
						</td>
						<td class="row2" style="text-align: center;">
							{link.web.DATE}
						</td>
						<td class="row2" style="text-align: center;">
							{link.web.COMPT} 
						</td>
						<td class="row2" style="text-align: center;">
							{link.web.NOTE}
						</td>
						<td class="row2" style="text-align: center;">
							{link.web.COM} 
						</td>
					</tr>
					# END link.web #
				</table>
				<p style="text-align:center;padding:6px;">{link.NO_CAT}</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left">
					<strong>{link.CAT_NAME}</strong>
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
		</div>		
		# END link #

		

		# START web #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<strong>{web.NAME}</strong> {EDIT}{DEL}
				</div>
				<div style="float:right">
					{web.COM}
				</div>
			</div>
			<div class="module_contents">
				<p>					
					<strong>{web.L_DESC}:</strong> {web.CONTENTS}
					<br /><br />
					<strong>{web.L_CAT}:</strong> 
					<a href="../web/web{web.U_WEB_CAT}" title="{web.CAT}">{web.CAT}</a><br />
					
					<strong>{web.L_DATE}:</strong> {web.DATE}<br />						
					<strong>{web.L_VIEWS}:</strong> {web.COMPT} {web.L_TIMES}
					<div class="spacer">&nbsp;</div>
				</p>
				<p style="text-align: center;">					
					<a href="{web.URL}" title="{web.NAME}" onclick="document.location = 'count.php?id={web.IDWEB}';"><img src="{web.MODULE_DATA_PATH}/images/{web.LANG}/bouton_url.gif" alt="" /></a>
					<br />
					{web.URL}
				</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<strong>{web.L_NOTE}:</strong> {web.NOTE}&nbsp;
			</div>
		</div>
			
		<br /><br />
		# INCLUDE handle_com #
		
		# END web #


		# START note #
		<form action="../web/web{note.U_WEB_ACTION_NOTE}" method="post" class="fieldset_content">
			<span id="note"></span>
			<fieldset>
				<legend>{L_NOTE}</legend>
				<dl>
					<dt><label for="note_select">{L_NOTE}</label></dt>
					<dd>
						<span class="text_small">{L_ACTUAL_NOTE}: {note.NOTE}</span>	
						<label>
							<select id="note_select" name="note">
								{note.SELECT}
							</select>
						</label>
					</dd>					
				</dl>
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend>{L_VOTE}</legend>
				<input type="submit" name="valid_note" value="{L_VOTE}" class="submit" />
			</fieldset>
		</form>
		# END note #
		