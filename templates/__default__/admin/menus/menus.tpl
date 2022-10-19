<script>
	// Hide/reveal the block menu to add menus
	var delay = 1200; // Delay to hide the block on mouseleave.
	var timeout;
	var displayed = false;
	var previous = '';
	var started = false;

	// Show the block
	function menu_display_block(divID)
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

	// Hide the block
	function menu_hide_block(idfield, stop)
	{
		if( stop && timeout )
			clearTimeout(timeout);
		else if( started )
			timeout = setTimeout('menu_display_block()', delay);
	}

	//  Opacity when block menu is unchecked
	function minimize_container(input, containerName)
	{
		let container = document.getElementById('mod_' + containerName);
		let content = container.getElementsByClassName('menus-block-container');
		if (!container)
			return;

		if(input.checked == false)
		{
			if(content)
			{
				for(let i=0; i < content.length; i++) {
					content[i].style.opacity = '0.5';
					content[i].style.filter = 'alpha(opacity=50)';
				}
			}
		}
		else
		{
			if(content)
			{
				for(let i=0; i < content.length; i++) {
					content[i].style.opacity = '1';
					content[i].style.filter = 'alpha(opacity=100)';
				}
			}
		}
	}

	// Sortable drag and drop
	var menusContainerList = new Array(
		'mod_topheader',
		'mod_header',
		'mod_subheader',
		'mod_left',
		'mod_right',
		'mod_topcentral',
		'mod_central',
		'mod_bottomcentral',
		'mod_topfooter',
		'mod_footer'
	);

	function build_menu_tree()
	{
		var containerListLength = menusContainerList.length;
		for(var i = 0; i < containerListLength; i++)
		{
			var sequence = jQuery('#' + menusContainerList[i]).sortable("serialize").get();
			jQuery('<input/>').attr({
				type: 'hidden',
				name: 'menu_tree_' + menusContainerList[i],
				value: JSON.stringify(sequence[0])
			}).appendTo('#form_menus');
		}
	}

	function createSortableMenu()
	{
		var containerListLength = menusContainerList.length;
		for(var i = 0; i < containerListLength; i++)
		{
			jQuery('#' + menusContainerList[i]).sortable({
				handle: '.fa-arrows-alt',
				group: 'menus',
				placeholder: '<div class="dropzone">' + ${escapejs(@common.drop.here)} + '</div>',
				containerSelector: '#mod_topheader, #mod_header, #mod_subheader, #mod_left, #mod_right, #mod_topcentral, #mod_central, #mod_bottomcentral, #mod_topfooter, #mod_footer',
				itemSelector: 'div.menus-block-container'
			});
		}
	}
</script>

