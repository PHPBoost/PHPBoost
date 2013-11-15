		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('max_files_number_per_page').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('columns_number').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('notation_scale').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			return true;
		}
		-->
		</script>

		# INCLUDE admin_download_menu #
		
		<div id="admin_contents">
			<form action="admin_download_config.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend>{L_DOWNLOAD_CONFIG}</legend>
					<div class="form-element">
						<label for="max_files_number_per_page">* {L_MAX_FILES_NUMBER_PER_PAGE}</label>
						<div class="form-field"><label><input type="text" size="3" maxlength="3" id="max_files_number_per_page" name="max_files_number_per_page" value="{MAX_FILES_NUMBER_PER_PAGE}"></label></div>
					</div>
					<div class="form-element">
						<label for="columns_number">* {L_COLUMNS_NUMBER}</label>
						<div class="form-field"><label><input type="text" size="3" maxlength="3" id="columns_number" name="columns_number" value="{COLUMNS_NUMBER}"></label></div>
					</div>
					<div class="form-element">
						<label for="notation_scale">* {L_NOTATION_SCALE}</label>
						<div class="form-field"><label><input type="text" size="2" maxlength="2" id="notation_scale" name="note_max" value="{NOTATION_SCALE}"></label></div>
					</div>
					<div class="form-element-textarea">
						<label for="contents">
							{L_ROOT_DESCRIPTION}
						</label>
						{KERNEL_EDITOR}
						<textarea id="contents" rows="15" cols="40" name="root_contents">{DESCRIPTION}</textarea>
					</div>
				</fieldset>

				<fieldset>
					<legend>
						{L_GLOBAL_AUTH}
					</legend>
					{L_GLOBAL_AUTH_EXPLAIN}
					<div class="form-element">
						
							<label>
								{L_READ_AUTH}
							</label>
						
						<div class="form-field">
							{READ_AUTH}
						</div>					
					</div>
					<div class="form-element">
						
							<label>
								{L_WRITE_AUTH}
							</label>
						
						<div class="form-field">
							{WRITE_AUTH}
						</div>					
					</div>
					<div class="form-element">
						
							<label>
								{L_CONTRIBUTION_AUTH}
							</label>
						
						<div class="form-field">
							{CONTRIBUTION_AUTH}
						</div>					
					</div>
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend>{L_DELETE}</legend>
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					&nbsp;&nbsp; 
					<button type="reset" value="true">{L_RESET}</button>				
				</fieldset>	
			</form>
				
		</div>	
		