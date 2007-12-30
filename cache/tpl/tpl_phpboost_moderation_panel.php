		
		
		<table class="module_table">
			<tr>
				<th colspan="2">
					<?php echo isset($this->_var['L_MODERATION_PANEL']) ? $this->_var['L_MODERATION_PANEL'] : ''; ?>
				</th>
			</tr>
			<tr>
				<td class="row3" colspan="2">
					&bull; <a href="moderation_panel.php"><?php echo isset($this->_var['L_MODERATION_PANEL']) ? $this->_var['L_MODERATION_PANEL'] : ''; ?></a>
				</td>			
			</tr>
			<tr>
				<td class="row2" style="width:150px;vertical-align:top;">
					<div id="dynamic_menu">
						<div onmouseover="show_menu(1);" onmouseout="hide_menu();">
							<h5 onclick="temporise_menu(1)"><?php echo isset($this->_var['L_MEMBERS']) ? $this->_var['L_MEMBERS'] : ''; ?></h5>
							<div class="vertical_block" id="smenu1">								
								<ul>
									<li><a href="moderation_panel<?php echo isset($this->_var['U_WARNING']) ? $this->_var['U_WARNING'] : ''; ?>" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/important.png);background-repeat:no-repeat ;background-position:5px;"><?php echo isset($this->_var['L_WARNING']) ? $this->_var['L_WARNING'] : ''; ?></a></li>
									<li><a href="moderation_panel<?php echo isset($this->_var['U_PUNISH']) ? $this->_var['U_PUNISH'] : ''; ?>" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/stop.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_PUNISHMENT']) ? $this->_var['L_PUNISHMENT'] : ''; ?></a></li>
									<li><a href="moderation_panel<?php echo isset($this->_var['U_BAN']) ? $this->_var['U_BAN'] : ''; ?>" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/forbidden.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_BAN']) ? $this->_var['L_BAN'] : ''; ?></a></li>
								</ul>
								<span class="dm_bottom"></span>
							</div>	
						</div>
						<br />
						<hr />
						<br />
						<div onmouseover="show_menu(2);" onmouseout="hide_menu();">
							<h5 onclick="temporise_menu(2)"><?php echo isset($this->_var['L_MODULES']) ? $this->_var['L_MODULES'] : ''; ?></h5>
							<div class="vertical_block" id="smenu2">								
								<?php if( isset($this->_var['C_MODULES_MODO']) && $this->_var['C_MODULES_MODO'] ) { ?>
								<ul>
									<?php if( !isset($this->_block['list_modules']) || !is_array($this->_block['list_modules']) ) $this->_block['list_modules'] = array();
foreach($this->_block['list_modules'] as $list_modules_key => $list_modules_value) {
$_tmpb_list_modules = &$this->_block['list_modules'][$list_modules_key]; ?>
									<li><a href="../<?php echo isset($_tmpb_list_modules['MOD_NAME']) ? $_tmpb_list_modules['MOD_NAME'] : ''; ?>/<?php echo isset($_tmpb_list_modules['U_LINK']) ? $_tmpb_list_modules['U_LINK'] : ''; ?>" <?php echo isset($_tmpb_list_modules['DM_A_CLASS']) ? $_tmpb_list_modules['DM_A_CLASS'] : ''; ?>><?php echo isset($_tmpb_list_modules['NAME']) ? $_tmpb_list_modules['NAME'] : ''; ?></a></li>
									<?php } ?>
								</ul>
								<span class="dm_bottom"></span>
								<?php } ?>
							</div>	
						</div>						
					</div>
					<br /><br /><br /><br /><br /><br />
				</td>
				<td class="row2" style="vertical-align:top;padding:6px;">
					<?php if( !isset($this->_block['all_action']) || !is_array($this->_block['all_action']) ) $this->_block['all_action'] = array();
