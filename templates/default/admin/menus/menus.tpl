<script>
<!--
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
		var container = document.getElementById('mod_' + containerName);

		if (!container)
			return;

		if(input.checked == false)
		{
			container.style.opacity = 0.5;
			container.style.filter='alpha(opacity=50)';
		}
		else
		{
			container.style.opacity = 1;
			container.style.filter='alpha(opacity=100)';
		}
	}

// Sortable drag and drop
	var menusContainerList = new Array(
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
				placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>',
				containerSelector: '#mod_header, #mod_subheader, #mod_left, #mod_right, #mod_topcentral, #mod_central, #mod_bottomcentral, #mod_topfooter, #mod_footer',
				itemSelector: 'div.menus-block-container'
			});
		}
	}
-->
</script>

<form id="form_menus" action="menus.php?action=save" method="post" onsubmit="build_menu_tree();">

	<div class="themesmanagement">
		<strong>{L_THEME_MANAGEMENT} :</strong>
		<select name="switchtheme" onchange="document.location = '?token={TOKEN}&amp;theme=' + this.options[this.selectedIndex].value;">
			# START themes #
				<option value="{themes.IDNAME}" {themes.SELECTED} >{themes.NAME}</option>
			# END themes #
		</select>
	</div>
	<div id="admin-contents">
		<div class="menusmanagement">
			<div id="container-top-header">
				<div class="container-block">
					<p class="menu-block-libelle mini-checkbox">
						<span class="form-field-checkbox">
							<label class="checkbox" for="header_enabled">
								<input id="header_enabled" onclick="minimize_container(this, 'header')" type="checkbox" name="header_enabled" {CHECKED_HEADER_COLUMN} />
								<span>&nbsp;</span>
							</label>
						</span>
						{L_HEADER}

					</p>
					<p class="menus-block-add" onclick="menu_display_block('addmenu1');" onmouseover="menu_hide_block('addmenu1', 1);" onmouseout="menu_hide_block('addmenu1', 0);">
						<i class="fa fa-plus" aria-hidden="true"></i> {L_ADD_MENU}
					</p>
					<div class="container-block-absolute" id="moveaddmenu1">
						<div onmouseover="menu_hide_block('addmenu1', 1);" onmouseout="menu_hide_block('addmenu1', 0);">
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=1" class="small">{L_ADD_LINKS_MENUS}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=1" class="small">{L_ADD_CONTENT_MENUS}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=1" class="small">{L_ADD_FEED_MENUS}</a>
							</p>
						</div>
					</div>
				</div>
				<div id="mod_header">
					# START mod_header #
						{mod_header.MENU}
					# END mod_header #
				</div>
			</div>
			<div id="container-sub-header">
				<div class="container-block">
					<p class="menu-block-libelle mini-checkbox">
						<span class="form-field-checkbox">
							<label class="checkbox" for="sub_header_enabled">
								<input id="sub_header_enabled" onclick="minimize_container(this, 'subheader')" type="checkbox" name="sub_header_enabled" {CHECKED_SUB_HEADER_COLUMN} />
								<span>&nbsp;</span>
							</label>
						</span>
						{L_SUB_HEADER}
					</p>
					<p class="menus-block-add" onclick="menu_display_block('addmenu2');" onmouseover="menu_hide_block('addmenu2', 1);" onmouseout="menu_hide_block('addmenu2', 0);">
						<i class="fa fa-plus" aria-hidden="true"></i> {L_ADD_MENU}
					</p>
					<div class="container-block-absolute" id="moveaddmenu2">
						<div onmouseover="menu_hide_block('addmenu2', 1);" onmouseout="menu_hide_block('addmenu2', 0);">
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=2" class="small">{L_ADD_LINKS_MENUS}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=2" class="small">{L_ADD_CONTENT_MENUS}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=2" class="small">{L_ADD_FEED_MENUS}</a>
							</p>
						</div>
					</div>
				</div>
				<div id="mod_subheader">
					# START mod_subheader #
						{mod_subheader.MENU}
					# END mod_subheader #
				</div>
			</div>
			<div id="container-global" class="menus-management-column">
				<div id="container-menu-left" class="menus-management-column-left">
					<div class="container-block">
						<div class="container-block-absolute" id="moveaddmenu3">
							<div onmouseover="menu_hide_block('addmenu3', 1);" onmouseout="menu_hide_block('addmenu3', 0);">
								<p class="menus-block-add menus-block-add-links">
									<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=7" class="small">{L_ADD_LINKS_MENUS}</a>
								</p>
								<p class="menus-block-add menus-block-add-links">
									<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=7" class="small">{L_ADD_CONTENT_MENUS}</a>
								</p>
								<p class="menus-block-add menus-block-add-links">
									<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=7" class="small">{L_ADD_FEED_MENUS}</a>
								</p>
							</div>
						</div>
						<p class="menu-block-libelle mini-checkbox">
							<span class="form-field-checkbox">
								<label class="checkbox" for="left_column_enabled">
									<input id="left_column_enabled" onclick="minimize_container(this, 'left')" type="checkbox" name="left_column_enabled" {CHECKED_LEFT_COLUMN} />
									<span>&nbsp;</span>
								</label>
							</span>
							{L_LEFT_MENU}
						</p><p class="menus-block-add" onclick="menu_display_block('addmenu3');" onmouseover="menu_hide_block('addmenu3', 1);" onmouseout="menu_hide_block('addmenu3', 0);">
							<i class="fa fa-plus" aria-hidden="true"></i> {L_ADD_MENU}
						</p>
					</div>
					<div id="mod_left">
						# START mod_left #
							{mod_left.MENU}
						# END mod_left #
					</div>
				</div>
				<div id="container-main" class="menus-management-column-central">
					<div id="container-top-content">
						<div class="container-block">
							<p class="menu-block-libelle mini-checkbox">
								<span class="form-field-checkbox">
									<label class="checkbox" for="top_central_enabled">
										<input id="top_central_enabled" onclick="minimize_container(this, 'topcentral')" type="checkbox" name="top_central_enabled" {CHECKED_TOP_CENTRAL_COLUMN} />
										<span>&nbsp;</span>
									</label>
								</span>
								{L_TOP_CENTRAL_MENU}
							</p>
							<p class="menus-block-add" onclick="menu_display_block('addmenu4');" onmouseover="menu_hide_block('addmenu4', 1);" onmouseout="menu_hide_block('addmenu4', 0);">
								<i class="fa fa-plus" aria-hidden="true"></i> {L_ADD_MENU}
							</p>
							<div class="container-block-absolute" id="moveaddmenu4">
								<div onmouseover="menu_hide_block('addmenu4', 1);" onmouseout="menu_hide_block('addmenu4', 0);">
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=3" class="small">{L_ADD_LINKS_MENUS}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=3" class="small">{L_ADD_CONTENT_MENUS}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=3" class="small">{L_ADD_FEED_MENUS}</a>
									</p>
								</div>
							</div>
						</div>
						<div id="mod_topcentral">
							# START mod_topcentral #
								{mod_topcentral.MENU}
							# END mod_topcentral #
						</div>
					</div>
					<div id="container-main-content">
						<div class="container-block">
							<p class="menu-block-libelle">{L_MENUS_AVAILABLE}</p>
							<p class="menus-block-add"></p>
						</div>
						<div id="mod_central">
							# START mod_central #
								{mod_central.MENU}
							# END mod_central #
						</div>
					</div>
					<div id="container-bottom-content">
						<div class="container-block">
							<p class="menu-block-libelle mini-checkbox">
								<span class="form-field-checkbox">
									<label class="checkbox" for="bottom_central_enabled">
										<input id="bottom_central_enabled" onclick="minimize_container(this, 'bottomcentral')" type="checkbox" name="bottom_central_enabled" {CHECKED_BOTTOM_CENTRAL_COLUMN} />
										<span>&nbsp;</span>
									</label>
								</span>
								{L_BOTTOM_CENTRAL_MENU}
							</p>
							<p class="menus-block-add" onclick="menu_display_block('addmenu5');" onmouseover="menu_hide_block('addmenu5', 1);" onmouseout="menu_hide_block('addmenu5', 0);">
								<i class="fa fa-plus" aria-hidden="true"></i> {L_ADD_MENU}
							</p>
							<div class="container-block-absolute" id="moveaddmenu5">
								<div onmouseover="menu_hide_block('addmenu5', 1);" onmouseout="menu_hide_block('addmenu5', 0);">
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=4" class="small">{L_ADD_LINKS_MENUS}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=4" class="small">{L_ADD_CONTENT_MENUS}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=4" class="small">{L_ADD_FEED_MENUS}</a>
									</p>
								</div>
							</div>
						</div>
						<div id="mod_bottomcentral">
							# START mod_bottomcentral #
								{mod_bottomcentral.MENU}
							# END mod_bottomcentral #
						</div>
					</div>
				</div>
				<div id="container-menu-right" class="menus-management-column-right">
					<div class="container-block">
						<div class="menu-manager-block">
							<p class="menu-block-libelle mini-checkbox">
								<span class="form-field-checkbox">
									<label class="checkbox" for="right_column_enabled">
										<input id="right_column_enabled" onclick="minimize_container(this, 'right')" type="checkbox" name="right_column_enabled" {CHECKED_RIGHT_COLUMN} />
										<span>&nbsp;</span>
									</label>
								</span>
								{L_RIGHT_MENU}
							</p>
							<p class="menus-block-add" onclick="menu_display_block('addmenu6');" onmouseover="menu_hide_block('addmenu6', 1);" onmouseout="menu_hide_block('addmenu6', 0);">
								<i class="fa fa-plus" aria-hidden="true"></i> {L_ADD_MENU}
							</p>
							<div class="container-block-absolute" id="moveaddmenu6">
								<div onmouseover="menu_hide_block('addmenu6', 1);" onmouseout="menu_hide_block('addmenu6', 0);">
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=8" class="small">{L_ADD_LINKS_MENUS}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=8" class="small">{L_ADD_CONTENT_MENUS}</a>
									</p>
									<p class="menus-block-add menus-block-add-links">
										<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=8" class="small">{L_ADD_FEED_MENUS}</a>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div id="mod_right">
						# START mod_right #
							{mod_right.MENU}
						# END mod_right #
					</div>
				</div>
			</div>
			<div id="container-top-footer">
				<div class="container-block">
					<p class="menu-block-libelle mini-checkbox">
						<span class="form-field-checkbox">
							<label class="checkbox" for="top_footer_enabled">
								<input id="top_footer_enabled" onclick="minimize_container(this, 'topfooter')" type="checkbox" name="top_footer_enabled" {CHECKED_TOP_FOOTER_COLUMN} />
								<span>&nbsp;</span>
							</label>
						</span>
						{L_TOP_FOOTER}
					</p>
					<p class="menus-block-add" onclick="menu_display_block('addmenu7');" onmouseover="menu_hide_block('addmenu7', 1);" onmouseout="menu_hide_block('addmenu7', 0);">
						<i class="fa fa-plus" aria-hidden="true"></i> {L_ADD_MENU}
					</p>
					<div class="container-block-absolute" id="moveaddmenu7">
						<div onmouseover="menu_hide_block('addmenu7', 1);" onmouseout="menu_hide_block('addmenu7', 0);">
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=5" class="small">{L_ADD_LINKS_MENUS}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=5" class="small">{L_ADD_CONTENT_MENUS}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=5" class="small">{L_ADD_FEED_MENUS}</a>
							</p>
						</div>
					</div>
				</div>
				<div id="mod_topfooter">
					# START mod_topfooter #
						{mod_topfooter.MENU}
					# END mod_topfooter #
				</div>
			</div>
			<div id="container-footer-content">
				<div class="container-block">
					<p class="menu-block-libelle mini-checkbox">
						<span class="form-field-checkbox">
							<label class="checkbox" for="footer_enabled">
								<input id="footer_enabled" onclick="minimize_container(this, 'footer')" type="checkbox" name="footer_enabled" {CHECKED_FOOTER_COLUMN} />
								<span>&nbsp;</span>
							</label>
						</span>
						{L_FOOTER}
					</p>
					<p class="menus-block-add" onclick="menu_display_block('addmenu8');" onmouseover="menu_hide_block('addmenu8', 1);" onmouseout="menu_hide_block('addmenu8', 0);">
						<i class="fa fa-plus" aria-hidden="true"></i> {L_ADD_MENU}
					</p>
					<div class="container-block-absolute" id="moveaddmenu8">
						<div onmouseover="menu_hide_block('addmenu8', 1);" onmouseout="menu_hide_block('addmenu8', 0);">
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/links.php?s=6" class="small">{L_ADD_LINKS_MENUS}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/content.php?s=6" class="small">{L_ADD_CONTENT_MENUS}</a>
							</p>
							<p class="menus-block-add menus-block-add-links">
								<a href="{PATH_TO_ROOT}/admin/menus/feed.php?s=6" class="small">{L_ADD_FEED_MENUS}</a>
							</p>
						</div>
					</div>
				</div>
				<div id="mod_footer">
					# START mod_footer #
						{mod_footer.MENU}
					# END mod_footer #
				</div>
			</div>
		</div>

		<div id="valid-position-menus">
			<button type="submit" class="button submit" name="valid" value="true">{L_VALID_POSTIONS}</button>
			<input type="hidden" name="theme" value="{NAME_THEME}">
			<input type="hidden" name="token" value="{TOKEN}">
		</div>
	</div>

	<script>
		jQuery(document).ready(function() {
			createSortableMenu();
		});
	</script>
</form>
