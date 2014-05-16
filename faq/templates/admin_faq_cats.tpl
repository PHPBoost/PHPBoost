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
			
			# START categories_management #
				<fieldset>
					<legend>{L_CATS_MANAGEMENT}</legend>
					{categories_management.CATEGORIES}
				</fieldset>

				<div style="text-align:center; margin:30px 20px;">
					<a href="admin_faq_cats.php?recount=1" title="{L_RECOUNT_QUESTIONS}">
						<i class="fa fa-refresh fa-2x"></i>
					</a>
					<br />
					<a href="admin_faq_cats.php?recount=1">{L_RECOUNT_QUESTIONS}</a>
				</div>
			# END categories_management #
			
			# START removing_interface #
			
			<form action="admin_faq_cats.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset-content">
				<fieldset>
					<legend>{L_REMOVING_CATEGORY}</legend>
					<p>{L_EXPLAIN_REMOVING}</p>
					
					<label>
						<input type="radio" name="action" value="delete"> {L_DELETE_CATEGORY_AND_CONTENT}
					</label>
					<br /> <br />
					<label>
						<input type="radio" name="action" value="move" checked="checked"> {L_MOVE_CONTENT}
					</label>
					&nbsp;
					{removing_interface.CATEGORY_TREE}
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="cat_to_del" value="{removing_interface.IDCAT}">
					<button type="submit" name="submit" value="true" class="submit">{L_SUBMIT}</button>	
				</fieldset>
			</form>
			
			# END removing_interface #

			# START edition_interface #
			
			<script>
			<!--
			function check_form(){
				if(document.getElementById('name').value == "")
				{
					alert("{L_REQUIRE_TITLE}");
					return false;
			    }

				return true;
			}
			
			var global_auth = {JS_GLOBAL};
			function change_status_global_auth()
			{
				if( global_auth )
					hide_div("hide_global_auth");
				else
					show_div("hide_global_auth");
				global_auth = !global_auth;
			}
			-->
			</script>
			<form action="admin_faq_cats.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="center">{L_REQUIRED_FIELDS}</p>
				<fieldset>
					<legend>{L_CATEGORY}</legend>
					<div class="form-element">
						<label for="name">
							* {L_NAME}
						</label>
						<div class="form-field">
							<input type="text" size="65" maxlength="100" id="name" name="name" value="{edition_interface.NAME}">
						</div>
					</div>
					<div class="form-element">
						<label for="id_parent">
							* {L_LOCATION}
						</label>
						<div class="form-field">
							{edition_interface.CATEGORIES_TREE}
						</div>
					</div>
					<div class="form-element">
						<label for="image">
							{L_IMAGE}
						</label>
						<div class="form-field">
							<input type="text" size="65" maxlength="100" id="image" name="image" value="{edition_interface.IMAGE}">
						</div>
					</div>
					<div class="form-element-textarea">
						<label for="description">
							{L_DESCRIPTION}
						</label>
						{KERNEL_EDITOR}
						<textarea id="contents" rows="15" cols="40" name="description">{edition_interface.DESCRIPTION}</textarea>
					</div>
					<div class="form-element">
						<label for="display_mode">{L_DISPLAY_MODE} <span class="field-description">{L_DISPLAY_EXPLAIN}</span></label>
						<div class="form-field">
							<select name="display_mode" id="display_mode">
								<option value="0" {edition_interface.AUTO_SELECTED}>{L_DISPLAY_AUTO}</option>
								<option value="1" {edition_interface.INLINE_SELECTED}>{L_DISPLAY_INLINE}</option>
								<option value="2" {edition_interface.BLOCK_SELECTED}>{L_DISPLAY_BLOCK}</option>
							</select>
						</div>
					</div>
					<div class="form-element">
						<label for="global_auth">{L_GLOBAL_AUTH} <span class="field-description">{L_GLOBAL_AUTH_EXPLAIN}</span></label>
						<div class="form-field">
							<input type="checkbox" name="global_auth" id="global_auth" onclick="javascript: change_status_global_auth();" {GLOBAL_CHECKED}>
						</div>
					</div>
					<div id="hide_global_auth" style="display:{DISPLAY_GLOBAL};">
						<div class="form-element">
							<label>
								{L_READ_AUTH}
							</label>
							<div class="form-field">
								{edition_interface.READ_AUTH}
							</div>
						</div>
						<div class="form-element">
							<label>
								{L_WRITE_AUTH}
							</label>
							<div class="form-field">
								{edition_interface.WRITE_AUTH}
							</div>
						</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="idcat" value="{edition_interface.IDCAT}">
					<button type="submit" name="submit" value="true" class="submit">{L_SUBMIT}</button>
					<button type="button" name="preview" onclick="XMLHttpRequest_preview();">{L_PREVIEW}</button>
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
			# END edition_interface #
			
		</div>
		