foreach($this->_block['all_action'] as $all_action_key => $all_action_value) {
$_tmpb_all_action = &$this->_block['all_action'][$all_action_key]; ?>
					<table class="module_table">
						<tr>
							<th colspan="3">
								<?php echo isset($this->_var['L_INFO_MANAGEMENT']) ? $this->_var['L_INFO_MANAGEMENT'] : ''; ?>
							</th>
						</tr>
						<tr>							
							<td style="text-align:center;width:34%" class="row2">
								<a href="moderation_panel<?php echo isset($this->_var['U_WARNING']) ? $this->_var['U_WARNING'] : ''; ?>" title="<?php echo isset($this->_var['L_USERS_WARNING']) ? $this->_var['L_USERS_WARNING'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/notice.png" alt="<?php echo isset($this->_var['L_USERS_WARNING']) ? $this->_var['L_USERS_WARNING'] : ''; ?>" /><br /><?php echo isset($this->_var['L_USERS_WARNING']) ? $this->_var['L_USERS_WARNING'] : ''; ?></a>
							</td>
							<td style="text-align:center;width:33%" class="row2">
								<a href="moderation_panel<?php echo isset($this->_var['U_PUNISH']) ? $this->_var['U_PUNISH'] : ''; ?>" title="<?php echo isset($this->_var['L_USERS_PUNISHMENT']) ? $this->_var['L_USERS_PUNISHMENT'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stop.png" alt="<?php echo isset($this->_var['L_USERS_PUNISHMENT']) ? $this->_var['L_USERS_PUNISHMENT'] : ''; ?>" /><br /><?php echo isset($this->_var['L_USERS_PUNISHMENT']) ? $this->_var['L_USERS_PUNISHMENT'] : ''; ?></a>
							</td>
							<td style="text-align:center;width:33%" class="row2">
								<a href="moderation_panel<?php echo isset($this->_var['U_BAN']) ? $this->_var['U_BAN'] : ''; ?>" title="<?php echo isset($this->_var['L_USERS_BAN']) ? $this->_var['L_USERS_BAN'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/forbidden.png" alt="<?php echo isset($this->_var['L_USERS_BAN']) ? $this->_var['L_USERS_BAN'] : ''; ?>" /><br /><?php echo isset($this->_var['L_USERS_BAN']) ? $this->_var['L_USERS_BAN'] : ''; ?></a>
							</td>
						</tr>
					</table>
					<?php } ?>
					
					<?php if( !isset($this->_block['user_list']) || !is_array($this->_block['user_list']) ) $this->_block['user_list'] = array();
