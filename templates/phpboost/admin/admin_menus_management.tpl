		<script type="text/javascript">
		<!--
		function Confirm_menu() {
			return confirm("{L_CONFIRM_DEL_MENU}");
		}
		var delay = 2000; //D�lai apr�s lequel le bloc est automatiquement masqu�, apr�s le d�part de la souris.
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
					<a href="admin_menus_add.php"><img src="../templates/{THEME}/images/admin/menus.png" alt="" /></a>
					<br />
					<a href="admin_menus_add.php" class="quick_link">{L_ADD_MENUS}</a>
				</li>
			</ul>
		</div>
			
		<div id="admin_contents">
			<form action="admin_menus.php" method="post">
				<table class="module_table" style="background:#FFFFFF;width:99%">
					<tr>
						<td colspan="{COLSPAN}" style="border:1px solid black;background:#BFFF8F">
							# START mod_subheader #
							<div style="margin:15px;" class="module_mini_container_horizontal">
								<div class="module_mini_top_horizontal">
									<span id="m{mod_subheader.IDMENU}"></span><h5 class="sub_title">{mod_subheader.NAME} {mod_subheader.EDIT}{mod_subheader.DEL}</h5>
								</div>
								<div class="module_mini_table_horizontal">
									<p>
										<select name="{mod_subheader.IDMENU}activ" onchange="document.location = {mod_subheader.U_ONCHANGE_ACTIV}">								
											<option value="1" {mod_subheader.ACTIV_ENABLED}>{L_ACTIV}</option>
											<option value="0" {mod_subheader.ACTIV_DISABLED}>{L_UNACTIV}</option>					
										</select>
									</p>
									<p>							
										<select name="{mod_subheader.IDMENU}secure" onchange="document.location = {mod_subheader.U_ONCHANGE_SECURE}">								
											{mod_subheader.RANK}
										</select>							
									</p>						
									<div style="width:100px;height:30px;margin:auto;">
										<div style="float:left">
											<a href="admin_menus.php?top=1&amp;id={mod_subheader.IDMENU}"><img src="../templates/{THEME}/images/admin/up.png" alt="" /></a>
											<a href="admin_menus.php?bot=1&amp;id={mod_subheader.IDMENU}"><img src="../templates/{THEME}/images/admin/down.png" alt="" /></a>
										</div>
										<div style="position:relative;float:right">
											<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-100px;float:left;display:none;" id="move{mod_subheader.IDMENU}">
												<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block({mod_subheader.IDMENU}, 1);" onmouseout="menu_hide_block({mod_subheader.IDMENU}, 0);">
													<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#BFFF8F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_subheader.IDMENU}">{L_SUB_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_subheader.IDMENU}">{L_LEFT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_subheader.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_subheader.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_subheader.IDMENU}">{L_RIGHT_MENU}</a></div>
												</div>
											</div>
										</div>
										<a href="javascript:menu_display_block({mod_subheader.IDMENU});" onmouseover="menu_hide_block({mod_subheader.IDMENU}, 1);" onmouseout="menu_hide_block({mod_subheader.IDMENU}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
									</div>
									
									<p>{mod_subheader.CONTENTS}</p>
								</div>
								<div class="module_mini_bottom_horizontal">
								</div>
							</div>
							# END mod_subheader #	
						</td>
					</tr>
					<tr>				
						# IF LEFT_COLUMN #
						<td style="width:18%;vertical-align:top;padding:4px;border:1px solid black;background:#9B8FFF">
							# START mod_left #
							<div class="module_mini_container">
								<div class="module_mini_top">
									<span id="m{mod_left.IDMENU}"></span><h5 class="sub_title">{mod_left.NAME} {mod_left.EDIT} {mod_left.DEL}</h5>
								</div>
								<div class="module_mini_table">
									<p>
										<select name="{mod_left.IDMENU}activ" onchange="document.location = {mod_left.U_ONCHANGE_ACTIV}">								
											<option value="1" {mod_left.ACTIV_ENABLED}>{L_ACTIV}</option>
											<option value="0" {mod_left.ACTIV_DISABLED}>{L_UNACTIV}</option>					
										</select>
									</p>						
									<p>
										<select name="{mod_left.IDMENU}secure" onchange="document.location = {mod_left.U_ONCHANGE_SECURE}">								
											{mod_left.RANK}
										</select>							
									</p>								
									<div style="width:100px;height:30px;margin:auto;">
										<div style="float:left">
											<a href="admin_menus.php?top=1&amp;id={mod_left.IDMENU}"><img src="../templates/{THEME}/images/admin/up.png" alt="" /></a>
											<a href="admin_menus.php?bot=1&amp;id={mod_left.IDMENU}"><img src="../templates/{THEME}/images/admin/down.png" alt="" /></a>
										</div>
										<div style="position:relative;float:right">
											<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-70px;float:left;display:none;" id="move{mod_left.IDMENU}">
												<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block({mod_left.IDMENU}, 1);" onmouseout="menu_hide_block({mod_left.IDMENU}, 0);">
													<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#BFFF8F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_left.IDMENU}">{L_SUB_HEADER}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_left.IDMENU}">{L_LEFT_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_left.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_left.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
													<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_left.IDMENU}">{L_RIGHT_MENU}</a></div>
												</div>
											</div>
											<a href="javascript:menu_display_block({mod_left.IDMENU});" onmouseover="menu_hide_block({mod_left.IDMENU}, 1);" onmouseout="menu_hide_block({mod_left.IDMENU}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
										</div>
									</div>
									
									<p>{mod_left.CONTENTS}</p>
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
								# START mod_topcentral #
								<div style="margin:15px;" class="module_mini_container_horizontal">
									<div class="module_mini_top_horizontal">
											<span id="m{mod_topcentral.IDMENU}"></span><h5 class="sub_title">{mod_topcentral.NAME} {mod_topcentral.EDIT}{mod_topcentral.DEL}</h5>
										</div>
										<div class="module_mini_table_horizontal">
											<p>
												<select name="{mod_topcentral.IDMENU}activ" onchange="document.location = {mod_topcentral.U_ONCHANGE_ACTIV}">								
													<option value="1" {mod_topcentral.ACTIV_ENABLED}>{L_ACTIV}</option>
													<option value="0" {mod_topcentral.ACTIV_DISABLED}>{L_UNACTIV}</option>					
												</select>
											</p>
											<p>							
												<select name="{mod_topcentral.IDMENU}secure" onchange="document.location = {mod_topcentral.U_ONCHANGE_SECURE}">								
													{mod_topcentral.RANK}
												</select>							
											</p>						
											<div style="width:100px;height:30px;margin:auto;">
												<div style="float:left">
													<a href="admin_menus.php?top=1&amp;id={mod_topcentral.IDMENU}"><img src="../templates/{THEME}/images/admin/up.png" alt="" /></a>
													<a href="admin_menus.php?bot=1&amp;id={mod_topcentral.IDMENU}"><img src="../templates/{THEME}/images/admin/down.png" alt="" /></a>
												</div>
												<div style="position:relative;float:right"><div style="position:absolute;z-index:100;margin-top:155px;margin-left:-70px;float:left;display:none;" id="move{mod_topcentral.IDMENU}">
													<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block({mod_topcentral.IDMENU}, 1);" onmouseout="menu_hide_block({mod_topcentral.IDMENU}, 0);">
														<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#BFFF8F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_topcentral.IDMENU}">{L_SUB_HEADER}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_topcentral.IDMENU}">{L_LEFT_MENU}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_topcentral.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_topcentral.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_topcentral.IDMENU}">{L_RIGHT_MENU}</a></div>
													</div>
												</div>
												<a href="javascript:menu_display_block({mod_topcentral.IDMENU});" onmouseover="menu_hide_block({mod_topcentral.IDMENU}, 1);" onmouseout="menu_hide_block({mod_topcentral.IDMENU}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
												</div>
											</div>
											<p>{mod_topcentral.CONTENTS}</p>
											<br />
										</div>
										<div class="module_mini_bottom_horizontal">
										</div>
									</div>
								</div>
								# END mod_topcentral #
							</div>
							<div style="width:96%;min-height:250px;margin:auto;border:1px solid black;">
								<p style="text-align:center;margin-top:5px;" class="text_strong">{L_MENUS_AVAILABLE}</p>
								# START mod_main #									
								<div class="module_mini_container" style="margin:5px;margin-top:0px;float:left">
									<div class="module_mini_top">
										<span id="m{mod_main.IDMENU}"></span><h5 class="sub_title">{mod_main.NAME} {mod_main.DEL} {mod_main.EDIT}</h5>
									</div>
									<div class="module_mini_table">
										<p>
											<select name="{mod_main.IDMENU}activ" onchange="document.location = {mod_main.U_ONCHANGE_ACTIV}">								
												<option value="1" {mod_main.ACTIV_ENABLED}>{L_ACTIV}</option>
												<option value="0" {mod_main.ACTIV_DISABLED}>{L_UNACTIV}</option>					
											</select>
										</p>
										<p>							
											<select name="{mod_main.IDMENU}secure" onchange="document.location = {mod_main.U_ONCHANGE_SECURE}">								
												{mod_main.RANK}
											</select>
										</p>
										<div style="width:100px;height:30px;margin:auto;">
											<div style="float:left">
											</div>
											<div style="position:relative;float:right">
												<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-130px;float:left;display:none;" id="move{mod_main.IDMENU}">
													<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block({mod_main.IDMENU}, 1);" onmouseout="menu_hide_block({mod_main.IDMENU}, 0);">
														<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#BFFF8F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_main.IDMENU}">{L_SUB_HEADER}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_main.IDMENU}">{L_LEFT_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_main.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_main.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_main.IDMENU}">{L_RIGHT_MENU}</a></div>
													</div>
												</div>
											</div>
											<a href="javascript:menu_display_block({mod_main.IDMENU});" onmouseover="menu_hide_block({mod_main.IDMENU}, 1);" onmouseout="menu_hide_block({mod_main.IDMENU}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
										</div>
										
										<p>{mod_main.CONTENTS}</p>
									</div>
									<div class="module_mini_bottom">
									</div>
								</div>
								# END mod_main #
								<div class="spacer">&nbsp;</div>
							</div>							
							<div id="bottom_contents" style="border:1px solid black;background:#FF5F5F;clear:both">
								# START mod_bottomcentral #
								<div style="margin:15px;" class="module_mini_container_horizontal">
									<div class="module_mini_top_horizontal">
										<span id="m{mod_bottomcentral.IDMENU}"></span><h5 class="sub_title">{mod_bottomcentral.NAME} {mod_bottomcentral.EDIT}{mod_bottomcentral.DEL}</h5>
									</div>
									<div class="module_mini_table_horizontal">
										<p>
											<select name="{mod_bottomcentral.IDMENU}activ" onchange="document.location = {mod_bottomcentral.U_ONCHANGE_ACTIV}">								
												<option value="1" {mod_bottomcentral.ACTIV_ENABLED}>{L_ACTIV}</option>
												<option value="0" {mod_bottomcentral.ACTIV_DISABLED}>{L_UNACTIV}</option>					
											</select>
										</p>
										<p>							
											<select name="{mod_bottomcentral.IDMENU}secure" onchange="document.location = {mod_bottomcentral.U_ONCHANGE_SECURE}">								
												{mod_bottomcentral.RANK}
											</select>							
										</p>						
										<div style="width:100px;height:30px;margin:auto;">
											<div style="float:left">
												<a href="admin_menus.php?top=1&amp;id={mod_bottomcentral.IDMENU}"><img src="../templates/{THEME}/images/admin/up.png" alt="" /></a>
												<a href="admin_menus.php?bot=1&amp;id={mod_bottomcentral.IDMENU}"><img src="../templates/{THEME}/images/admin/down.png" alt="" /></a>
											</div>
											<div style="position:relative;float:right">
												<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-70px;float:left;display:none;" id="move{mod_bottomcentral.IDMENU}">
													<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block({mod_bottomcentral.IDMENU}, 1);" onmouseout="menu_hide_block({mod_bottomcentral.IDMENU}, 0);">
														<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#BFFF8F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_bottomcentral.IDMENU}">{L_SUB_HEADER}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_bottomcentral.IDMENU}">{L_LEFT_MENU}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_bottomcentral.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_bottomcentral.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
															<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_bottomcentral.IDMENU}">{L_RIGHT_MENU}</a></div>
													</div>
												</div>
												<a href="javascript:menu_display_block({mod_bottomcentral.IDMENU});" onmouseover="menu_hide_block({mod_bottomcentral.IDMENU}, 1);" onmouseout="menu_hide_block({mod_bottomcentral.IDMENU}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
											</div>
										</div>
										
										<p>{mod_bottomcentral.CONTENTS}</p>							
									</div>
									<div class="module_mini_bottom_horizontal">
									</div>
								</div>
								# END mod_bottomcentral #
							</div>							
						</td>				
						
						# IF RIGHT_COLUMN #
						<td style="width:18%;vertical-align:top;padding:4px;border:1px solid black;background:#EA6FFF">
							# START mod_right #
							<div class="module_mini_container">
								<div class="module_mini_top">
									<span id="m{mod_right.IDMENU}"></span><h5 class="sub_title">{mod_right.NAME} {mod_right.EDIT}{mod_right.DEL}</h5>
								</div>
								<div class="module_mini_table">
									<p>
										<select name="{mod_right.IDMENU}activ" onchange="document.location = {mod_right.U_ONCHANGE_ACTIV}">								
											<option value="1" {mod_right.ACTIV_ENABLED}>{L_ACTIV}</option>
											<option value="0" {mod_right.ACTIV_DISABLED}>{L_UNACTIV}</option>					
										</select>
									</p>
									<p>							
										<select name="{mod_right.IDMENU}secure" onchange="document.location = {mod_right.U_ONCHANGE_SECURE}">								
											{mod_right.RANK}
										</select>							
									</p>						
									<div style="width:100px;height:30px;margin:auto;">
										<div style="float:left">
											<a href="admin_menus.php?top=1&amp;id={mod_right.IDMENU}"><img src="../templates/{THEME}/images/admin/up.png" alt="" /></a>
											<a href="admin_menus.php?bot=1&amp;id={mod_right.IDMENU}"><img src="../templates/{THEME}/images/admin/down.png" alt="" /></a>
										</div>
										<div style="position:relative;float:right">
											<div style="position:absolute;z-index:100;margin-top:155px;margin-left:-110px;float:left;display:none;" id="move{mod_right.IDMENU}">
												<div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block({mod_right.IDMENU}, 1);" onmouseout="menu_hide_block({mod_right.IDMENU}, 0);">
													<div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#BFFF8F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=subheader&amp;id={mod_right.IDMENU}">{L_SUB_HEADER}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=left&amp;id={mod_right.IDMENU}">{L_LEFT_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=topcentral&amp;id={mod_right.IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=bottomcentral&amp;id={mod_right.IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
														<div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move=right&amp;id={mod_right.IDMENU}">{L_RIGHT_MENU}</a></div>
												</div>
											</div>
											<a href="javascript:menu_display_block({mod_right.IDMENU});" onmouseover="menu_hide_block({mod_right.IDMENU}, 1);" onmouseout="menu_hide_block({mod_right.IDMENU}, 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
										</div>
									</div>
									
									<p>{mod_right.CONTENTS}</p>							
								</div>
								<div class="module_mini_bottom">
								</div>
							</div>
							# END mod_right #				
						</td>
						# ENDIF #
					</tr>
				</table>
			</form>
			
			<table class="module_table">
				<tr> 
					<th colspan="6">
						{L_MENUS_MANAGEMENT}
					</th>
				</tr>
				<tr> 
					<td class="row2">
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#BFFF8F;border:1px solid black"></div> <div style="clear:right">{L_SUB_HEADER}</div>
						<br />
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right">{L_LEFT_MENU}</div>
						<br />
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right">{L_TOP_CENTRAL_MENU}</div>
					</td>
					<td class="row2" style="vertical-align:top">
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right">{L_BOTTOM_CENTRAL_MENU}</div>
						<br />
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right">{L_RIGHT_MENU}</div>
					</td>
				</tr>
			</table>
		</div>
		
