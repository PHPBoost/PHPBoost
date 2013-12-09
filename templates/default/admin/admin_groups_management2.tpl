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
					document.getElementById('loading_groups').innerHTML = '<i class="icon-spinner icon-spin"></i>';
				
				data = 'login=' + login;
				var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/kernel/framework/ajax/member_xmlhttprequest.php?token={TOKEN}&insert_member=1');
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
					{
						document.getElementById("xmlhttprequest-result-search").innerHTML = xhr_object.responseText;
						show_div("xmlhttprequest-result-search");
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
			'#800000', '#FFA500', '#808000', '#008000', '#008080', '#0000FF', '#666699', '#808080',
			'#FF0000', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#ACA899',
			'#FFC0CB', '#FFCC00', '#FFFF00', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
			'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#E3007B', '#FFFFFF');							
			
			contents = '<table><tr>';
			for(i = 0; i < 40; i++)
			{
				br = (i+1) % 8;
				br = (br == 0 && i != 0 && i < 39) ? '</tr><tr>' : '';
				contents += '<td><a style="background:' + color[i] + ';" onclick="javascript:insert_color(\'' + color[i] + '\');"></a></td>' + br;								
			}
			document.getElementById("color_group_list").innerHTML = contents + '</tr></table>';
		}	
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_GROUPS_MANAGEMENT}</li>
				<li>
					<a href="admin_groups.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php" class="quick_link">{L_GROUPS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_groups.php?add=1"><img src="{PATH_TO_ROOT}/templates/default/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php?add=1" class="quick_link">{L_ADD_GROUPS}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">		
			# IF C_EDIT_GROUP #					
			<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset-content">
				<fieldset>
					<legend>{L_GROUPS_MANAGEMENT}</legend>
					<div class="form-element">
						<label for="name">* {L_NAME}</label>
						<div class="form-field"><label><input type="text" size="25" id="name" name="name" value="{NAME}"></label></div>
					</div>
					<div class="form-element">
						<label for="auth_flood">{L_AUTH_FLOOD}</label>
						<div class="form-field"><label><input type="radio" {AUTH_FLOOD_ENABLED} name="auth_flood" id="auth_flood" value="1"> {L_YES}
						</label>&nbsp;&nbsp; 
						<label><input type="radio" {AUTH_FLOOD_DISABLED} name="auth_flood" value="0"> {L_NO}</label></div>
					</div>
					<div class="form-element">
						<label for="pm_group_limit">{L_PM_GROUP_LIMIT}</label><br /><span>{L_PM_GROUP_LIMIT_EXPLAIN}</span>
						<div class="form-field"><label><input type="text" size="3" name="pm_group_limit" id="pm_group_limit" value="{PM_GROUP_LIMIT}"></label></div>
					</div>
					<div class="form-element">
						<label for="data_group_limit">{L_DATA_GROUP_LIMIT}</label><br /><span>{L_DATA_GROUP_LIMIT_EXPLAIN}</span>
						<div class="form-field"><label><input type="text" size="3" name="data_group_limit" id="data_group_limit" value="{DATA_GROUP_LIMIT}"> {L_MB}</label></div>
					</div>
					<div class="form-element" class="overflow_visible">
						<label for="color_group">{L_COLOR_GROUP}</label><br /><span>{L_COLOR_GROUP_EXPLAIN}</span>
						<div class="form-field">#<input type="text" size="7" name="color_group" id="color_group" value="{COLOR_GROUP}">
							<a href="javascript:bbcode_color();bb_display_block('1', '');" onmouseout="bb_hide_block('1', '', 0);" class="bbcode_hover" title="{L_BB_COLOR}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/color.png" alt="" class="valign-middle" /></a>	
							<div class="color-picker" style="display:none;" id="bb-block1">
								<div id="color_group_list" class="bbcode-block" onmouseover="bb_hide_block('1', '', 1);" onmouseout="bb_hide_block('1', '', 0);">
								</div>
							</div>
						</div>
					</div>
					<div class="form-element">
						<label for="img_group">{L_IMG_ASSOC_GROUP}</label><br /><span>{L_IMG_ASSOC_GROUP_EXPLAIN}</span>
						<div class="form-field">
							<label>
								<select name="img" id="img_group" onchange="img_change(this.options[selectedIndex].value)">
									{IMG_GROUPS}
								</select>
								<img src="{PATH_TO_ROOT}/images/group/{IMG}" id="img_group_change" alt="" class="valign-middle" style="display:none" />
							</label>
						</div>
					</div>
				</fieldset>						
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{GROUP_ID}" class="update">
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					&nbsp;&nbsp; 
					<button type="reset" value="true">{L_RESET}</button>
					<input type="hidden" name="token" value="{TOKEN}">
				</fieldset>
			</form>
			
			# INCLUDE message_helper #
			
			<form action="admin_groups.php?id={GROUP_ID}" method="post" onsubmit="return check_form_add_mbr();" class="fieldset-content">
				<fieldset>
					<legend>{L_ADD_MBR_GROUP}</legend>
					<div class="form-element">
						<label for="login">* {L_PSEUDO}</label>
						<div class="form-field">
							<input type="text" size="20" maxlength="25" id="login" value="{LOGIN}" name="login_mbr"> 
							<span id="loading_groups"></span>
							<script type="text/javascript">
							<!--								
								document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search();" type="button">');
							-->
							</script>
							<div id="xmlhttprequest-result-search" style="display:none;" class="xmlhttprequest-result-search"></div>
						</div>
					</div>
				</fieldset>	
				<fieldset class="fieldset-submit">
					<legend>{L_ADD}</legend>
					<button type="submit" name="add_mbr" value="true">{L_ADD}</button>
					<input type="hidden" value="{TOKEN}" name="token">
				</fieldset>
			</form>
			
			{L_MBR_GROUP}
			<table>
				<thead>
					<tr> 
						<th>
							{L_PSEUDO}
						</th>	
						<th>
							{L_DELETE}
						</th>					
					</tr>
				</thead>
				# IF C_PAGINATION #
				<tfoot>
					<tr>
						<th colspan="2">
							{PAGINATION}
						</th>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START member #
					<tr> 
						<td>
							<a href="{member.U_PROFILE}" class="{member.LEVEL_CLASS}" # IF member.C_GROUP_COLOR # style="color:{member.GROUP_COLOR}" # ENDIF #>{member.LOGIN}</a>
						</td>
						<td>
							<a href="admin_groups.php?del_mbr=1&amp;id={GROUP_ID}&amp;user_id={member.USER_ID}&amp;token={TOKEN}" class="icon-delete" data-confirmation="delete-element"></a>
						</td>
					</tr>
					# END member #
					# IF C_NO_MEMBERS #
					<tr>
						<td colspan="2">
							{NO_MEMBERS}
						</td>
					</tr>
					# ENDIF #
				</tbody>
			</table>
			# ENDIF #
			
			
			# IF C_ADD_GROUP #
			
			# INCLUDE message_helper #
			
			<form action="admin_groups.php?add=1" method="post" enctype="multipart/form-data" class="fieldset-content">				
				<fieldset>
				<legend>{L_UPLOAD_GROUPS}</legend>						
					<div class="form-element">
						<label for="upload_groups">{L_UPLOAD_GROUPS}</label><br />{L_UPLOAD_FORMAT}
						<div class="form-field"><label>
							<input type="hidden" name="max_file_size" value="2000000">
							<input type="file" id="upload_groups" name="upload_groups" size="30" class="file">
						</label></div>
					</div>
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_UPLOAD}</legend>
					<button type="submit" name="" value="true">{L_UPLOAD}</button>
					<input type="hidden" value="{TOKEN}" name="token">
				</fieldset>
			</form>
			
			
			<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset-content">
				<fieldset>
					<legend>{L_ADD_GROUPS}</legend>
					<div class="form-element">
						<label for="name">* {L_NAME}</label>
						<div class="form-field"><label><input type="text" maxlength="25" size="25" id="name" name="name" value=""></label></div>
					</div>
					<div class="form-element">
						<label for="auth_flood">{L_AUTH_FLOOD}</label>
						<div class="form-field"><label><input type="radio" name="auth_flood" id="auth_flood" checked="checked" value="1"> {L_YES}</label>
						&nbsp;&nbsp; 
						<label><input type="radio" name="auth_flood" value="0"> {L_NO}</label></div>
					</div>
					<div class="form-element">
						<label for="pm_group_limit">{L_PM_GROUP_LIMIT}</label><br /><span>{L_PM_GROUP_LIMIT_EXPLAIN}</span>
						<div class="form-field"><label><input type="text" size="3" name="pm_group_limit" id="pm_group_limit" value="75"></label></div>
					</div>
					<div class="form-element">
						<label for="data_group_limit">{L_DATA_GROUP_LIMIT}</label><br /><span>{L_DATA_GROUP_LIMIT_EXPLAIN}</span>
						<div class="form-field"><label><input type="text" size="3" name="data_group_limit" id="data_group_limit" value="5"> {L_MB}</label></div>
					</div>
					<div class="form-element" class="overflow_visible">
						<label for="color_group">{L_COLOR_GROUP}</label><br /><span>{L_COLOR_GROUP_EXPLAIN}</span>
						<div class="form-field">#<input type="text" size="7" name="color_group" id="color_group" value="{COLOR_GROUP}">
							<a href="javascript:bbcode_color();bb_display_block('1', '');" onmouseout="bb_hide_block('1', '', 0);" class="bbcode_hover" title="{L_BB_COLOR}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/color.png" alt="" class="valign-middle" /></a>	
							<div class="color-picker" style="display:none;" id="bb-block1">
								<div id="color_group_list" class="bbcode-block" onmouseover="bb_hide_block('1', '', 1);" onmouseout="bb_hide_block('1', '', 0);">
								</div>
							</div>
						</div>
					</div>
					<div class="form-element">
						<label for="img_group">{L_IMG_ASSOC_GROUP}</label><br /><span>{L_IMG_ASSOC_GROUP_EXPLAIN}</span>
						<div class="form-field"><label>
							<select name="img" id="img_group" onchange="img_change(this.options[selectedIndex].value)">
								{IMG_GROUPS}
							</select>				
							<img src="{PATH_TO_ROOT}/images/group/{IMG}" id="img_group_change" alt="" class="valign-middle" style="display:none" />
						</label></div>
					</div>				
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_ADD}</legend>
					<input type="hidden" name="add" value="1">
					<button type="submit" name="valid" value="true">{L_ADD}</button>
					<input type="hidden" value="{TOKEN}" name="token">
				</fieldset>
			</form>
			# ENDIF #
		</div>
		