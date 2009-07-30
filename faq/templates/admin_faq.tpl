		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_FAQ_MANAGEMENT}</li>
				<li>
					<a href="admin_faq.php"><img src="faq.png" alt="{L_CONFIG_MANAGEMENT}" /></a>
					<br />
					<a href="admin_faq.php" class="quick_link">{L_CONFIG_MANAGEMENT}</a>
				</li>
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
			</ul>
		</div>

		<div id="admin_contents">
		
			# IF C_ERROR_HANDLER #
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				</div>		
			# ENDIF #
		
			<form action="admin_faq.php?token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_CONFIG_MANAGEMENT}</legend>
					<dl>
						<dt>
							<label for="faq_name">{L_FAQ_NAME}</label>
							<br />
							<span class="text_small">{L_FAQ_NAME_EXPLAIN}</span>
						</dt>
						<dd>
							<input type="text" size="65" maxlength="100" id="faq_name" name="faq_name" value="{FAQ_NAME}" class="text" />
						</dd>	
					</dl>
					<dl>
						<dt>
							<label for="num_cols">{L_NBR_COLS}</label>
							<br />
							<span class="text_small">{L_NBR_COLS_EXPLAIN}</span>
						</dt>
						<dd>
							<input type="text" size="3" maxlength="3" id="num_cols" name="num_cols" value="{NUM_COLS}" class="text" />
						</dd>	
					</dl>
					<dl>
						<dt>
							<label for="display_mode">{L_DISPLAY_MODE}</label>
							<br />
							<span class="text_small">{L_DISPLAY_MODE_EXPLAIN}</span>
						</dt>
						<dd>
							<select name="display_mode" id="display_mode">
								<option value="block"{SELECTED_BLOCK}>{L_BLOCKS}</option>
								<option value="inline"{SELECTED_INLINE}>{L_INLINE}</option>
							</select>
						</dd>	
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_AUTH}</legend>
					<p>{L_AUTH_EXPLAIN}</p>
					<dl>
						<dt>
							<label for="auth_read">{L_AUTH_READ}</label>
						</dt>
						<dd>
							{AUTH_READ}
						</dd>	
					</dl>
					<dl>
						<dt>
							<label for="auth_read">{L_AUTH_WRITE}</label>
						</dt>
						<dd>
							{AUTH_WRITE}
						</dd>	
					</dl>
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
				</fieldset>
			</form>
		</div>		
		