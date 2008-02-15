		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('size_limit').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			return true;
		}
		function check_select_multiple_ranks(id, start)
		{
			var i;
			for(i = start; i <= 3; i++)
			{
				if( document.getElementById(id + i) )
					document.getElementById(id + i).selected = true;
			}
		}
		function check_select_multiple(id, status)
		{
			var i;
			
			for(i = 0; i < {NBR_EXTENSIONS}; i++)
			{	
				if( document.getElementById(id + i) )
					document.getElementById(id + i).selected = status;			
			}
		}			
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_FILES_MANAGEMENT}</li>
				<li>
					<a href="admin_files.php"><img src="../templates/{THEME}/images/admin/files.png" alt="" /></a>
					<br />
					<a href="admin_files.php" class="quick_link">{L_FILES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_files_config.php"><img src="../templates/{THEME}/images/admin/files.png" alt="" /></a>
					<br />
					<a href="admin_files_config.php" class="quick_link">{L_CONFIG_FILES}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">	
			<form action="admin_files_config.php" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend>{L_CONFIG_FILES}</legend>
					<dl> 
						<dt><label>* {L_AUTH_FILES}</label></dt>
						<dd><label>{AUTH_FILES}</label></dd>
					<dl>
					<dl> 
						<dt><label for="size_limit">* {L_SIZE_LIMIT}</label></dt>
						<dd><label><input type="text" size="3" id="size_limit" name="size_limit" value="{SIZE_LIMIT}" class="text" /> {L_MB}</label></dd>
					</dl>
					<dl> 
						<dt><label for="bandwidth_protect">{L_BANDWIDTH_PROTECT}</label><br /><span>{L_BANDWIDTH_PROTECT_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" name="bandwidth_protect" id="bandwidth_protect" value="1"{BANDWIDTH_PROTECT_ENABLED} /> {L_ACTIV}</label>
							&nbsp;
							<label><input type="radio" name="bandwidth_protect" value="0"{BANDWIDTH_PROTECT_DISABLED} /> {L_UNACTIV}</label>
						</dd>
					</dl>
					<dl> 
						<dt><label>* {L_AUTH_EXTENSIONS}</label></dt>
						<dd><label>
							<select id="auth_extensions" name="auth_extensions[]" size="25" multiple="multiple">
								{AUTH_EXTENSIONS}
							</select>	
							<br />
							<a href="javascript:check_select_multiple('ext', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('ext', false);">{L_SELECT_NONE}</a>							
						</label></dd>
					<dl>
					<dl> 
						<dt><label for="auth_extensions_sup">{L_EXTEND_EXTENSIONS}</label><br /><span>{L_EXTEND_EXTENSIONS_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="35" id="auth_extensions_sup" name="auth_extensions_sup" value="{AUTH_EXTENSIONS_SUP}" class="text" /></label></dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					<input type="reset" value="{L_RESET}" class="reset" />					
				</fieldset>		
			</form>
		</div>
		