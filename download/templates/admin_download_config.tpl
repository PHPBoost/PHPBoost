		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('nbr_download_max').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('nbr_column').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			if(document.getElementById('note_max').value == "") {
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
					<legend>
						{L_GLOBAL_AUTH}
					</legend>
					{L_GLOBAL_AUTH_EXPLAIN}
					<dl>
						<dt>
							<label>
								{L_READ_AUTH}
							</label>
						</dt>
						<dd>
							{READ_AUTH}
						</dd>					
					</dl>
					<dl>
						<dt>
							<label>
								{L_WRITE_AUTH}
							</label>
						</dt>
						<dd>
							{WRITE_AUTH}
						</dd>					
					</dl>
					<dl>
						<dt>
							<label>
								{L_CONTRIBUTION_AUTH}
							</label>
						</dt>
						<dd>
							{CONTRIBUTION_AUTH}
						</dd>					
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_DOWNLOAD_CONFIG}</legend>
					<dl>
						<dt><label for="nbr_file_max">* {L_NBR_FILE_MAX}</label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="nbr_file_max" name="nbr_file_max" value="{NBR_FILE_MAX}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_column">* {L_NBR_COLUMN_MAX}</label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="nbr_column" name="nbr_column" value="{NBR_COLUMN}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="note_max">* {L_NOTE_MAX}</label></dt>
						<dd><label><input type="text" size="2" maxlength="2" id="note_max" name="note_max" value="{NOTE_MAX}" class="text" /></label></dd>
					</dl>
					<label for="contents">
						{L_ROOT_DESCRIPTION}
					</label>
					{KERNEL_EDITOR}
					<textarea id="contents" rows="15" cols="40" name="root_contents">{DESCRIPTION}</textarea>
				</fieldset>
								
				<fieldset class="fieldset_submit">
					<legend>{L_DELETE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>	
		