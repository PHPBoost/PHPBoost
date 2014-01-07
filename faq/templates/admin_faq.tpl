		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_FAQ_MANAGEMENT}</li>
				<li>
					<a href="admin_faq_cats.php"><img src="faq.png" alt="{L_CATS_MANAGEMENT}" /></a>
					<br />
					<a href="admin_faq_cats.php" class="quick_link">{L_CATS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_faq_cats.php?new=1"><img src="faq.png" alt="{L_ADD_CAT}" /></a>
					<br />
					<a href="admin_faq_cats.php?new=1" class="quick_link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_faq.php?p=1"><img src="faq.png" alt="{L_QUESTIONS_LIST}" /></a>
					<br />
					<a href="admin_faq.php?p=1" class="quick_link">{L_QUESTIONS_LIST}</a>
				</li>
				<li>
					<a href="management.php?new=1"><img src="faq.png" alt="{L_ADD_QUESTION}" /></a>
					<br />
					<a href="management.php?new=1" class="quick_link">{L_ADD_QUESTION}</a>
				</li>
				<li>
					<a href="admin_faq.php"><img src="faq.png" alt="{L_CONFIG_MANAGEMENT}" /></a>
					<br />
					<a href="admin_faq.php" class="quick_link">{L_CONFIG_MANAGEMENT}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
		
			# INCLUDE message_helper #
		
			<form action="admin_faq.php?token={TOKEN}" method="post" class="fieldset-content">
				<fieldset>
					<legend>{L_CONFIG_MANAGEMENT}</legend>
					<div class="form-element">
						<label for="faq_name">
							{L_FAQ_NAME}
							<span class="field-description">{L_FAQ_NAME_EXPLAIN}</span>
						</label>
						<div class="form-field">
							<input type="text" size="65" maxlength="100" id="faq_name" name="faq_name" value="{FAQ_NAME}">
						</div>	
					</div>
					<div class="form-element">
						<label for="num_cols">
							{L_NBR_COLS}
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
					<button type="submit" name="submit" value="true">{L_SUBMIT}</button>
				</fieldset>
			</form>
		</div>		