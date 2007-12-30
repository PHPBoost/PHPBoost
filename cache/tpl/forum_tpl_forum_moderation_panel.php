		<?php $this->tpl_include('forum_top'); ?>
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">&bull; <a href="../member/moderation_panel.php"><?php echo isset($this->_var['L_MODERATION_PANEL']) ? $this->_var['L_MODERATION_PANEL'] : ''; ?></a> :: <a href="index.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_FORUM_INDEX']) ? $this->_var['L_FORUM_INDEX'] : ''; ?></a> &raquo; <a href="moderation_forum.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_MODERATION_FORUM']) ? $this->_var['L_MODERATION_FORUM'] : ''; ?></a> <?php echo isset($this->_var['U_MODERATION_FORUM_ACTION']) ? $this->_var['U_MODERATION_FORUM_ACTION'] : ''; ?></div>
			<div class="module_contents">
				<table class="module_table">
					<tr>							
						<td style="text-align:center;" class="row2">
							<a href="moderation_forum.php?action=warning" title="<?php echo isset($this->_var['L_USERS_WARNING']) ? $this->_var['L_USERS_WARNING'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/notice.png" alt="<?php echo isset($this->_var['L_USERS_WARNING']) ? $this->_var['L_USERS_WARNING'] : ''; ?>" /><br /><?php echo isset($this->_var['L_USERS_WARNING']) ? $this->_var['L_USERS_WARNING'] : ''; ?></a>
						</td>
						<td style="text-align:center;" class="row2">
							<a href="moderation_forum.php?action=punish" title="<?php echo isset($this->_var['L_USERS_PUNISHMENT']) ? $this->_var['L_USERS_PUNISHMENT'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stop.png" alt="<?php echo isset($this->_var['L_USERS_PUNISHMENT']) ? $this->_var['L_USERS_PUNISHMENT'] : ''; ?>" /><br /><?php echo isset($this->_var['L_USERS_PUNISHMENT']) ? $this->_var['L_USERS_PUNISHMENT'] : ''; ?></a>
						</td>
						<td style="text-align:center;" class="row2">
							<a href="moderation_forum.php?action=alert" title="<?php echo isset($this->_var['L_ALERT_MANAGEMENT']) ? $this->_var['L_ALERT_MANAGEMENT'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/important.png" alt="<?php echo isset($this->_var['L_ALERT_MANAGEMENT']) ? $this->_var['L_ALERT_MANAGEMENT'] : ''; ?>" /><br /><?php echo isset($this->_var['L_ALERT_MANAGEMENT']) ? $this->_var['L_ALERT_MANAGEMENT'] : ''; ?></a>
						</td>
					</tr>
				</table>				
				<br /><br />
				
				
				<?php if( !isset($this->_block['main']) || !is_array($this->_block['main']) ) $this->_block['main'] = array();
