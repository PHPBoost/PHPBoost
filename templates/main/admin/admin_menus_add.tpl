		<script type="text/javascript">
		<!--

		function check_msg(){
			if(document.getElementById('name').value == "") {
				alert("{L_REQUIRE_TITLE}");
				return false;
		    }
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
		    }
			return true;
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
			<form action="admin_menus_add.php" method="post">
				<table class="module_table">
					<tr> 
						<th>
							{L_ACTION_MENUS}
						</th>
					</tr>
					<tr> 
						<td class="row2" style="text-align:center">
							{L_EXPLAIN_MENUS}
						</td>
					</tr>
					<tr>				
						<td style="vertical-align:top;padding:10px;">
							<div style="width:156px;margin:auto;">
								# START add #
								<div class="module_mini_top" style="text-align:center;padding-left:0">
									<input type="text" size="18" name="name" id="name" class="text" />
								</div>
								<div class="module_mini_table">
									<select name="activ">								
										<option value="1" selected="selected">{L_ACTIV}</option>
										<option value="0">{L_UNACTIV}</option>					
									</select>
									<br />								
									<select name="secure">								
										# START select #	
										{add.select.RANK}
										# END select #									
									</select>							
									<br />							
									<textarea type="text" class="post" rows="15" cols="5" id="contents" name="contents"></textarea> 
								</div>
								<div class="module_mini_bottom">
								</div>
								# END add #		
						
								# START edit #
								<div class="module_mini_top">
									<h5 class="sub_title"><input type="text" size="18" value="{edit.NAME}" name="name" id="name" class="text" /></h5>
								</div>
								<div class="module_mini_table">
									<select name="activ">								
										<option value="1" {edit.ACTIV_ENABLED}>{L_ACTIV}</option>
										<option value="0" {edit.ACTIV_DISABLED}>{L_UNACTIV}</option>					
									</select>
									<br />								
									<select name="secure">								
										# START select #	
										{edit.select.RANK}
										# END select #									
									</select>							
									<br /><br />							
									<textarea type="text" class="post" rows="15" cols="5" id="contents" name="contents">{edit.CONTENTS}</textarea> 
								</div>
								<div class="module_mini_bottom">
								</div>
								# END edit #	
								<div style="text-align:center">
									<img src="../templates/{THEME}/images/admin/minus.png" style="cursor: pointer;cursor: hand;" onclick="textarea_resize('contents', -130, 'width');" alt="" />
									<img src="../templates/{THEME}/images/admin/plus.png" style="cursor: pointer;cursor: hand;" onclick="textarea_resize('contents', 130, 'width');" alt="" />
								</div>
							</div>
							{BBCODE}					
						</td>
					</tr>
				</table>
			
				<br /><br />
			
				<fieldset class="fieldset_submit">
					<legend>{L_ACTION}</legend>
					<input type="hidden" name="action" value="{ACTION}" />
					<input type="hidden" name="id" value="{IDMENU}" />
					<input type="submit" name="valid" value="{L_ACTION}" class="submit" />
					<input type="reset" value="{L_RESET}" class="reset" />					
				</fieldset>	
			</form>
		</div>