		<script>
		<!--
		function check_form(){
			if(document.getElementById('code_smiley').value == "") {
				alert("{L_REQUIRE_CODE}");
				return false;
		    }
			if(document.getElementById('url_smiley').value == "" || document.getElementById('url_smiley').value == "--") {
				alert("{L_REQUIRE_URL}");
				return false;
		    }
			
			return true;
		}

		function img_smiley(smiley_url)
		{
			if( document.getElementById('img_smiley') )
				document.getElementById('img_smiley').innerHTML = '<img src="{PATH_TO_ROOT}/images/smileys/' + smiley_url + '" alt="" />';
		}
		-->
		</script>
		
		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_ADD_SMILEY}</li>
				<li>
					<a href="admin_smileys.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/smileys.png" alt="" /></a>
					<br />
					<a href="admin_smileys.php" class="quick-link">{L_SMILEY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_smileys_add.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/smileys.png" alt="" /></a>
					<br />
					<a href="admin_smileys_add.php" class="quick-link">{L_ADD_SMILEY}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin-contents">
			# INCLUDE message_helper #
			
			<form action="admin_smileys_add.php?token={TOKEN}" method="post" enctype="multipart/form-data" class="fieldset-content">
				<fieldset>
					<legend>{L_UPLOAD_SMILEY}</legend>
					<div class="form-element">
						<label for="upload_smiley">{L_EXPLAIN_UPLOAD_IMG}</label>
						<div class="form-field"><label><input type="file" name="upload_smiley" id="upload_smiley" size="30" class="file">
						<input type="hidden" name="max_file_size" value="2000000"></label></div>
					</div>
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_UPLOAD}</legend>
					<button type="submit" class="submit" name="submit" value="true">{L_UPLOAD}</button>
				</fieldset>
			</form>
			
			<form action="admin_smileys_add.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
				<legend>{L_ADD_SMILEY}</legend>
					<div class="form-element">
						<label for="code_smiley">* {L_SMILEY_CODE}</label>
						<div class="form-field"><label><input type="text" size="40" maxlength="100" id="code_smiley" name="code_smiley" value="{CODE_SMILEY}"> </label></div>
					</div>
					<div class="form-element">
						<label for="code_smiley">* {L_SMILEY_AVAILABLE}</label>
						<div class="form-field"><label>
							<select name="url_smiley" id="url_smiley" onchange="img_smiley(this.options[selectedIndex].value)">
								{SMILEY_OPTIONS}
							</select>
							<span id="img_smiley"></span>
						</label></div>
					</div>
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_ADD}</legend>
					<button type="submit" name="add" value="true">{L_ADD}</button>
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
		</div>
		