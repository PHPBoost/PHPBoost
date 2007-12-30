		<script type="text/javascript">
		<!--
		function check_form_add_mbr(){
			if(document.getElementById('login_mbr').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_PSEUDO']) ? $this->_var['L_REQUIRE_PSEUDO'] : ''; ?>");
				return false;
		    }
			return true;
		}

		function check_form(){
			if(document.getElementById('name').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_NAME']) ? $this->_var['L_REQUIRE_NAME'] : ''; ?>");
				return false;
		    }
			return true;
		}

		function Confirm() {
		return confirm("<?php echo isset($this->_var['L_CONFIRM_DEL_MEMBER_GROUP']) ? $this->_var['L_CONFIRM_DEL_MEMBER_GROUP'] : ''; ?>");
		}

		function img_change(url)
		{
			if (document.images)
			{
				document.images['img_group'].src = "../images/group/" + url;
			}
		}

		function XMLHttpRequest_search()
		{
			var xhr_object = null;
			var data = null;
			var filename = "../includes/xmlhttprequest.php?group_member=1";
			var login = document.getElementById("login_mbr").value;
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
						show_div("xmlhttprequest_result_search");
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

		function hide_div2(divID)
		{
			if( document.getElementById && document.getElementById(divID) ) //Pour les navigateurs récents
			{
				Pdiv = document.getElementById(divID);
				if( Pdiv.className == divID ) Pdiv.className = divID + '2';
			}
			else if( document.all && document.all[divID] ) //Pour les vieilles versions
			{
				Pdiv = document.all[divID];
				if( Pdiv.className == divID ) Pdiv.className = divID + '2';
			}
			else if( document.layers && document.layers[divID] ) //Pour les très vieilles versions
			{
				Pdiv = document.layers[divID];
				if( Pdiv.className == divID ) Pdiv.className = divID + '2';
			}
		}

		function insert_XMLHttpRequest(login)
		{
			document.getElementById("login_mbr").value = login;
		}

		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_GROUPS_MANAGEMENT']) ? $this->_var['L_GROUPS_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_groups.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php" class="quick_link"><?php echo isset($this->_var['L_GROUPS_MANAGEMENT']) ? $this->_var['L_GROUPS_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_groups.php?add=1"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php?add=1" class="quick_link"><?php echo isset($this->_var['L_ADD_GROUPS']) ? $this->_var['L_ADD_GROUPS'] : ''; ?></a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">		
			<?php if( !isset($this->_block['edit_group']) || !is_array($this->_block['edit_group']) ) $this->_block['edit_group'] = array();
foreach($this->_block['edit_group'] as $edit_group_key => $edit_group_value) {
$_tmpb_edit_group = &$this->_block['edit_group'][$edit_group_key]; ?>
					
			<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_GROUPS_MANAGEMENT']) ? $this->_var['L_GROUPS_MANAGEMENT'] : ''; ?></legend>
					<dl>
						<dt><label for="name">* <?php echo isset($this->_var['L_NAME']) ? $this->_var['L_NAME'] : ''; ?></label></dt>
						<dd><label><input type="text" size="25" id="name" name="name" value="<?php echo isset($_tmpb_edit_group['NAME']) ? $_tmpb_edit_group['NAME'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label><?php echo isset($this->_var['L_AUTH_FLOOD']) ? $this->_var['L_AUTH_FLOOD'] : ''; ?></label></dt>
						<dd><label><input type="radio" <?php echo isset($this->_var['AUTH_FLOOD_ENABLED']) ? $this->_var['AUTH_FLOOD_ENABLED'] : ''; ?> name="auth_flood" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?>
						</label>&nbsp;&nbsp; 
						<label><input type="radio" <?php echo isset($this->_var['AUTH_FLOOD_DISABLED']) ? $this->_var['AUTH_FLOOD_DISABLED'] : ''; ?> name="auth_flood" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label><?php echo isset($this->_var['L_PM_NO_LIMIT']) ? $this->_var['L_PM_NO_LIMIT'] : ''; ?></label></dt>
						<dd><label><input type="radio" <?php echo isset($this->_var['PM_NO_LIMIT_ENABLED']) ? $this->_var['PM_NO_LIMIT_ENABLED'] : ''; ?> name="pm_no_limit" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?>
						</label>&nbsp;&nbsp; 
						<label><input type="radio" <?php echo isset($this->_var['PM_NO_LIMIT_DISABLED']) ? $this->_var['PM_NO_LIMIT_DISABLED'] : ''; ?> name="pm_no_limit" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label><?php echo isset($this->_var['L_DATA_NO_LIMIT']) ? $this->_var['L_DATA_NO_LIMIT'] : ''; ?></label></dt>
						<dd><label><input type="radio" <?php echo isset($this->_var['DATA_NO_LIMIT_ENABLED']) ? $this->_var['DATA_NO_LIMIT_ENABLED'] : ''; ?> name="data_no_limit" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?>
						</label>&nbsp;&nbsp; 
						<label><input type="radio" <?php echo isset($this->_var['DATA_NO_LIMIT_DISABLED']) ? $this->_var['DATA_NO_LIMIT_DISABLED'] : ''; ?> name="data_no_limit" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label for="img"><?php echo isset($this->_var['L_IMG_ASSOC_GROUP']) ? $this->_var['L_IMG_ASSOC_GROUP'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_IMG_ASSOC_GROUP_EXPLAIN']) ? $this->_var['L_IMG_ASSOC_GROUP_EXPLAIN'] : ''; ?></span></dt>
						<dd><label>
							<select name="img" id="img" onChange="img_change(this.options[selectedIndex].value)">
								<?php if( !isset($_tmpb_edit_group['select']) || !is_array($_tmpb_edit_group['select']) ) $_tmpb_edit_group['select'] = array();
foreach($_tmpb_edit_group['select'] as $select_key => $select_value) {
$_tmpb_select = &$_tmpb_edit_group['select'][$select_key]; ?>
									<?php echo isset($_tmpb_select['IMG_GROUP']) ? $_tmpb_select['IMG_GROUP'] : ''; ?>
								<?php } ?>
							</select>				
							<img src="../images/group/<?php echo isset($_tmpb_edit_group['IMG']) ? $_tmpb_edit_group['IMG'] : ''; ?>" name="img_group" alt="" style="vertical-align:middle;" /></label></dd>
					</dl>
				</fieldset>						
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
					<input type="hidden" name="id" value="<?php echo isset($_tmpb_edit_group['GROUP_ID']) ? $_tmpb_edit_group['GROUP_ID'] : ''; ?>" class="update" />
					<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
				</fieldset>
			</form>
			
			<?php if( !isset($this->_block['error_handler']) || !is_array($this->_block['error_handler']) ) $this->_block['error_handler'] = array();
foreach($this->_block['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$this->_block['error_handler'][$error_handler_key]; ?>
				<div class="error_handler_position">
						<span id="errorh"></span>
						<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
							<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
							<br />	
						</div>
				</div>
			<?php } ?>
			
			<form action="admin_groups.php?id=<?php echo isset($_tmpb_edit_group['GROUP_ID']) ? $_tmpb_edit_group['GROUP_ID'] : ''; ?>" method="post" onsubmit="return check_form_add_mbr();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_ADD_MBR_GROUP']) ? $this->_var['L_ADD_MBR_GROUP'] : ''; ?></legend>
					<dl>
						<dt><label for="login_mbr">* <?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?></label></dt>
						<dd><label>
							<input type="text" size="20 maxlenght="25" id="login_mbr" value="<?php echo isset($this->_var['LOGIN']) ? $this->_var['LOGIN'] : ''; ?>" name="login_mbr" class="text" />
							<script type="text/javascript">
							<!--								
								document.write('<input value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">');
							-->
							</script>
							<div id="xmlhttprequest_result_search" onblur="this.style.display = 'none'" style="display:none;" class="xmlhttprequest_result_search"></div>
						</label></dd>
					</dl>
				</fieldset>	
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_ADD']) ? $this->_var['L_ADD'] : ''; ?></legend>
					<input type="submit" name="add_mbr" value="<?php echo isset($this->_var['L_ADD']) ? $this->_var['L_ADD'] : ''; ?>" class="submit" />
				</fieldset>
			</form>
			
			<table class="module_table">
				<th colspan="2">
					<?php echo isset($this->_var['L_MBR_GROUP']) ? $this->_var['L_MBR_GROUP'] : ''; ?><span id="add"></span>
				</th>				
				<tr> 
					<td class="row1">
						* <?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?>
					</td>
					<td style="width:50%;" class="row1"> 
						<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>
					</td>
				</tr>
				
				<?php if( !isset($_tmpb_edit_group['member']) || !is_array($_tmpb_edit_group['member']) ) $_tmpb_edit_group['member'] = array();
foreach($_tmpb_edit_group['member'] as $member_key => $member_value) {
$_tmpb_member = &$_tmpb_edit_group['member'][$member_key]; ?>
				<tr> 
					<td class="row2">
						<a href="../member/member<?php echo isset($_tmpb_member['U_USER_ID']) ? $_tmpb_member['U_USER_ID'] : ''; ?>"><?php echo isset($_tmpb_member['LOGIN']) ? $_tmpb_member['LOGIN'] : ''; ?></a>
					</td>
					<td class="row2">
						<a href="admin_groups.php?del_mbr=1&amp;id=<?php echo isset($_tmpb_edit_group['GROUP_ID']) ? $_tmpb_edit_group['GROUP_ID'] : ''; ?>&amp;user_id=<?php echo isset($_tmpb_member['USER_ID']) ? $_tmpb_member['USER_ID'] : ''; ?>" onClick="javascript:return Confirm();"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png" alt="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" title="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" /></a>
					</td>
				</tr>
				<?php } ?>
			</table>
					
			
			<p style="text-align: center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></p>
			<?php } ?>

			
			
			<?php if( !isset($this->_block['add_group']) || !is_array($this->_block['add_group']) ) $this->_block['add_group'] = array();
foreach($this->_block['add_group'] as $add_group_key => $add_group_value) {
$_tmpb_add_group = &$this->_block['add_group'][$add_group_key]; ?>
			<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_ADD_GROUPS']) ? $this->_var['L_ADD_GROUPS'] : ''; ?></legend>
					<dl>
						<dt><label for="name">* <?php echo isset($this->_var['L_NAME']) ? $this->_var['L_NAME'] : ''; ?></label></dt>
						<dd><label><input type="text" maxlength="25" size="25" id="name" name="name" value="" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label><?php echo isset($this->_var['L_AUTH_FLOOD']) ? $this->_var['L_AUTH_FLOOD'] : ''; ?></label></dt>
						<dd><label><input type="radio" name="auth_flood" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></label>
						&nbsp;&nbsp; 
						<label><input type="radio" checked="checked" name="auth_flood" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label><?php echo isset($this->_var['L_PM_NO_LIMIT']) ? $this->_var['L_PM_NO_LIMIT'] : ''; ?></label></dt>
						<dd><label><input type="radio" name="pm_no_limit" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></label>
						&nbsp;&nbsp; 
						<label><input type="radio" checked="checked" name="pm_no_limit" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label><?php echo isset($this->_var['L_DATA_NO_LIMIT']) ? $this->_var['L_DATA_NO_LIMIT'] : ''; ?></label></dt>
						<dd><label><input type="radio" name="data_no_limit" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></label>
						&nbsp;&nbsp; 
						<label><input type="radio" checked="checked" name="data_no_limit" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label for="id"><?php echo isset($this->_var['L_IMG_ASSOC_GROUP']) ? $this->_var['L_IMG_ASSOC_GROUP'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_IMG_ASSOC_GROUP_EXPLAIN']) ? $this->_var['L_IMG_ASSOC_GROUP_EXPLAIN'] : ''; ?></span></dt>
						<dd><label>
							<select name="img" id="img" onChange="img_change(this.options[selectedIndex].value)">
								<?php if( !isset($_tmpb_add_group['select']) || !is_array($_tmpb_add_group['select']) ) $_tmpb_add_group['select'] = array();
foreach($_tmpb_add_group['select'] as $select_key => $select_value) {
$_tmpb_select = &$_tmpb_add_group['select'][$select_key]; ?>
									<?php echo isset($_tmpb_select['IMG_GROUP']) ? $_tmpb_select['IMG_GROUP'] : ''; ?>
								<?php } ?>
							</select>				
							<img src="../images/group/<?php echo isset($_tmpb_add_group['IMG']) ? $_tmpb_add_group['IMG'] : ''; ?>" name="img_group" alt="" style="vertical-align:middle" />
						</label></dd>
					</dl>				
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_ADD']) ? $this->_var['L_ADD'] : ''; ?></legend>
					<input type="hidden" name="add" value="1" />
					<input type="submit" name="valid" value="<?php echo isset($this->_var['L_ADD']) ? $this->_var['L_ADD'] : ''; ?>" class="submit" />
				</fieldset>
			</form>
			<?php } ?>
		</div>
		