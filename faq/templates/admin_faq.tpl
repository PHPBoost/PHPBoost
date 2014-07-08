		<script>
		<!--
		function check_form(){
			if(document.getElementById('num_cols').value == "")
			{
				alert("{L_REQUIRE_NBR_COLS}");
				return false;
			}

			return true;
		}
		-->
		</script>
		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_FAQ_MANAGEMENT}</li>
				<li>
					<a href="admin_faq_cats.php"><img src="faq.png" alt="{L_CATS_MANAGEMENT}" /></a>
					<br />
					<a href="admin_faq_cats.php" class="quick-link">{L_CATS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_faq_cats.php?new=1"><img src="faq.png" alt="{L_ADD_CAT}" /></a>
					<br />
					<a href="admin_faq_cats.php?new=1" class="quick-link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_faq.php?p=1"><img src="faq.png" alt="{L_QUESTIONS_LIST}" /></a>
					<br />
					<a href="admin_faq.php?p=1" class="quick-link">{L_QUESTIONS_LIST}</a>
				</li>
				<li>
					<a href="management.php?new=1"><img src="faq.png" alt="{L_ADD_QUESTION}" /></a>
					<br />
					<a href="management.php?new=1" class="quick-link">{L_ADD_QUESTION}</a>
				</li>
				<li>
					<a href="admin_faq.php"><img src="faq.png" alt="{L_CONFIG_MANAGEMENT}" /></a>
					<br />
					<a href="admin_faq.php" class="quick-link">{L_CONFIG_MANAGEMENT}</a>
				</li>
			</ul>
		</div>

		<div id="admin-contents">
		
			# INCLUDE message_helper #
		
			<form action="admin_faq.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_CONFIG_MANAGEMENT}</legend>
					<div class="form-element">
						<label for="num_cols">
							* {L_NBR_COLS}
							<span class="field-description">{L_NBR_COLS_EXPLAIN}</span>
						</label>
						<div class="form-field">
							<input type="text" size="3" maxlength="3" id="num_cols" name="num_cols" value="{NUM_COLS}">
						</div>
					</div>
					<div class="form-element">
						<label for="display_mode">
							{L_DISPLAY_MODE}
							<span class="field-description">{L_DISPLAY_MODE_EXPLAIN}</span>
						</label>
						<div class="form-field">
							<select name="display_mode" id="display_mode">
								<option value="block"{SELECTED_BLOCK}>{L_BLOCKS}</option>
								<option value="inline"{SELECTED_INLINE}>{L_INLINE}</option>
							</select>
						</div>	
					</div>
					<div class="form-element-textarea">
						<label for="contents">{L_ROOT_DESCRIPTION}</label>
						{KERNEL_EDITOR}
						<textarea id="contents" rows="15" cols="40" name="root_contents">{ROOT_CAT_DESCRIPTION}</textarea>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{L_AUTH}</legend>
					<p>{L_AUTH_EXPLAIN}</p>
					<div class="form-element">
						
							<label for="auth_read">{L_AUTH_READ}</label>
						
						<div class="form-field">
							{AUTH_READ}
						</div>	
					</div>
					<div class="form-element">
						
							<label for="auth_read">{L_AUTH_WRITE}</label>
						
						<div class="form-field">
							{AUTH_WRITE}
						</div>
					</div>
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<button type="submit" name="submit" value="true" class="submit">{L_SUBMIT}</button>
				</fieldset>
			</form>
		</div>