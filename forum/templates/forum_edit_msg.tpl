# INCLUDE FORUM_TOP #

<script>
	function check_form_post(){
		if(document.getElementById('content').value == "") {
			alert("{@warning.text}");
			return false;
		}
	}
</script>
<article itemscope="itemscope" itemtype="https://schema.org/Creativework" id="article-forum-edit-message" class="forum-content">
	<header>
		<h2>
			<a class="offload" href="index.php">{@forum.index}</a> <i class="fa fa-angle-double-right small" aria-hidden="true"></i>
			<a class="offload" href="{U_CATEGORY}">{CATEGORY_NAME}</a> <i class="fa fa-angle-double-right small" aria-hidden="true"></i>
			<a class="offload" href="{U_TITLE_T}">{TITLE_T}</a> <span><em>{DESCRIPTION}</em></span>
		</h2>
	</header>
	<div class="content">
		<form action="{U_ACTION}" method="post" onsubmit="return check_form_post();">
			# INCLUDE MESSAGE_HELPER #

			<div class="fieldset-content">
				<p class="align-center small text-italic">{@form.required.fields}</p>
				<fieldset>
					<legend>{@forum.edit.in.topic}: {TITLE_T}</legend>
					<div class="form-element form-element-textarea">
						<label for="content">* {@user.message}</label>
						<div class="form-field form-field-textarea bbcode-sidebar">
							{KERNEL_EDITOR}
							<textarea rows="25" cols="66" id="content" name="content">{CONTENT}</textarea>
						</div>
					</div>
				</fieldset>

				<fieldset class="fieldset-submit">
					<legend>{@form.submit}</legend>
					<input type="hidden" name="p_update" value="{P_UPDATE}">
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="submit" class="button submit" name="edit_msg" value="true">{@form.submit}</button>
					<button type="button" class="button preview-button" onclick="XMLHttpRequest_preview();">{@form.preview}</button>
					<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
				</fieldset>
			</div>
		</form>
	</div>
	<footer>
		<a class="offload" href="index.php">{@forum.index}</a> <i class="fa fa-angle-double-right small" aria-hidden="true"></i>
		<a class="offload" href="{U_CATEGORY}">{CATEGORY_NAME}</a> <i class="fa fa-angle-double-right small" aria-hidden="true"></i>
		<a class="offload" href="{U_TITLE_T}">{TITLE_T}</a> <span><em>{DESCRIPTION}</em></span>
	</footer>
</article>
<script src="{PATH_TO_ROOT}/BBCode/templates/js/bbcode-sidebar# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>

# INCLUDE FORUM_BOTTOM #
