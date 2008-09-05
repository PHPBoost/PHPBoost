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

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_MENUS_MANAGEMENT}</li>
				<li>
					<a href="admin_menus.php"><img src="../templates/{THEME}/images/admin/menus.png" alt="" /></a>
					<br />
					<a href="admin_menus.php" class="quick_link">{L_MENUS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_menus_add.php?type=1"><img src="../templates/{THEME}/images/admin/menus.png" alt="" /></a>
					<br />
					<a href="admin_menus_add.php?type=1" class="quick_link">{L_ADD_CONTENT_MENUS}</a>
				</li>
				<li>
					<a href="admin_menus_add.php?type=2"><img src="../templates/{THEME}/images/admin/menus.png" alt="" /></a>
					<br />
					<a href="admin_menus_add.php?type=2" class="quick_link">{L_ADD_LINKS_MENUS}</a>
				</li>
			</ul>
		</div>
			
		<div id="admin_contents">
			<form action="admin_menus.php" method="post">
				<table class="module_table" style="background:#FFFFFF;width:99%">
					<tr>
						<td colspan="{COLSPAN}" style="border:1px solid black;background:#EE713A">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_HEADER}</p>
							# START mod_header #
							<div style="margin:15px;width:auto" class="module_position">
								<div class="module_top_l"></div>
								<div class="module_top_r"></div>
								<div class="module_top">
									<strong><span id="m{mod_header.IDMENU}"></span></strong><h5 class="sub_title">{mod_header.NAME} {mod_header.EDIT} {mod_header.DEL}</h5>
								</div>
									
								<div class="module_contents">
									<p>
										<select name="{mod_header.IDMENU}activ" onchange="document.location = {mod_header.U_ONCHANGE_ACTIV}">
											<option value="1" {mod_header.ACTIV_ENABLED}>{L_ACTIV}</option>
											<option value="0" {mod_header.ACTIV_DISABLED}>{L_UNACTIV}</option>
										</select>
									</p>
									<div style="width:100px;height:30px;">
										<div style="float:left">
											{mod_header.UP}
											{mod_header.DOWN}
										</div>
										<div style="position:relative;float:right">
											<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-100px;float:left;display:none;" id="movemenu{mod_header.IDMENU}">
												<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{mod_header.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_header.IDMENU}', 0);">
													<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EE713A;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=header&amp;id={mod_header.IDMENU}">{L_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_header.IDMENU}">{L_SUB_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_header.IDMENU}">{L_LEFT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_header.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_header.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_header.IDMENU}">{L_RIGHT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#61B85C;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topfooter&amp;id={mod_header.IDMENU}">{L_TOP_FOOTER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=footer&amp;id={mod_header.IDMENU}">{L_FOOTER}</a></div>
												</div>
											</div>
										</div>
										<a href="javascript:menu_display_block('menu{mod_header.IDMENU}');" onmouseover="menu_hide_block('menu{mod_header.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_header.IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
									</div>
									<p>{mod_header.CONTENTS}</p>
									<br /><br />
								</div>
								<div class="module_bottom_l"></div>
								<div class="module_bottom_r"></div>
								<div class="module_bottom"></div>
							</div>
							# END mod_header #
						</td>
					</tr>
					<tr>
						<td colspan="{COLSPAN}" style="border:1px solid black;background:#CCFF99">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_SUB_HEADER}</p>
							# START mod_subheader #
							<div style="margin:15px;width:auto" class="module_position">
								<div class="module_top_l"></div>
								<div class="module_top_r"></div>
								<div class="module_top">
									<strong><span id="m{mod_subheader.IDMENU}"></span></strong><h5 class="sub_title">{mod_subheader.NAME} {mod_subheader.EDIT} {mod_subheader.DEL}</h5>
								</div>
									
								<div class="module_contents">
									<p>
										<select name="{mod_subheader.IDMENU}activ" onchange="document.location = {mod_subheader.U_ONCHANGE_ACTIV}">
											<option value="1" {mod_subheader.ACTIV_ENABLED}>{L_ACTIV}</option>
											<option value="0" {mod_subheader.ACTIV_DISABLED}>{L_UNACTIV}</option>
										</select>
									</p>
									<div style="width:100px;height:30px;">
										<div style="float:left">
											{mod_subheader.UP}
											{mod_subheader.DOWN}
										</div>
										<div style="position:relative;float:right">
											<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-100px;float:left;display:none;" id="movemenu{mod_subheader.IDMENU}">
												<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{mod_subheader.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_subheader.IDMENU}', 0);">
													<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EE713A;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=header&amp;id={mod_subheader.IDMENU}">{L_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_subheader.IDMENU}">{L_SUB_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_subheader.IDMENU}">{L_LEFT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_subheader.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_subheader.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_subheader.IDMENU}">{L_RIGHT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#61B85C;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topfooter&amp;id={mod_subheader.IDMENU}">{L_TOP_FOOTER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=footer&amp;id={mod_subheader.IDMENU}">{L_FOOTER}</a></div>
												</div>
											</div>
										</div>
										<a href="javascript:menu_display_block('menu{mod_subheader.IDMENU}');" onmouseover="menu_hide_block('menu{mod_subheader.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_subheader.IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
									</div>
									<p>{mod_subheader.CONTENTS}</p>
									<br /><br />
								</div>
								<div class="module_bottom_l"></div>
								<div class="module_bottom_r"></div>
								<div class="module_bottom"></div>
							</div>
							# END mod_subheader #
						</td>
					</tr>
					<tr>				
						# IF LEFT_COLUMN #
						<td style="width:18%;vertical-align:top;padding:4px;border:1px solid black;background:#9B8FFF">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_LEFT_MENU}</p>
							# START mod_left #
							<div class="module_mini_container">
								<div class="module_mini_top">
									<span id="m{mod_left.IDMENU}"></span><h5 class="sub_title">{mod_left.NAME} {mod_left.EDIT} {mod_left.DEL}</h5>
								</div>
								<div class="module_mini_contents">
									<p>
										<select name="{mod_left.IDMENU}activ" onchange="document.location = {mod_left.U_ONCHANGE_ACTIV}">								
											<option value="1" {mod_left.ACTIV_ENABLED}>{L_ACTIV}</option>
											<option value="0" {mod_left.ACTIV_DISABLED}>{L_UNACTIV}</option>					
										</select>
									</p>						
									<div style="width:100px;height:30px;margin:auto;">
										<div style="float:left">
											{mod_left.UP}
											{mod_left.DOWN}
										</div>
										<div style="position:relative;float:right">
											<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-70px;float:left;display:none;" id="movemenu{mod_left.IDMENU}">
												<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{mod_left.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_left.IDMENU}', 0);">
													<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EE713A;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=header&amp;id={mod_left.IDMENU}">{L_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_left.IDMENU}">{L_SUB_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_left.IDMENU}">{L_LEFT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_left.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_left.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_left.IDMENU}">{L_RIGHT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#61B85C;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topfooter&amp;id={mod_left.IDMENU}">{L_TOP_FOOTER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=footer&amp;id={mod_left.IDMENU}">{L_FOOTER}</a></div>
												</div>
											</div>
											<a href="javascript:menu_display_block('menu{mod_left.IDMENU}');" onmouseover="menu_hide_block('menu{mod_left.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_left.IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
										</div>
									</div>
									
									<p>{mod_left.CONTENTS}</p>&nbsp;
								</div>
								<div class="module_mini_bottom">
								</div>
							</div>
							# END mod_left #				
						</td>
						# ENDIF #
						
						<td style="vertical-align:top;border:1px solid black;background:#E5E5E5">
							<div id="links">
								&nbsp;&nbsp;<a class="small_link" href="" title="{L_INDEX}">{L_INDEX}</a>
							</div>
							<div id="top_contents" style="border:1px solid black;background:#FFE25F">
								<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_TOP_CENTRAL_MENU}</p>
								# START mod_topcentral #
								<div style="margin:15px;width:auto" class="module_position">					
									<div class="module_top_l"></div>		
									<div class="module_top_r"></div>
									<div class="module_top">
										<strong><span id="m{mod_topcentral.IDMENU}"></span><h5 class="sub_title">{mod_topcentral.NAME} {mod_topcentral.EDIT} {mod_topcentral.DEL}</h5></strong>
									</div>
									<div class="module_contents">
										<p>
											<select name="{mod_topcentral.IDMENU}activ" onchange="document.location = {mod_topcentral.U_ONCHANGE_ACTIV}">								
												<option value="1" {mod_topcentral.ACTIV_ENABLED}>{L_ACTIV}</option>
												<option value="0" {mod_topcentral.ACTIV_DISABLED}>{L_UNACTIV}</option>					
											</select>
										</p>
										<div style="width:100px;height:30px;">
											<div style="float:left">
												{mod_topcentral.UP}
												{mod_topcentral.DOWN}
											</div>
											<div style="position:relative;float:right"><div style="position:absolute;z-index:100;margin-top:155px;margin-left:-70px;float:left;display:none;" id="movemenu{mod_topcentral.IDMENU}">
												<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{mod_topcentral.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_topcentral.IDMENU}', 0);">
													<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EE713A;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=header&amp;id={mod_topcentral.IDMENU}">{L_HEADER}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_topcentral.IDMENU}">{L_SUB_HEADER}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_topcentral.IDMENU}">{L_LEFT_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_topcentral.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_topcentral.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_topcentral.IDMENU}">{L_RIGHT_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#61B85C;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topfooter&amp;id={mod_right.IDMENU}">{L_TOP_FOOTER}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=footer&amp;id={mod_topcentral.IDMENU}">{L_FOOTER}</a></div>
												</div>
											</div>
											<a href="javascript:menu_display_block('menu{mod_topcentral.IDMENU}');" onmouseover="menu_hide_block('menu{mod_topcentral.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_topcentral.IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
											</div>
										</div>
										<p>{mod_topcentral.CONTENTS}</p>&nbsp;
									</div>
									<div class="module_bottom_l"></div>
									<div class="module_bottom_r"></div>
									<div class="module_bottom"></div>
								</div>
								# END mod_topcentral #
								&nbsp;
							</div>
							<div style="width:96%;min-height:250px;margin:auto;border:1px solid black;">
								<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_MENUS_AVAILABLE}</p>
								# START mod_main #									
								<div class="module_mini_container" style="margin:5px;margin-top:0px;float:left">
									<div class="module_mini_top">
										<span id="m{mod_main.IDMENU}"></span><h5 class="sub_title">{mod_main.NAME} {mod_main.DEL} {mod_main.EDIT}</h5>
									</div>
									<div class="module_mini_contents">
										<p>
											<select name="{mod_main.IDMENU}activ" onchange="document.location = {mod_main.U_ONCHANGE_ACTIV}">								
												<option value="1">{L_ACTIV}</option>
												<option value="0" selected="selected">{L_UNACTIV}</option>					
											</select>
										</p>
										<div style="width:100px;height:30px;margin:auto;">
											<div style="float:left">
											</div>
											<div style="position:relative;float:right">
												<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-130px;float:left;display:none;" id="movemenu{mod_main.IDMENU}">
													<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{mod_main.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_main.IDMENU}', 0);">
														<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EE713A;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=header&amp;id={mod_main.IDMENU}">{L_HEADER}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_main.IDMENU}">{L_SUB_HEADER}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_main.IDMENU}">{L_LEFT_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_main.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_main.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_main.IDMENU}">{L_RIGHT_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#61B85C;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topfooter&amp;id={mod_main.IDMENU}">{L_TOP_FOOTER}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=footer&amp;id={mod_main.IDMENU}">{L_FOOTER}</a></div>
													</div>
												</div>
											</div>
											<a href="javascript:menu_display_block('menu{mod_main.IDMENU}');" onmouseover="menu_hide_block('menu{mod_main.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_main.IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
										</div>
										
										<p>{mod_main.CONTENTS}</p>&nbsp;
									</div>
									<div class="module_mini_bottom">
									</div>
								</div>
								# END mod_main #
								
								<div class="spacer">&nbsp;</div>
								
								<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_UNINSTALLED_MENUS}</p>								
								# START mod_main_uninstalled #
								<div class="module_mini_container" style="margin:5px;margin-top:0px;float:left">
									<div class="module_mini_top">
										<h5 class="sub_title">{mod_main_uninstalled.NAME}</h5>
									</div>
									<div class="module_mini_contents">
										<a href="{mod_main_uninstalled.U_INSTALL}"><img src="../templates/{THEME}/images/admin/files_mini.png" class="valign_middle" alt="" /><a/>
										<br />
										<a href="{mod_main_uninstalled.U_INSTALL}">{L_INSTALL}<a/>
									</div>
									<div class="module_mini_bottom">
									</div>
								</div>
								# END mod_main_uninstalled #
								<div class="spacer">&nbsp;</div>
							</div>							
							<div id="bottom_contents" style="border:1px solid black;background:#FF5F5F;clear:both">
								<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_BOTTOM_CENTRAL_MENU}</p>
								# START mod_bottomcentral #
								<div style="margin:15px;width:auto" class="module_position">					
									<div class="module_top_l"></div>		
									<div class="module_top_r"></div>
									<div class="module_top">
										<strong><span id="m{mod_bottomcentral.IDMENU}"></span><h5 class="sub_title">{mod_bottomcentral.NAME} {mod_bottomcentral.EDIT}{mod_bottomcentral.DEL}</h5></strong>
									</div>
									<div class="module_contents">
										<p>
											<select name="{mod_bottomcentral.IDMENU}activ" onchange="document.location = {mod_bottomcentral.U_ONCHANGE_ACTIV}">								
												<option value="1" {mod_bottomcentral.ACTIV_ENABLED}>{L_ACTIV}</option>
												<option value="0" {mod_bottomcentral.ACTIV_DISABLED}>{L_UNACTIV}</option>					
											</select>
										</p>
										<div style="width:100px;height:30px;">
											<div style="float:left">
												{mod_bottomcentral.UP}
												{mod_bottomcentral.DOWN}
											</div>
											<div style="position:relative;float:right">
												<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-70px;float:left;display:none;" id="movemenu{mod_bottomcentral.IDMENU}">
													<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{mod_bottomcentral.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_bottomcentral.IDMENU}', 0);">
														<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EE713A;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=header&amp;id={mod_bottomcentral.IDMENU}">{L_HEADER}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_bottomcentral.IDMENU}">{L_SUB_HEADER}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_bottomcentral.IDMENU}">{L_LEFT_MENU}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_bottomcentral.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_bottomcentral.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_bottomcentral.IDMENU}">{L_RIGHT_MENU}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#61B85C;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topfooter&amp;id={mod_bottomcentral.IDMENU}">{L_TOP_FOOTER}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=footer&amp;id={mod_bottomcentral.IDMENU}">{L_FOOTER}</a></div>
													</div>
												</div>
												<a href="javascript:menu_display_block('menu{mod_bottomcentral.IDMENU}');" onmouseover="menu_hide_block('menu{mod_bottomcentral.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_bottomcentral.IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
											</div>
										</div>
										
										<p>{mod_bottomcentral.CONTENTS}</p>&nbsp;
									</div>
									<div class="module_bottom_l"></div>		
									<div class="module_bottom_r"></div>
									<div class="module_bottom"></div>
								</div>
								# END mod_bottomcentral #
								&nbsp;
							</div>							
						</td>				
						
						# IF RIGHT_COLUMN #
						<td style="width:18%;vertical-align:top;padding:4px;border:1px solid black;background:#EA6FFF">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_RIGHT_MENU}</p>
							# START mod_right #
							<div class="module_mini_container">
								<div class="module_mini_top">
									<span id="m{mod_right.IDMENU}"></span><h5 class="sub_title">{mod_right.NAME} {mod_right.EDIT} {mod_right.DEL}</h5>
								</div>
								<div class="module_mini_contents">
									<p>
										<select name="{mod_right.IDMENU}activ" onchange="document.location = {mod_right.U_ONCHANGE_ACTIV}">								
											<option value="1" {mod_right.ACTIV_ENABLED}>{L_ACTIV}</option>
											<option value="0" {mod_right.ACTIV_DISABLED}>{L_UNACTIV}</option>					
										</select>
									</p>
									<div style="width:100px;height:30px;margin:auto;">
										<div style="float:left">
											{mod_right.UP}
											{mod_right.DOWN}
										</div>
										<div style="position:relative;float:right">
											<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-110px;float:left;display:none;" id="movemenu{mod_right.IDMENU}">
												<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{mod_right.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_right.IDMENU}', 0);">
													<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EE713A;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=header&amp;id={mod_right.IDMENU}">{L_HEADER}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_right.IDMENU}">{L_SUB_HEADER}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_right.IDMENU}">{L_LEFT_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_right.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_right.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_right.IDMENU}">{L_RIGHT_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#61B85C;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topfooter&amp;id={mod_right.IDMENU}">{L_TOP_FOOTER}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=footer&amp;id={mod_right.IDMENU}">{L_FOOTER}</a></div>
												</div>
											</div>
											<a href="javascript:menu_display_block('menu{mod_right.IDMENU}');" onmouseover="menu_hide_block('menu{mod_right.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_right.IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
										</div>
									</div>
									
									<p>{mod_right.CONTENTS}</p>&nbsp;					
								</div>
								<div class="module_mini_bottom">
								</div>
							</div>
							# END mod_right #				
						</td>
						# ENDIF #
					</tr>
					<tr>
						<td colspan="{COLSPAN}" style="border:1px solid black;background:#61B85C">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_TOP_FOOTER}</p>
							# START mod_topfooter #
							<div style="margin:15px;width:auto" class="module_position">
								<div class="module_top_l"></div>
								<div class="module_top_r"></div>
								<div class="module_top">
									<strong><span id="m{mod_topfooter.IDMENU}"></span></strong><h5 class="sub_title">{mod_topfooter.NAME} {mod_topfooter.EDIT} {mod_topfooter.DEL}</h5>
								</div>
									
								<div class="module_contents">
									<p>
										<select name="{mod_topfooter.IDMENU}activ" onchange="document.location = {mod_topfooter.U_ONCHANGE_ACTIV}">
											<option value="1" {mod_topfooter.ACTIV_ENABLED}>{L_ACTIV}</option>
											<option value="0" {mod_topfooter.ACTIV_DISABLED}>{L_UNACTIV}</option>
										</select>
									</p>
									<div style="width:100px;height:30px;">
										<div style="float:left">
											{mod_topfooter.UP}
											{mod_topfooter.DOWN}
										</div>
										<div style="position:relative;float:right">
											<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-100px;float:left;display:none;" id="movemenu{mod_topfooter.IDMENU}">
												<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{mod_topfooter.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_topfooter.IDMENU}', 0);">
													<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EE713A;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=header&amp;id={mod_topfooter.IDMENU}">{L_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_topfooter.IDMENU}">{L_SUB_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_topfooter.IDMENU}">{L_LEFT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_topfooter.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_topfooter.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_topfooter.IDMENU}">{L_RIGHT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#61B85C;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topfooter&amp;id={mod_topfooter.IDMENU}">{L_TOP_FOOTER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=footer&amp;id={mod_topfooter.IDMENU}">{L_FOOTER}</a></div>
												</div>
											</div>
										</div>
										<a href="javascript:menu_display_block('menu{mod_topfooter.IDMENU}');" onmouseover="menu_hide_block('menu{mod_topfooter.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_topfooter.IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
									</div>
									<p>{mod_topfooter.CONTENTS}</p>
									<br /><br />
								</div>
								<div class="module_bottom_l"></div>
								<div class="module_bottom_r"></div>
								<div class="module_bottom"></div>
							</div>
							# END mod_topfooter #
						</td>
					</tr>
					<tr>
						<td colspan="{COLSPAN}" style="border:1px solid black;background:#A8D1CB">
							<p class="text_center text_strong" style="padding:6px;padding-bottom:0px;">{L_FOOTER}</p>
							# START mod_footer #
							<div style="margin:15px;width:auto" class="module_position">
								<div class="module_top_l"></div>
								<div class="module_top_r"></div>
								<div class="module_top">
									<strong><span id="m{mod_footer.IDMENU}"></span></strong><h5 class="sub_title">{mod_footer.NAME} {mod_footer.EDIT} {mod_footer.DEL}</h5>
								</div>
									
								<div class="module_contents">
									<p>
										<select name="{mod_footer.IDMENU}activ" onchange="document.location = {mod_footer.U_ONCHANGE_ACTIV}">
											<option value="1" {mod_footer.ACTIV_ENABLED}>{L_ACTIV}</option>
											<option value="0" {mod_footer.ACTIV_DISABLED}>{L_UNACTIV}</option>
										</select>
									</p>
									<div style="width:100px;height:30px;">
										<div style="float:left">
											{mod_footer.UP}
											{mod_footer.DOWN}
										</div>
										<div style="position:relative;float:right">
											<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-100px;float:left;display:none;" id="movemenu{mod_footer.IDMENU}">
												<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{mod_footer.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_footer.IDMENU}', 0);">
													<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EE713A;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=header&amp;id={mod_footer.IDMENU}">{L_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_footer.IDMENU}">{L_SUB_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_footer.IDMENU}">{L_LEFT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_footer.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_footer.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_footer.IDMENU}">{L_RIGHT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#61B85C;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topfooter&amp;id={mod_footer.IDMENU}">{L_TOP_FOOTER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=footer&amp;id={mod_footer.IDMENU}">{L_FOOTER}</a></div>
												</div>
											</div>
										</div>
										<a href="javascript:menu_display_block('menu{mod_footer.IDMENU}');" onmouseover="menu_hide_block('menu{mod_footer.IDMENU}', 1);" onmouseout="menu_hide_block('menu{mod_footer.IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
									</div>
									<p>{mod_footer.CONTENTS}</p>
									<br /><br />
								</div>
								<div class="module_bottom_l"></div>
								<div class="module_bottom_r"></div>
								<div class="module_bottom"></div>
							</div>
							# END mod_footer #
						</td>
					</tr>
				</table>
				
				<fieldset class="fieldset_submit" style="padding-top:20px" id="submit_menus">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
				<script type="text/javascript">
				<!--
				document.getElementById('submit_menus').style.display = 'none';
				-->
				</script>
			</form>
			
			<table class="module_table">
				<tr> 
					<th colspan="6">
						{L_MENUS_MANAGEMENT}
					</th>
				</tr>
				<tr> 
					<td class="row2">
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#EE713A;border:1px solid black"></div> <div style="clear:right">{L_HEADER}</div>
						<br />
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right">{L_SUB_HEADER}</div>
						<br />
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right">{L_LEFT_MENU}</div>
						<br />
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right">{L_TOP_CENTRAL_MENU}</div>
					</td>
					<td class="row2" style="vertical-align:top">
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right">{L_BOTTOM_CENTRAL_MENU}</div>
						<br />
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right">{L_RIGHT_MENU}</div>
						<br />
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#61B85C;border:1px solid black"></div> <div style="clear:right">{L_TOP_FOOTER}</div>
						<br />
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right">{L_FOOTER}</div>
					</td>
				</tr>
			</table>
		</div>
		
