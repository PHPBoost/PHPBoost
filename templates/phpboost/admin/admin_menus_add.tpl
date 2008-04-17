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
					<a href="admin_menus_php"><img src="../templates/{THEME}/images/admin/menus.png" alt="" /></a>
					<br />
					<a href="admin_menus_php" class="quick_link">{L_ADD_MENUS}</a>
				</li>
			</ul>
		</div>
			
		<div id="admin_contents">
			<form action="admin_menus_add.php" method="post" class="fieldset_content">
				<fieldset> 
					<legend>{L_ACTION_MENUS}</legend>
					<p>{L_EXPLAIN_MENUS}</p>
					# IF C_ADD_MENU #
					<dl>
						<dt><label for="name">{L_NAME}</label></dt>
						<dd><label><input type="text" size="18" name="name" id="name" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="location">* {L_LOCATION}</label></dt>
						<dd><label>
							<select name="location" id="location">								
								<option value="header">{L_HEADER}</option>
								<option value="subheader">{L_SUB_HEADER}</option>
								<option value="left" selected="selected">{L_LEFT_MENU}</option>	
								<option value="topcentral">{L_TOP_CENTRAL_MENU}</option>	
								<option value="bottomcentral">{L_BOTTOM_CENTRAL_MENU}</option>	
								<option value="right">{L_RIGHT_MENU}</option>	
								<option value="top_footer">{L_TOP_FOOTER}</option>	
								<option value="footer">{L_FOOTER}</option>	
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="activ">{L_STATUS}</label></dt>
						<dd><label>
							<select name="activ" id="activ">								
								<option value="1" selected="selected">{L_ACTIV}</option>
								<option value="0">{L_UNACTIV}</option>					
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="secure">{L_RANK}</label></dt>
						<dd><label>
							<select name="secure">								
								{RANKS}								
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="use_tpl">{L_USE_TPL}</label></dt>
						<dd><label><input type="checkbox" size="18" name="use_tpl" id="use_tpl" checked="checked" class="text" /></label></dd>
					</dl>
					<label>
						# INCLUDE handle_bbcode #	
						<textarea type="text" rows="15" cols="5" id="contents" name="contents"></textarea> 
					</label>
					# ENDIF #		
					
					# IF C_EDIT_MENU #
					<dl>
						<dt><label for="name">{L_NAME}</label></dt>
						<dd><label><input type="text" size="18" value="{NAME}" name="name" id="name" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="location">* {L_LOCATION}</label></dt>
						<dd><label>
							<select name="location" id="location">								
								{LOCATIONS}
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="activ">{L_STATUS}</label></dt>
						<dd><label>
							<select name="activ">								
								<option value="1" {ACTIV_ENABLED}>{L_ACTIV}</option>
								<option value="0" {ACTIV_DISABLED}>{L_UNACTIV}</option>					
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="secure">{L_RANK}</label></dt>
						<dd><label>
							<select name="secure">								
								{RANKS}
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="use_tpl">{L_USE_TPL}</label></dt>
						<dd><label><input type="checkbox" size="18" name="use_tpl" id="use_tpl" {USE_TPL} class="text" /></label></dd>
					</dl>
					<label>
						# INCLUDE handle_bbcode #	
						<textarea type="text" rows="15" cols="5" id="contents" name="contents">{CONTENTS}</textarea> 
						<br />
					</label>
					# ENDIF #	
				</fieldset>		
			
				<fieldset class="fieldset_submit">
					<legend>{L_ACTION}</legend>
					<input type="hidden" name="action" value="{ACTION}" />
					<input type="hidden" name="id" value="{IDMENU}" />
					<input type="submit" name="valid" value="{L_ACTION}" class="submit" />
					<input type="reset" value="{L_RESET}" class="reset" />					
				</fieldset>	
			</form>
		</div>