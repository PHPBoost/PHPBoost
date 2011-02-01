		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/bbcode.js"></script>
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
			return confirm("{L_CONFIRM_DEL_USER_GROUP}");
		}
		function img_change(url)
		{
			if( document.images && url != '' )
			{	
				document.getElementById('img_group_change').style.display = 'inline';
				document.getElementById('img_group_change').src = "{PATH_TO_ROOT}/images/group/" + url;
			}
			else
				document.getElementById('img_group_change').style.display = 'none';
		}
		function XMLHttpRequest_search()
		{
			var login = document.getElementById("login").value;
			if( login != "" )
			{
				if( document.getElementById('loading_groups') )
					document.getElementById('loading_groups').innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
				
				data = 'login=' + login;
				var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/kernel/framework/ajax/member_xmlhttprequest.php?token={TOKEN}&insert_member=1');
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
					{
						document.getElementById("xmlhttprequest_result_search").innerHTML = xhr_object.responseText;
						show_div("xmlhttprequest_result_search");
						if( document.getElementById('loading_groups') )
							document.getElementById('loading_groups').innerHTML = '';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					{	
						if( document.getElementById('loading_groups') )
							document.getElementById('loading_groups').innerHTML = '';
					}
				}
				xmlhttprequest_sender(xhr_object, data);
			}	
			else
				alert("{L_REQUIRE_LOGIN}");
		}

		function hide_div2(divID)
		{
			if( document.getElementById && document.getElementById(divID) ) //Pour les navigateurs r�cents
			{
				Pdiv = document.getElementById(divID);
				if( Pdiv.className == divID ) Pdiv.className = divID + '2';
			}
			else if( document.all && document.all[divID] ) //Pour les vieilles versions
			{
				Pdiv = document.all[divID];
				if( Pdiv.className == divID ) Pdiv.className = divID + '2';
			}
			else if( document.layers && document.layers[divID] ) //Pour les tr�s vieilles versions
			{
				Pdiv = document.layers[divID];
				if( Pdiv.className == divID ) Pdiv.className = divID + '2';
			}
		}
		function insert_color(color)
		{
			document.getElementById("color_group").value = color.replace(/#/g, '');
		}
		function bbcode_color()
		{
			var i;
			var br;
			var contents;
			var color = new Array(
			'#000000', '#433026', '#333300', '#003300', '#003366', '#000080', '#333399', '#333333',
			'#800000', '#ffa500', '#808000', '#008000', '#008080', '#0000ff', '#666699', '#808080',
			'#ff0000', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#ACA899',
			'#ffc0cb', '#FFCC00', '#ffff00', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
			'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#CC99FF', '#FFFFFF');							
			
			contents = '<table style="border-collapse:collapse;margin:auto;"><tr>';
			for(i = 0; i < 40; i++)
			{
				br = (i+1) % 8;
				br = (br == 0 && i != 0 && i < 39) ? '</tr><tr>' : '';
				contents += '<td style="padding:2px;"><a onclick="javascript:insert_color(\'' + color[i] + '\');" class="bbcode_hover"><span style="background:' + color[i] + ';padding:0px 4px;border:1px solid #ACA899;">&nbsp;</span></a></td>' + br;								
			}
			document.getElementById("color_group_list").innerHTML = contents + '</tr></table>';
		}	
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_GROUPS_MANAGEMENT}</li>
				<li>
					<a href="admin_groups.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php" class="quick_link">{L_GROUPS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_groups.php?add=1"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php?add=1" class="quick_link">{L_ADD_GROUPS}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">		
			# IF C_EDIT_GROUP #					
			<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_GROUPS_MANAGEMENT}</legend>
					<dl>
						<dt><label for="name">* {L_NAME}</label></dt>
						<dd><label><input type="text" size="25" id="name" name="name" value="{NAME}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="auth_flood">{L_AUTH_FLOOD}</label></dt>
						<dd><label><input type="radio" {AUTH_FLOOD_ENABLED} name="auth_flood" id="auth_flood" value="1" /> {L_YES}
						</label>&nbsp;&nbsp; 
						<label><input type="radio" {AUTH_FLOOD_DISABLED} name="auth_flood" value="0" class="text" /> {L_NO}</label></dd>
					</dl>
					<dl>
						<dt><label for="pm_group_limit">{L_PM_GROUP_LIMIT}</label><br /><span>{L_PM_GROUP_LIMIT_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" name="pm_group_limit" id="pm_group_limit" value="{PM_GROUP_LIMIT}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="data_group_limit">{L_DATA_GROUP_LIMIT}</label><br /><span>{L_DATA_GROUP_LIMIT_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" name="data_group_limit" id="data_group_limit" value="{DATA_GROUP_LIMIT}" class="text" /> {L_MB}</label></dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="color_group">{L_COLOR_GROUP}</label><br /><span>{L_COLOR_GROUP_EXPLAIN}</span></dt>
						<dd>#<input type="text" size="7" name="color_group" id="color_group" value="{COLOR_GROUP}" class="text" />
							<a href="javascript:bbcode_color();bb_display_block('1', '');" onmouseout="bb_hide_block('1', '', 0);" class="bbcode_hover" title="{L_BB_COLOR}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>	
							<div style="position:relative;z-index:100;display:none;" id="bb_block1">
								<div id="color_group_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('1', '', 1);" onmouseout="bb_hide_block('1', '', 0);">
								</div>
							</div>
						</dd>
					</dl>
					<dl>
						<dt><label for="img_group">{L_IMG_ASSOC_GROUP}</label><br /><span>{L_IMG_ASSOC_GROUP_EXPLAIN}</span></dt>
						<dd>
							<label>
								<select name="img" id="img_group" onchange="img_change(this.options[selectedIndex].value)">
									{IMG_GROUPS}
								</select>
								<img src="{PATH_TO_ROOT}/images/group/{IMG}" id="img_group_change" alt="" class="valign_middle" style="display:none" />
							</label>
						</dd>
					</dl>
				</fieldset>						
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{GROUP_ID}" class="update" />
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
					<input type="hidden" name="token" value="{TOKEN}" />
				</fieldset>
			</form>
			
			# INCLUDE message_helper #
			
			<form action="admin_groups.php?id={GROUP_ID}" method="post" onsubmit="return check_form_add_mbr();" class="fieldset_content">
				<fieldset>
					<legend>{L_ADD_MBR_GROUP}</legend>
					<dl>
						<dt><label for="login">* {L_PSEUDO}</label></dt>
						<dd>
							<input type="text" size="20" maxlength="25" id="login" value="{LOGIN}" name="login_mbr" class="text" /> 
							<span id="loading_groups"></span>
							<script type="text/javascript">
							<!--								
								document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search();" type="button" class="submit">');
							-->
							</script>
							<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
						</dd>
					</dl>
				</fieldset>	
				<fieldset class="fieldset_submit">
					<legend>{L_ADD}</legend>
					<input type="submit" name="add_mbr" value="{L_ADD}" class="submit" />
					<input type="hidden" value="{TOKEN}" name="token" />
				</fieldset>
			</form>
			
			<table class="module_table">
				<tr> 
					<th colspan="2">
						{L_MBR_GROUP}<span id="add"></span>
					</th>				
				</tr>
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
						<a href="{PATH_TO_ROOT}/member/member{member.U_USER_ID}">{member.LOGIN}</a>
					</td>
					<td class="row2">
						<a href="admin_groups.php?del_mbr=1&amp;id={GROUP_ID}&amp;user_id={member.USER_ID}&amp;token={TOKEN}" onclick="javascript:return Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
				</tr>
				# END member #
			</table>
					
			<p style="text-align: center;">{PAGINATION}</p>
			# ENDIF #
			
			
			# IF C_ADD_GROUP #
			<form action="admin_groups.php?add=1" method="post" enctype="multipart/form-data" class="fieldset_content">				
				<fieldset>
				<legend>{L_UPLOAD_GROUPS}</legend>						
					<dl>
						<dt><label for="upload_groups">{L_UPLOAD_GROUPS}</label><br />{L_UPLOAD_FORMAT}</dt>
						<dd><label>
							<input type="hidden" name="max_file_size" value="2000000" />
							<input type="file" id="upload_groups" name="upload_groups" size="30" class="file" />
						</label></dd>
					</dl>
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend>{L_UPLOAD}</legend>
					<input type="submit" value="{L_UPLOAD}" class="submit" />
					<input type="hidden" value="{TOKEN}" name="token" />
				</fieldset>
			</form>
			
			
			<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_ADD_GROUPS}</legend>
					<dl>
						<dt><label for="name">* {L_NAME}</label></dt>
						<dd><label><input type="text" maxlength="25" size="25" id="name" name="name" value="" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="auth_flood">{L_AUTH_FLOOD}</label></dt>
						<dd><label><input type="radio" name="auth_flood" id="auth_flood" checked="checked" value="1" /> {L_YES}</label>
						&nbsp;&nbsp; 
						<label><input type="radio" name="auth_flood" value="0" /> {L_NO}</label></dd>
					</dl>
					<dl>
						<dt><label for="pm_group_limit">{L_PM_GROUP_LIMIT}</label><br /><span>{L_PM_GROUP_LIMIT_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" name="pm_group_limit" id="pm_group_limit" value="75" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="data_group_limit">{L_DATA_GROUP_LIMIT}</label><br /><span>{L_DATA_GROUP_LIMIT_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" name="data_group_limit" id="data_group_limit" value="5" class="text" /> {L_MB}</label></dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="color_group">{L_COLOR_GROUP}</label><br /><span>{L_COLOR_GROUP_EXPLAIN}</span></dt>
						<dd>#<input type="text" size="7" name="color_group" id="color_group" value="{COLOR_GROUP}" class="text" />
							<a href="javascript:bbcode_color();bb_display_block('1', '');" onmouseout="bb_hide_block('1', '', 0);" class="bbcode_hover" title="{L_BB_COLOR}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>	
							<div style="position:relative;z-index:100;display:none;" id="bb_block1">
								<div id="color_group_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('1', '', 1);" onmouseout="bb_hide_block('1', '', 0);">
								</div>
							</div>
						</dd>
					</dl>
					<dl>
						<dt><label for="img_group">{L_IMG_ASSOC_GROUP}</label><br /><span>{L_IMG_ASSOC_GROUP_EXPLAIN}</span></dt>
						<dd><label>
							<select name="img" id="img_group" onchange="img_change(this.options[selectedIndex].value)">
								{IMG_GROUPS}
							</select>				
							<img src="{PATH_TO_ROOT}/images/group/{IMG}" id="img_group_change" alt="" class="valign_middle" style="display:none" />
						</label></dd>
					</dl>				
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend>{L_ADD}</legend>
					<input type="hidden" name="add" value="1" />
					<input type="submit" name="valid" value="{L_ADD}" class="submit" />
					<input type="hidden" value="{TOKEN}" name="token" />
				</fieldset>
			</form>
			# ENDIF #
		</div>
		