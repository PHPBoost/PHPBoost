		<script>
		<!--
			var path = '{PICTURES_DATA_PATH}';
			var selected_cat = {SELECTED_CAT};
			function check_form_post(){
				
				if(document.getElementById('title').value == "") {
					alert("{L_ALERT_TITLE}");
					return false;
				}
				if(document.getElementById('contents').value == "") {
					alert("{L_ALERT_CONTENTS}");
					return false;
				}
				return true;
			}
		-->
		</script>

		# START preview #
		<article>
			<header>
				<h1>{L_PREVIEWING}: {preview.TITLE}</h1>
			</header>
			<div class="content" id="preview">
				# START preview.menu #
					<div class="wiki-summary">
						<div class="wiki-summary-title">{L_TABLE_OF_CONTENTS}</div>
						{preview.menu.MENU}
					</div>
				# END preview.menu #
				<br /><br />
				{preview.CONTENTS}
			</div>
			<footer></footer>
		</article>
		# END preview #
		
		# INCLUDE message_helper #
		
		<form action="{TARGET}" method="post" onsubmit="return check_form_post();" class="fieldset-content wiki-edit">
			<p class="center">{L_REQUIRE}</p>
			<fieldset>
				<legend>{TITLE}</legend>
				# START create #
				<div class="form-element">
					<label for="title">* {L_TITLE_FIELD}</label>
					<div class="form-field"><label><input type="text" id="title" name="title" maxlength="250" class="field-large" value="{ARTICLE_TITLE}"></label></div>
				</div>
				<div class="form-element">
					<label>{L_CURRENT_CAT}</label>
					<div class="form-field">
						<input type="hidden" name="id_cat" id="id_cat" value="{ID_CAT}"/>
						<div id="selected_cat">{CURRENT_CAT}</div>
					</div>
				</div>
				<div class="form-element">
					<label>{L_CAT}</label>
					<div class="explorer inline">
						<div class="cats">
							<div class="content">
								<ul>
									<li><a class="{CAT_0}" id="class_0" href="javascript:select_cat(0);"><i class="fa fa-folder"></i>{L_DO_NOT_SELECT_ANY_CAT}</a>
										<ul>
											# START create.list #
											<li class="sub">
												{create.list.DIRECTORY}
											</li>
											# END create.list #
											{CAT_LIST}
										</ul>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				# END create #
				<div class="form-element-textarea">
					<label for="contents">* {L_CONTENTS}</label>
					# INCLUDE post_js_tools #
					{KERNEL_EDITOR}
					<div class="form-field-textarea">
						<textarea rows="25" id="contents" name="contents">{CONTENTS}</textarea>
					</div>
				</div>
				# IF C_VERIF_CODE #
				<div class="form-element">
					<label for="captcha">* ${LangLoader::get_message('form.captcha', 'common')}</label>
					<div class="form-field">
						{VERIF_CODE}
					</div>
				</div>
				<script>
				<!--
				jQuery(document).ready(function() {
					jQuery('button[name="submit"]').click(function()
					{
						if(!jQuery('#captcha').val() && !jQuery('#g-recaptcha-response').val()) {
							alert(${escapejs(LangLoader::get_message('captcha.validation_error', 'status-messages-common'))});
							return false;
						}
					});
				});
				-->
				</script>
				# ENDIF #
				
			</fieldset>
			<fieldset class="fieldset-submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="is_cat" value="{IS_CAT}">
				<input type="hidden" name="id_edit" value="{ID_EDIT}">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" name="submit" class="submit">{L_SUBMIT}</button>
				<button type="submit" name="preview" value="preview">{L_PREVIEW}</button>
				<button type="reset">{L_RESET}</button>
			</fieldset>
		</form>
