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
			<form action="admin_menus_add.php" method="post" class="fieldset_content">
				<fieldset> 
					<legend>{L_ACTION_MENUS}</legend>
					<p>{L_EXPLAIN_MENUS}</p>
					# START add #
					<dl>
						<dt><label for="name">{L_NAME}</label></dt>
						<dd><label><input type="text" size="18" name="name" id="name" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="activ">{L_STATUS}</label></dt>
						<dd><label>
							<select name="activ">								
								<option value="1" selected="selected">{L_ACTIV}</option>
								<option value="0">{L_UNACTIV}</option>					
							</select>
						</label></dd>
					</dl>
					<label>
						# INCLUDE handle_bbcode #	
						<textarea type="text" rows="15" cols="5" id="contents" name="contents"></textarea> 
					</label>
					# END add #		
					
					# START edit #
					<dl>
						<dt><label for="name">{L_NAME}</label></dt>
						<dd><label><input type="text" size="18" value="{edit.NAME}" name="name" id="name" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="activ">{L_STATUS}</label></dt>
						<dd><label>
							<select name="activ">								
								<option value="1" {edit.ACTIV_ENABLED}>{L_ACTIV}</option>
								<option value="0" {edit.ACTIV_DISABLED}>{L_UNACTIV}</option>					
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="secure">{L_RANK}</label></dt>
						<dd><label>
							<select name="secure">								
								# START edit.select #	
								{edit.select.RANK}
								# END edit.select #									
							</select>
						</label></dd>
					</dl>
					<label>
						# INCLUDE handle_bbcode #	
						<textarea type="text" rows="15" cols="5" id="contents" name="contents">{edit.CONTENTS}</textarea> 
						<br />
					</label>
					# END edit #	
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