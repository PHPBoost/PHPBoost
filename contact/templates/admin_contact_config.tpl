		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_CONTACT}</li>
				<li>
					<a href="admin_contact.php"><img src="contact.png" alt="" /></a>
					<br />
					<a href="admin_contact.php" class="quick_link">{L_CONTACT_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
		
			<form action="admin_contact.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend>{L_CONTACT_CONFIG}</legend>
					<dl>
						<dt><label for="contact_verifcode">{L_CONTACT_VERIFCODE}</label><br /><span>{L_CONTACT_VERIFCODE_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" {CONTACT_VERIFCODE_ENABLED} name="contact_verifcode" id="contact_verifcode" value="1" />	{L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {CONTACT_VERIFCODE_DISABLED} name="contact_verifcode" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="contact_difficulty_verifcode">{L_CAPTCHA_DIFFICULTY}</label></dt>
						<dd>
							<label>
								<select name="contact_difficulty_verifcode" id="contact_difficulty_verifcode">
									# START difficulty #
									<option value="{difficulty.VALUE}" {difficulty.SELECTED}>{difficulty.VALUE}</option>
									# END difficulty #
								</select>         
							</label>
						</dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />			
				</fieldset>
			</form>
		</div>
		