		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_DEL_ENTRY}");
		}
		-->
		</script>

		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('name').value == "") {
				alert("{L_REQUIRE_NAME}");
				return false;
		    }
			if(document.getElementById('url').value == "") {
				alert("{L_REQUIRE_URL}");
				return false;
		    }
			if(document.getElementById('urlsep').value == "") {
				alert("{L_REQUIRE_URL}");
				return false;
		    }
			
			return true;
		}

		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_LINK_CONFIGURATION}</li>
				<li>
					<a href="admin_links.php"><img src="links.png" alt="" /></a>
					<br />
					<a href="admin_links.php" class="quick_link">{L_LINK_CONFIGURATION}</a>
				</li>
				<li>
					<a href="admin_links.php?add=1"><img src="links.png" alt="" /></a>
					<br />
					<a href="admin_links.php?add=1" class="quick_link">{L_LINK_ADD}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			# START management #
			
			<form action="admin_links.php" method="post">
				<table class="module_table">
					<tr> 
						<th colspan="6">
							{L_LINK_CONFIGURATION}
						</th>
					</tr>
					<tr> 
						<td class="row1">
							{L_NAME}
						</td>
						<td class="row1">
							{L_PATH}
						</td>
						<td class="row1">
							{L_VISIBLE}
						</td>
						<td class="row1">
							{L_RANK}
						</td>
						<td class="row1">
							{L_POSITION}
						</td>
						<td class="row1">
							{L_DEL}
						</td>
					</tr>
					<tr>
						# START links #
							<tr>
								<td class="row2" style="text-align:center;"> 
									<span id="l{management.links.IDCAT}"></span>
									<input type="text" maxlength="40" size="17" name="{management.links.IDCAT}name" value="{management.links.NAME}" class="text" />
								</td>
								<td class="row2" style="text-align:center;">
									{management.links.URL}
								</td>
								
								# START activ #
									<td class="row2" style="text-align:center;"> 
										<input type="radio" {management.links.activ.ACTIV_ENABLED} name="{management.links.IDCAT}activ" value="1" /> {L_YES}&nbsp;&nbsp; 
										<input type="radio" {management.links.activ.ACTIV_DISABLED} name="{management.links.IDCAT}activ" value="0" /> {L_NO}
									</td>
								# END activ #
									
								
								<td class="row2" style="text-align:center;"> 
									<select name="{management.links.IDCAT}secure">						
										# START select #	
											{management.links.select.RANK}
										# END select #							
									</select>
								</td>
								<td class="row2" style="text-align:center;">
									{management.links.TOP}
									{management.links.BOTTOM}
								</td>
								<td class="row2" style="text-align:center;">
									<a href="admin_links.php?del=1&amp;id={management.links.IDCAT}" onClick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="" /></a>
								</td>
							</tr>
						# END links #
					</tr>
				</table>
				
				<br /><br />
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />			
				</fieldset>
			</form>
			
			# END management #
			
			
			# START add #
			# START error_handler #
			<span id="errorh"></span>
			<div class="{add.error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
				<img src="../templates/{THEME}/images/{add.error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {add.error_handler.L_ERROR}
				<br />	
			</div>
			<br />	
			# END error_handler #
			<form action="admin_links.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_LINK_ADD}</legend>
					<dl>
						<dt><label for="name">* {L_NAME}</label></dt>
						<dd><label><input type="text" maxlength="60" size="20" id="name" name="name" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="url">* {L_PATH}</label></dt>
						<dd><label><input type="text" maxlength="255" size="30" id="url" name="url" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="activ">{L_VISIBLE}</label></dt>
						<dd>						
							<label><input type="radio" name="activ" id="activ" checked="checked" value="1" /> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" name="activ" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="secure">* {L_RANK}</label></dt>
						<dd><label>
							<select name="secure" id="secure">
								<option value="-1" selected="selected">{L_GUEST}</option>
								<option value="0" >{L_MEMBER}</option>
								<option value="1" >{L_MODO}</option>
								<option value="2" >{L_ADMIN}</option>
							</select>
						</label></dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_ADD}</legend>
					<input type="submit" name="add" value="{L_ADD}" class="submit" />
					&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />			
				</fieldset>
			</form>

			<form action="admin_links.php" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_ADD_SEP}</legend>
					<dl>
						<dt><label for="name2">* {L_NAME}</label></dt>
						<dd><label><input type="text" maxlength="60" size="20" name="name2" id="name2" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label>{L_IMG}</label></dt>
						<dd><label><img src="../templates/{THEME}/images/row1.png" alt="" /></label></dd>
					</dl>
					<dl>
						<dt><label for="activ_add">{L_VISIBLE}</label></dt>
						<dd>						
							<label><input type="radio" name="activ" id="activ_add" checked="checked" value="1" /> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" name="activ" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="secure_add">* {L_RANK}</label></dt>
						<dd><label>
							<select name="secure" id="secure_add">
								<option value="-1" selected="selected">{L_GUEST}</option>
								<option value="0" >{L_MEMBER}</option>
								<option value="1" >{L_MODO}</option>
								<option value="2" >{L_ADMIN}</option>
							</select>
						</label></dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_ADD}</legend>
					<input type="submit" name="sepa" value="{L_ADD}" class="submit" />
					&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />			
				</fieldset>	
			</form>
			
			# END add #
		</div>
		