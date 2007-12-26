		{JAVA}

		# START cat #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					{cat.L_CATEGORIE} {cat.EDIT}
				</div>
				<div style="float:right">
					{cat.PAGINATION}
				</div>
			</div>
			<div class="module_contents">
				# START download #
				<div style="float:left;text-align:center;width:{cat.download.WIDTH}%;height:80px;">
					{cat.download.U_IMG_CAT}
					<a href="../download/download{cat.download.U_DOWNLOAD_CAT}">{cat.download.CAT}</a> ({cat.download.TOTAL})<br />
					<span class="text_small">{cat.download.CONTENTS}</span>
					<br /><br /><br />
				</div>	
				# END download #
				
				<div class="text_small" style="text-align:center;clear:both">
					{cat.TOTAL_FILE} {cat.L_HOW_DOWNLOAD}
				</div>
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
					{link.CAT_NAME}
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
			<div class="module_contents">
				<table class="module_table">
					<tr>
						<th style="text-align:center;">
							<a href="download{U_DOWNLOAD_ALPHA_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_FILE}
							<a href="download{U_DOWNLOAD_ALPHA_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="download{U_DOWNLOAD_SIZE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_SIZE}
							<a href="download{U_DOWNLOAD_SIZE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="download{U_DOWNLOAD_DATE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_DATE}					
							<a href="download{U_DOWNLOAD_DATE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="download{U_DOWNLOAD_VIEW_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_DOWNLOAD}					
							<a href="download{U_DOWNLOAD_VIEW_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="download{U_DOWNLOAD_NOTE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_NOTE}					
							<a href="download{U_DOWNLOAD_NOTE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="download{U_DOWNLOAD_COM_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
							{L_COM}
							<a href="download{U_DOWNLOAD_COM_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
						</th>
					</tr>
					# START download #
					<tr>	
						<td class="row2">
							&raquo; <a href="download{link.download.U_DOWNLOAD_LINK}">{link.download.NAME}</a>
						</td>
						<td class="row2" style="text-align: center;">
							{link.download.SIZE}
						</td>
						<td class="row2" style="text-align: center;">
							{link.download.DATE}
						</td>
						<td class="row2" style="text-align: center;">
							{link.download.COMPT} 
						</td>
						<td class="row2" style="text-align: center;">
							{link.download.NOTE}
						</td>
						<td class="row2" style="text-align: center;">
							{link.download.COM} 
						</td>
					</tr>
					# END download #
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


		# START download #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					{download.NAME} {EDIT}{DEL}
				</div>
				<div style="float:right">
					{download.COM}
				</div>
			</div>
			<div class="module_contents">
				<p>					
					<strong>{download.L_DESC}:</strong> {download.CONTENTS}						
					<br /><br />						
					<strong>{download.L_CAT}:</strong> 
					<a href="../download/download{download.U_DOWNLOAD_CAT}" title="{download.CAT}">{download.CAT}</a><br />						
					<strong>{download.L_DATE}:</strong> {download.DATE}<br />						
					<strong>{download.L_SIZE}:</strong> {download.SIZE}<br />						
					<strong>{download.L_DOWNLOAD}:</strong> {download.COMPT} {download.L_TIMES}
				</p>
				<p style="text-align: center;">					
					<a href="../download/count.php?id={download.IDURL}"><img src="{download.MODULE_DATA_PATH}/images/{download.LANG}/bouton_dl.gif" alt="" /></a>
					<br />
					{download.URL}
				</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<strong>{download.L_NOTE}:</strong> {download.NOTE}&nbsp;
			</div>
		</div>
		
		<br /><br />
		{HANDLE_COM}
		
		# END download #


		# START note #
		<form action="../download/download{note.U_DOWNLOAD_ACTION_NOTE}" method="post" class="fieldset_content">
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
