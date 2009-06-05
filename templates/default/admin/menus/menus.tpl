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

<div id="admin_contents">
	<table class="module_table" style="background:#FFFFFF;width:100%">
		<tr>
			<td colspan="{COLSPAN}" style="border:1px solid black;background:#cee6cd">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_HEADER}</p>
				# START mod_header #
				{mod_header.MENU}
				# END mod_header #
			</td>
		</tr>
		<tr>
			<td colspan="{COLSPAN}" style="border:1px solid black;background:#CCFF99">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_SUB_HEADER}</p>
				# START mod_subheader #
				{mod_subheader.MENU}
				# END mod_subheader #
			</td>
		</tr>
		<tr>				
			# IF LEFT_COLUMN #
			<td style="width:18%;vertical-align:top;padding:4px;border:1px solid black;background:#afafaf">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_LEFT_MENU}</p>
				<div class="module_mini_container" style="background:none;padding:0;margin:auto;float:none;border:none;">
				# START mod_left #
				{mod_left.MENU}
				# END mod_left #
				
                # IF NOT RIGHT_COLUMN #
				<hr /><br />
				# START mod_right #
				{mod_right.MENU}
				# END mod_right #
				# ENDIF #   			
				</div>
			</td>
			# ENDIF #
			
			<td style="vertical-align:top;border:1px solid black;background:#E5E5E5">
				<table class="module_table" style="width:100%;margin-top:0;">
					<tr>
						<td style="border:1px solid black;padding:4px;">
							&nbsp;&nbsp;<a class="small_link" href="{START_PAGE}" title="{L_INDEX}">{L_INDEX}</a> <img src="{PATH_TO_ROOT}/templates/{THEME}/images/breadcrumb.png" alt="" class="valign_middle" />
						</td>
					</tr>
					<tr>
						<td style="border:1px solid black;background:#FFE25F">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_TOP_CENTRAL_MENU}</p>
							# START mod_topcentral #
							{mod_topcentral.MENU}
							# END mod_topcentral #
							&nbsp;
						</td>
					</tr>
					<tr>
						<td style="border:1px solid black;">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_MENUS_AVAILABLE}</p>
							# START mod_main #
							{mod_main.MENU}
							# END mod_main #
							
							<div class="spacer">&nbsp;</div>
							
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_UNINSTALLED_MENUS}</p>								
							# START mod_main_uninstalled #
							<div class="module_mini_container" style="margin:5px;margin-top:0px;float:left">
								<div class="module_mini_top">
									<h5 class="sub_title">{mod_main_uninstalled.NAME}</h5>
								</div>
								<div class="module_mini_contents">
									<a href="{mod_main_uninstalled.U_INSTALL}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/files_mini.png" class="valign_middle" alt="" /></a>
									<br />
									<a href="{mod_main_uninstalled.U_INSTALL}">{L_INSTALL}</a>
								</div>
								<div class="module_mini_bottom">
								</div>
							</div>
							# END mod_main_uninstalled #
						</td>
					</tr>
					<tr>
						<td style="border:1px solid black;background:#FF5F5F">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_BOTTOM_CENTRAL_MENU}</p>
							# START mod_bottomcentral #
							{mod_bottomcentral.MENU}
							# END mod_bottomcentral #
							&nbsp;
						</td>
					</tr>
				</table>							
			</td>
			
			# IF RIGHT_COLUMN #
			<td style="width:18%;vertical-align:top;padding:4px;border:1px solid black;background:#bdaeca">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_RIGHT_MENU}</p>
				# START mod_right #
				{mod_right.MENU}
				# END mod_right #
				
                # IF NOT LEFT_COLUMN #
				<hr /><br />
				# START mod_left #
				{mod_left.MENU}
				# END mod_left #
				# ENDIF #			
			</td>
			# ENDIF #
		</tr>
		<tr>
			<td colspan="{COLSPAN}" style="border:1px solid black;background:#90ab8e">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_TOP_FOOTER}</p>
				# START mod_topfooter #
				{mod_topfooter.MENU}
				# END mod_topfooter #
			</td>
		</tr>
		<tr>
			<td colspan="{COLSPAN}" style="border:1px solid black;background:#A8D1CB">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_FOOTER}</p>
				# START mod_footer #
				{mod_footer.MENU}
				# END mod_footer #
			</td>
		</tr>
	</table>
	
	<table class="module_table">
		<tr> 
			<th colspan="6">{L_MENUS_MANAGEMENT}</th>
		</tr>
		<tr> 
			<td class="row2">
				<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#cee6cd;border:1px solid black"></div> <div style="clear:right">{L_HEADER}</div>
				<br />
				<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right">{L_SUB_HEADER}</div>
				<br />
				<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#afafaf;border:1px solid black"></div> <div style="clear:right">{L_LEFT_MENU}</div>
				<br />
				<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right">{L_TOP_CENTRAL_MENU}</div>
			</td>
			<td class="row2" style="vertical-align:top">
				<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right">{L_BOTTOM_CENTRAL_MENU}</div>
				<br />
				<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#bdaeca;border:1px solid black"></div> <div style="clear:right">{L_RIGHT_MENU}</div>
				<br />
				<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#90ab8e;border:1px solid black"></div> <div style="clear:right">{L_TOP_FOOTER}</div>
				<br />
				<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right">{L_FOOTER}</div>
			</td>
		</tr>
	</table>

</div>