foreach($this->_block['user_list'] as $user_list_key => $user_list_value) {
$_tmpb_user_list = &$this->_block['user_list'][$user_list_key]; ?>
					<script type="text/javascript">
					<!--
						function XMLHttpRequest_search()
						{
							var xhr_object = null;
							var data = null;
							var filename = "../includes/xmlhttprequest.php?<?php echo isset($this->_var['U_XMLHTTPREQUEST']) ? $this->_var['U_XMLHTTPREQUEST'] : ''; ?>=1";
							var login = document.getElementById("login").value;
							var data = null;
							
							if(window.XMLHttpRequest) // Firefox
							   xhr_object = new XMLHttpRequest();
							else if(window.ActiveXObject) // Internet Explorer
							   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
							else // XMLHttpRequest non supporté par le navigateur
								return;
							
							if( login != "" )
							{
								data = "login=" + login;
							   
								xhr_object.open("POST", filename, true);

								xhr_object.onreadystatechange = function() 
								{
									if( xhr_object.readyState == 4 ) 
									{
										document.getElementById("xmlhttprequest_result_search").innerHTML = xhr_object.responseText;
										hide_div("xmlhttprequest_result_search");
									}
								}

								xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

								xhr_object.send(data);
							}	
							else
							{
								alert("<?php echo isset($this->_var['L_REQUIRE_LOGIN']) ? $this->_var['L_REQUIRE_LOGIN'] : ''; ?>");
							}		
						}
						
						function hide_div(divID)
						{
							if( document.getElementById(divID) )
								document.getElementById(divID).style.display = 'block';
						}
						-->
					</script>
					
					<form action="moderation_panel<?php echo isset($this->_var['U_ACTION']) ? $this->_var['U_ACTION'] : ''; ?>" method="post" class="fieldset_content">
						<fieldset>
							<legend><?php echo isset($this->_var['L_SEARCH_MEMBER']) ? $this->_var['L_SEARCH_MEMBER'] : ''; ?></legend>
							<dl>
								<dt><label for="login"><?php echo isset($this->_var['L_SEARCH_MEMBER']) ? $this->_var['L_SEARCH_MEMBER'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_JOKER']) ? $this->_var['L_JOKER'] : ''; ?></span></dt>
								<dd><label>
									<input type="text" size="20" maxlength="25" id="login" value="" name="login" class="text" />						
									<script type="text/javascript">
									<!--								
										document.write('<input value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">');
									-->
									</script>
									<noscript>
										<input type="submit" name="search_member" value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" class="submit" />
									</noscript>
									<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
								</label></dd>
							</dl>
						</fieldset>
					</form>
					
					<table class="module_table">
						<tr>			
							<th style="width:25%;"><?php echo isset($this->_var['L_LOGIN']) ? $this->_var['L_LOGIN'] : ''; ?></th>
							<th style="width:25%;"><?php echo isset($this->_var['L_INFO']) ? $this->_var['L_INFO'] : ''; ?></th>
							<th style="width:25%;"><?php echo isset($this->_var['L_ACTION_USER']) ? $this->_var['L_ACTION_USER'] : ''; ?></th>
							<th style="width:25%;"><?php echo isset($this->_var['L_PM']) ? $this->_var['L_PM'] : ''; ?></th>
						</tr>	
						<?php if( !isset($_tmpb_user_list['list']) || !is_array($_tmpb_user_list['list']) ) $_tmpb_user_list['list'] = array();
foreach($_tmpb_user_list['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_user_list['list'][$list_key]; ?>
						<tr>
							<td class="row1" style="text-align:center;width:25%;">
								<a href="../member/<?php echo isset($_tmpb_list['U_PROFILE']) ? $_tmpb_list['U_PROFILE'] : ''; ?>"><?php echo isset($_tmpb_list['LOGIN']) ? $_tmpb_list['LOGIN'] : ''; ?></a>
							</td>
							<td class="row1" style="text-align:center;width:25%;">
								<?php echo isset($_tmpb_list['INFO']) ? $_tmpb_list['INFO'] : ''; ?>
							</td>
							<td class="row1" style="text-align:center;width:25%;">
								<?php echo isset($_tmpb_list['U_ACTION_USER']) ? $_tmpb_list['U_ACTION_USER'] : ''; ?>
							</td>
							<td class="row1" style="text-align:center;width:25%;">
								<a href="../member/pm<?php echo isset($_tmpb_list['U_PM']) ? $_tmpb_list['U_PM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/pm.png" alt="" /></a>
							</td>
						</tr>
						<?php } ?>
						
						<?php if( !isset($_tmpb_user_list['empty']) || !is_array($_tmpb_user_list['empty']) ) $_tmpb_user_list['empty'] = array();
foreach($_tmpb_user_list['empty'] as $empty_key => $empty_value) {
$_tmpb_empty = &$_tmpb_user_list['empty'][$empty_key]; ?>
						<tr>
							<td class="row1" style="text-align:center;" colspan="4">
								<?php echo isset($_tmpb_empty['NO_USER']) ? $_tmpb_empty['NO_USER'] : ''; ?>
							</td>
						</tr>		
						<?php } ?>
					</table>
					</form>
					<?php } ?>

					

					<?php if( !isset($this->_block['user_info']) || !is_array($this->_block['user_info']) ) $this->_block['user_info'] = array();
foreach($this->_block['user_info'] as $user_info_key => $user_info_value) {
$_tmpb_user_info = &$this->_block['user_info'][$user_info_key]; ?>
					<script type="text/javascript">
					<!--
					function change_textarea_level(replace_value, regex)
					{
						var contents = document.getElementById('action_contents').innerHTML;
						<?php echo isset($_tmpb_user_info['REPLACE_VALUE']) ? $_tmpb_user_info['REPLACE_VALUE'] : ''; ?>		
						
						document.getElementById('action_contents').innerHTML = contents;	
					}
					-->
					</script>
					
					<form action="moderation_panel<?php echo isset($_tmpb_user_info['U_ACTION_INFO']) ? $_tmpb_user_info['U_ACTION_INFO'] : ''; ?>" method="post">		
						<table class="module_table">
							<tr>
								<td class="row1" style="width:30%;">
									<?php echo isset($this->_var['L_LOGIN']) ? $this->_var['L_LOGIN'] : ''; ?>
								</td>
								<td class="row2">
									<?php echo isset($_tmpb_user_info['LOGIN']) ? $_tmpb_user_info['LOGIN'] : ''; ?>
								</td>
							</tr>
							<tr>
								<td class="row1">
									<?php echo isset($this->_var['L_PM']) ? $this->_var['L_PM'] : ''; ?>
								</td>
								<td class="row2">
									<a href="../member/pm<?php echo isset($_tmpb_user_info['U_PM']) ? $_tmpb_user_info['U_PM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/pm.png" alt="PM" /></a>
								</td>
							</tr>
							<tr>
								<td class="row1" style="vertical-align:top">
									<label for="action_contents"><?php echo isset($this->_var['L_ALTERNATIVE_PM']) ? $this->_var['L_ALTERNATIVE_PM'] : ''; ?></label>
								</td>
								<td class="row2">
									<?php $this->tpl_include('handle_bbcode'); ?>
									<label><textarea name="action_contents" id="action_contents" class="post" rows="12"><?php echo isset($this->_var['ALTERNATIVE_PM']) ? $this->_var['ALTERNATIVE_PM'] : ''; ?></textarea></label>
								</td>
							</tr>
							<tr>
								<td class="row1">
									<?php echo isset($this->_var['L_INFO_EXPLAIN']) ? $this->_var['L_INFO_EXPLAIN'] : ''; ?>
								</td>
								<td class="row2">
									<span id="action_info"><?php echo isset($_tmpb_user_info['INFO']) ? $_tmpb_user_info['INFO'] : ''; ?></span>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<select name="new_info" onchange="change_textarea_level(this.options[this.selectedIndex].value, <?php echo isset($_tmpb_user_info['REGEX']) ? $_tmpb_user_info['REGEX'] : ''; ?>)">
										<?php echo isset($_tmpb_user_info['SELECT']) ? $_tmpb_user_info['SELECT'] : ''; ?>
									</select>
									<input type="submit" name="valid_user" value="<?php echo isset($this->_var['L_CHANGE_INFO']) ? $this->_var['L_CHANGE_INFO'] : ''; ?>" class="submit" />					
								</td>
							</tr>
						</table>
					</form>					
					<?php } ?>
					
					
					
					<?php if( !isset($this->_block['user_ban']) || !is_array($this->_block['user_ban']) ) $this->_block['user_ban'] = array();
foreach($this->_block['user_ban'] as $user_ban_key => $user_ban_value) {
$_tmpb_user_ban = &$this->_block['user_ban'][$user_ban_key]; ?>					
					<form action="moderation_panel<?php echo isset($_tmpb_user_ban['U_ACTION_INFO']) ? $_tmpb_user_ban['U_ACTION_INFO'] : ''; ?>" method="post">		
						<table class="module_table">
							<tr>
								<td class="row1" style="width:30%;">
									<?php echo isset($this->_var['L_LOGIN']) ? $this->_var['L_LOGIN'] : ''; ?>
								</td>
								<td class="row2">
									<?php echo isset($_tmpb_user_ban['LOGIN']) ? $_tmpb_user_ban['LOGIN'] : ''; ?>
								</td>
							</tr>
							<tr>
								<td class="row1">
									<?php echo isset($this->_var['L_PM']) ? $this->_var['L_PM'] : ''; ?>
								</td>
								<td class="row2">
									<a href="../member/pm<?php echo isset($_tmpb_user_ban['U_PM']) ? $_tmpb_user_ban['U_PM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/pm.png" alt="" /></a>
								</td>
							</tr>
							<tr>
								<td class="row1">
									<?php echo isset($this->_var['L_DELAY_BAN']) ? $this->_var['L_DELAY_BAN'] : ''; ?>
								</td>
								<td class="row2">
								<select name="user_ban">					
										<?php if( !isset($_tmpb_user_ban['select_ban']) || !is_array($_tmpb_user_ban['select_ban']) ) $_tmpb_user_ban['select_ban'] = array();
foreach($_tmpb_user_ban['select_ban'] as $select_ban_key => $select_ban_value) {
$_tmpb_select_ban = &$_tmpb_user_ban['select_ban'][$select_ban_key]; ?>	
											<?php echo isset($_tmpb_select_ban['TIME']) ? $_tmpb_select_ban['TIME'] : ''; ?>
										<?php } ?>						
									</select>
								</td>
							</tr>
							<tr>
								<td class="row2" colspan="2" style="text-align:center;">
									<input type="submit" name="valid_user" value="<?php echo isset($this->_var['L_BAN']) ? $this->_var['L_BAN'] : ''; ?>" class="submit" />					
								</td>
							</tr>
						</table>
					</form>					
					<?php } ?>
					
					<br /><br /><br />
				</td>			
			</tr>
		</table>
		
		