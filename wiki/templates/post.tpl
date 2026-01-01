<script>
	var selected_cat = {SELECTED_CATEGORY};
	function check_form_post(){

		if(document.getElementById('title').value == "") {
			alert("{@warning.title}");
			return false;
		}
		if(document.getElementById('contents').value == "") {
			alert("{@warning.text}");
			return false;
		}
		return true;
	}
</script>

<section id="module-wiki">
	# INCLUDE MESSAGE_HELPER #
	<header class="section-header">
		# IF C_PREVIEW #<h2># ELSE #<h1># ENDIF #
		# IF C_IS_CATEGORY #
			# IF C_EDIT_CATEGORY #
				{@wiki.category.edit}
				<h3 class="align-center">{TITLE}</h3>
			# ELSE #
				{@wiki.create.category}
			# ENDIF #
		# ELSE #
			# IF C_EDIT_ITEM #
				{@wiki.edit.item}
				<h3 class="align-center">{TITLE}</h3>
			# ELSE #
				{@wiki.create.item}
			# ENDIF #
		# ENDIF #
		# IF C_PREVIEW #</h2># ELSE #</h1># ENDIF #
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				<form action="{U_TARGET}" method="post" onsubmit="return check_form_post();" class="fieldset-content wiki-edit">
					<p class="align-center small text-italic">{@form.required.fields}</p>
					<fieldset>
						<legend>{@form.parameters}</legend>
						# START create #
							<div class="form-element">
								<label for="title">* # IF C_IS_CATEGORY #{@form.name}# ELSE #{@form.title}# ENDIF #</label>
								<div class="form-field"><input type="text" id="title" name="title" value="{EDIT_TITLE}"></div>
							</div>
							<div class="form-element">
								<label>{@wiki.current.category}</label>
								<div class="form-field">
									<input type="hidden" name="id_cat" id="id_cat" value="{ID_CATEGORY}"/>
									<div id="selected_cat">{CURRENT_CATEGORY}</div>
								</div>
							</div>
							<div class="form-element">
								<label>{@wiki.select.category}</label>
								<div class="form-field">
									<div class="explorer">
										<div class="cats">
											<div class="content no-list">
												<ul>
													<li><a class="{CATEGORY_0}" id="class-0" href="javascript:select_cat(0);"><i class="fa fa-folder" aria-hidden="true"></i>{@common.root}</a>
														<ul>
															# START create.list #
																<li class="sub">
																	# IF create.list.C_SUB_CAT #
																		<a class="parent" href="javascript:show_wiki_cat_contents({create.list.ID}, 1);" aria-label="{@common.display}">
																			<i class="fa fa-plus-square" id="img-subfolder-{create.list.ID}"></i>
																			<i class="fa fa-folder" id="img-folder-{create.list.ID}"></i>
																		</a>
																		<a id="class-{create.list.ID}" href="javascript:select_cat({create.list.ID});">{create.list.TITLE}</a>
																	# ELSE #
																		<a id="class-{create.list.ID}" href="javascript:select_cat({create.list.ID});"><i class="fa fa-folder" aria-hidden="true"></i>{create.list.TITLE}</a>
																	# ENDIF #
																	<span id="cat-{create.list.ID}"></span>
																</li>
															# END create.list #
															{CATEGORY_LIST}
														</ul>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						# END create #
						<div class="form-element form-element-textarea">
							<label for="content">* {@wiki.content}</label>
							# INCLUDE POST_JS_TOOLS #
							<div class="form-field form-field-textarea bbcode-sidebar">
								{KERNEL_EDITOR}
								<textarea class="auto-resize" rows="15" id="content" name="content">{CONTENT}</textarea>
							</div>
						</div>
						<fieldset class="fieldset-submit">
							<legend>{@form.preview}</legend>
							<button id="preview-button" type="submit" class="button preview-button" name="preview" value="preview">{@form.preview}</button>
						</fieldset>
						# START preview #
							<div class="auto-resize xmlhttprequest-preview" id="preview">
								<header class="section-header">
									<h1># IF C_EDIT_CATEGORY #{TITLE}# ELSE ## IF C_EDIT_ITEM #{TITLE}# ELSE #{preview.TITLE}# ENDIF ## ENDIF #</h1>
								</header>
								<div class="sub-section">
									<div class="content-container">
										<div class="content">
											# START preview.menu #
												<div class="wiki-summary">
													<div class="wiki-summary-title">{@wiki.summary.menu}</div>
													{preview.menu.MENU}
												</div>
											# END preview.menu #
											<br /><br />
											{preview.CONTENT}
										</div>
									</div>
								</div>
							</div>
						# END preview #

						# IF C_EDIT #
							<div class="form-element form-element-textarea">
								<label>{@wiki.changing.reason.label}</label>
								<textarea maxlength="100" rows="2" id="change_reason" name="change_reason">{CHANGE_REASON}</textarea>
							</div>
						# ENDIF #

						# IF C_CAPTCHA #
							<div class="form-element">
								<label for="captcha">* {@form.captcha}</label>
								<div class="form-field">
									{CAPTCHA}
								</div>
							</div>
							<script>
								jQuery(document).ready(function() {
									jQuery('button[name="submit"]').on('click', function()
									{
										if(!jQuery('#captcha').val() && !jQuery('#g-recaptcha-response').val()) {
											alert(${escapejs(@warning.captcha.validation.error)});
											return false;
										}
									});
								});
							</script>
						# ENDIF #
					</fieldset>
					<fieldset class="fieldset-submit">
						<legend>{@form.submit}</legend>
						<div class="fieldset-inset">
							<button type="submit" class="button submit" name="submit">{@form.submit}</button>
							<input type="hidden" name="is_cat" value="{IS_CATEGORY}">
							<input type="hidden" name="id_edit" value="{ID_EDIT}">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="reset" class="button reset-button" name="default">{@form.reset}</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</section>
<script>
	jQuery('.bbcode-bar > nav > ul').prepend(jQuery('.wikibar'));
</script>
