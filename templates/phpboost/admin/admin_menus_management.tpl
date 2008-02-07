		<script type="text/javascript">
		<!--
		function Confirm_menu() {
			return confirm("{L_CONFIRM_DEL_MENU}");
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
				<table class="module_table" style="background:#FFFFFF">
					<tr>
						<td colspan="3" style="height:100px;border:1px solid black;background:#BFFF8F">
						</td>
					</tr>
					<tr>				
						# IF LEFT_COLUMN #
						<td style="width:156px;vertical-align:top;padding:4px;border:1px solid black;background:#9B8FFF">
							# START mod_left #
							<div class="module_mini_top">
								<span id="m{mod_left.IDMENU}"></span><h5 class="sub_title">{mod_left.NAME} {mod_left.EDIT} {mod_left.DEL}</h5>
							</div>
							<div class="module_mini_table">
								<select name="{mod_left.IDMENU}activ" onchange="document.location = {mod_left.U_ONCHANGE_ACTIV}">								
									<option value="1" {mod_left.ACTIV_ENABLED}>{L_ACTIV}</option>
									<option value="0" {mod_left.ACTIV_DISABLED}>{L_UNACTIV}</option>					
								</select>
								<br />								
								<select name="{mod_left.IDMENU}secure" onchange="document.location = {mod_left.U_ONCHANGE_SECURE}">								
									{mod_left.RANK}
								</select>							
								<br />							
								<a href="admin_menus.php?top=1&amp;id={mod_left.IDMENU}"><img src="../templates/{THEME}/images/admin/up.png" alt="" /></a>
								<a href="admin_menus.php?bot=1&amp;id={mod_left.IDMENU}"><img src="../templates/{THEME}/images/admin/down.png" alt="" /></a>
								<a href="admin_menus.php?id={mod_left.IDMENU}&amp;right=1"><img src="../templates/{THEME}/images/right.png" alt="" /></a>
								{mod_left.CONTENTS}
							</div>
							<div class="module_mini_bottom">
							</div>
							<br />	
							# END mod_left #				
						</td>
						# ENDIF #
						
						<td style="vertical-align:top;border:1px solid black;background:#E5E5E5">
							<div id="links">
								&nbsp;&nbsp;<a class="small_link" href="" title="Accueil">Accueil</a>
							</div>
							<div id="top_contents" style="height:100px;border:1px solid black;background:#FFE25F">
							
							</div>
							<br />
							<p style="text-align:center;" class="text_strong">{L_MENUS_AVAILABLE}</p>
							# START mod_main #									
							<div style="width:156px;margin:10px;float:left">
								<div class="module_mini_top">
									<span id="m{mod_main.IDMENU}"></span><h5 class="sub_title">{mod_main.NAME} {mod_main.DEL} {mod_main.EDIT}</h5>
								</div>
								<div class="module_mini_table">
									<select name="{mod_main.IDMENU}activ" onchange="document.location = {mod_main.U_ONCHANGE_ACTIV}">								
										<option value="1" {mod_main.ACTIV_ENABLED}>{L_ACTIV}</option>
										<option value="0" {mod_main.ACTIV_DISABLED}>{L_UNACTIV}</option>					
									</select>
									<br />								
									<select name="{mod_main.IDMENU}secure" onchange="document.location = {mod_main.U_ONCHANGE_SECURE}">								
										{mod_main.RANK}
									</select>							
									<br />							
									<a href="admin_menus.php?top=1&amp;id={mod_main.IDMENU}"><img src="../templates/{THEME}/images/admin/up.png" alt="" /></a>
									<a href="admin_menus.php?bot=1&amp;id={mod_main.IDMENU}"><img src="../templates/{THEME}/images/admin/down.png" alt="" /></a>
									<br />
									<a href="admin_menus.php?id={mod_main.IDMENU}&amp;left=1"><img src="../templates/{THEME}/images/left.png" alt="" /></a>
									<a href="admin_menus.php?id={mod_main.IDMENU}&amp;right=1"><img src="../templates/{THEME}/images/right.png" alt="" /></a>
									{mod_main.CONTENTS}
								</div>
								<div class="module_mini_bottom">
								</div>
							</div>
							# END mod_main #	

							<br />
							<div id="bottom_contents" style="height:100px;border:1px solid black;background:#FF5F5F;clear:both">
							
							</div>							
						</td>				
						
						# IF RIGHT_COLUMN #
						<td style="width:156px;vertical-align:top;padding:4px;border:1px solid black;background:#EA6FFF">
							# START mod_right #
							<div class="module_mini_top">
								<span id="m{mod_right.IDMENU}"></span><h5 class="sub_title">{mod_right.NAME} {mod_right.EDIT}{mod_right.DEL}</h5>
							</div>
							<div class="module_mini_table">
								<select name="{mod_right.IDMENU}activ" onchange="document.location = {mod_right.U_ONCHANGE_ACTIV}">								
									<option value="1" {mod_right.ACTIV_ENABLED}>{L_ACTIV}</option>
									<option value="0" {mod_right.ACTIV_DISABLED}>{L_UNACTIV}</option>					
								</select>
								<br />								
								<select name="{mod_right.IDMENU}secure" onchange="document.location = {mod_right.U_ONCHANGE_SECURE}">								
									{mod_right.RANK}
								</select>							
								<br />							
								<a href="admin_menus.php?id={mod_right.IDMENU}&amp;left=1"><img src="../templates/{THEME}/images/left.png" alt="" /></a>
								<a href="admin_menus.php?top=1&amp;id={mod_right.IDMENU}"><img src="../templates/{THEME}/images/admin/up.png" alt="" /></a>
								<a href="admin_menus.php?bot=1&amp;id={mod_right.IDMENU}"><img src="../templates/{THEME}/images/admin/down.png" alt="" /></a>
								{mod_right.CONTENTS}							
							</div>
							<div class="module_mini_bottom">
							</div>
							<br />	
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
						<br />
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right">{L_BOTTOM_CENTRAL_MENU}</div>
						<br />
						<div style="float:left;margin-left:5px;margin-right:10px;height:15px;width:15px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right">{L_RIGHT_MENU}</div>
					</td>
				</tr>
			</table>
		</div>
		