foreach($this->_block['main'] as $main_key => $main_value) {
$_tmpb_main = &$this->_block['main'][$main_key]; ?>
				<script type="text/javascript">
				<!--

				function Confirm_history()
				{
					return confirm("<?php echo isset($this->_var['L_DEL_HISTORY']) ? $this->_var['L_DEL_HISTORY'] : ''; ?>");
				}
				-->
				</script>
				<form action="moderation_forum<?php echo isset($_tmpb_main['U_ACTION_HISTORY']) ? $_tmpb_main['U_ACTION_HISTORY'] : ''; ?>" method="post" onsubmit="javascript:return Confirm_history();">					
					<table class="module_table">	
						<tr>
							<th colspan="4">
								<?php echo isset($this->_var['L_HISTORY']) ? $this->_var['L_HISTORY'] : ''; ?>
							</th>
						</tr>
						<tr style="text-align:center;font-weight: bold;width: 150px">
							<td class="row3">
								<?php echo isset($this->_var['L_MODO']) ? $this->_var['L_MODO'] : ''; ?>
							</td>
							<td class="row3">
								<?php echo isset($this->_var['L_ACTION']) ? $this->_var['L_ACTION'] : ''; ?>
							</td>
							<td class="row3">
								<?php echo isset($this->_var['L_MEMBER_CONCERN']) ? $this->_var['L_MEMBER_CONCERN'] : ''; ?>
							</td>
							<td class="row3"style="width: 150px">
								<?php echo isset($this->_var['L_DATE']) ? $this->_var['L_DATE'] : ''; ?>
							</td>
						</tr>
						
						<?php if( !isset($_tmpb_main['list']) || !is_array($_tmpb_main['list']) ) $_tmpb_main['list'] = array();
foreach($_tmpb_main['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_main['list'][$list_key]; ?> 
						<tr style="text-align:center;">
							<td class="row2" style="width: 150px">
								<a href="../member/member<?php echo isset($_tmpb_list['U_MEMBER_ID']) ? $_tmpb_list['U_MEMBER_ID'] : ''; ?>"><?php echo isset($_tmpb_list['LOGIN']) ? $_tmpb_list['LOGIN'] : ''; ?></a>
							</td>
							<td class="row2">
								<?php echo isset($_tmpb_list['U_ACTION']) ? $_tmpb_list['U_ACTION'] : ''; ?>
							</td>
							<td class="row2" style="width: 150px">
								<?php echo isset($_tmpb_list['U_MEMBER_CONCERN']) ? $_tmpb_list['U_MEMBER_CONCERN'] : ''; ?>
							</td>
							<td class="row2" style="width: 150px">
								<?php echo isset($_tmpb_list['DATE']) ? $_tmpb_list['DATE'] : ''; ?>
							</td>
						</tr>
						<?php } ?>
						
						<?php if( !isset($_tmpb_main['no_action']) || !is_array($_tmpb_main['no_action']) ) $_tmpb_main['no_action'] = array();
foreach($_tmpb_main['no_action'] as $no_action_key => $no_action_value) {
$_tmpb_no_action = &$_tmpb_main['no_action'][$no_action_key]; ?>
						<tr style="text-align:center;">
							<td class="row2" colspan="4">
								<?php echo isset($_tmpb_no_action['L_NO_ACTION']) ? $_tmpb_no_action['L_NO_ACTION'] : ''; ?>
							</td>
						</tr>
						<?php } ?>
						
						<tr>
							<td class="row3" colspan="4" style="text-align:center;">
								<?php if( !isset($_tmpb_main['admin']) || !is_array($_tmpb_main['admin']) ) $_tmpb_main['admin'] = array();
foreach($_tmpb_main['admin'] as $admin_key => $admin_value) {
$_tmpb_admin = &$_tmpb_main['admin'][$admin_key]; ?>
								<span style="float:left"><input type="submit" name="valid" value="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" class="submit" /></span> 
								<?php } ?>
								
								<a href="moderation_forum<?php echo isset($_tmpb_main['U_MORE_ACTION']) ? $_tmpb_main['U_MORE_ACTION'] : ''; ?>"><?php echo isset($this->_var['L_MORE_ACTION']) ? $this->_var['L_MORE_ACTION'] : ''; ?></a>
							</td>
						</tr>
					</table>
				</form>	
				<?php } ?>

				

				<?php if( !isset($this->_block['alert']) || !is_array($this->_block['alert']) ) $this->_block['alert'] = array();
foreach($this->_block['alert'] as $alert_key => $alert_value) {
$_tmpb_alert = &$this->_block['alert'][$alert_key]; ?>
				<script type="text/javascript">
				<!--
				function check_alert(status)
				{
					for (i = 0; i < document.alert.length; i++)
					{
						document.alert.elements[i].checked = status;
					}
				}
				function Confirm_msg() {
					return confirm("<?php echo isset($_tmpb_alert['L_DELETE_MESSAGE']) ? $_tmpb_alert['L_DELETE_MESSAGE'] : ''; ?>");
				}
				-->
				</script>
		
				<table class="module_table">
					<tr>			
						<th style="width:31px;"><input type="checkbox" onClick="if(this.checked) {check_convers(true)} else {check_convers(false)};" /></th>
						<th style="width:20%;"><?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></th>
						<th style="width:20%;"><?php echo isset($this->_var['L_TOPIC']) ? $this->_var['L_TOPIC'] : ''; ?></th>
						<th style="width:100px;"><?php echo isset($this->_var['L_STATUS']) ? $this->_var['L_STATUS'] : ''; ?></th>
						<th style="width:70px;"><?php echo isset($this->_var['L_LOGIN']) ? $this->_var['L_LOGIN'] : ''; ?></th>
						<th style="width:70px;"><?php echo isset($this->_var['L_TIME']) ? $this->_var['L_TIME'] : ''; ?></th>
					</tr>
				</table>
				
				<form name="alert" action="moderation_forum<?php echo isset($this->_var['U_ACTION_ALERT']) ? $this->_var['U_ACTION_ALERT'] : ''; ?>" method="post" onsubmit="javascript:return Confirm_alert();">
					<table class="module_table">
						<?php if( !isset($_tmpb_alert['list']) || !is_array($_tmpb_alert['list']) ) $_tmpb_alert['list'] = array();
foreach($_tmpb_alert['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_alert['list'][$list_key]; ?>
						<tr>
							<td class="row1" style="text-align:center;width:25px;">
								<input type="checkbox" name="<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>" />
							</td>
							<td class="row1" style="text-align:center;width:20%;">
								<?php echo isset($_tmpb_list['TITLE']) ? $_tmpb_list['TITLE'] : ''; ?>
							</td>
							<td class="row1" style="text-align:center;width:20%;">
								<?php echo isset($_tmpb_list['TOPIC']) ? $_tmpb_list['TOPIC'] : ''; ?>
							</td>
							<td class="row1" style="text-align:center;width:100px;<?php echo isset($_tmpb_list['BACKGROUND_COLOR']) ? $_tmpb_list['BACKGROUND_COLOR'] : ''; ?>">
								<?php echo isset($_tmpb_list['STATUS']) ? $_tmpb_list['STATUS'] : ''; ?>
							</td>
							<td class="row1" style="text-align:center;width:70px;">
								<?php echo isset($_tmpb_list['LOGIN']) ? $_tmpb_list['LOGIN'] : ''; ?>
							</td>
							<td class="row1" style="text-align:center;width:70px;">
								<?php echo isset($_tmpb_list['TIME']) ? $_tmpb_list['TIME'] : ''; ?>
							</td>
						</tr>
						<?php } ?>
											
						<?php if( !isset($_tmpb_alert['empty']) || !is_array($_tmpb_alert['empty']) ) $_tmpb_alert['empty'] = array();
foreach($_tmpb_alert['empty'] as $empty_key => $empty_value) {
$_tmpb_empty = &$_tmpb_alert['empty'][$empty_key]; ?>		
						<tr>
							<td class="row2" colspan="6" style="text-align:center;">
								<?php echo isset($_tmpb_empty['NO_ALERT']) ? $_tmpb_empty['NO_ALERT'] : ''; ?>
							</td>
						</tr>		
						<?php } ?>					
						<tr>
							<td class="row2" colspan="6">
								&nbsp;<input type="submit" value="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" class="submit" />
							</td>
						</tr>
					</table>
				</form>
				<?php } ?>

				

				<?php if( !isset($this->_block['alert_id']) || !is_array($this->_block['alert_id']) ) $this->_block['alert_id'] = array();
foreach($this->_block['alert_id'] as $alert_id_key => $alert_id_value) {
$_tmpb_alert_id = &$this->_block['alert_id'][$alert_id_key]; ?>
				<table class="module_table">
					<tr>
						<td class="row1" style="width:180px;">
							<?php echo isset($_tmpb_alert_id['L_TITLE']) ? $_tmpb_alert_id['L_TITLE'] : ''; ?>
						</td>
						<td class="row2">
							<?php echo isset($_tmpb_alert_id['TITLE']) ? $_tmpb_alert_id['TITLE'] : ''; ?>
						</td>
					</tr>
					<tr>
						<td class="row1">
							<?php echo isset($_tmpb_alert_id['L_TOPIC']) ? $_tmpb_alert_id['L_TOPIC'] : ''; ?>
						</td>
						<td class="row2">
							<?php echo isset($_tmpb_alert_id['TOPIC']) ? $_tmpb_alert_id['TOPIC'] : ''; ?>
						</td>
					</tr>
					<tr>
						<td class="row1">
							<?php echo isset($_tmpb_alert_id['L_CAT']) ? $_tmpb_alert_id['L_CAT'] : ''; ?>
						</td>
						<td class="row2">
							<?php echo isset($_tmpb_alert_id['CAT']) ? $_tmpb_alert_id['CAT'] : ''; ?>
						</td>
					</tr>
					<tr>
						<td class="row1">
							<?php echo isset($_tmpb_alert_id['L_CONTENTS']) ? $_tmpb_alert_id['L_CONTENTS'] : ''; ?>
						</td>
						<td class="row2">
							<?php echo isset($_tmpb_alert_id['CONTENTS']) ? $_tmpb_alert_id['CONTENTS'] : ''; ?>
						</td>
					</tr>
					<tr>
						<td class="row1">
							<?php echo isset($_tmpb_alert_id['L_STATUS']) ? $_tmpb_alert_id['L_STATUS'] : ''; ?>
						</td>
						<td class="row2">
							<span style="float:left;"><?php echo isset($_tmpb_alert_id['STATUS']) ? $_tmpb_alert_id['STATUS'] : ''; ?></span>
							<span style="float:right;"><?php echo isset($_tmpb_alert_id['CHANGE_STATUS']) ? $_tmpb_alert_id['CHANGE_STATUS'] : ''; ?></span>
						</td>
					</tr>
					<tr>
						<td class="row1">
							<?php echo isset($_tmpb_alert_id['L_LOGIN']) ? $_tmpb_alert_id['L_LOGIN'] : ''; ?>
						</td>
						<td class="row2">
							<?php echo isset($_tmpb_alert_id['LOGIN']) ? $_tmpb_alert_id['LOGIN'] : ''; ?>
						</td>
					</tr>
					<tr>
						<td class="row1">
							<?php echo isset($_tmpb_alert_id['L_TIME']) ? $_tmpb_alert_id['L_TIME'] : ''; ?>
						</td>
						<td class="row2">
							<?php echo isset($_tmpb_alert_id['TIME']) ? $_tmpb_alert_id['TIME'] : ''; ?>
						</td>
					</tr>
				</table>					
				<?php } ?>

				

				<?php if( !isset($this->_block['alert_id_not_auth']) || !is_array($this->_block['alert_id_not_auth']) ) $this->_block['alert_id_not_auth'] = array();
foreach($this->_block['alert_id_not_auth'] as $alert_id_not_auth_key => $alert_id_not_auth_value) {
$_tmpb_alert_id_not_auth = &$this->_block['alert_id_not_auth'][$alert_id_not_auth_key]; ?>
				<table class="module_table">
					<tr>
						<th colspan="2">
							<?php echo isset($this->_var['L_MODERATION_FORUM']) ? $this->_var['L_MODERATION_FORUM'] : ''; ?> :: <?php echo isset($this->_var['L_ALERT_MANAGEMENT']) ? $this->_var['L_ALERT_MANAGEMENT'] : ''; ?> 
						</th>
					</tr>				
					<tr>
						<td style="text-align:center;" colspan="2">
							<br /><br />
							<?php echo isset($_tmpb_alert_id_not_auth['NO_ALERT']) ? $_tmpb_alert_id_not_auth['NO_ALERT'] : ''; ?>
							<br /><br />
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
						var filename = "xmlhttprequest.php?<?php echo isset($this->_var['U_XMLHTTPREQUEST']) ? $this->_var['U_XMLHTTPREQUEST'] : ''; ?>=1";
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

				<form action="moderation_forum<?php echo isset($this->_var['U_ACTION']) ? $this->_var['U_ACTION'] : ''; ?>" method="post">
				<table class="module_table">
					<tr>
						<td style="vertical-align: top;text-align: center;" class="row2">
							<?php echo isset($this->_var['L_SEARCH_MEMBER']) ? $this->_var['L_SEARCH_MEMBER'] : ''; ?>: <input type="text" size="20" maxlenght="25" id="login" value="" name="login" class="text" />			
							<script type="text/javascript">
							<!--								
								document.write('<input value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">');
							-->
							</script>
							
							<noscript>
								<input type="submit" name="search_member" value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" class="submit" />
							</noscript>
						</td>
						<td class="row2">
							<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
						</td>
					</tr>
				</table>
				<table class="module_table">
					<tr>			
						<th style="width:25%;"><?php echo isset($this->_var['L_LOGIN']) ? $this->_var['L_LOGIN'] : ''; ?></th>
						<th style="width:25%;"><?php echo isset($this->_var['L_INFO']) ? $this->_var['L_INFO'] : ''; ?></th>
						<th style="width:25%;"><?php echo isset($this->_var['L_ACTION_USER']) ? $this->_var['L_ACTION_USER'] : ''; ?></th>
						<th style="width:25%;"><?php echo isset($this->_var['L_PM']) ? $this->_var['L_PM'] : ''; ?></th>
					</tr>
				</table>
				<table class="module_table">	
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
						<td class="row1" style="text-align:center;">
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
				<form action="moderation_forum<?php echo isset($_tmpb_user_info['U_ACTION_INFO']) ? $_tmpb_user_info['U_ACTION_INFO'] : ''; ?>" method="post">		
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
								<label for="new_info"><?php echo isset($this->_var['L_INFO_EXPLAIN']) ? $this->_var['L_INFO_EXPLAIN'] : ''; ?></label>
							</td>
							<td class="row2">
								<span id="action_info"><?php echo isset($_tmpb_user_info['INFO']) ? $_tmpb_user_info['INFO'] : ''; ?></span>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<label><select name="new_info" id="new_info" onchange="change_textarea_level(this.options[this.selectedIndex].value, <?php echo isset($_tmpb_user_info['REGEX']) ? $_tmpb_user_info['REGEX'] : ''; ?>)">
									<?php echo isset($_tmpb_user_info['SELECT']) ? $_tmpb_user_info['SELECT'] : ''; ?>
								</select></label>	
								<input type="submit" name="valid_user" value="<?php echo isset($this->_var['L_CHANGE_INFO']) ? $this->_var['L_CHANGE_INFO'] : ''; ?>" class="submit" />				
							</td>
						</tr>
					</table>
				</form>				
				<?php } ?>


				
			</div>	
			<div class="module_bottom_r"></div>	
			<div class="module_bottom_l"></div>
			<div class="module_bottom text_strong">
				&bull; <a href="../member/moderation_panel.php"><?php echo isset($this->_var['L_MODERATION_PANEL']) ? $this->_var['L_MODERATION_PANEL'] : ''; ?></a> :: <a href="index.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_FORUM_INDEX']) ? $this->_var['L_FORUM_INDEX'] : ''; ?></a> &raquo; <a href="moderation_forum.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_MODERATION_FORUM']) ? $this->_var['L_MODERATION_FORUM'] : ''; ?></a><?php echo isset($this->_var['U_MODERATION_FORUM_ACTION']) ? $this->_var['U_MODERATION_FORUM_ACTION'] : ''; ?>
			</div>	
		</div>
		
		<?php $this->tpl_include('forum_bottom'); ?>
		