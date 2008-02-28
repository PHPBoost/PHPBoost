		{JAVA}

		# IF C_DOWNLOAD_CAT #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					{L_CATEGORIE} {EDIT}
				</div>
				<div style="float:right">
					{PAGINATION}
				</div>
			</div>
			<div class="module_contents">
				&nbsp;
				# START cat_list #
				<div style="float:left;text-align:center;width:{cat_list.WIDTH}%;padding-bottom:20px;">
					{cat_list.U_IMG_CAT}
					<a href="../download/download{cat_list.U_DOWNLOAD_CAT}">{cat_list.CAT}</a> ({cat_list.TOTAL})<br />
					<span class="text_small">{cat_list.CONTENTS}</span>
				</div>	
				# END cat_list #
				
				<div class="text_small" style="text-align:center;clear:both">
					{TOTAL_FILE} {L_HOW_DOWNLOAD}
				</div>
				&nbsp;
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
		


		# IF C_DOWNLOAD_LINK #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					{CAT_NAME}
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
							&raquo; <a href="download{download.U_DOWNLOAD_LINK}">{download.NAME}</a>
						</td>
						<td class="row2" style="text-align: center;">
							{download.SIZE}
						</td>
						<td class="row2" style="text-align: center;">
							{download.DATE}
						</td>
						<td class="row2" style="text-align: center;">
							{download.COMPT} 
						</td>
						<td class="row2" style="text-align: center;">
							{download.NOTE}
						</td>
						<td class="row2" style="text-align: center;">
							{download.COM} 
						</td>
					</tr>
					# END download #
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


		# IF C_DISPLAY_DOWNLOAD #
		
		<script type="text/javascript">
		<!--
		var note_max = {NOTE_MAX};
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
					{NAME} {EDIT}{DEL}
				</div>
				<div style="float:right">
					{COM}
				</div>
			</div>
			<div class="module_contents">
				<p>					
					<strong>{L_DESC}:</strong> {CONTENTS}						
					<br /><br />						
					<strong>{L_CAT}:</strong> 
					<a href="../download/download{U_DOWNLOAD_CAT}" title="{CAT}">{CAT}</a><br />						
					<strong>{L_DATE}:</strong> {DATE}<br />						
					<strong>{L_SIZE}:</strong> {SIZE}<br />						
					<strong>{L_DOWNLOAD}:</strong> {COMPT} {L_TIMES}
					<div class="spacer">&nbsp;</div>
				</p>
				<p style="text-align: center;">					
					<a href="../download/count.php?id={IDURL}"><img src="{MODULE_DATA_PATH}/images/{LANG}/bouton_dl.gif" alt="" /></a>
				</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				{NOTE} <span id="download_loading"></span>
			</div>
		</div>
		
		<br /><br />
		# INCLUDE handle_com #
		# ENDIF #


		# IF C_DISPLAY_DOWNLOAD_NOTE #
		<form action="../download/download{U_DOWNLOAD_ACTION_NOTE}" method="post" class="fieldset_content">
			<span id="note"></span>
			<fieldset>
				<legend>{L_NOTE}</legend>
				<dl>
					<dt><label for="note_select">{L_NOTE}</label></dt>
					<dd>
						<span class="text_small">{L_ACTUAL_NOTE}: {NOTE}</span>	
						<label>
							<select id="note_select" name="note">
								{SELECT}
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
		# ENDIF #
