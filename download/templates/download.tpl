		{JAVA}

		# IF C_DOWNLOAD_CAT #
		<div class="module_position">			
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				{TITLE}
				# IF C_ADMIN #
				<a href="{U_ADMIN_CAT}">
					<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="">
				</a>
				# END IF #
			</div>
			<div class="module_contents">
			
				# START description #
					{description.DESCRIPTION}
					<hr style="margin-top:25px;" />
				# END description #
				
				# IF C_SUB_CATS #
					# START row #
						# START row.list_cats #
							<div style="float:left;width:{row.list_cats.WIDTH}%;text-align:center;margin:20px 0px;">
								# IF C_CAT_IMG #
									<a href="{row.list_cats.U_CAT}" title="{row.list_cats.IMG_NAME}"><img src="{row.list_cats.SRC}" alt="{row.list_cats.IMG_NAME}" /></a>
									<br />
								# ENDIF #
								<a href="{row.list_cats.U_CAT}">{row.list_cats.NAME}</a>
								
								# IF C_ADMIN #
								<a href="{row.list_cats.U_ADMIN_CAT}">
									<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="">
								</a>
								# ENDIF #
								<div class="text_small">
									{row.list_cats.NUM_FILES}
								</div>
							</div>
						# END row.list_cats #
						<div class="spacer">&nbsp;</div>
					# END row #
					<hr style="margin-bottom:25px;" />
				# ENDIF #
				
				# IF C_FILES #
					<div class="text_strong" style="text-align:center;">
						{L_FILES_IN_THIS_CATEGORY}
					</div>
					# START file #
						<div class="block_position" style="margin-bottom:20px;">
							<div class="row1 block_contents">
								# IF file.C_IMG #
									<div class="float_right">
										<img src="{file.IMG}" alt="" />
									</div>
								# ENDIF #
								<strong><a href="{file.U_DOWNLOAD_LINK}">{file.NAME}</a></strong>
								# IF file.C_DESCRIPTION #
									<p>
									{file.DESCRIPTION}
									</p>
								# ENDIF #
								<div class="text_small">
									{file.DATE} &bull; {file.COUNT_DL} &bull; {file.COMS} &bull; {file.NOTE}
								</div>								
							</div>
						</div>						
					# END file #
					<div style="align:center;">{PAGINATION}</div>
				# ENDIF #
				
				# IF C_NO_FILE #
					<div class="notice">
						{L_NO_FILE_THIS_CATEGORY}
					</div>
				# ENDIF #
				<div class="spacer"></div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
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
					{NAME}
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
					<a href="../download/download{U_DOWNLOAD_CAT}" title="{CAT}">{CAT}</a><br />						
					<strong>{L_DATE}:</strong> {DATE}<br />						
					<strong>{L_SIZE}:</strong> {SIZE}<br />						
					<strong>{L_DOWNLOAD}:</strong> {COMPT} {L_TIMES}
					<div class="spacer">&nbsp;</div>
				</p>
				<p style="text-align: center;">					
					<a href="../download/count.php?id={IDURL}"><img src="{MODULE_DATA_PATH}/images/{LANG}/bouton_dl.gif" alt="" /></a>
				</p>
				&nbsp;
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
