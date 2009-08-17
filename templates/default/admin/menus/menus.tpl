<script type="text/javascript">
<!--
function Confirm_menu() {
	return confirm("{L_CONFIRM_DEL_MENU}");
}
var delay = 2000; //Délai après lequel le bloc est automatiquement masqué, après le départ de la souris.
var timeout;
var displayed = false;
var previous = '';
var started = false;

//Affiche le bloc.
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
//Cache le bloc.
function menu_hide_block(idfield, stop)
{
	if( stop && timeout )
		clearTimeout(timeout);
	else if( started )
		timeout = setTimeout('menu_display_block()', delay);
}
-->
</script>

<div id="admin_contents admin_contents_no_column">
	<table class="module_table" style="background:#FFFFFF;width:100%;border:2px solid black;">
		<tr>
			<td colspan="{COLSPAN}" style="padding:4px;border:1px solid black;background:#dfeade">
				<p class="menu_block_libelle">{L_HEADER}</p>
				# START mod_header #
				{mod_header.MENU}
				# END mod_header #
				
				<div style="width:140px;margin:auto;">
					<div style="position:relative;float:left;">
						<div style="position:absolute;z-index:99;margin-top:133px;margin-left:0px;float:left;display:none;" id="moveaddmenu1">
							<div style="position:absolute;bottom:28px;z-index:100;" onmouseover="menu_hide_block('addmenu1', 1);" onmouseout="menu_hide_block('addmenu1', 0);">
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/links.php" class="small_link">{L_ADD_LINKS_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/content.php" class="small_link">{L_ADD_CONTENT_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/feed.php" class="small_link">{L_ADD_FEED_MENUS}</a></p>
							</div>
						</div>
						<p class="menus_block_add" onclick="menu_display_block('addmenu1');" onmouseover="menu_hide_block('addmenu1', 1);" onmouseout="menu_hide_block('addmenu1', 0);">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
						</p>
					</div>
				</div>
				<div class="spacer"></div>
			</td>
		</tr>
		<tr>
			<td colspan="{COLSPAN}" style="padding:4px;border:1px solid black;background:#f1ffe3">
				<p class="menu_block_libelle">{L_SUB_HEADER}</p>
				# START mod_subheader #
				{mod_subheader.MENU}
				# END mod_subheader #
				
				<div style="width:140px;margin:auto;">
					<div style="position:relative;float:left;">
						<div style="position:absolute;z-index:99;margin-top:133px;margin-left:0px;float:left;display:none;" id="moveaddmenu2">
							<div style="position:absolute;bottom:28px;z-index:100;" onmouseover="menu_hide_block('addmenu2', 1);" onmouseout="menu_hide_block('addmenu2', 0);">
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/links.php" class="small_link">{L_ADD_LINKS_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/content.php" class="small_link">{L_ADD_CONTENT_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/feed.php" class="small_link">{L_ADD_FEED_MENUS}</a></p>
							</div>
						</div>
						<p class="menus_block_add" onclick="menu_display_block('addmenu2');" onmouseover="menu_hide_block('addmenu2', 1);" onmouseout="menu_hide_block('addmenu2', 0);">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
						</p>
					</div>
				</div>
				<div class="spacer"></div>
			</td>
		</tr>
		<tr>				
			# IF C_LEFT_COLUMN #
			<td style="width:185px;vertical-align:top;padding:4px;border:1px solid black;background:#e8eefd">
				<p class="menu_block_libelle">{L_LEFT_MENU}</p>
				# START mod_left #
				{mod_left.MENU}
				# END mod_left #
				
                # IF NOT C_RIGHT_COLUMN #
				<hr style="margin:10px 0px" />
				# START mod_right #
				{mod_right.MENU}
				# END mod_right #
				# ENDIF # 

				<div style="width:140px;margin:auto;">
					<div style="position:relative;float:left;">
						<div style="position:absolute;z-index:99;margin-top:133px;margin-left:0px;float:left;display:none;" id="moveaddmenu3">
							<div style="position:absolute;bottom:28px;z-index:100;" onmouseover="menu_hide_block('addmenu3', 1);" onmouseout="menu_hide_block('addmenu3', 0);">
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/links.php" class="small_link">{L_ADD_LINKS_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/content.php" class="small_link">{L_ADD_CONTENT_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/feed.php" class="small_link">{L_ADD_FEED_MENUS}</a></p>
							</div>
						</div>
						<p class="menus_block_add" onclick="menu_display_block('addmenu3');" onmouseover="menu_hide_block('addmenu3', 1);" onmouseout="menu_hide_block('addmenu3', 0);">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
						</p>
					</div>
				</div>
				<div class="spacer"></div>
			</td>
			# ENDIF #
			
			<td style="vertical-align:top;border:1px solid black;background:#f4f4f4;">
				<div style="padding:4px;border:1px solid black;background:#ffe1e1">
					<p class="menu_block_libelle">{L_TOP_CENTRAL_MENU}</p>
					# START mod_topcentral #
					{mod_topcentral.MENU}
					# END mod_topcentral #
					
					<div style="width:140px;margin:auto;">
						<div style="position:relative;float:left;">
							<div style="position:absolute;z-index:99;margin-top:133px;margin-left:0px;float:left;display:none;" id="moveaddmenu4">
								<div style="position:absolute;bottom:28px;z-index:100;" onmouseover="menu_hide_block('addmenu4', 1);" onmouseout="menu_hide_block('addmenu4', 0);">
									<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/links.php" class="small_link">{L_ADD_LINKS_MENUS}</a></p>
									<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/content.php" class="small_link">{L_ADD_CONTENT_MENUS}</a></p>
									<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/feed.php" class="small_link">{L_ADD_FEED_MENUS}</a></p>
								</div>
							</div>
							<p class="menus_block_add" onclick="menu_display_block('addmenu4');" onmouseover="menu_hide_block('addmenu4', 1);" onmouseout="menu_hide_block('addmenu4', 0);">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
							</p>
						</div>
					</div>
					<div class="spacer"></div>
				</div>
				<div style="padding:4px;border:1px solid black;">
					<p class="menu_block_libelle">{L_MENUS_AVAILABLE}</p>
					# START mod_main #
					{mod_main.MENU}
					# END mod_main #
					<div class="spacer">&nbsp;</div>
				</div>
				<div style="padding:4px;border:1px solid black;background:#ffe1e1">
					<p class="menu_block_libelle">{L_BOTTOM_CENTRAL_MENU}</p>
					# START mod_bottomcentral #
					{mod_bottomcentral.MENU}
					# END mod_bottomcentral #
					
					<div style="width:140px;margin:auto;">
						<div style="position:relative;float:left;">
							<div style="position:absolute;z-index:99;margin-top:133px;margin-left:0px;float:left;display:none;" id="moveaddmenu5">
								<div style="position:absolute;bottom:28px;z-index:100;" onmouseover="menu_hide_block('addmenu5', 1);" onmouseout="menu_hide_block('addmenu5', 0);">
									<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/links.php" class="small_link">{L_ADD_LINKS_MENUS}</a></p>
									<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/content.php" class="small_link">{L_ADD_CONTENT_MENUS}</a></p>
									<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/feed.php" class="small_link">{L_ADD_FEED_MENUS}</a></p>
								</div>
							</div>
							<p class="menus_block_add" onclick="menu_display_block('addmenu5');" onmouseover="menu_hide_block('addmenu5', 1);" onmouseout="menu_hide_block('addmenu5', 0);">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
							</p>
						</div>
					</div>
					<div class="spacer"></div>
				</div>						
			</td>
			
			# IF RIGHT_COLUMN #
			<td style="width:230px;vertical-align:top;padding:4px;border:1px solid black;background:#e8eefd">
				<p class="menu_block_libelle">{L_RIGHT_MENU}</p>
				# START mod_right #
				{mod_right.MENU}
				# END mod_right #
				
                # IF NOT C_LEFT_COLUMN #
				<hr /><br />
				# START mod_left #
				{mod_left.MENU}
				# END mod_left #
				# ENDIF #
				<div style="width:140px;margin:auto;">
					<div style="position:relative;float:left;">
						<div style="position:absolute;z-index:99;margin-top:133px;margin-left:0px;float:left;display:none;" id="moveaddmenu6">
							<div style="position:absolute;bottom:28px;z-index:100;" onmouseover="menu_hide_block('addmenu6', 1);" onmouseout="menu_hide_block('addmenu6', 0);">
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/links.php" class="small_link">{L_ADD_LINKS_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/content.php" class="small_link">{L_ADD_CONTENT_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/feed.php" class="small_link">{L_ADD_FEED_MENUS}</a></p>
							</div>
						</div>
						<p class="menus_block_add" onclick="menu_display_block('addmenu6');" onmouseover="menu_hide_block('addmenu6', 1);" onmouseout="menu_hide_block('addmenu6', 0);">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
						</p>
					</div>
				</div>
				<div class="spacer"></div>
			</td>
			# ENDIF #
		</tr>
		<tr>
			<td colspan="{COLSPAN}" style="padding:4px;border:1px solid black;background:#e8ffe5">
				<p class="menu_block_libelle">{L_TOP_FOOTER}</p>
				# START mod_topfooter #
				{mod_topfooter.MENU}
				# END mod_topfooter #
				<div style="width:140px;margin:auto;">
					<div style="position:relative;float:left;">
						<div style="position:absolute;z-index:99;margin-top:133px;margin-left:0px;float:left;display:none;" id="moveaddmenu7">
							<div style="position:absolute;bottom:28px;z-index:100;" onmouseover="menu_hide_block('addmenu7', 1);" onmouseout="menu_hide_block('addmenu7', 0);">
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/links.php" class="small_link">{L_ADD_LINKS_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/content.php" class="small_link">{L_ADD_CONTENT_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/feed.php" class="small_link">{L_ADD_FEED_MENUS}</a></p>
							</div>
						</div>
						<p class="menus_block_add" onclick="menu_display_block('addmenu7');" onmouseover="menu_hide_block('addmenu7', 1);" onmouseout="menu_hide_block('addmenu7', 0);">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
						</p>
					</div>
				</div>
				<div class="spacer"></div>
			</td>
		</tr>
		<tr>
			<td colspan="{COLSPAN}" style="padding:4px;border:1px solid black;background:#e6fffb">
				<p class="menu_block_libelle">{L_FOOTER}</p>
				# START mod_footer #
				{mod_footer.MENU}
				# END mod_footer #
				<div style="width:140px;margin:auto;">
					<div style="position:relative;float:left;">
						<div style="position:absolute;z-index:99;margin-top:133px;margin-left:0px;float:left;display:none;" id="moveaddmenu8">
							<div style="position:absolute;bottom:28px;z-index:100;" onmouseover="menu_hide_block('addmenu8', 1);" onmouseout="menu_hide_block('addmenu8', 0);">
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/links.php" class="small_link">{L_ADD_LINKS_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/content.php" class="small_link">{L_ADD_CONTENT_MENUS}</a></p>
								<p class="menus_block_add" style="margin:0px;margin-top:-1px;"><a href="{PATH_TO_ROOT}/admin/menus/feed.php" class="small_link">{L_ADD_FEED_MENUS}</a></p>
							</div>
						</div>
						<p class="menus_block_add" onclick="menu_display_block('addmenu8');" onmouseover="menu_hide_block('addmenu8', 1);" onmouseout="menu_hide_block('addmenu8', 0);">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" class="valign_middle" alt="" /> {L_ADD_MENU}
						</p>
					</div>
				</div>
				<div class="spacer"></div>
			</td>
		</tr>
	</table>
</div>

