		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_MAINTAIN}</li>
				<li>
					<a href="admin_maintain.php"><img src="../templates/{THEME}/images/admin/maintain.png" alt="" /></a>
					<br />
					<a href="admin_maintain.php" class="quick_link">{L_MAINTAIN}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			<form action="admin_maintain.php" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_SET_MAINTAIN}</legend>
					<dl>
						<dt><label for="maintain">{L_SET_MAINTAIN}</label></dt>
						<dd><label>
							<select name="maintain" id="maintain">				
							# START select_maintain #				
								{select_maintain.DELAY}				
							# END select_maintain #				
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="display_delay">{L_MAINTAIN_DELAY}</label></dt>
						<dd><label><input type="radio" {DISPLAY_DELAY_ENABLED} name="display_delay" id="display_delay" value="1" /> {L_YES}</label>
						&nbsp;&nbsp; 
						<label><input type="radio" {DISPLAY_DELAY_DISABLED} name="display_delay" value="0" /> {L_NO}</label></dd>
					</dl>
					<dl>
						<dt><label for="maintain_display_admin">{L_MAINTAIN_DISPLAY_ADMIN}</label></dt>
						<dd><label><input type="radio" {DISPLAY_ADMIN_ENABLED} name="maintain_display_admin" id="maintain_display_admin" value="1" /> {L_YES}</label>
						&nbsp;&nbsp; 
						<label><input type="radio" {DISPLAY_ADMIN_DISABLED} name="maintain_display_admin" value="0" /> {L_NO}</label></dd>
					</dl>
					<label for="contents">{L_MAINTAIN_TEXT}</label>
					<label>
						# INCLUDE handle_bbcode #	
						<textarea type="text" rows="14" cols="20" name="contents" id="contents">{MAINTAIN_CONTENTS}</textarea>
					</label>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					<script type="text/javascript">
					<!--				
					document.write('&nbsp;&nbsp;<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
					-->
					</script>
					&nbsp;&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />		
				</fieldset>	
			</form>	
		</div>
		