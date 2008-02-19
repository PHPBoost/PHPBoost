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
				&nbsp;
				# START cat.download #
				<div style="float:left;text-align:center;width:{cat.download.WIDTH}%;height:80px;">
					{cat.download.U_IMG_CAT}
					<a href="../download/download{cat.download.U_DOWNLOAD_CAT}">{cat.download.CAT}</a> ({cat.download.TOTAL})<br />
					<span class="text_small">{cat.download.CONTENTS}</span>
					<br /><br /><br />
				</div>	
				# END cat.download #
				
				<div class="text_small" style="text-align:center;clear:both">
					{cat.TOTAL_FILE} {cat.L_HOW_DOWNLOAD}
				</div>
				&nbsp;
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
					# START link.download #
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
					# END link.download #
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
		
		<script type="text/javascript">
		<!--
		var note_max = {download.NOTE_MAX};
		var array_note = new Array();		
		var timeout = null;
		var on_img = 0;
		function select_stars(divid, note)
		{
			var star_img;
			var decimal;
			for(var i = 1; i <= note_max; i++)
			{
				star_img = 'stars.png';
				if( note < i )
				{							
					decimal = i - note;
					if( decimal >= 1 )
						star_img = 'stars0.png';
					else if( decimal >= 0.75 )
						star_img = 'stars1.png';
					else if( decimal >= 0.50 )
						star_img = 'stars2.png';
					else
						star_img = 'stars3.png';
				}
							
				if( document.getElementById(divid + '_stars' + i) )
					document.getElementById(divid + '_stars' + i).src = '../templates/{THEME}/images/' + star_img;
			}
		}
		function out_div(divid, note)
		{
			if( timeout == null )
				timeout = setTimeout('select_stars(' + divid + ', ' + note + ');on_img = 0;', '50');
		}		
		function over_div()
		{
			if( on_img == 0 )
				on_img = 1;
			clearTimeout(timeout);
			timeout = null;
		}
		function send_note(id_file, idcat, note)
		{
			var regex = /\/|\\|\||\?|<|>|\"/;
			var get_nbrnote;
			var get_note;
			
			document.getElementById('download_loading').innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
			
			data = "id_file=" + id_file + "&note=" + note + "&idcat=" + idcat;
			var xhr_object = xmlhttprequest_init('xmlhttprequest.php?note_pics=1');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{	
					document.getElementById('download_loading').innerHTML = '';
					if( xhr_object.responseText == '-1' )
						alert("{L_ALREADY_VOTED}");
					else
					{	
						eval(xhr_object.responseText);
						array_note[id_file] = get_note;
						select_stars(id_file, get_note);
						if( document.getElementById('download_note') )
							document.getElementById('download_note').innerHTML = '(' + get_nbrnote + ' ' + ((get_nbrnote > 1) ? '{L_VOTES}' : '{L_VOTE}') + ')';
					}				
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					document.getElementById('download_loading').innerHTML = '';
			}
			xmlhttprequest_sender(xhr_object, data);
		}
		-->
		</script> 
		
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
					<div class="spacer">&nbsp;</div>
				</p>
				<p style="text-align: center;">					
					<a href="../download/count.php?id={download.IDURL}"><img src="{download.MODULE_DATA_PATH}/images/{download.LANG}/bouton_dl.gif" alt="" /></a>
				</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				{download.NOTE} <span id="download_loading"></span>
			</div>
		</div>
		
		<br /><br />
		# INCLUDE handle_com #
		
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
