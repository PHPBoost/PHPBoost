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
		function check_select_multiple_ext(id, status)
		{
			for(var i = 0; i < {NBR_EXTENSIONS}; i++)
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
					<a href="admin_files.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/files.png" alt="" /></a>
					<br />
					<a href="admin_files.php" class="quick_link">{L_FILES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_files_config.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/files.png" alt="" /></a>
					<br />
					<a href="admin_files_config.php" class="quick_link">{L_CONFIG_FILES}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">	
			<form action="admin_files_config.php" method="post" onsubmit="return check_form_conf();" class="fieldset-content">
				<fieldset>
					<legend>{L_CONFIG_FILES}</legend>
					<div class="form-element"> 
						<label>* {L_AUTH_FILES}</label>
						<div class="form-field">{AUTH_FILES}</div>
					</div>
					<div class="form-element"> 
						<label for="size_limit">* {L_SIZE_LIMIT}</label>
						<div class="form-field"><input type="text" size="3" id="size_limit" name="size_limit" value="{SIZE_LIMIT}"> {L_MB}</div>
					</div>
					<div class="form-element"> 
						<label for="bandwidth_protect">
							{L_BANDWIDTH_PROTECT}
							<span class="field-description">{L_BANDWIDTH_PROTECT_EXPLAIN}</span>
						</label>
						<div class="form-field">
							<label><input type="radio" name="bandwidth_protect" id="bandwidth_protect" value="1" {BANDWIDTH_PROTECT_ENABLED}> {L_ACTIV}</label>
							<label><input type="radio" name="bandwidth_protect" value="0" {BANDWIDTH_PROTECT_DISABLED}> {L_UNACTIV}</label>
						</div>
					</div>
					<div class="form-element"> 
						<label>* {L_AUTH_EXTENSIONS}</label>
						<div class="form-field">
							<select id="auth_extensions" name="auth_extensions[]" size="12" multiple="multiple">
								{AUTH_EXTENSIONS}
							</select>	
							<br />
							<a class="small" href="javascript:check_select_multiple_ext('ext', true);">{L_SELECT_ALL}</a>/<a class="small" href="javascript:check_select_multiple_ext('ext', false);">{L_SELECT_NONE}</a>							
						</div>
					</div>
					<div class="form-element"> 
						<label for="auth_extensions_sup">
							{L_EXTEND_EXTENSIONS}
							<span class="field-description">{L_EXTEND_EXTENSIONS_EXPLAIN}</span>
						</label>
						<div class="form-field">
							<input type="text" size="35" id="auth_extensions_sup" name="auth_extensions_sup" value="{AUTH_EXTENSIONS_SUP}">
						</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					<button type="reset" value="true">{L_RESET}</button>
					<input type="hidden" name="token" value="{TOKEN}">					
				</fieldset>		
			</form>
		</div>
		