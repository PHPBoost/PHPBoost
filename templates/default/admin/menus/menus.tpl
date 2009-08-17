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
			</td>
		</tr>
		<tr>
			<td colspan="{COLSPAN}" style="padding:4px;border:1px solid black;background:#f1ffe3">
				<p class="menu_block_libelle">{L_SUB_HEADER}</p>
				# START mod_subheader #
				{mod_subheader.MENU}
				# END mod_subheader #
			</td>
		</tr>
		<tr>				
			# IF LEFT_COLUMN #
			<td style="width:185px;vertical-align:top;padding:4px;border:1px solid black;background:#dfdfdf">
				<p class="menu_block_libelle">{L_LEFT_MENU}</p>
				# START mod_left #
				{mod_left.MENU}
				# END mod_left #
				
                # IF NOT RIGHT_COLUMN #
				<hr /><br />
				# START mod_right #
				{mod_right.MENU}
				# END mod_right #
				# ENDIF #   			
			</td>
			# ENDIF #
			
			<td style="vertical-align:top;border:1px solid black;background:#f4f4f4;">
				<div style="padding:4px;border:1px solid black;background:#ffe1e1">
					<p class="menu_block_libelle">{L_TOP_CENTRAL_MENU}</p>
					# START mod_topcentral #
					{mod_topcentral.MENU}
					# END mod_topcentral #
					<div class="spacer">&nbsp;</div>
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
					<div class="spacer">&nbsp;</div>
				</div>						
			</td>
			
			# IF RIGHT_COLUMN #
			<td style="width:230px;vertical-align:top;padding:4px;border:1px solid black;background:#dfdfdf">
				<p class="menu_block_libelle">{L_RIGHT_MENU}</p>
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
			<td colspan="{COLSPAN}" style="padding:4px;border:1px solid black;background:#e8ffe5">
				<p class="menu_block_libelle">{L_TOP_FOOTER}</p>
				# START mod_topfooter #
				{mod_topfooter.MENU}
				# END mod_topfooter #
			</td>
		</tr>
		<tr>
			<td colspan="{COLSPAN}" style="padding:4px;border:1px solid black;background:#e6fffb">
				<p class="menu_block_libelle">{L_FOOTER}</p>
				# START mod_footer #
				{mod_footer.MENU}
				# END mod_footer #
			</td>
		</tr>
	</table>
</div>

