		<script type="text/javascript">
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

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_ADD_SMILEY}</li>
				<li>
					<a href="admin_smileys.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/smileys.png" alt="" /></a>
					<br />
					<a href="admin_smileys.php" class="quick_link">{L_SMILEY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_smileys_add.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/smileys.png" alt="" /></a>
					<br />
					<a href="admin_smileys_add.php" class="quick_link">{L_ADD_SMILEY}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #
			
			<form action="admin_smileys.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
				<legend>{L_EDIT_SMILEY}</legend>
					<dl>
						<dt><label for="code_smiley">* {L_SMILEY_CODE}</label></dt>
						<dd><label><input type="text" size="40" maxlength="100" id="code_smiley" name="code_smiley" value="{CODE_SMILEY}" class="text" /> </label></dd>
					</dl>
					<dl>
						<dt><label for="code_smiley">* {L_SMILEY_AVAILABLE}</label></dt>
						<dd><label>
							<select name="url_smiley" id="url_smiley" onchange="img_smiley(this.options[selectedIndex].value)">
								{SMILEY_OPTIONS}					
							</select>
							<span id="img_smiley">{IMG_SMILEY}</span>
						</label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_DELETE}</legend>
					<input type="hidden" name="idsmiley" value="{IDSMILEY}" />
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>
		