		<link href="{PICTURES_DATA_PATH}/gallery.css" rel="stylesheet" type="text/css" media="screen, handheld">
		<script type="text/javascript">
		<!--
		var previous_path_pics = '';
		function display_pics(id, path, type)
		{
			document.getElementById('pics_max').innerHTML = '';					
			if( previous_path_pics != path )
			{	
				document.getElementById('pics_max').innerHTML = '<img src="' + path + '" alt="" /></a>';	
				previous_path_pics = path;
			}
		}
		function display_pics_popup(path, width, height)
		{
			width = parseInt(width);
			height = parseInt(height);
			if( height == 0 )
				height = screen.height - 150;
			if( width == 0 )
				width = screen.width - 200;
			window.open(path, '', 'width='+(width+17)+', height='+(height+17)+', location=no, status=no, toolbar=no, scrollbars=1, resizable=yes');
		}
		function display_rename_file(id, previous_name, previous_cut_name)
		{
			if( document.getElementById('fi' + id) )
			{	
				document.getElementById('fi_' + id).style.display = 'none';
				document.getElementById('fi' + id).style.display = 'inline';
				document.getElementById('fi' + id).innerHTML = '<input size="27" type="text" name="fiinput' + id + '" id="fiinput' + id + '" value="' + previous_name.replace(/\"/g, "&quot;") + '" onblur="rename_file(\'' + id + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'").replace(/\"/g, "&quot;") + '\');">';
				document.getElementById('fiinput' + id).focus();
			}
		}	
		function rename_file(id_file, previous_cut_name)
		{
			var name = document.getElementById("fiinput" + id_file).value;
			var regex = /\/|\\|\||\?|<|>/;
			
			if( regex.test(name) ) //interdiction des caract�res sp�ciaux dans la nom.
			{
				alert("{L_FILE_FORBIDDEN_CHARS}");	
				document.getElementById('fi_' + id_file).style.display = 'inline';
				document.getElementById('fi' + id_file).style.display = 'none';
			}
			else
			{
				document.getElementById('img' + id_file).innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
				data = "id_file=" + id_file + "&name=" + name.replace(/&/g, "%26") + "&previous_name=" + previous_cut_name.replace(/&/g, "%26");
				var xhr_object = xmlhttprequest_init('xmlhttprequest.php?rename_pics=1&token={TOKEN}');
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '0' )
					{
						document.getElementById('fi' + id_file).style.display = 'none';
						document.getElementById('fi_' + id_file).style.display = 'inline';
						document.getElementById('fi_' + id_file).innerHTML = xhr_object.responseText;
						
						html_protected_name = name.replace(/\'/g, "\\\'").replace(/\"/g, "&quot;");
						html_protected_name2 = xhr_object.responseText.replace(/\'/g, "\\\'").replace(/\"/g, "&quot;");
						
						document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + html_protected_name + '\', \'' + html_protected_name2 + '\');" title="{L_EDIT}" class="icon-edit"></a>';
						document.getElementById('img' + id_file).innerHTML = '';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '0' )
						document.getElementById('img' + id_file).innerHTML = '';
				}
				xmlhttprequest_sender(xhr_object, data);
			}
		}
		function pics_aprob(id_file)
		{
			var regex = /\/|\\|\||\?|<|>|\"/;
			
			document.getElementById('img' + id_file).innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';

			data = "id_file=" + id_file;
			var xhr_object = xmlhttprequest_init('xmlhttprequest.php?token={TOKEN}&aprob_pics=1&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' )
				{	
					var img_aprob, title_aprob;
					if( xhr_object.responseText == 0 )
					{
						img_aprob = 'icon-eye-slash';
						title_aprob = '{L_UNAPROB}';
					}
					else
					{
						img_aprob = 'icon-eye';
						title_aprob = '{L_APROB}';
					}
					
					document.getElementById('img' + id_file).innerHTML = '';
					if( document.getElementById('img_aprob' + id_file) )
					{
						if(document.getElementById('img_aprob' + id_file).hasClassName('icon-eye')){
							document.getElementById('img_aprob' + id_file).removeClassName('icon-eye');
							document.getElementById('img_aprob' + id_file).addClassName('icon-eye-slash');
						} else {
							document.getElementById('img_aprob' + id_file).removeClassName('icon-eye-slash');
							document.getElementById('img_aprob' + id_file).addClassName('icon-eye');
						}
						document.getElementById('img_aprob' + id_file).title = '' + title_aprob;
						document.getElementById('img_aprob' + id_file).alt = '' + title_aprob;
					}
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '-1' )
					document.getElementById('img' + id_file).innerHTML = '';
			}
			xmlhttprequest_sender(xhr_object, data);
		}
		
		var delay = 2000; //D�lai apr�s lequel le bloc est automatiquement masqu�, apr�s le d�part de la souris.
		var timeout;
		var displayed = false;
		var previous = '';
		var started = false;
		
		//Affiche le bloc.
		function pics_display_block(divID)
		{
			if( timeout )
				clearTimeout(timeout);
			
			if( document.getElementById(previous) )
			{		
				document.getElementById(previous).style.display = 'none';
				started = false
			}	

			if( document.getElementById('move' + divID) )
			{			
				document.getElementById('move' + divID).style.display = 'block';
				previous = 'move' + divID;
				started = true;
			}
		}
		//Cache le bloc.
		function pics_hide_block(idfield, stop)
		{
			if( stop && timeout )
				clearTimeout(timeout);
			else if( started )
				timeout = setTimeout('pics_display_block()', delay);
		}
		
		{ARRAY_JS}
		var start_thumb = {START_THUMB};
		//Miniatures d�filantes.
		function display_thumbnails(direction)
		{			
			if( direction == 'left' )
			{	
				if( start_thumb > 0 )
				{
					start_thumb--;
					if( start_thumb == 0 )
						document.getElementById('display_left').innerHTML = '';
					else
						document.getElementById('display_left').innerHTML = '<a href="javascript:display_thumbnails(\'left\')"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/left.png" class="valign_middle" alt="" /></a>';
					document.getElementById('display_right').innerHTML = '<a href="javascript:display_thumbnails(\'right\')"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/right.png" class="valign_middle" alt="" /></a>';
				}
				else
					return;
			}
			else if( direction == 'right' )
			{
				if( start_thumb <= {MAX_START} )
				{
					start_thumb++;
					if( start_thumb == ({MAX_START} + 1) )
						document.getElementById('display_right').innerHTML = '';
					else
						document.getElementById('display_right').innerHTML = '<a href="javascript:display_thumbnails(\'right\')"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/right.png" class="valign_middle" alt="" /></a>';
					document.getElementById('display_left').innerHTML = '<a href="javascript:display_thumbnails(\'left\')"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/left.png" class="valign_middle" alt="" /></a>';
				}
				else
					return;
			}	
			
			var j = 0;
			for(var i = 0; i <= {NBR_PICS}; i++)
			{
				if( document.getElementById('thumb' + i) ) 
				{
					var key_left = start_thumb + j;
					var key_right = start_thumb + j;
					if( direction == 'left' && array_pics[key_left] )							
					{	
						document.getElementById('thumb' + i).innerHTML = '<a href="admin_gallery' + array_pics[key_left]['link'] + '"><img src="pics/thumbnails/' + array_pics[key_left]['path'] + '" alt="" /></a>';
						j++;
					}
					else if( direction == 'right' && array_pics[key_right] ) 
					{
						document.getElementById('thumb' + i).innerHTML = '<a href="admin_gallery' + array_pics[key_right]['link'] + '"><img 	src="pics/thumbnails/' + array_pics[key_right]['path'] + '" alt="" /></a>';				
						j++;
					}
				}
			}
		}
		-->
		</script>
		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_GALLERY_MANAGEMENT}</li>
				<li>
					<a href="admin_gallery.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery.php" class="quick_link">{L_GALLERY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_gallery_add.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_add.php" class="quick_link">{L_GALLERY_PICS_ADD}</a>
				</li>
				<li>
					<a href="admin_gallery_cat.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_cat.php" class="quick_link">{L_GALLERY_CAT_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_gallery_cat_add.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_cat_add.php" class="quick_link">{L_GALLERY_CAT_ADD}</a>
				</li>
				<li>
					<a href="admin_gallery_config.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_config.php" class="quick_link">{L_GALLERY_CONFIG}</a>
				</li>
			</ul>
		</div>
						 
		<div id="admin_contents">
			# INCLUDE message_helper #
			
			{PAGINATION}

			<table class="module_table">
				<tr> 
					<th colspan="2">
						{L_GALLERY}
					</th>
				</tr>
				<tr>
					<td class="row2">				
						<p class="row3">
							<span style="float:left">
								<a href="admin_gallery.php">{L_GALLERY_MANAGEMENT}</a> &raquo; {U_GALLERY_CAT_LINKS}
							</span>
							<span style="float:right">
								{PAGINATION}
							</span>
							<br />
						</p>
						
						# START cat #
						<table class="module_table">
							<tr>
								<th colspan="{COLSPAN}">
									{L_CATEGORIES}
								</th>
							</tr>
								
							# START cat.list #
							{cat.list.TR_START}								
								<td class="row2" style="vertical-align:bottom;text-align:center;width:{COLUMN_WIDTH_CATS}%">
									<a href="admin_gallery.php?cat={cat.list.IDCAT}">{cat.list.IMG}</a>
									
									<br />
									<a href="admin_gallery.php?cat={cat.list.IDCAT}">{cat.list.CAT}</a> <a href="admin_gallery_cat.php?id={cat.list.IDCAT}" title="{L_EDIT}" class="icon-edit"></a> 
									<br />
									{cat.list.LOCK} <span class="smaller">{cat.list.L_NBR_PICS}</span> 
								</td>	
							{cat.list.TR_END}
							# END cat.list #						
						
							# START cat.end_td #
								{cat.end_td.TD_END}
							{cat.end_td.TR_END}
							# END cat.end_td #
							
						</table>	
						# END cat #
						
						<br />
						
						# START pics #
						<p style="text-align:center">		
							{PAGINATION_PICS}<span id="pics_max"></span>
						</p>				
						<table class="module_table">				
							<tr>
								<th colspan="{COLSPAN}">
									{GALLERY} {pics.EDIT}
								</th>
							</tr>
							<tr>
								<td colspan="{COLSPAN}" style="text-align:center;" class="row2">
									{pics.PICS_MAX}
								</td>
							</tr>
							
							# START pics.pics_max #
							<tr>
								<td colspan="{COLSPAN}" class="row1">
									<table style="border-collapse:collapse;margin:auto;width:400px" class="row2">
										<tr>
											<td>
												&nbsp;&nbsp;&nbsp;{pics.pics_max.U_PREVIOUS} 
											</td>	
											<td style="text-align:right;">
												{pics.pics_max.U_NEXT}&nbsp;&nbsp;&nbsp;
											</td>
										</tr>
									</table>
									<br />
									<table style="border-collapse:collapse;margin:auto;width:100%" class="row2">
										<tr>
											<th colspan="2">
												{L_INFORMATIONS}
											</th>
										</tr>
										<tr>
											<td class="row2 text_small" style="width:50%;border:none;padding:4px;">
												<strong>{L_NAME}:</strong> {pics.pics_max.NAME}
											</td>
											<td class="row2 text_small" style="border:none;padding:4px;">
												<strong>{L_POSTOR}:</strong> {pics.pics_max.POSTOR}
											</td>
										</tr>
										<tr>										
											<td class="row2 text_small" style="border:none;padding:4px;">
												<strong>{L_VIEWS}:</strong> {pics.pics_max.VIEWS}
											</td>
											<td class="row2 text_small" style="border:none;padding:4px;">
												<strong>{L_ADD_ON}:</strong> {pics.pics_max.DATE}
											</td>
										</tr>
										<tr>										
											<td class="row2 text_small" style="border:none;padding:4px;">
												<strong>{L_DIMENSION}:</strong> {pics.pics_max.DIMENSION}
											</td>
											<td class="row2 text_small" style="border:none;padding:4px;">
												<strong>{L_SIZE}:</strong> {pics.pics_max.SIZE} Ko
											</td>
										</tr>
										<tr>										
											<td colspan="2" class="row2 text_small" style="border:none;padding:4px;">
												&nbsp;&nbsp;&nbsp;<span id="fihref{pics.pics_max.ID}"><a href="javascript:display_rename_file('{pics.pics_max.ID}', '{pics.pics_max.RENAME}', '{pics.pics_max.RENAME_CUT}');" title="{L_EDIT}" class="icon-edit"></a>
												
												<a href="gallery{pics.pics_max.U_DEL}" title="{L_DELETE}" class="icon-delete" data-confirmation="delete-element"></a> 
									
												<div style="position:absolute;z-index:100;margin-top:110px;float:left;display:none;" id="move{pics.pics_max.ID}">
													<div class="bbcode_block" style="width:190px;overflow:auto;" onmouseover="pics_hide_block({pics.pics_max.ID}, 1);" onmouseout="pics_hide_block({pics.pics_max.ID}, 0);">
														<div style="margin-bottom:4px;"><strong>{L_MOVETO}</strong>:</div>
														<select class="valign_middle" name="{pics.pics_max.ID}cat" onchange="document.location = 'gallery{pics.pics_max.U_MOVE}">
															{pics.pics_max.CAT}
														</select>
														<br /><br />
													</div>
												</div>
												<a href="javascript:pics_display_block({pics.pics_max.ID});" onmouseover="pics_hide_block({pics.pics_max.ID}, 1);" onmouseout="pics_hide_block({pics.pics_max.ID}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a>
												
												
												<a id="img_aprob{pics.pics_max.ID}" href="javascript:pics_aprob({pics.pics_max.ID});" # IF pics.pics_max.C_APPROVED #title="{L_APROB}" class="icon-eye"# ELSE #title="{L_UNAPROB}" class="icon-eye-slash"# ENDIF #></a>
												&nbsp;<span id="img{pics.pics_max.ID}"></span>
											</td>
										</tr>
									</table>
									
									<br /><br />
									
									<table class="module_table" style="width:100%;">
										<tr>
											<th colspan="{pics.pics_max.COLSPAN}">
												{L_THUMBNAILS}
											</th>
										</tr>
										<tr>
											<td class="row2" style="width:50px;text-align:center">
												{pics.pics_max.U_LEFT_THUMBNAILS}
											</td>
											
											# START pics.pics_max.list_preview_pics #
												{pics.pics_max.list_preview_pics.PICS}
											# END pics.pics_max.list_preview_pics #
											
											<td class="row2" style="width:50px;text-align:center">
												{pics.pics_max.U_RIGHT_THUMBNAILS}
											</td>
										</tr>
									</table>
									
									<br />
									# INCLUDE handle_com #
								</td>
							</tr>
							# END pics.pics_max #
							
							# START pics.list #
							{pics.list.TR_START}
								<td class="row2" style="padding:6px;text-align:center;width:{COLUMN_WIDTH_PICS}%">
									<table style="border:0;margin:auto;">
										<tr>
											<td style="height:{HEIGHT_MAX}px;">
												<span id="pics{pics.list.ID}"><a class="com" href="{pics.list.U_DISPLAY}" title="{pics.list.TITLE}" rel="lightbox[2]">{pics.list.IMG}</a></span>
											</td>
										</tr>
										<tr>
											<td style="text-align:center;" class="smaller">
												<a class="com" href="{pics.list.U_DISPLAY}" title="{pics.list.TITLE}" rel="lightbox[2]"><span id="fi_{pics.list.ID}">{pics.list.NAME}</span></a> <span id="fi{pics.list.ID}"></span>
												<br />
												{pics.list.U_POSTOR}
											</td>
										</tr>									
										<tr>
											<td style="text-align:center;">
												{pics.list.RENAME_FILE}
												
												<a href="admin_gallery.php?del={pics.list.ID}&amp;token={TOKEN}&amp;cat={CAT_ID}" title="{L_DELETE}" class="icon-delete" data-confirmation="delete-element"></a>
									
												<div style="position:absolute;z-index:100;margin-top:110px;float:left;display:none;" id="move{pics.list.ID}">
													<div class="bbcode_block" style="width:190px;overflow:auto;" onmouseover="pics_hide_block({pics.list.ID}, 1);" onmouseout="pics_hide_block({pics.list.ID}, 0);">
														<div style="margin-bottom:4px;"><strong>{L_MOVETO}</strong>:</div>
														<select class="valign_middle" name="{pics.list.ID}cat" onchange="document.location = 'admin_gallery.php?id={pics.list.ID}&amp;token={TOKEN}&amp;move=' + this.options[this.selectedIndex].value">
															{pics.list.CAT}
														</select>
														<br /><br />
													</div>
												</div>
												<a href="javascript:pics_display_block({pics.list.ID});" onmouseover="pics_hide_block({pics.list.ID}, 1);" onmouseout="pics_hide_block({pics.list.ID}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a>
												
												<a id="img_aprob{pics.list.ID}" href="javascript:pics_aprob({pics.list.ID});" # IF pics.list.C_APPROVED #title="{L_APROB}" class="icon-eye"# ELSE #title="{L_UNAPROB}" class="icon-eye-slash"# ENDIF #></a>
												&nbsp;<span id="img{pics.list.ID}"></span>
											</td>
										</tr>
									</table>
								</td>
							{pics.list.TR_END}
							# END pics.list #				
						
							# START pics.end_td_pics #
								{pics.end_td_pics.TD_END}
							{pics.end_td_pics.TR_END}
							# END pics.end_td_pics #
							
						</table>	
						
						<p style="text-align:center">		
							{PAGINATION_PICS}
						</p>			
						# END pics #
						
						<p style="text-align:center" class="smaller">
							{L_TOTAL_IMG}
						</p>	

						<p class="row3">
							<span style="float:left">
								<a href="admin_gallery.php">{L_GALLERY_MANAGEMENT}</a> &raquo; {U_GALLERY_CAT_LINKS}
							</span>
							<span style="float:right">
								{PAGINATION}
							</span>
							<br />
						</p>				
					</td>
				</tr>
			</table>
		</div>	