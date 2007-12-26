		<script type="text/javascript">
		<!--
		function check_form_add_mbr(){
			if(document.getElementById('login_mbr').value == "") {
				alert("{L_REQUIRE_PSEUDO}");
				return false;
		    }
			return true;
		}

		function check_form(){
			if(document.getElementById('name').value == "") {
				alert("{L_REQUIRE_NAME}");
				return false;
		    }
			return true;
		}

		function Confirm() {
		return confirm("{L_CONFIRM_DEL_MEMBER_GROUP}");
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
				alert("{L_REQUIRE_LOGIN}");
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
				<li class="title_menu">{L_GROUPS_MANAGEMENT}</li>
				<li>
					<a href="admin_groups.php"><img src="../templates/{THEME}/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php" class="quick_link">{L_GROUPS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_groups.php?add=1"><img src="../templates/{THEME}/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php?add=1" class="quick_link">{L_ADD_GROUPS}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">		
			# START edit_group #
					
			<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_GROUPS_MANAGEMENT}</legend>
					<dl>
						<dt><label for="name">* {L_NAME}</label></dt>
						<dd><label><input type="text" size="25" id="name" name="name" value="{edit_group.NAME}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label>{L_AUTH_FLOOD}</label></dt>
						<dd><label><input type="radio" {AUTH_FLOOD_ENABLED} name="auth_flood" value="1" /> {L_YES}
						</label>&nbsp;&nbsp; 
						<label><input type="radio" {AUTH_FLOOD_DISABLED} name="auth_flood" value="0" /> {L_NO}</label></dd>
					</dl>
					<dl>
						<dt><label>{L_PM_NO_LIMIT}</label></dt>
						<dd><label><input type="radio" {PM_NO_LIMIT_ENABLED} name="pm_no_limit" value="1" /> {L_YES}
						</label>&nbsp;&nbsp; 
						<label><input type="radio" {PM_NO_LIMIT_DISABLED} name="pm_no_limit" value="0" /> {L_NO}</label></dd>
					</dl>
					<dl>
						<dt><label>{L_DATA_NO_LIMIT}</label></dt>
						<dd><label><input type="radio" {DATA_NO_LIMIT_ENABLED} name="data_no_limit" value="1" /> {L_YES}
						</label>&nbsp;&nbsp; 
						<label><input type="radio" {DATA_NO_LIMIT_DISABLED} name="data_no_limit" value="0" /> {L_NO}</label></dd>
					</dl>
					<dl>
						<dt><label for="img">{L_IMG_ASSOC_GROUP}</label><br /><span>{L_IMG_ASSOC_GROUP_EXPLAIN}</span></dt>
						<dd><label>
							<select name="img" id="img" onChange="img_change(this.options[selectedIndex].value)">
								# START select #
									{edit_group.select.IMG_GROUP}
								# END select #
							</select>				
							<img src="../images/group/{edit_group.IMG}" name="img_group" alt="" style="vertical-align:middle;" /></label></dd>
					</dl>
				</fieldset>						
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{edit_group.GROUP_ID}" class="update" />
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>
			
			# START error_handler #
				<div class="error_handler_position">
						<span id="errorh"></span>
						<div class="{edit_group.error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
							<img src="../templates/{THEME}/images/{edit_group.error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {edit_group.error_handler.L_ERROR}
							<br />	
						</div>
				</div>
			# END error_handler #
			
			<form action="admin_groups.php?id={edit_group.GROUP_ID}" method="post" onsubmit="return check_form_add_mbr();" class="fieldset_content">
				<fieldset>
					<legend>{L_ADD_MBR_GROUP}</legend>
					<dl>
						<dt><label for="login_mbr">* {L_PSEUDO}</label></dt>
						<dd><label>
							<input type="text" size="20 maxlenght="25" id="login_mbr" value="{LOGIN}" name="login_mbr" class="text" />
							<script type="text/javascript">
							<!--								
								document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">');
							-->
							</script>
							<div id="xmlhttprequest_result_search" onblur="this.style.display = 'none'" style="display:none;" class="xmlhttprequest_result_search"></div>
						</label></dd>
					</dl>
				</fieldset>	
				<fieldset class="fieldset_submit">
					<legend>{L_ADD}</legend>
					<input type="submit" name="add_mbr" value="{L_ADD}" class="submit" />
				</fieldset>
			</form>
			
			<table class="module_table">
				<th colspan="2">
					{L_MBR_GROUP}<span id="add"></span>
				</th>				
				<tr> 
					<td class="row1">
						* {L_PSEUDO}
					</td>
					<td style="width:50%;" class="row1"> 
						{L_DELETE}
					</td>
				</tr>
				
				# START member #
				<tr> 
					<td class="row2">
						<a href="../member/member{edit_group.member.U_USER_ID}">{edit_group.member.LOGIN}</a>
					</td>
					<td class="row2">
						<a href="admin_groups.php?del_mbr=1&amp;id={edit_group.GROUP_ID}&amp;user_id={edit_group.member.USER_ID}" onClick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
				</tr>
				# END member #
			</table>
					
			
			<p style="text-align: center;">{PAGINATION}</p>
			# END edit_group #

			
			
			# START add_group #
			<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_ADD_GROUPS}</legend>
					<dl>
						<dt><label for="name">* {L_NAME}</label></dt>
						<dd><label><input type="text" maxlength="25" size="25" id="name" name="name" value="" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label>{L_AUTH_FLOOD}</label></dt>
						<dd><label><input type="radio" name="auth_flood" value="1" /> {L_YES}</label>
						&nbsp;&nbsp; 
						<label><input type="radio" checked="checked" name="auth_flood" value="0" /> {L_NO}</label></dd>
					</dl>
					<dl>
						<dt><label>{L_PM_NO_LIMIT}</label></dt>
						<dd><label><input type="radio" name="pm_no_limit" value="1" /> {L_YES}</label>
						&nbsp;&nbsp; 
						<label><input type="radio" checked="checked" name="pm_no_limit" value="0" /> {L_NO}</label></dd>
					</dl>
					<dl>
						<dt><label>{L_DATA_NO_LIMIT}</label></dt>
						<dd><label><input type="radio" name="data_no_limit" value="1" /> {L_YES}</label>
						&nbsp;&nbsp; 
						<label><input type="radio" checked="checked" name="data_no_limit" value="0" /> {L_NO}</label></dd>
					</dl>
					<dl>
						<dt><label for="id">{L_IMG_ASSOC_GROUP}</label><br /><span>{L_IMG_ASSOC_GROUP_EXPLAIN}</span></dt>
						<dd><label>
							<select name="img" id="img" onChange="img_change(this.options[selectedIndex].value)">
								# START select #
									{add_group.select.IMG_GROUP}
								# END select #
							</select>				
							<img src="../images/group/{add_group.IMG}" name="img_group" alt="" style="vertical-align:middle" />
						</label></dd>
					</dl>				
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend>{L_ADD}</legend>
					<input type="hidden" name="add" value="1" />
					<input type="submit" name="valid" value="{L_ADD}" class="submit" />
				</fieldset>
			</form>
			# END add_group #
		</div>
		