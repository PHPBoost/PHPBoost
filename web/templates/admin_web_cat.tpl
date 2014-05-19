		<script>
		<!--
		function change_icon(id, img_path)
		{
			if( document.getElementById(id + 'icon_img') )
				document.getElementById(id + 'icon_img').innerHTML = '<img src="' + img_path + '" alt="" class="valign-middle" />';
		}
		-->
		</script>

		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_WEB_MANAGEMENT}</li>
				<li>
					<a href="admin_web_cat.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php" class="quick-link">{L_WEB_CAT}</a>
				</li>
				<li>
					<a href="admin_web_cat.php#add_cat"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php#add_cat" class="quick-link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_web.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web.php" class="quick-link">{L_WEB_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_web_add.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_add.php" class="quick-link">{L_WEB_ADD}</a>
				</li>
				<li>
					<a href="admin_web_config.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_config.php" class="quick-link">{L_WEB_CONFIG}</a>
				</li>
			</ul>
		</div> 
		
		<div id="admin-contents">
			# INCLUDE message_helper #
			
			<form action="admin_web_cat.php?token={TOKEN}" method="post">
				<table>
					<caption>{L_WEB_CAT}</caption>
					<thead> 
						<tr>
							<th>* {L_NAME}</th>
							<th>{L_DESC}</th>
							<th>{L_ICON}</th>
							<th>{L_RANK}</th>
							<th>{L_STATUS}</th>
							<th>{L_POSITION}</th>
							<th>{L_DELETE}</th>
						</tr>
					</thead>
					<tbody>
						# START cat #
						<tr>
							<td> 
								<span id="w{cat.IDCAT}"></span>
								<input type="text" maxlength="60" size="20" name="{cat.IDCAT}cat" value="{cat.CAT}">
							</td>
							<td> 
								<textarea rows="3" cols="40" name="{cat.IDCAT}contents">{cat.CONTENTS}</textarea> 
							</td>
							<td> 
								<select name="{cat.IDCAT}icon" onchange="change_icon('{cat.IDCAT}', this.options[this.selectedIndex].value)" onclick="change_icon('{cat.IDCAT}', this.options[this.selectedIndex].value)">
									{cat.IMG_LIST}
								</select>
								<span id="{cat.IDCAT}icon_img">{cat.IMG_ICON}</span>
								<br />
								<span class="smaller">{L_OR_DIRECT_PATH}</span> <input type="text" name="{cat.IDCAT}icon_path" value="{cat.IMG_PATH}" onblur="if( this.value != '' )change_icon('{cat.IDCAT}', this.value)">
							</td>
							<td> 
								<select name="{cat.IDCAT}secure">
									# START cat.select_secure #
										{cat.select_secure.RANK}
									# END cat.select_secure #
								</select>
							</td>
							<td class="input-radio">
								<label><input type="radio" {cat.ACTIV_ENABLED} name="{cat.IDCAT}aprob" value="1"> {L_ACTIV}</label>
								<label><input type="radio" {cat.ACTIV_DISABLED} name="{cat.IDCAT}aprob" value="0"> {L_UNACTIV}</label>
							</td>
							<td>
								{cat.TOP}
								{cat.BOTTOM}
							</td>
							<td>
								<a href="admin_web_cat.php?del=1&amp;id={cat.IDCAT}&amp;token={TOKEN}" class="fa fa-delete" data-confirmation="delete-element"></a>
							</td>
						</tr>
						# END cat #
						# IF NOT C_CATS #
						<tr>
							<td colspan="7">{L_NO_CAT}</td>
						</tr>
						# ENDIF #
					</tbody>
				</table>
				<fieldset class="fieldset-submit" style="margin:0px;">
					<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true" class="submit">{L_UPDATE}</button>
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>	
			</form>
			
			<div id="add_cat"></div>
			<form action="admin_web_cat.php?token={TOKEN}" method="post" class="fieldset-content">
				<fieldset>
					<legend>{L_ADD_CAT}</legend>
					<div class="form-element">
						<label for="cat">* {L_NAME}</label>
						<div class="form-field">
							<input type="text" size="25" maxlength="60" name="cat" id="cat">
						</div>
					</div>
					<div class="form-element">
						<label for="cat">{L_DESC}</label>
						<div class="form-field">
							<textarea rows="3" cols="20" name="contents" id="contents"></textarea>
						</div>
					</div>
					<div class="form-element">
						<label for="icon">{L_ICON}</label>
						<div class="form-field">
							<label><select name="icon" id="icon" onchange="change_icon('', this.options[this.selectedIndex].value)" onclick="change_icon('', this.options[this.selectedIndex].value)">
								{IMG_LIST}
							</select></label>
							<span id="icon_img">{IMG_ICON}</span>
							<br />
							<label><span class="smaller">{L_OR_DIRECT_PATH}</span> <input type="text" name="icon_path" id="icon_path" value="" onblur="if( this.value != '' )change_icon('', this.value)"></label>
						</div>
					</div>
					<div class="form-element">
						<label for="contents">{L_RANK}</label>
						<div class="form-field"><label>
							<select name="secure">
								<option value="-1" selected="selected">{L_GUEST}</option>
								<option value="0">{L_USER}</option>
								<option value="1">{L_MODO}</option>
								<option value="2">{L_ADMIN}</option>
							</select>
						</label></div>
					</div>
					<div class="form-element">
						<label for="contents">{L_ACTIVATION}</label>
						<div class="form-field">
							<label><input type="radio" name="aprob" checked="checked" value="1"> {L_ACTIV}</label>
							<label><input type="radio" name="aprob" value="0"> {L_UNACTIV}</label>
						</div>
					</div>
				</fieldset>	
				
				<fieldset class="fieldset-submit">
					<legend>{L_ADD}</legend>
					<button type="submit" name="add" value="true" class="submit">{L_ADD}</button>
					<button type="reset" value="true">{L_RESET}</button>				
				</fieldset>	
			</form>
		</div>
		