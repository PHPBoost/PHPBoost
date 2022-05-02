<script>
	var pics_displayed = 0;
	function display_pics(id, path)
	{
		if( pics_displayed != id )
		{
			document.getElementById('pics_max').innerHTML = '<img src="' + path + '" alt="' + path + '" />';
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
			document.getElementById('fi' + id).innerHTML = '<input class="pic-name-input" type="text" name="fiinput' + id + '" id="fiinput' + id + '" value="' + previous_name.replace(/\"/g, "&quot;") + '" onblur="rename_file(\'' + id + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'").replace(/\"/g, "&quot;") + '\');">';
			document.getElementById('fiinput' + id).focus();
		}
	}

	function rename_file(id_file, previous_cut_name)
	{
		var name = document.getElementById("fiinput" + id_file).value;
		var regex = /\/|\\|\||\?|<|>/;

		if( regex.test(name) ) //interdiction des caractères spéciaux dans le nom.
		{
			alert("${escapejs(@warning.file.forbidden.chars)}");
			document.getElementById('fi_' + id_file).style.display = 'inline';
			document.getElementById('fi' + id_file).style.display = 'none';
		}
		else
		{
			document.getElementById('img' + id_file).innerHTML = '<i class="fa fa-fw fa-spinner fa-spin"></i>';

			data = "id_file=" + id_file + "&name=" + name.replace(/&/g, "%26") + "&previous_name=" + previous_cut_name.replace(/&/g, "%26");
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/gallery/xmlhttprequest.php?token={TOKEN}&rename_pics=1');
			xhr_object.onreadystatechange = function()
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '0' )
				{
					document.getElementById('fi' + id_file).style.display = 'none';
					document.getElementById('fi_' + id_file).style.display = 'inline';
					document.getElementById('fi_' + id_file).innerHTML = xhr_object.responseText;

					html_protected_name = name.replace(/\'/g, "\\\'").replace(/\"/g, "&quot;");
					html_protected_name2 = xhr_object.responseText.replace(/\'/g, "\\\'").replace(/\"/g, "&quot;");

					document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\',\'' + html_protected_name + '\',\'' + html_protected_name2 + '\');" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>';
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
		document.getElementById('img' + id_file).innerHTML = '<i class="fa fa-fw fa-spinner fa-spin"></i>';

		data = 'id_file=' + id_file;
		var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/gallery/xmlhttprequest.php?token={TOKEN}&aprob_pics=1');
		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' )
			{
				var img_aprob, title_aprob;
				if( xhr_object.responseText == 0 )
				{
					img_aprob = 'fa-eye-slash';
					title_aprob = '{@common.disapprove}';
				}
				else
				{
					img_aprob = 'fa-eye';
					title_aprob = '{@common.approve}';
				}

				document.getElementById('img' + id_file).innerHTML = '';
				if( document.getElementById('img_aprob' + id_file) )
				{
					if(document.getElementById('img_aprob' + id_file).className == "fa fa-fw fa-eye-slash"){
						document.getElementById('img_aprob' + id_file).className = "fa fa-fw fa-eye";
					} else {
						document.getElementById('img_aprob' + id_file).className = "fa fa-fw fa-eye-slash";
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
					document.getElementById('display_left').innerHTML = '<a href="javascript:display_thumbnails(\'left\')"><i class="fa fa-fw fa-arrow-left fa-2x"></i></a>';
				document.getElementById('display_right').innerHTML = '<a href="javascript:display_thumbnails(\'right\')"><i class="fa fa-fw fa-arrow-right fa-2x"></i></a>';
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
					document.getElementById('display_right').innerHTML = '<a href="javascript:display_thumbnails(\'right\')"><i class="fa fa-fw fa-arrow-right fa-2x"></i></a>';
				document.getElementById('display_left').innerHTML = '<a href="javascript:display_thumbnails(\'left\')"><i class="fa fa-fw fa-arrow-left fa-2x"></i></a>';
			}
			else
				return;
		}

		var j = 0;
		for(var i = 0; i <= {JS_ITEMS_NUMBER}; i++)
		{
			if( document.getElementById('thumb' + i) )
			{
				var key_left = start_thumb + j;
				var key_right = start_thumb + j;
				if( direction == 'left' && array_pics[key_left] )
				{
					document.getElementById('thumb' + i).innerHTML = '<a href="' + array_pics[key_left]['link'] + '"><img src="{PATH_TO_ROOT}/gallery/pics/thumbnails/' + array_pics[key_left]['path'] + '" alt="' + array_pics[key_left]['path'] + '" /></a>';
					j++;
				}
				else if( direction == 'right' && array_pics[key_right] )
				{
					document.getElementById('thumb' + i).innerHTML = '<a href="' + array_pics[key_right]['link'] + '"><img src="{PATH_TO_ROOT}/gallery/pics/thumbnails/' + array_pics[key_right]['path'] + '" alt="' + array_pics[key_right]['path'] + '" /></a>';
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
		if ('{DISPLAY_MODE}' == 'resize' && incr_pics_displayed == idpics)
			incr_pics_displayed = 0;
		else
		{
			if (document.getElementById('gv' + idpics))
			{
				if (already_view && ('{DISPLAY_MODE}' == 'full_screen' || '{DISPLAY_MODE}' == 'resize'))
				{
					data = '';
					var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/gallery/xmlhttprequest.php?token={TOKEN}&id=' + idpics + '&cat={ID_CATEGORY}&increment_view=1');
					xmlhttprequest_sender(xhr_object, data);
				}

				var views = 0;
				views = document.getElementById('gv' + idpics).innerHTML;
				views++;
				document.getElementById('gv' + idpics).innerHTML = views;
				document.getElementById('gvl' + idpics).innerHTML = (views > 1) ? "{@common.views}" : "{@common.view}";

				already_view = true;
				incr_pics_displayed = idpics;
			}
		}
	}
</script>

# INCLUDE MESSAGE_HELPER #

<section id="module-gallery" class="several-items">
	<header class="section-header">
		<div class="controls align-right">
			<a href="${relative_url(SyndicationUrlBuilder::rss('gallery', ID_CATEGORY))}" aria-label="{@common.syndication}"><i class="fa fa-fw fa-rss warning" aria-hidden="true"></i></a>
			# IF NOT C_ROOT_CATEGORY #{MODULE_NAME}# ENDIF #
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1>
			# IF C_ROOT_CATEGORY #{MODULE_NAME}# ELSE #{CATEGORY_NAME}# ENDIF #
		</h1>
	</header>

	# IF C_CATEGORY_DESCRIPTION #
		<div class="sub-section">
			<div class="content-container">
				<div class="cat-description">
					{CATEGORY_DESCRIPTION}
				</div>
			</div>
		</div>
	# ENDIF #

	<div class="gallery-tools-container">
		<nav id="cssmenu-galleryfilter" class="cssmenu cssmenu-right cssmenu-actionslinks cssmenu-tools">
			<ul class="level-0 hidden">
				<li class="has-sub"><a class="cssmenu-title"><i class="fa fa-fw fa-eye" aria-hidden="true"></i> <span>{@common.display}</span></a>
					<ul class="level-1">
						<li><a href="{U_BEST_VIEWS}" class="cssmenu-title offload"><i class="fa fa-fw fa-eye" aria-hidden="true"></i> <span>{@gallery.top.views}</span></a></li>
						# IF C_NOTATION_ENABLED #<li><a href="{U_BEST_NOTES}" class="cssmenu-title offload"><i class="far fa-star" aria-hidden="true"></i> <span>{@gallery.top.rated}</span></a></li># ENDIF #
					</ul>
				</li>
				<li class="has-sub"><a class="cssmenu-title"><i class="fa fa-fw fa-sort" aria-hidden="true"></i> <span>{@common.sort.by}</span></a>
					<ul class="level-1">
						<li><a href="{U_SORT_BY_NAME}" class="cssmenu-title offload"><i class="fa fa-fw fa-tag" aria-hidden="true"></i> <span>{@common.sort.by.alphabetic}</span></a></li>
						<li><a href="{U_SORT_BY_DATE}" class="cssmenu-title offload"><i class="fa fa-fw fa-clock" aria-hidden="true"></i> <span>{@common.sort.by.date}</span></a></li>
						<li><a href="{U_SORT_BY_VIEWS}" class="cssmenu-title offload"><i class="fa fa-fw fa-eye" aria-hidden="true"></i> <span>{@common.sort.by.views.number}</span></a></li>
						# IF C_NOTATION_ENABLED #
							<li><a href="{U_SORT_BY_NOTES}" class="cssmenu-title offload"><i class="far fa-star" aria-hidden="true"></i> <span>{@common.sort.by.best.note}</span></a></li>
						# ENDIF #
						# IF C_COMMENTS_ENABLED #
							<li><a href="{U_SORT_BY_COMMENTS}" class="cssmenu-title offload"><i class="fa fa-fw fa-comments" aria-hidden="true"></i> <span>{@common.sort.by.comments.number}</span></a></li>
						# ENDIF #
					</ul>
				</li>
				<li class="has-sub"><a class="cssmenu-title"><i class="fa fa-fw fa-sort-alpha-down"></i> <span>{@common.sort.direction}</span></a>
					<ul class="level-1">
						<li><a href="{U_ASC}" class="cssmenu-title offload"><i class="fa fa-fw fa-sort-amount-down" aria-hidden="true"></i> <span>{@common.sort.desc}</span></a></li>
						<li><a href="{U_DESC}" class="cssmenu-title offload"><i class="fa fa-fw fa-sort-amount-up" aria-hidden="true"></i> <span>{@common.sort.asc}</span></a></li>
					</ul>
				</li>
			</ul>
		</nav>
		<script>
			jQuery("#cssmenu-galleryfilter").menumaker({
				title: "{@common.sort.options}",
				format: "multitoggle",
				actionslinks: true,
				breakpoint: 768
			});
			jQuery(document).ready(function() {
				jQuery("#cssmenu-galleryfilter ul").removeClass('hidden');
			});
		</script>
		<div class="spacer"></div>
	</div>

	# IF C_SUB_CATEGORIES #
		<div class="sub-section">
			<div class="content-container">
				<div class="cell-flex cell-columns-{COLUMNS_NUMBER} cell-tile">
					# START sub_categories_list #
						<div class="cell cell-category category-{sub_categories_list.CATEGORY_ID}" itemscope>
							<div class="cell-header">
								<h5 class="cell-name" itemprop="name"><a class="offload" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></h5>
								<span class="small pinned notice" role="contentinfo" itemprop="items" aria-label="{sub_categories_list.ITEMS_NUMBER} # IF sub_categories_list.C_SEVERAL_ITEMS #{@gallery.items}# ELSE #{@gallery.item}# ENDIF #">{sub_categories_list.ITEMS_NUMBER}</span>
							</div>
							<div class="cell-body">
								<div class="cell-thumbnail cell-landscape cell-center" itemprop="thumbnail">
									# IF sub_categories_list.C_CATEGORY_THUMBNAIL #
										<img itemprop="thumbnailUrl" src="{sub_categories_list.U_CATEGORY_THUMBNAIL}" alt="{sub_categories_list.CATEGORY_NAME}" />
									# ENDIF #
									<a class="cell-thumbnail-caption small offload" href="{sub_categories_list.U_CATEGORY}">
										{@common.see.category}
									</a>
								</div>
							</div>
						</div>
					# END sub_categories_list #
				</div>
				# IF C_SUBCATEGORIES_PAGINATION #<span class="align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
			</div>
		</div>
	# ENDIF #

	# IF C_GALLERY_PICS #
		<div class="sub-section">
			<div class="content-container">
				<article id="gallery-item-{ID_CATEGORY}" class="gallery-item category-{ID_CATEGORY}">
					<header>
						<h2>{@common.image}</h2>
					</header>
					<div class="content">
						<p class="align-center" id="pics_max"></p>

						# IF C_GALLERY_PICS_MAX #
							<p class="pics-max"><a href="{U_ITEM_MAX}" data-lightbox="formatter"><img src="{U_ITEM_MAX}" alt="{NAME}" /></a></p>
							<div class="cell-tile cell-flex cell-columns-3">
								<div class="cell cell-1-3">
									<div class="cell-header">
										<h6 class="cell-name">{@common.informations}</h6>
									</div>
									<div class="cell-list">
										<ul>
											# IF C_TITLE_ENABLED #
												<li class="li-stretch">
													<span class="text-strong">{@common.name}</span>
													<span><span id="fi_{ID}">{NAME}</span> <span id="fi{ID}"></span></span>
												</li>
											# ENDIF #
											# IF C_AUTHOR_DISPLAYED #
												<li class="li-stretch">
													<span class="text-strong">{@common.author}</span>
													<span>
														# IF C_AUTHOR_EXISTS #
															<a itemprop="author" class="{AUTHOR_LEVEL_CLASS} offload"# IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}"# ENDIF # href="{U_AUTHOR_PROFILE}">{AUTHOR_DISPLAY_NAME}</a>
														# ELSE #
															<span class="visitor">{@user.guest}</span>
														# ENDIF #
													</span>
												</li>
											# ENDIF #
											# IF C_VIEWS_COUNTER_ENABLED #
												<li class="li-stretch"><span class="text-strong">{@common.views.number}</span><span>{VIEWS_NUMBER}</span></li>
											# ENDIF #
											<li class="li-stretch"><span class="text-strong">{@gallery.date.added}</span><span>{DATE}</span></li>
											<li class="li-stretch"><span class="text-strong">{@common.dimensions}</span><span>{DIMENSION}</span></li>
											<li class="li-stretch"><span class="text-strong">{@common.size}</span><span>{SIZE} {@common.unit.kilobytes}</span></li>
											# IF C_COMMENTS_ENABLED #
												<li class="li-stretch"><span class="text-strong">{@common.comments}</span><a class="offload" href="{U_COMMENTS}">{COMMENTS_NUMBER}</a></li>
											# ENDIF #
											# IF C_NOTATION_ENABLED #
												<li class="align-center"><div class="text-strong">{KERNEL_NOTATION}</div></li>
											# ENDIF #
											# IF C_GALLERY_PICS_MODO #
												<li id="img{ID}" class="li-stretch">
													<span id="fihref{ID}"><a href="javascript:display_rename_file('{ID}','{RENAME}','{RENAME_CUT}');" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a></span>

													<a href="{U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
													<div id="move{ID}" class="modal-container cell-modal inline-block cell-tile">
														<a data-modal data-target="gallery-pic-move-to" aria-label="{@common.move.to}"><i class="fa fa-fw fa-fw fa-share" aria-hidden="true"></i></a>
														<div id="gallery-pic-move-to" class="modal modal-animation">
															<div class="close-modal" aria-label="{@common.close}"></div>
															<div class="cell content-panel">
																<div class="cell-header">
																	<div class="cell-name">{@common.move.to} :</div>
																</div>
																<div class="cell-input">
																	<select name="{ID}cat" onchange="document.location = '{U_MOVE}'">
																		{CATEGORIES_LIST}
																	</select>
																</div>
															</div>
														</div>
													</div>
													<a href="javascript:pics_aprob({ID});" aria-label="# IF C_DISPLAY_STATUS #{@common.disapprove}# ELSE #{@common.approve}# ENDIF #"><i id="img_aprob{ID}" class="far fa-fw # IF C_DISPLAY_STATUS #fa-eye-slash# ELSE #fa-eye# ENDIF #" aria-hidden="true"></i></a>
												</li>
											# ENDIF #
										</ul>
									</div>
								</div>
								<div class="cell cell-2-3">
									<div class="flex-between">
										<span><a class="offload" href="{U_PREVIOUS}#pics_max" aria-label="{@common.previous}"><i class="fa fa-fw fa-arrow-left fa-2x" aria-hidden="true"></i></a></span>
										<span><a class="offload" href="{U_NEXT}#pics_max" aria-label="{@common.next}"> <i class="fa fa-fw fa-arrow-right fa-2x" aria-hidden="true"></i></a></span>
									</div>
									<table class="pics-max-thumbnails">
										<thead>
											<tr>
												<th colspan="{COLSPAN}">
													{@gallery.thumbnails} ({ITEMS_NUMBER})
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<span id="display_left"># IF C_LEFT_THUMBNAILS #<a href="javascript:display_thumbnails('left')" aria-label="{@common.previous}"><i class="fa fa-fw fa-arrow-left fa-2x" aria-hidden="true"></i></a># ENDIF #</span>
												</td>
												# START list_preview_pics #
													<td class="align-center# IF list_preview_pics.C_CURRENT_ITEM # current# ENDIF #" style="height:{list_preview_pics.HEIGHT}px">
														<span id="thumb{list_preview_pics.ID}">
															<a class="offload" href="{list_preview_pics.URL}"><img src="pics/thumbnails/{list_preview_pics.PATH}" alt="{list_preview_pics.NAME}" /></a>
														</span>
													</td>
												# END list_preview_pics #
												<td>
													<span id="display_right"># IF C_RIGHT_THUMBNAILS #<a href="javascript:display_thumbnails('right')" aria-label="{@common.next}"><i class="fa fa-fw fa-arrow-right fa-2x" aria-hidden="true"></i></a># ENDIF #</span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<aside>
								{COMMENTS}
							</aside>
						# ENDIF #
						# IF C_ITEMS #
							<div class="cell-flex cell-columns-{COLUMNS_NUMBER} cell-tile">
								# START pics_list #
									<div class="cell small# IF pics_list.C_NEW_CONTENT # new-content# ENDIF #">
										<div class="cell-header">
											# IF C_NAME_DISPLAYED #
												<div class="cell-name">
													<a id="fi_{pics_list.ID}" class="ellipsis offload" href="{pics_list.U_ITEM}">
														{pics_list.NAME}
													</a>
												</div>
											# ELSE #
												<span id="fi_{pics_list.ID}"></span>
											# ENDIF # <span id="fi{pics_list.ID}"></span>
											<span id="img{pics_list.ID}"></span>
											<!-- <a href="{PATH_TO_ROOT}/gallery/pics/{pics_list.PATH}" class="float-right" download=""><i class="fa fa-fw fa-download"></i></a> -->
										</div>
										<div class="cell-body">
											<div class="cell-thumbnail cell-landscape cell-center">
												<img src="{pics_list.U_PICTURE}" alt="{pics_list.NAME}" class="gallery-img" />
												<a class="cell-thumbnail-caption small" aria-label="# IF C_NAME_DISPLAYED #{pics_list.NAME}# ENDIF #" href="{pics_list.U_DISPLAY}" onclick="{pics_list.ONCLICK}" # IF NOT pics_list.ONCLICK # data-lightbox="formatter"# ENDIF #>
													{@form.picture.full.size}
												</a>
											</div>
										</div>
										<div class="cell-list">
											<ul>
												# IF C_AUTHOR_DISPLAYED #
													<li>
														# IF pics_list.C_AUTHOR_EXISTS #
															<a itemprop="author" class="{pics_list.AUTHOR_LEVEL_CLASS} offload"# IF pics_list.C_AUTHOR_GROUP_COLOR # style="color:{pics_list.AUTHOR_GROUP_COLOR}"# ENDIF # href="{pics_list.U_AUTHOR_PROFILE}">
																{@common.by} {pics_list.AUTHOR_DISPLAY_NAME}
															</a>
														# ELSE #
															{@common.by} {@user.guest}
														# ENDIF #
													</li>
												# ENDIF #
												# IF C_VIEWS_COUNTER_ENABLED #
													<li>
														<span id="gv{pics_list.ID}">{pics_list.VIEWS_NUMBER}</span> <span id="gvl{pics_list.ID}"># IF pics_list.C_SEVERAL_VIEWS #{@common.views}# ELSE #{@common.view}# ENDIF #</span>
													</li>
												# ENDIF #
												# IF C_COMMENTS_ENABLED #
													<li>
														<a class="offload" href="{pics_list.U_COMMENTS}">{pics_list.COMMENTS}</a>
													</li>
												# ENDIF #
												# IF C_NOTATION_ENABLED #
													<li>
														{pics_list.KERNEL_NOTATION}
													</li>
												# ENDIF #
											</ul>
										</div>
										# IF C_CONTROLS #
											<div class="cell-list">
												<ul>
													<li class="li-stretch">
														<a id="fihref{pics_list.ID}" href="javascript:display_rename_file('{pics_list.ID}','{pics_list.RENAME}','{pics_list.RENAME_CUT}');" aria-label="{@common.edit}"><i class="far fa-edit"></i></a>
														<a href="{pics_list.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-trash-alt"></i></a>
														<div id="move{pics_list.ID}" class="modal-container cell-modal cell-tile">
															<a data-modal data-target="gallery-pic-move-to" aria-label="{@common.move.to}"><i class="fa fa-fw fa-share" aria-hidden="true"></i></a>
															<div id="gallery-pic-move-to" class="modal modal-animation">
																<div class="close-modal" aria-label="{@common.close}"></div>
																<div class="cell content-panel">
																	<div class="cell-header">
																		<div class="cell-name">{@common.move.to} :</div>
																	</div>
																	<div class="cell-input">
																		<select name="{pics_list.ID}cat" onchange="document.location = '{pics_list.U_MOVE}">
																			{pics_list.CATEGORIES_LIST}
																		</select>
																	</div>
																</div>
															</div>
														</div>
														<a href="javascript:pics_aprob({pics_list.ID});" aria-label="# IF pics_list.C_APPROVED #{@common.disapprove}# ELSE #{@common.approve}# ENDIF #"><i id="img_aprob{pics_list.ID}" class="far fa-fw # IF pics_list.C_APPROVED #fa-eye-slash# ELSE #fa-eye# ENDIF #"></i></a>
													</li>
												</ul>
											</div>
										# ENDIF #
									</div>
								# END pics_list #
							</div>
						# ENDIF #
					</div>
					<footer># IF C_PAGINATION ## INCLUDE PAGINATION ## ENDIF #</footer>
				</article>
			</div>
		</div>
	# ENDIF #
	<footer></footer>
</section>
