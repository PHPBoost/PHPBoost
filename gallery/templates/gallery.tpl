		<script type="text/javascript">
		<!--		
		var pics_displayed = 0;
		function display_pics(id, path)
		{
			if( pics_displayed != id )
			{	
				document.getElementById('pics_max').innerHTML = '<img src="' + path + '" alt="" />';	
				pics_displayed = id;
			}
			else
			{
				document.getElementById('pics_max').innerHTML = '';	
				pics_displayed = 0;
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
				document.getElementById('fi' + id).innerHTML = '<input size="27" type="text" name="fiinput' + id + '" id="fiinput' + id + '" class="text" value="' + previous_name.replace(/\"/g, "&quot;") + '" onblur="rename_file(\'' + id + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'").replace(/\"/g, "&quot;") + '\');">';
				document.getElementById('fiinput' + id).focus();
			}
		}	
		function rename_file(id_file, previous_cut_name)
		{
			var name = document.getElementById("fiinput" + id_file).value;
			var regex = /\/|\\|\||\?|<|>/;

			if( regex.test(name) ) //interdiction des caractères spéciaux dans le nom.
			{
				alert("{L_FILE_FORBIDDEN_CHARS}");	
				document.getElementById('fi_' + id_file).style.display = 'inline';
				document.getElementById('fi' + id_file).style.display = 'none';
			}
			else
			{
				document.getElementById('img' + id_file).innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';

				data = "id_file=" + id_file + "&name=" + name.replace(/&/g, "%26") + "&previous_name=" + previous_cut_name.replace(/&/g, "%26");
				var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/gallery/xmlhttprequest.php?token={TOKEN}&rename_pics=1&token={TOKEN}');
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '0' )
					{
						document.getElementById('fi' + id_file).style.display = 'none';
						document.getElementById('fi_' + id_file).style.display = 'inline';
						document.getElementById('fi_' + id_file).innerHTML = xhr_object.responseText;
						
						html_protected_name = name.replace(/\'/g, "\\\'").replace(/\"/g, "&quot;");
						html_protected_name2 = xhr_object.responseText.replace(/\'/g, "\\\'").replace(/\"/g, "&quot;");
						
						document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + html_protected_name + '\', \'' + html_protected_name2 + '\');" class="edit"></a>';
						document.getElementById('img' + id_file).innerHTML = '';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '0' )
						document.getElementById('img' + id_file).innerHTML = '';
				}
				xmlhttprequest_sender(xhr_object, data);
			}
		}
		function pics_aprob(id_file, aprob)
		{
			document.getElementById('img' + id_file).innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';

			data = 'id_file=' + id_file;
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/gallery/xmlhttprequest.php?token={TOKEN}&aprob_pics=1&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' )
				{	
					var img_aprob, title_aprob;
					if( xhr_object.responseText == 0 )
					{
						img_aprob = 'unvisible.png';
						title_aprob = '{L_UNAPROB}';
					}
					else
					{
						img_aprob = 'visible.png';
						title_aprob = '{L_APROB}';
					}
					
					document.getElementById('img' + id_file).innerHTML = '';
					if( document.getElementById('img_aprob' + id_file) )
					{
						document.getElementById('img_aprob' + id_file).src = '{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/' + img_aprob;
						document.getElementById('img_aprob' + id_file).title = '' + title_aprob;
						document.getElementById('img_aprob' + id_file).alt = '' + title_aprob;
					}
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '-1' )
					document.getElementById('img' + id_file).innerHTML = '';
			}
			xmlhttprequest_sender(xhr_object, data);
		}
		
		var delay = 2000; //Délai après lequel le bloc est automatiquement masqué après le départ de la souris.
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
		//Miniatures défilantes.
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
						document.getElementById('thumb' + i).innerHTML = '<a href="{PATH_TO_ROOT}/gallery/gallery' + array_pics[key_left]['link'] + '"><img src="{PATH_TO_ROOT}/gallery/pics/thumbnails/' + array_pics[key_left]['path'] + '" alt="" /></a>';
						j++;
					}
					else if( direction == 'right' && array_pics[key_right] ) 
					{
						document.getElementById('thumb' + i).innerHTML = '<a href="{PATH_TO_ROOT}/gallery/gallery' + array_pics[key_right]['link'] + '"><img src="{PATH_TO_ROOT}/gallery/pics/thumbnails/' + array_pics[key_right]['path'] + '" alt="" /></a>';				
						j++;
					}
				}
			}
		}	
		//incrément le nombre de vues d'une image.
		var already_view = false;
		var incr_pics_displayed = 0;
		function increment_view(idpics)
		{
			if ({DISPLAY_MODE} == 1 && incr_pics_displayed == idpics)
				incr_pics_displayed = 0;
			else
			{
				if (document.getElementById('gv' + idpics))
				{	
					if (already_view && ({DISPLAY_MODE} == 3 || {DISPLAY_MODE} == 1))
					{
						data = '';
						var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/gallery/xmlhttprequest.php?token={TOKEN}&id=' + idpics + '&cat={CAT_ID}&increment_view=1');
						xmlhttprequest_sender(xhr_object, data);
					}

					var views = 0;
					views = document.getElementById('gv' + idpics).innerHTML;
					views++;
					document.getElementById('gv' + idpics).innerHTML = views;
					document.getElementById('gvl' + idpics).innerHTML = (views > 1) ? "{L_VIEWS}" : "{L_VIEW}";

					already_view = true;
					incr_pics_displayed = idpics;
				}
			}
		}	
		-->
		</script> 

		# INCLUDE message_helper #
		<div class="spacer"></div>
		
		<section>					
			<header>
				<h1>
					<a href="${relative_url(SyndicationUrlBuilder::rss('gallery', CAT_ID))}" class="syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
					{L_GALLERY} {U_GALLERY_CAT_LINKS}
					
					<span class="actions">
						{PAGINATION}
					</span>
				</h1>
			</header>
			<div class="content">
				<div style="margin-bottom:50px;">
					<menu class="dynamic_menu right">
						<ul>
							<li><a><i class="icon-reorder"></i></a>
								<ul>
									<li class="extend"><a><i class="icon-eye-open"></i> {L_DISPLAY}</a>
										<ul>
											<li><a href="{U_BEST_VIEWS}"><i class="icon-eye-open"></i> {L_BEST_VIEWS}</a></li>
											<li><a href="{U_BEST_NOTES}"><i class="icon-star-half-empty"></i> {L_BEST_NOTES}</a></li>
										</ul>
									</li>
									<li class="extend"><a><i class="icon-sort"></i> {L_ORDER_BY}</a>
										<ul>
											<li><a href="{U_ORDER_BY_NAME}"><i class="icon-tag"></i> {L_NAME}</a></li>
											<li><a href="{U_ORDER_BY_DATE}"><i class="icon-time"></i> {L_DATE}</a></li>
											<li><a href="{U_ORDER_BY_VIEWS}"><i class="icon-eye-open"></i> {L_VIEWS}</a></li>
											<li><a href="{U_ORDER_BY_NOTES}"><i class="icon-star-half-empty"></i> {L_NOTES}</a></li>
											<li><a href="{U_ORDER_BY_COM}"><i class="icon-comment"></i> {L_COM}</a></li>
										</ul>
									</li>
									<li class="extend"><a><i class="icon-sort-by-alphabet"></i> {L_DIRECTION}</a>
										<ul>
											<li><a href="{U_ASC}"><i class="icon-sort-by-attributes"></i> {L_ASC}</a></li>
											<li><a href="{U_DESC}"><i class="icon-sort-by-attributes-alt"></i> {L_DESC}</a></li>	
										</ul>
									</li>
									# IF U_ADD_PICS #
										<li class="separator"></li>
										<li><a href="{U_ADD_PICS}"><i class="icon-plus"></i> {L_ADD_PICS}</a></li>
									# ENDIF #
									# IF U_EDIT #<li><a href="{U_EDIT}"><i class="icon-pencil"></i> {L_CAT_EDIT}</a></li># ENDIF #
									# IF U_EDIT_CAT #<li><a href="{U_EDIT_CAT}"><i class="icon-reorder"></i> {L_EDIT_CAT}</a></li># ENDIF #
								</ul>
							</li>
						</ul>
					</menu>
				</div>
				
				# IF C_GALLERY_CATS #
				<section class="block">
					<header><h1>{L_CATEGORIES}</h1></header>
					<div class="contents">
						<table style="width:100%">
							# START cat_list #
							{cat_list.OPEN_TR}								
							<td style="vertical-align:bottom;text-align:center;width:{COLUMN_WIDTH_CATS}%;margin:15px 0px;">
								<a href="{cat_list.U_CAT}">{cat_list.IMG}</a>
								<br />
								<a href="{cat_list.U_CAT}">{cat_list.CAT}</a> {cat_list.EDIT}
								<br />
								<span class="smaller">{cat_list.DESC}</span> 
								<br />
								{cat_list.LOCK} <span class="smaller">{cat_list.L_NBR_PICS}</span>
							</td>	
							{cat_list.CLOSE_TR}
							# END cat_list #
						
							# START end_table_cats #
								{end_table_cats.TD_END}
							{end_table_cats.TR_END}
							# END end_table_cats #
						</table>
					</div>
					<footer></footer>
				</section>
				# ENDIF #
				
				
				# IF C_GALLERY_PICS #
				<article class="block">
					<header><h1>{GALLERY}</h1></header>
					<div class="contents">
						<p style="text-align:center" id="pics_max">{PAGINATION_PICS}</p>				
						
						# IF C_GALLERY_PICS_MAX #
							<p style="text-align:center;padding:15px 0px;overflow:auto;">{IMG_MAX}</p>
							<div style="margin:auto;width:400px;height:32px;padding:0;" class="row2">
								<span style="float:left">&nbsp;&nbsp;&nbsp;{U_PREVIOUS}</span>
								<span style="float:right">{U_NEXT}&nbsp;&nbsp;&nbsp;</span>
							</div>
							<br />
							<table class="module_table" style="width:100%">
								<tr>
									<th colspan="2">
										{L_INFORMATIONS}
									</th>
								</tr>
								<tr>
									# IF C_TITLE_ENABLED #<td class="row2 text_small" style="width:50%;border:none;padding:4px;">
										<strong>{L_NAME}:</strong> {NAME}
									</td># ENDIF #
									# IF C_AUTHOR_DISPLAYED #<td class="row2 text_small" style="border:none;padding:4px;">
										<strong>{L_POSTOR}:</strong> {POSTOR}
									</td># ENDIF #
								</tr>
								<tr>										
									# IF C_VIEWS_COUNTER_ENABLED #<td class="row2 text_small" style="border:none;padding:4px;">
										<strong>{L_VIEWS}:</strong> {VIEWS}
									</td># ENDIF #
									<td class="row2 text_small" style="border:none;padding:4px;">
										<strong>{L_ADD_ON}:</strong> {DATE}
									</td>
								</tr>
								<tr>										
									<td class="row2 text_small" style="border:none;padding:4px;">
										<strong>{L_DIMENSION}:</strong> {DIMENSION}
									</td>
									<td class="row2 text_small" style="border:none;padding:4px;">
										<strong>{L_SIZE}:</strong> {SIZE} {L_KB}
									</td>
								</tr>
								<tr>										
									# IF C_NOTATION_ENABLED #<td class="row2 text_small" style="border:none;padding:4px;">
										{KERNEL_NOTATION}
									</td># ENDIF #
									# IF C_COMMENTS_ENABLED # #<td class="row2 text_small" style="border:none;padding:4px;vertical-align:top">
										<strong>{L_COM}:</strong> {COM}
									</td># ENDIF #
								</tr>
								
								# IF C_GALLERY_PICS_MODO #
								<tr>										
									<td colspan="2" class="row2 text_small" style="border:none;padding:4px;">
										&nbsp;&nbsp;&nbsp;<span id="fihref{ID}"><a href="javascript:display_rename_file('{ID}', '{RENAME}', '{RENAME_CUT}');" class="edit"></a></span>									
										<a href="{U_DEL}" title="{L_DELETE}" class="delete"></a> 						
										<div style="position:absolute;z-index:100;margin-top:95px;float:left;display:none;" id="move{ID}">
											<div class="bbcode_block" style="width:190px;overflow:auto;" onmouseover="pics_hide_block({ID}, 1);" onmouseout="pics_hide_block({ID}, 0);">
												<div style="margin-bottom:4px;"><strong>{L_MOVETO}</strong>:</div>
												<select class="valign_middle" name="{ID}cat" onchange="document.location = 'gallery{U_MOVE}">
													{CAT}
												</select>
												<br /><br />
											</div>
										</div>
										<a href="javascript:pics_display_block({ID});" onmouseover="pics_hide_block({ID}, 1);" onmouseout="pics_hide_block({ID}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a>
										
										
										<a href="javascript:pics_aprob({ID});"><img id="img_aprob{ID}" src="{PATH_TO_ROOT}/templates/{THEME}/images/{IMG_APROB}" alt="{L_APROB_IMG}" title="{L_APROB_IMG}" class="valign_middle" /></a>
										&nbsp;<span id="img{ID}"></span>
									</td>
								</tr>
								# ENDIF #						
							</table>					
							<br /><br />					
							<table class="module_table" style="width:100%;">
								<tr>
									<th colspan="{COLSPAN}">
										{L_THUMBNAILS}
									</th>
								</tr>
								<tr>
									<td class="row2" style="width:50px;text-align:center">
										{U_LEFT_THUMBNAILS}
									</td>
									
									# START list_preview_pics #
										{list_preview_pics.PICS}
									# END list_preview_pics #
									
									<td class="row2" style="width:50px;text-align:center">
										{U_RIGHT_THUMBNAILS}
									</td>
								</tr>
							</table>
						{COMMENTS}
						# ENDIF #
						
						<table class="module_table">
							# START pics_list #
								{pics_list.OPEN_TR}
								<td style="width:{COLUMN_WIDTH_PICS}%;text-align:center;padding:15px 0px;vertical-align:middle" class="smaller">
									<div style="margin-bottom:5px;" id="pics{pics_list.ID}"><a class="small" href="{pics_list.U_DISPLAY}">{pics_list.IMG}</a></div>
									{pics_list.NAME}
									{pics_list.POSTOR}
									{pics_list.VIEWS}
									{pics_list.COM}
									{pics_list.KERNEL_NOTATION}
										
									<div style="width:180px;margin:auto;">										
										# IF C_GALLERY_MODO #
										<span id="fihref{pics_list.ID}"><a href="javascript:display_rename_file('{pics_list.ID}', '{pics_list.RENAME}', '{pics_list.RENAME_CUT}');" title="{L_EDIT}" class="edit"></a></span>
										<a href="{pics_list.U_DEL}"title="{L_DELETE}" class="delete"></a>									
										<div style="position:relative;margin:auto;width:170px;display:none;float:right" onmouseover="pics_hide_block({pics_list.ID}, 1);" onmouseout="pics_hide_block({pics_list.ID}, 0);" id="move{pics_list.ID}">
											<div style="position:absolute;z-index:100;margin-top:90px;">
												<div class="bbcode_block" style="width:170px;overflow:auto;">
													<div style="margin-bottom:4px;" class="smaller"><strong>{L_MOVETO}</strong>:</div>
													<select class="valign_middle" name="{pics_list.ID}cat" onchange="document.location = '{pics_list.U_MOVE}">
														{pics_list.CAT}
													</select>
													<br /><br />
												</div>
											</div>
										</div>
										<a href="javascript:pics_display_block({pics_list.ID});" onmouseover="pics_hide_block({pics_list.ID}, 1);" onmouseout="pics_hide_block({pics_list.ID}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a>
											
										<a href="javascript:pics_aprob({pics_list.ID});" title="{pics_list.L_APROB_IMG}"><img id="img_aprob{pics_list.ID}" src="{PATH_TO_ROOT}/templates/{THEME}/images/{pics_list.IMG_APROB}" alt="{pics_list.L_APROB_IMG}" title="{pics_list.L_APROB_IMG}" class="valign_middle" /></a>
										# ENDIF #											
										<span id="img{pics_list.ID}"></span>										
									</div>
								</td>
								{pics_list.CLOSE_TR}
							# END pics_list #
							
							# START end_table #
								{end_table.TD_END}
								
							{end_table.TR_END}
							# END end_table #
						</table>
						<p style="text-align:center">{PAGINATION_PICS}</p>			
					</div>
					<footer></footer>
				</article>
				# ENDIF #
					
				<p style="text-align:center;padding-top:15px;" class="smaller">{L_TOTAL_IMG}</p>
			</div>
			<footer>
				<div style="float:right">
					{PAGINATION}
				</div>
			</footer>
		</section>