<form id="form_menus" action="menus.php?action=save" method="post" onsubmit="build_menu_tree();">

	<div class="themesmanagement">
		<h2>{@menu.menus.management}</h2>
		<div class="grouped-inputs">
			<span class="grouped-element">{@menu.theme.management} :</span>
			<select class="grouped-element" name="switchtheme" onchange="document.location = '?token={TOKEN}&amp;theme=' + this.options[this.selectedIndex].value;">
				# START themes #
					<option value="{themes.THEME_ID}" {themes.SELECTED} >{themes.NAME}</option>
				# END themes #
			</select>
		</div>
	</div>
	<div id="admin-contents">
		<div class="menusmanagement">
			<div id="container-top-header">
				<div class="container-block">
					<p class="menu-block-libelle mini-checkbox flex-between">
						<span>
							<span class="form-field-checkbox">
								<label class="checkbox" for="top_header_enabled">
									<input id="top_header_enabled" onclick="minimize_container(this, 'topheader')" type="checkbox" name="top_header_enabled" {CHECKED_TOP_HEADER_COLUMN} />
									<span>&nbsp;</span>
								</label>
							</span>
							<span class="text-strong">{@menu.top.header}</span>
						</span>
						<span class="pinned notice">{TOP_HEADER_MENUS_NUMBER}</span>
					</p>
					<p class="menus-block-add" onclick="menu_display_block('addmenu9');" onmouseover="menu_hide_block('addmenu9', 1);" onmouseout="menu_hide_block('addmenu9', 0);">
						<i class="fa fa-plus" aria-hidden="true"></i> {@menu.add.menu}
					</p>
					<div class="container-block-absolute" id="moveaddmenu9">
						<div onmouseover="menu_hide_block('addmenu9', 1);" onmouseout="menu_hide_block('addmenu9', 0);">
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=9" class="small">{@menu.links.menu}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=9" class="small">{@menu.content.menu}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=9" class="small">{@menu.feed.menu}</a>
							</p>
						</div>
					</div>
				</div>
				<div id="mod_topheader" class="menus-block-list">
					# START mod_topheader #
						{mod_topheader.MENU}
					# END mod_topheader #
				</div>
			</div>
			<div id="container-inner-header">
				<div class="container-block">
					<p class="menu-block-libelle mini-checkbox flex-between">
						<span>
							<span class="form-field-checkbox">
								<label class="checkbox" for="header_enabled">
									<input id="header_enabled" onclick="minimize_container(this, 'header')" type="checkbox" name="header_enabled" {CHECKED_HEADER_COLUMN} />
									<span>&nbsp;</span>
								</label>
							</span>
							<span class="text-strong">{@menu.header}</span>
						</span>
						<span class="pinned notice">{HEADER_MENUS_NUMBER}</span>
					</p>
					<p class="menus-block-add" onclick="menu_display_block('addmenu1');" onmouseover="menu_hide_block('addmenu1', 1);" onmouseout="menu_hide_block('addmenu1', 0);">
						<i class="fa fa-plus" aria-hidden="true"></i> {@menu.add.menu}
					</p>
					<div class="container-block-absolute" id="moveaddmenu1">
						<div onmouseover="menu_hide_block('addmenu1', 1);" onmouseout="menu_hide_block('addmenu1', 0);">
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=1" class="small">{@menu.links.menu}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=1" class="small">{@menu.content.menu}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=1" class="small">{@menu.feed.menu}</a>
							</p>
						</div>
					</div>
				</div>
				<div id="mod_header" class="menus-block-list">
					# START mod_header #
						{mod_header.MENU}
					# END mod_header #
				</div>
			</div>
			<div id="container-sub-header">
				<div class="container-block">
					<p class="menu-block-libelle mini-checkbox flex-between">
						<span>
							<span class="form-field-checkbox">
								<label class="checkbox" for="sub_header_enabled">
									<input id="sub_header_enabled" onclick="minimize_container(this, 'subheader')" type="checkbox" name="sub_header_enabled" {CHECKED_SUB_HEADER_COLUMN} />
									<span>&nbsp;</span>
								</label>
							</span>
							<span class="text-strong">{@menu.sub.header}</span>
						</span>
						<span class="pinned notice">{SUB_HEADER_MENUS_NUMBER}</span>
					</p>
					<p class="menus-block-add" onclick="menu_display_block('addmenu2');" onmouseover="menu_hide_block('addmenu2', 1);" onmouseout="menu_hide_block('addmenu2', 0);">
						<i class="fa fa-plus" aria-hidden="true"></i> {@menu.add.menu}
					</p>
					<div class="container-block-absolute" id="moveaddmenu2">
						<div onmouseover="menu_hide_block('addmenu2', 1);" onmouseout="menu_hide_block('addmenu2', 0);">
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=2" class="small">{@menu.links.menu}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=2" class="small">{@menu.content.menu}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=2" class="small">{@menu.feed.menu}</a>
							</p>
						</div>
					</div>
				</div>
				<div id="mod_subheader" class="menus-block-list">
					# START mod_subheader #
						{mod_subheader.MENU}
					# END mod_subheader #
				</div>
			</div>
			<div id="container-global" class="menus-management-column">
				<div id="container-menu-left" class="menus-management-column-left">
					<div class="container-block">
						<p class="menu-block-libelle mini-checkbox flex-between">
							<span>
								<span class="form-field-checkbox">
									<label class="checkbox" for="left_column_enabled">
										<input id="left_column_enabled" onclick="minimize_container(this, 'left')" type="checkbox" name="left_column_enabled" {CHECKED_LEFT_COLUMN} />
										<span>&nbsp;</span>
									</label>
								</span>
								<span class="text-strong">{@menu.left}	</span>
							</span>
							<span class="pinned notice">{LEFT_MENUS_NUMBER}</span>
						</p>
						<p class="menus-block-add" onclick="menu_display_block('addmenu3');" onmouseover="menu_hide_block('addmenu3', 1);" onmouseout="menu_hide_block('addmenu3', 0);">
							<i class="fa fa-plus" aria-hidden="true"></i> {@menu.add.menu}
						</p>
						<div class="container-block-absolute" id="moveaddmenu3">
							<div onmouseover="menu_hide_block('addmenu3', 1);" onmouseout="menu_hide_block('addmenu3', 0);">
								<p class="menus-block-add menus-block-add-links">
									<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=7" class="small">{@menu.links.menu}</a>
								</p>
								<p class="menus-block-add menus-block-add-links">
									<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=7" class="small">{@menu.content.menu}</a>
								</p>
								<p class="menus-block-add menus-block-add-links">
									<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=7" class="small">{@menu.feed.menu}</a>
								</p>
							</div>
						</div>
					</div>
					<div id="mod_left" class="menus-block-list">
						# START mod_left #
							{mod_left.MENU}
						# END mod_left #
					</div>
				</div>
				<div id="container-main" class="menus-management-column-central">
					<div id="container-top-content">
						<div class="container-block">
							<p class="menu-block-libelle mini-checkbox flex-between">
								<span>
									<span class="form-field-checkbox">
										<label class="checkbox" for="top_central_enabled">
											<input id="top_central_enabled" onclick="minimize_container(this, 'topcentral')" type="checkbox" name="top_central_enabled" {CHECKED_TOP_CENTRAL_COLUMN} />
											<span>&nbsp;</span>
										</label>
									</span>
									<span class="text-strong">{@menu.top.central}</span>
								</span>
								<span class="pinned notice">{TOP_CENTRAL_MENUS_NUMBER}</span>
							</p>
							<p class="menus-block-add" onclick="menu_display_block('addmenu4');" onmouseover="menu_hide_block('addmenu4', 1);" onmouseout="menu_hide_block('addmenu4', 0);">
								<i class="fa fa-plus" aria-hidden="true"></i> {@menu.add.menu}
							</p>
							<div class="container-block-absolute" id="moveaddmenu4">
								<div onmouseover="menu_hide_block('addmenu4', 1);" onmouseout="menu_hide_block('addmenu4', 0);">
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=3" class="small">{@menu.links.menu}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=3" class="small">{@menu.content.menu}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=3" class="small">{@menu.feed.menu}</a>
									</p>
								</div>
							</div>
						</div>
						<div id="mod_topcentral" class="menus-block-list">
							# START mod_topcentral #
								{mod_topcentral.MENU}
							# END mod_topcentral #
						</div>
					</div>
					<div id="container-main-content">
						<div class="container-block">
							<p class="menu-block-libelle flex-between">
								<span class="text-strong">{@menu.available.menus}</span>
								<span class="pinned notice">{AVAILABLE_MENUS_NUMBER}</span>
							</p>
							<p class="menus-block-add"></p>
						</div>
						<div id="mod_central" class="menus-block-list">
							# START mod_central #
								{mod_central.MENU}
							# END mod_central #
						</div>
					</div>
					<div id="container-bottom-content">
						<div class="container-block">
							<p class="menu-block-libelle mini-checkbox flex-between">
								<span>
									<span class="form-field-checkbox">
										<label class="checkbox" for="bottom_central_enabled">
											<input id="bottom_central_enabled" onclick="minimize_container(this, 'bottomcentral')" type="checkbox" name="bottom_central_enabled" {CHECKED_BOTTOM_CENTRAL_COLUMN} />
											<span>&nbsp;</span>
										</label>
									</span>
									<span class="text-strong">{@menu.bottom.central}</span>
								</span>
								<span class="pinned notice">{BOTTOM_CENTRAL_MENUS_NUMBER}</span>
							</p>
							<p class="menus-block-add" onclick="menu_display_block('addmenu5');" onmouseover="menu_hide_block('addmenu5', 1);" onmouseout="menu_hide_block('addmenu5', 0);">
								<i class="fa fa-plus" aria-hidden="true"></i> {@menu.add.menu}
							</p>
							<div class="container-block-absolute" id="moveaddmenu5">
								<div onmouseover="menu_hide_block('addmenu5', 1);" onmouseout="menu_hide_block('addmenu5', 0);">
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=4" class="small">{@menu.links.menu}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=4" class="small">{@menu.content.menu}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=4" class="small">{@menu.feed.menu}</a>
									</p>
								</div>
							</div>
						</div>
						<div id="mod_bottomcentral" class="menus-block-list">
							# START mod_bottomcentral #
								{mod_bottomcentral.MENU}
							# END mod_bottomcentral #
						</div>
					</div>
				</div>
				<div id="container-menu-right" class="menus-management-column-right">
					<div class="container-block">
						<div class="menu-manager-block">
							<p class="menu-block-libelle mini-checkbox flex-between">
								<span>
									<span class="form-field-checkbox">
										<label class="checkbox" for="right_column_enabled">
											<input id="right_column_enabled" onclick="minimize_container(this, 'right')" type="checkbox" name="right_column_enabled" {CHECKED_RIGHT_COLUMN} />
											<span>&nbsp;</span>
										</label>
									</span>
									<span class="text-strong">{@menu.right}</span>
								</span>
								<span class="pinned notice">{RIGHT_MENUS_NUMBER}</span>
							</p>
							<p class="menus-block-add" onclick="menu_display_block('addmenu6');" onmouseover="menu_hide_block('addmenu6', 1);" onmouseout="menu_hide_block('addmenu6', 0);">
								<i class="fa fa-plus" aria-hidden="true"></i> {@menu.add.menu}
							</p>
							<div class="container-block-absolute" id="moveaddmenu6">
								<div onmouseover="menu_hide_block('addmenu6', 1);" onmouseout="menu_hide_block('addmenu6', 0);">
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=8" class="small">{@menu.links.menu}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=8" class="small">{@menu.content.menu}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=8" class="small">{@menu.feed.menu}</a>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div id="mod_right" class="menus-block-list">
						# START mod_right #
							{mod_right.MENU}
						# END mod_right #
					</div>
				</div>
			</div>
			<div id="container-top-footer">
				<div class="container-block">
					<p class="menu-block-libelle mini-checkbox flex-between">
						<span>
							<span class="form-field-checkbox">
								<label class="checkbox" for="top_footer_enabled">
									<input id="top_footer_enabled" onclick="minimize_container(this, 'topfooter')" type="checkbox" name="top_footer_enabled" {CHECKED_TOP_FOOTER_COLUMN} />
									<span>&nbsp;</span>
								</label>
							</span>
							<span class="text-strong">{@menu.top.footer}</span>
						</span>
						<span class="pinned notice">{TOP_FOOTER_MENUS_NUMBER}</span>
					</p>
					<p class="menus-block-add" onclick="menu_display_block('addmenu7');" onmouseover="menu_hide_block('addmenu7', 1);" onmouseout="menu_hide_block('addmenu7', 0);">
						<i class="fa fa-plus" aria-hidden="true"></i> {@menu.add.menu}
					</p>
					<div class="container-block-absolute" id="moveaddmenu7">
						<div onmouseover="menu_hide_block('addmenu7', 1);" onmouseout="menu_hide_block('addmenu7', 0);">
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=5" class="small">{@menu.links.menu}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=5" class="small">{@menu.content.menu}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=5" class="small">{@menu.feed.menu}</a>
							</p>
						</div>
					</div>
				</div>
				<div id="mod_topfooter" class="menus-block-list">
					# START mod_topfooter #
						{mod_topfooter.MENU}
					# END mod_topfooter #
				</div>
			</div>
			<div id="container-footer-content">
				<div class="container-block">
					<p class="menu-block-libelle mini-checkbox flex-between">
						<span>
							<span class="form-field-checkbox">
								<label class="checkbox" for="footer_enabled">
									<input id="footer_enabled" onclick="minimize_container(this, 'footer')" type="checkbox" name="footer_enabled" {CHECKED_FOOTER_COLUMN} />
									<span>&nbsp;</span>
								</label>
							</span>
							<span class="text-strong">{@menu.footer}</span>
						</span>
						<span class="pinned notice">{FOOTER_MENUS_NUMBER}</span>
					</p>
					<p class="menus-block-add" onclick="menu_display_block('addmenu8');" onmouseover="menu_hide_block('addmenu8', 1);" onmouseout="menu_hide_block('addmenu8', 0);">
						<i class="fa fa-plus" aria-hidden="true"></i> {@menu.add.menu}
					</p>
					<div class="container-block-absolute" id="moveaddmenu8">
						<div onmouseover="menu_hide_block('addmenu8', 1);" onmouseout="menu_hide_block('addmenu8', 0);">
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=6" class="small">{@menu.links.menu}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=6" class="small">{@menu.content.menu}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=6" class="small">{@menu.feed.menu}</a>
							</p>
						</div>
					</div>
				</div>
				<div id="mod_footer" class="menus-block-list">
					# START mod_footer #
						{mod_footer.MENU}
					# END mod_footer #
				</div>
			</div>
		</div>

		<div id="valid-position-menus">
			<button type="submit" class="button bgc-full link-color big" name="valid" value="true"><i class="far fa-fw fa-square"></i> {@menu.position}</button>
			<input type="hidden" name="theme" value="{THEME_NAME}">
			<input type="hidden" name="token" value="{TOKEN}">
		</div>
	</div>

	<script>
		jQuery(document).ready(function() {
			createSortableMenu();

			// Change validation button status when save
			let menusUrl = window.location.hash;
			if(menusUrl != '') {
				jQuery('#valid-position-menus button')
					.addClass('success')
					.html('<i class="far fa-fw fa-check-square"></i>' + ${escapejs(@menu.valid.position)});
				if (performance.navigation.type == performance.navigation.TYPE_RELOAD) { // back to init status on reloading page
					history.pushState('', '', ' ');
					jQuery('#valid-position-menus button')
						.removeClass('success')
						.html('<i class="far fa-fw fa-square"></i>' + ${escapejs(@menu.position)});
				}
			}

			// Change validation button on moving
			jQuery('.menus-block-container').each(function() {
				let $this = jQuery(this),
					thisId = $this.attr('id'),
					thisParent = $this.parent().attr('id'),
					thisPrev = $this.prev().attr('id'),
					thisPos = thisParent + '-' + thisPrev;
				$this.on('mouseup', function() {
					let newParent = $this.closest('.menusmanagement').find('.dropzone').parent().attr('id'),
						newPrev = $this.siblings('.dropzone').prev().attr('id'),
						newPos = newParent + '-' + newPrev;
					if($this.hasClass('dragged')) {
						if(newPos != thisPos && newPrev != thisId)
							jQuery('#valid-position-menus button')
								.addClass('warning')
								.html('<i class="far fa-fw fa-square"></i>' + ${escapejs(@menu.valid.position)});
					}
				});
			});

			// Change validation button on changing checkboxes status
			jQuery('[type="checkbox"]').on('change', function(){
				jQuery('#valid-position-menus button').addClass('warning').html('<i class="far fa-fw fa-square"></i>' + ${escapejs(@menu.valid.position)});
			});

			// opacity for unchecked block on page loading
			jQuery('.container-block').each(function(){
				let cb = jQuery(this).find('[type="checkbox"]');
				if(!cb.is(':checked')) jQuery(this).siblings().find('.menus-block-container').css('opacity', 0.5);
			});
		});
	</script>
</form>
