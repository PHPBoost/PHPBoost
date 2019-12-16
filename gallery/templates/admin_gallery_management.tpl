<script>
<!--
var previous_path_pics = '';
function display_pics(id, path, type)
{
	document.getElementById('pics_max').innerHTML = '';
	if( previous_path_pics != path )
	{
		document.getElementById('pics_max').innerHTML = '<img src="' + path + '" alt="' + path + '" /></a>';
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
		document.getElementById('fi' + id).innerHTML = '<input type="text" name="fiinput' + id + '" id="fiinput' + id + '" value="' + previous_name.replace(/\"/g, "&quot;") + '" onblur="rename_file(\'' + id + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'").replace(/\"/g, "&quot;") + '\');">';
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
		document.getElementById('img' + id_file).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
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

				document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + html_protected_name + '\', \'' + html_protected_name2 + '\');" aria-label="{L_EDIT}"><i class="fa fa-edit" aria-hidden="true"></i></a>';
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

	document.getElementById('img' + id_file).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

	data = "id_file=" + id_file;
	var xhr_object = xmlhttprequest_init('xmlhttprequest.php?aprob_pics=1&token={TOKEN}');
	xhr_object.onreadystatechange = function()
	{
		if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' )
		{
			var img_aprob, title_aprob;
			if( xhr_object.responseText == 0 )
			{
				img_aprob = 'fa-eye-slash';
				title_aprob = '{L_UNAPROB}';
			}
			else
			{
				img_aprob = 'fa-eye';
				title_aprob = '{L_APROB}';
			}

			document.getElementById('img' + id_file).innerHTML = '';
			if( document.getElementById('img_aprob' + id_file) )
			{
				if(document.getElementById('img_aprob' + id_file).className == "fa fa-eye-slash"){
					document.getElementById('img_aprob' + id_file).className = "fa fa-eye";
				} else {
					document.getElementById('img_aprob' + id_file).className = "fa fa-eye-slash";
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

var delay = 2000; //Délai après lequel le bloc est automatiquement masqué, après le départ de la souris.
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
				document.getElementById('display_left').innerHTML = '<a href="javascript:display_thumbnails(\'left\')"><i class="fa fa-arrow-left fa-2x"></i></a>';
			document.getElementById('display_right').innerHTML = '<a href="javascript:display_thumbnails(\'right\')"><i class="fa fa-arrow-right fa-2x"></i></a>';
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
				document.getElementById('display_right').innerHTML = '<a href="javascript:display_thumbnails(\'right\')"><i class="fa fa-arrow-right fa-2x"></i></a>';
			document.getElementById('display_left').innerHTML = '<a href="javascript:display_thumbnails(\'left\')"><i class="fa fa-arrow-left fa-2x"></i></a>';
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
				document.getElementById('thumb' + i).innerHTML = '<a href="admin_gallery' + array_pics[key_left]['link'] + '"><img src="pics/thumbnails/' + array_pics[key_left]['path'] + '" alt="' + array_pics[key_left]['path'] + '" /></a>';
				j++;
			}
			else if( direction == 'right' && array_pics[key_right] )
			{
				document.getElementById('thumb' + i).innerHTML = '<a href="admin_gallery' + array_pics[key_right]['link'] + '"><img src="pics/thumbnails/' + array_pics[key_right]['path'] + '" alt="' + array_pics[key_right]['path'] + '" /></a>';
				j++;
			}
		}
	}
}
-->
</script>

<nav id="admin-quick-menu">
	<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {L_GALLERY_MANAGEMENT}
	</a>
	<ul>
		<li>
			<a href="${Url::to_rel('/gallery')}" class="quick-link">${LangLoader::get_message('home', 'main')}</a>
		</li>
		<li>
			<a href="admin_gallery.php" class="quick-link">{L_GALLERY_MANAGEMENT}</a>
		</li>
		<li>
			<a href="admin_gallery_add.php" class="quick-link">{L_GALLERY_PICS_ADD}</a>
		</li>
		<li>
			<a href="admin_gallery_config.php" class="quick-link">{L_GALLERY_CONFIG}</a>
		</li>
		<li>
			<a href="${relative_url(GalleryUrlBuilder::documentation())}" class="quick-link">${LangLoader::get_message('module.documentation', 'admin-modules-common')}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	# INCLUDE message_helper #

	# START pics #
		<section>
			<header>
				<div class="align-right">
					# IF pics.C_EDIT #<a href="{pics.U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF #
				</div>
				<h1>{GALLERY} </h1>
				# IF C_PAGINATION #
					<p class="align-center">
						# INCLUDE PAGINATION #
					</p>
				# ENDIF #
			</header>

			# START cat #
				<article>
					<header>
						<h2>{L_CATEGORIES}</h2>
						# IF C_PAGINATION ## INCLUDE PAGINATION ## ENDIF #
					</header>
					<div class="cell-flex cell-columns-{COLUMNS_NUMBER} cell-tile">
						# START cat.list #
						<div class="cell">
							<div class="cell-header">
								<a href="admin_gallery.php?cat={cat.list.IDCAT}">{cat.list.CAT}</a>
							</div>
							<div class="cell-body">
								<div class="cell-thumbnail cell-landscape">
									# IF cat.list.C_IMG #<img itemprop="thumbnailUrl" src="{cat.list.IMG}" alt="{cat.list.CAT}" /># ENDIF #
									<a class="cell-thumbnail-caption" href="admin_gallery.php?cat={cat.list.IDCAT}" aria-label="{cat.list.CAT}"><i class="fa fa-eye"></i></a>
								</div>
							</div>
							<div class="cell-footer">{cat.list.L_NBR_PICS}</div>
						</div>
						# END cat.list #
					</div>
					<div class="spacer"></div>
					# IF C_SUBCATEGORIES_PAGINATION #<span class="align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
				</article>
			# END cat #

			# START pics.pics_max #
				<table class="table table-pics">
					<thead>
						<tr>
							<th colspan="{pics.pics_max.COLSPAN_PICTURE}">
								{pics.pics_max.PICTURE_NAME}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="pics_max" colspan="{pics.pics_max.COLSPAN_PICTURE}">
								<img src="show_pics.php?id={pics.ID}&amp;cat={pics.IDCAT}" alt="{pics.CATNAME}" />
							</td>
						</tr>
						<tr>
							# IF pics.pics_max.C_PREVIOUS #
							<td class="align-left no-separator">
								<a href="admin_gallery.php?cat={pics.pics_max.ID_CATEGORY}&amp;id={pics.pics_max.ID_PREVIOUS}#pics_max" class="fa fa-arrow-left fa-2x"></a> <a href="admin_gallery.php?cat={pics.pics_max.ID_CATEGORY}&amp;id={pics.pics_max.ID_PREVIOUS}#pics_max">{L_PREVIOUS}</a>
							</td>
							# ENDIF #
							# IF pics.pics_max.C_NEXT #
							<td class="align-right no-separator">
								<a href="admin_gallery.php?cat={pics.pics_max.ID_CATEGORY}&amp;id={pics.pics_max.ID_NEXT}#pics_max">{L_NEXT}</a> <a href="admin_gallery.php?cat={pics.pics_max.ID_CATEGORY}&amp;id={pics.pics_max.ID_NEXT}#pics_max" class="fa fa-arrow-right fa-2x"></a>
							</td>
							# ENDIF #
						</tr>
					</tbody>
				</table>

				<div class="spacer"></div>

				<table class="table">
					<thead>
						<tr>
							<th colspan="2">
								{L_INFORMATIONS}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="smaller">
								<strong>{L_NAME}:</strong> <span id="fi_{pics.pics_max.ID}">{pics.pics_max.PICTURE_NAME}</span> <span id="fi{pics.pics_max.ID}"></span>
							</td>
							<td class="smaller">
								<strong>{L_POSTOR}:</strong> # IF pics.pics_max.C_POSTOR_EXIST #<a class="small {pics.pics_max.POSTOR_LEVEL_CLASS}"# IF pics.pics_max.C_POSTOR_GROUP_COLOR # style="color:{pics.pics_max.POSTOR_GROUP_COLOR}"# ENDIF # href="{pics.pics_max.U_POSTOR_PROFILE}">{pics.pics_max.POSTOR}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF #
							</td>
						</tr>
						<tr>
							<td class="smaller">
								<strong>{L_VIEWS}:</strong> {pics.pics_max.VIEWS}
							</td>
							<td class="smaller">
								<strong>{L_ADD_ON}:</strong> {pics.pics_max.DATE}
							</td>
						</tr>
						<tr>
							<td class="smaller">
								<strong>{L_DIMENSION}:</strong> {pics.pics_max.DIMENSION}
							</td>
							<td class="smaller">
								<strong>{L_SIZE}:</strong> {pics.pics_max.SIZE} Ko
							</td>
						</tr>
						<tr>
							<td colspan="2" class="small">
								&nbsp;&nbsp;&nbsp;<span id="fihref{pics.pics_max.ID}"><a href="javascript:display_rename_file('{pics.pics_max.ID}', '{pics.pics_max.RENAME}', '{pics.pics_max.RENAME_CUT}');" aria-label="{L_EDIT}"><i class="fa fa-edit" aria-hidden="true"></i></a>

								<a href="gallery.php?del={pics.pics_max.ID}&amp;cat={pics.pics_max.ID_CATEGORY}&amp;token={pics.pics_max.TOKEN}" aria-label="{L_DELETE}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>

								<div id="move{pics.pics_max.ID}" class="move-pics-container">
									<div class="bbcode-block move-pics-block" onmouseover="pics_hide_block({pics.pics_max.ID}, 1);" onmouseout="pics_hide_block({pics.pics_max.ID}, 0);">
										<div>{L_MOVETO} :</div>
										<select class="valign-middle" name="{pics.pics_max.ID}cat" onchange="document.location = 'gallery.php?id={pics.pics_max.ID}&amp;token={pics.pics_max.TOKEN}&amp;move=' + this.options[this.selectedIndex].value">
											{pics.pics_max.CAT}
										</select>
									</div>
								</div>
								<a href="javascript:pics_display_block({pics.pics_max.ID});" onmouseover="pics_hide_block({pics.pics_max.ID}, 1);" onmouseout="pics_hide_block({pics.pics_max.ID}, 0);" aria-label="{L_MOVETO}"><i class="fa fa-share" aria-hidden="true"></i></a>
								<a id="img_aprob{pics.pics_max.ID}" href="javascript:pics_aprob({pics.pics_max.ID});" # IF pics.pics_max.C_APPROVED #aria-label="{L_APROB}" class="fa fa-eye"# ELSE #aria-label="{L_UNAPROB}" class="fa fa-eye-slash"# ENDIF #></a>
								&nbsp;<span id="img{pics.pics_max.ID}"></span>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="spacer"></div>

				<table class="table">
					<thead>
						<tr>
							<th colspan="{pics.pics_max.COLSPAN}">
								{L_THUMBNAILS}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="width:50px;">
								<span id="display_left">
								# IF pics.pics_max.C_LEFT_THUMBNAILS #
								<a href="javascript:display_thumbnails('left')" class="fa fa-arrow-left fa-2x"></a>
								# ENDIF #
								</span>
							</td>

							# START pics.pics_max.list_preview_pics #
								<td class="align-center" style="height:{pics.pics_max.list_preview_pics.HEIGHT}px"><span id="thumb{pics.pics_max.list_preview_pics.ID}"><a href="{pics.pics_max.list_preview_pics.URL}"><img src="pics/thumbnails/{pics.pics_max.list_preview_pics.PATH}" alt="{pics.pics_max.list_preview_pics.NAME}" /></a></span></td>
							# END pics.pics_max.list_preview_pics #


							<td style="width:50px;">
								<span id="display_right">
								# IF pics.pics_max.C_RIGHT_THUMBNAILS #
									<a href="javascript:display_thumbnails('right')" class="fa fa-arrow-right fa-2x"></a>
								# ENDIF #
								</span>
							</td>
						</tr>
					</tbody>
				</table>
			# END pics.pics_max #

			# IF NOT pics.C_PICS_MAX #
				<article>
					<header>
						<div class="align-right"> # IF pics.C_EDIT #<a href="{pics.U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF #</div>
						<h2>{GALLERY}</h2>
					</header>
					<div class="cell-flex cell-columns-{COLUMNS_NUMBER} cell-tile">
						# START pics.list #
							<div class="cell">
								<div class="cell-header">
									<div class="cell-name">
										<a class="com" href="{pics.list.U_DISPLAY}"><span id="fi_{pics.list.ID}">{pics.list.NAME}</span></a> <span id="fi{pics.list.ID}"></span>
									</div>
								</div>
								<div class="cell-body">
									<div class="cell-thumbnail cell-landscape">
										<div id="pics{pics.list.ID}" class="pics-list-element">
											<img src="pics/{pics.list.PATH}" alt="{pics.list.ALT_NAME}" />
										</div>
										<a class="cell-thumbnail-caption" href="pics/{pics.list.PATH}" data-lightbox="formatter" aria-label="{pics.list.NAME}">${LangLoader::get_message('general-config.view_real_preview', 'admin-config-common')}</a>
									</div>
								</div>
								<div class="cell-list">
									<ul>
										<li>
											# IF pics.list.C_POSTOR_EXIST #{L_BY} <a class="small {pics.list.POSTOR_LEVEL_CLASS}"# IF pics.list.C_POSTOR_GROUP_COLOR # style="color:{pics.list.POSTOR_GROUP_COLOR}"# ENDIF # href="{pics.list.U_POSTOR_PROFILE}">{pics.list.POSTOR}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF #
										</li>
									</ul>
								</div>
								<div class="cell-footer">
									<span id="fihref{pics.list.ID}"><a href="javascript:display_rename_file('{pics.list.ID}', '{pics.list.PROTECTED_TITLE}', '{pics.list.PROTECTED_NAME}');" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a></span>
									<a href="admin_gallery.php?del={pics.list.ID}&amp;token={TOKEN}&amp;cat={CAT_ID}" data-confirmation="delete-element" aria-label="{L_DELETE}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>

									<div id="move{pics.list.ID}" class="move-pics-container">
										<div class="bbcode-block move-pics-block" onmouseover="pics_hide_block({pics.list.ID}, 1);" onmouseout="pics_hide_block({pics.list.ID}, 0);">
											<div>{L_MOVETO} :</div>
											<select class="valign-middle" name="{pics.list.ID}cat" onchange="document.location = 'admin_gallery.php?id={pics.list.ID}&amp;token={TOKEN}&amp;move=' + this.options[this.selectedIndex].value">
												{pics.list.CAT}
											</select>
										</div>
									</div>
									<a href="javascript:pics_display_block({pics.list.ID});" onmouseover="pics_hide_block({pics.list.ID}, 1);" onmouseout="pics_hide_block({pics.list.ID}, 0);" aria-label="{L_MOVETO}"><i class="fa fa-share"></i></a>

									<a id="img_aprob{pics.list.ID}" href="javascript:pics_aprob({pics.list.ID});" # IF pics.list.C_APPROVED #aria-label="{L_APROB}" class="fa fa-eye"# ELSE #aria-label="{L_UNAPROB}" class="fa fa-eye-slash"# ENDIF #></a>
									&nbsp;<span id="img{pics.list.ID}"></span>
								</div>
							</div>
						# END pics.list #
					</div>
				</article>
			# ENDIF #

			# IF C_DISPLAY_NO_PICTURES_MESSAGE #
				# IF NOT C_PICTURES #
					<div class="message-helper bgc notice">
						{L_TOTAL_IMG}
					</div>
					# ELSE #
					<p class="nbr-total-pics smaller">
						{L_TOTAL_IMG}
					</p>
				# ENDIF #
			# ENDIF #
			# IF C_PAGINATION #
				<p class="align-center">
					# INCLUDE PAGINATION #
				</p>
			# ENDIF #
		</section>
	# END pics #
</div>
