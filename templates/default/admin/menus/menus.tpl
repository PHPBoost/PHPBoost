<div id="admin_contents">
	<table style="background:#FFFFFF; width:99%;">
		<tr>
			<td colspan="{COLSPAN}" style="padding:2px;border:1px solid black;background:#EE713A">
				<div style="width:100%;height:100%" id="droppable_{I_HEADER}">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_HEADER}</p>
				# START mod_header #{mod_header.MENU}# END mod_header #
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="{COLSPAN}" style="padding:2px;border:1px solid black;background:#CCFF99">
				<div style="width:100%;height:100%" id="droppable_{I_SUBHEADER}">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_SUB_HEADER}</p>
				# START mod_subheader #{mod_subheader.MENU}# END mod_subheader #
				</div>
			</td>
		</tr>
		<tr>				
			# IF LEFT_COLUMN #
			<td style="padding:2px;width:20%;vertical-align:top;border:1px solid black;background:#9B8FFF">
			<div style="width:100%;height:100%" id="droppable_{I_LEFT}">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_LEFT_MENU}</p>
				<div class="module_mini_container" style="background:none;padding:0;margin:auto;float:none;border:none;">
				# START mod_left #{mod_left.MENU}# END mod_left #				
				</div>
			</div>
			</td>
			# ENDIF #
			
			<td style="vertical-align:top;border:1px solid black;background:#E5E5E5">
				<table style="width:100%;margin-top:0;">
					<tr>
						<td style="border:1px solid black;padding:4px;">
							&nbsp;&nbsp;<a class="small_link" href="{START_PAGE}" title="{L_INDEX}">{L_INDEX}</a> <img src="{PATH_TO_ROOT}/templates/{THEME}/images/breadcrumb.png" alt="" class="valign_middle" />
						</td>
					</tr>
					<tr>
						<td style="padding:2px;border:1px solid black;background:#FFE25F">
						<div style="width:100%-10px;height:100%" id="droppable_{I_TOPCENTRAL}">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_TOP_CENTRAL_MENU}</p>
							# START mod_topcentral #{mod_topcentral.MENU}# END mod_topcentral #
							&nbsp;
						</div>
						</td>
					</tr>
					<tr>
						<td style="padding:2px;border:1px solid black;">
						<div style="width:100%-10px;height:100%" id="droppable_available">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_MENUS_AVAILABLE}</p>
							# START mod_main #{mod_main.MENU}# END mod_main #
							
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
						</div>
						</td>
					</tr>
					<tr>
						<td style="padding:2px;border:1px solid black;background:#FF5F5F">
						<div style="width:100%-10px;height:100%" id="droppable_{I_BOTTOMCENTRAL}">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_BOTTOM_CENTRAL_MENU}</p>
							# START mod_bottomcentral #{mod_bottomcentral.MENU}# END mod_bottomcentral #
							&nbsp;
						</div>
						</td>
					</tr>
				</table>
			</td>
			
			# IF RIGHT_COLUMN #
			<td style="width:20%;vertical-align:top;padding:2px;border:1px solid black;background:#EA6FFF">
			<div style="width:100%;height:100%" id="droppable_{I_RIGHT}">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_RIGHT_MENU}</p>
				# START mod_right #{mod_right.MENU}# END mod_right #				
			</div>
			</td>
			# ENDIF #
		</tr>
		<tr>
			<td colspan="{COLSPAN}" style="padding:2px;border:1px solid black;background:#61B85C">
			<div style="width:100%;height:100%" id="droppable_{I_TOPFOOTER}">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_TOP_FOOTER}</p>
				# START mod_topfooter #{mod_topfooter.MENU}# END mod_topfooter #
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="{COLSPAN}" style="padding:2px;border:1px solid black;background:#A8D1CB">
			<div style="width:100%;height:100%" id="droppable_{I_FOOTER}">
				<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_FOOTER}</p>
				# START mod_footer #{mod_footer.MENU}# END mod_footer #
			</div>
			</td>
		</tr>
	</table>

<script type="text/javascript">
  // <![CDATA[
var drop_array = ['droppable_available','droppable_{I_HEADER}','droppable_{I_SUBHEADER}',
	'droppable_{I_LEFT}', 'droppable_{I_TOPCENTRAL}','droppable_{I_BOTTOMCENTRAL}','droppable_{I_RIGHT}',
	'droppable_{I_TOPFOOTER}','droppable_{I_FOOTER}'];

for(i=0; i<drop_array.length; i++) {
	Droppables.add(drop_array[i], { 
		accept: 'draggable',
		hoverclass: 'droppable_hover',
		onDrop: function(drag_elt, drop_elt) {
			var in1 = drop_elt.id;
			var p1 = in1.indexOf("_",0);
			var out1 = in1.substring(p1+1, in1.length); 
			var in2 = drag_elt.id;
			var p2 = in2.indexOf("_",0);
			var out2 = in2.substring(p1+1, in1.length); 
			location.href = "../menus/menus.php?move=" + out1 + "&id=" + out2 + "&token={U_TOKEN}";
			}
	});
}
  // ]]>
</script>

</div>

