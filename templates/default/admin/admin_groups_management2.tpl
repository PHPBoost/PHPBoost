		<script>
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
		
		function load_autocompleter() {
			jQuery("#login").autocomplete({
				serviceUrl: "{PATH_TO_ROOT}/kernel/framework/ajax/dispatcher.php?url=/users_autocomplete&token={TOKEN}",
				paramName: 'value',
				showNoSuggestionNotice: true,
				noSuggestionNotice: ${escapejs(LangLoader::get_message('no_result', 'main'))},
				onSelect: function (suggestion) {
					jQuery("#login").val(jQuery(suggestion.value).html());
				},
				formatResult: function (suggestion, currentValue) { 
					return suggestion.value;
				}
			});
		}
		</script>

		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_GROUPS_MANAGEMENT}</li>
				<li>
					<a href="admin_groups.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php" class="quick-link">{L_GROUPS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_groups.php?add=1"><img src="{PATH_TO_ROOT}/templates/default/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php?add=1" class="quick-link">{L_ADD_GROUPS}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin-contents">
			# IF C_EDIT_GROUP #
			<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_GROUPS_MANAGEMENT}</legend>
					<div class="form-element">
						<label for="name">* {L_NAME}</label>
						<div class="form-field">
							<label><input type="text" id="name" name="name" value="{NAME}"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="auth_flood">{L_AUTH_FLOOD}</label>
						<div class="form-field">
							<label><input type="radio" {AUTH_FLOOD_ENABLED} name="auth_flood" id="auth_flood" value="1"> {L_YES}</label>
							<label><input type="radio" {AUTH_FLOOD_DISABLED} name="auth_flood" value="0"> {L_NO}</label>
						</div>
					</div>
					<div class="form-element">
						<label for="pm_group_limit">{L_PM_GROUP_LIMIT} <span>{L_PM_GROUP_LIMIT_EXPLAIN}</span></label>
						<div class="form-field">
							<label><input type="number" min="-1" max="100000" name="pm_group_limit" id="pm_group_limit" value="{PM_GROUP_LIMIT}"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="data_group_limit">{L_DATA_GROUP_LIMIT} <span class="field-description">{L_DATA_GROUP_LIMIT_EXPLAIN}</span></label>
						<div class="form-field">
							<label><input type="text" size="3" name="data_group_limit" id="data_group_limit" value="{DATA_GROUP_LIMIT}"> {L_MB}</label>
						</div>
					</div>
					<div class="form-element">
						<label for="color_group">{L_COLOR_GROUP}</label>
						<div class="form-field">
							<label><input type="color" name="color_group" id="color_group" value="{COLOR_GROUP}" pattern="#[A-Fa-f0-9]{6}" placeholder="#000000"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="img_group">{L_IMG_ASSOC_GROUP} <span class="field-description">{L_IMG_ASSOC_GROUP_EXPLAIN}</span></label>
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
					<button type="submit" class="submit" name="valid" value="true">{L_UPDATE}</button>
					<button type="reset" value="true">{L_RESET}</button>
					<input type="hidden" name="token" value="{TOKEN}">
				</fieldset>
			</form>
			
			# INCLUDE message_helper #
			
			<form action="admin_groups.php?id={GROUP_ID}" method="post" onsubmit="return check_form_add_mbr();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_ADD_MBR_GROUP}</legend>
					<div class="form-element">
						<label for="login">* {L_PSEUDO}</label>
						<div class="form-field">
							<input type="text" maxlength="25" name="login_mbr" id="login" value="{LOGIN}" autocomplete="off" onfocus="javascript:load_autocompleter();" />
						</div>
					</div>
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_ADD}</legend>
					<button type="submit" class="submit" name="add_mbr" value="true">{L_ADD}</button>
					<input type="hidden" value="{TOKEN}" name="token">
				</fieldset>
			</form>
			
			
			<table>
				<caption>
					{L_MBR_GROUP}
				</caption>
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
				<tbody>
					# START member #
					<tr> 
						<td>
							<a href="{member.U_PROFILE}" class="{member.LEVEL_CLASS}" # IF member.C_GROUP_COLOR # style="color:{member.GROUP_COLOR}" # ENDIF #>{member.LOGIN}</a>
						</td>
						<td>
							<a href="admin_groups.php?del_mbr=1&amp;id={GROUP_ID}&amp;user_id={member.USER_ID}&amp;token={TOKEN}" class="fa fa-delete" data-confirmation="delete-element"></a>
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
						<label for="upload_groups">{L_UPLOAD_GROUPS} <span class="field-description">{L_UPLOAD_FORMAT}</span></label>
						<div class="form-field"><label>
							<input type="hidden" name="max_file_size" value="2000000">
							<input type="file" id="upload_groups" name="upload_groups" class="file">
						</label></div>
					</div>
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_UPLOAD}</legend>
					<button type="submit" class="submit" name="valid" value="true">{L_UPLOAD}</button>
					<input type="hidden" value="{TOKEN}" name="token">
				</fieldset>
			</form>
			
			<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_ADD_GROUPS}</legend>
					<div class="form-element">
						<label for="name">* {L_NAME}</label>
						<div class="form-field">
							<label><input type="text" maxlength="25" id="name" name="name" value=""></label>
						</div>
					</div>
					<div class="form-element">
						<label for="auth_flood">{L_AUTH_FLOOD}</label>
						<div class="form-field">
							<label><input type="radio" name="auth_flood" id="auth_flood" checked="checked" value="1"> {L_YES}</label> 
							<label><input type="radio" name="auth_flood" value="0"> {L_NO}</label>
						</div>
					</div>
					<div class="form-element">
						<label for="pm_group_limit">{L_PM_GROUP_LIMIT} <span class="field-description">{L_PM_GROUP_LIMIT_EXPLAIN}</span></label>
						<div class="form-field">
							<label><input type="number" min="-1" max="100000" name="pm_group_limit" id="pm_group_limit" value="75"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="data_group_limit">{L_DATA_GROUP_LIMIT} <span class="field-description">{L_DATA_GROUP_LIMIT_EXPLAIN}</span></label>
						<div class="form-field">
							<label><input type="text" size="3" name="data_group_limit" id="data_group_limit" value="5"> {L_MB}</label>
						</div>
					</div>
					<div class="form-element">
						<label for="color_group">{L_COLOR_GROUP}</label>
						<div class="form-field">
							<label><input type="color" name="color_group" id="color_group" value="{COLOR_GROUP}" pattern="#[A-Fa-f0-9]{6}" placeholder="#000000"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="img_group">{L_IMG_ASSOC_GROUP} <span class="field-description">{L_IMG_ASSOC_GROUP_EXPLAIN}</span></label>
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
					<button type="submit" class="submit" name="valid" value="true">{L_ADD}</button>
					<input type="hidden" value="{TOKEN}" name="token">
				</fieldset>
			</form>
			# ENDIF #
		</div>