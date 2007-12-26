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
				<table class="module_table">
					<tr> 
						<th colspan="6">
							{L_MENUS_MANAGEMENT}
						</th>
					</tr>		
				</table>
				<table class="module_table">
					<tr>				
						# START mod_left #
							{mod_left.START_LEFT}
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
										# START select #	
										{mod_left.select.RANK}
										# END select #									
									</select>							
									<br />							
									{mod_left.TOP}
									{mod_left.BOTTOM}
									<a href="admin_menus.php?id={mod_left.IDMENU}&amp;right=1"><img src="../templates/{THEME}/images/right.png" alt="" /></a>
									{mod_left.CONTENTS}
								</div>
								<div class="module_mini_bottom">
								</div>
								<br />	
							{mod_left.END_LEFT}
						# END mod_left #				
						
							<td style="padding: 6px;vertical-align:top;">
								<table class="module_table" style="width:100%;">
									<tr>
										<th colspan="2">
											{L_MENUS_AVAILABLE}
										</th>
									</tr>
									<tr>								
										<td class="row1" style="text-align:center;width:50%">
											<img src="../templates/{THEME}/images/left.png" alt="" />
										</td>
										<td class="row1" style="text-align:center;width:50%">
											<img src="../templates/{THEME}/images/right.png" alt="" />
										</td>
									</tr>							
									<tr>
										<td style="vertical-align:top;">
											<table style="width:100%;border-collapse:collapse;border-spacing:0px;">
												# START main_left #									
												{main_left.START_LEFT}
												<tr>
													<td style="width:156px;vertical-align:top;padding:4px;">
														<div style="width: 156px;margin:auto;">
															<div class="module_mini_top">
																<span id="m{main_left.IDMENU}"></span><h5 class="sub_title">{main_left.NAME} {main_left.DEL} {main_left.EDIT}</h5>
															</div>
															<div class="module_mini_table">
																<select name="{main_left.IDMENU}activ" onchange="document.location = {main_left.U_ONCHANGE_ACTIV}">								
																	<option value="1" {main_left.ACTIV_ENABLED}>{L_ACTIV}</option>
																	<option value="0" {main_left.ACTIV_DISABLED}>{L_UNACTIV}</option>					
																</select>
																<br />								
																<select name="{main_left.IDMENU}secure" onchange="document.location = {main_left.U_ONCHANGE_SECURE}">								
																	# START select #	
																	{main_left.select.RANK}
																	# END select #									
																</select>							
																<br />							
																{main_left.LEFT}
																{main_left.RIGHT}
																{main_left.CONTENTS}
															</div>
															<div class="module_mini_bottom">
															</div>
														</div>
														<br />
														{main_left.END_LEFT}
												# END main_left #			
													
												{END_TABLE_LEFT}
												
												# START left_unactiv #
												{left_unactiv.LEFT_UNACTIV}
												# END left_unactiv #
											</table>
										</td>
										<td style="vertical-align:top;">
											<table style="width:100%;border-collapse:collapse;border-spacing:0px;">
												# START main_right #									
												{main_right.START_RIGHT}
												<tr>
													<td style="width:156px;vertical-align:top;padding:4px;">
														<div style="width: 156px;margin:auto;">
															<div class="module_mini_top">
																<span id="m{main_right.IDMENU}"></span><h5 class="sub_title">{main_right.NAME} {main_right.DEL} {main_right.EDIT}</h5>
															</div>
															<div class="module_mini_table">
																<select name="{main_right.IDMENU}activ" onchange="document.location = {main_right.U_ONCHANGE_ACTIV}">								
																	<option value="1" {main_right.ACTIV_ENABLED}>{L_ACTIV}</option>
																	<option value="0" {main_right.ACTIV_DISABLED}>{L_UNACTIV}</option>					
																</select>
																<br />								
																<select name="{main_right.IDMENU}secure" onchange="document.location = {main_right.U_ONCHANGE_SECURE}">								
																	# START select #	
																	{main_right.select.RANK}
																	# END select #									
																</select>							
																<br />							
																{main_right.LEFT}
																{main_right.RIGHT}
																{main_right.CONTENTS}
															</div>
															<div class="module_mini_bottom">
															</div>
														</div>
														<br />
														{main_right.END_RIGHT}
												# END main_right #
												
												{END_TABLE_RIGHT}
															
												# START right_unactiv #
												{right_unactiv.RIGHT_UNACTIV}
												# END right_unactiv #											
											</table>
										</td>
									</tr>														
									<tr>
										<td colspan="2">
											<br /><br /><br /><br /><br /><br /><br />
											<br /><br /><br /><br /><br /><br /><br />
										</td>
									</tr>
								</table>					
							</td>				
						
						# START mod_right #
							{mod_right.START_RIGHT}
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
										# START select #	
										{mod_right.select.RANK}
										# END select #									
									</select>							
									<br />							
									<a href="admin_menus.php?id={mod_right.IDMENU}&amp;left=1"><img src="../templates/{THEME}/images/left.png" alt="" /></a>
									{mod_right.TOP}
									{mod_right.BOTTOM}
									{mod_right.CONTENTS}							
								</div>
								<div class="module_mini_bottom">
								</div>
								<br />	
							{mod_right.END_RIGHT}
						# END mod_right #				
					</tr>
					<tr> 
						<th colspan="6">
							&nbsp;
							<noscript>
								<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
							</noscript>
						</th>
					</tr>
				</table>
			</form>
		</div>
		