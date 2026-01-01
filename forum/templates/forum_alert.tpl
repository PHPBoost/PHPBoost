# INCLUDE FORUM_TOP #

<article itemscope="itemscope" itemtype="https://schema.org/Creativework" id="article-forum-moderation" class="forum-content">
	<header class="section-header">
		<h2><a href="{U_CATEGORY}" class="offload">{CATEGORY_NAME}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="{U_TITLE_T}" class="offload">{TITLE_T}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="#">{@forum.report.topic}</a></h2>
	</header>

	# START alert_form #
		<script>
			function check_form_alert(){
				if(document.getElementById('content').value == "") {
					alert("{@warning.text}");
					return false;
				}
				if(document.getElementById('title').value == "") {
					alert("{@warning.title}");
					return false;
				}
				return true;
			}
		</script>

		<form method="post" action="alert.php" onsubmit="javascript:return check_form_alert();">
			<fieldset>
				<div class="message-helper bgc warning">{@H|forum.report.clue}: <a href="{alert_form.U_TOPIC}" class="offload">{alert_form.TITLE}</a></div>
				<div class="form-element">
					<label for="title">* {@forum.report.title}</label>
					<div class="form-field">
						<input type="text" name="title" id="title">
					</div>
				</div>
				<div class="form-element form-element-textarea">
					<label for="content">* {@forum.report.content}</label>
					<div class="form-field form-field-textarea bbcode-sidebar">
						{KERNEL_EDITOR}
						<textarea rows="15" cols="40" id="content" name="content"></textarea>
					</div>

					<input type="hidden" name="id" value="{alert_form.REPORT_ID}">
					<button onclick="XMLHttpRequest_preview();" class="button preview-button" type="button">{@form.preview}</button>
				</div>
			</fieldset>

			<fieldset class="fieldset-submit">
				<button type="submit" name="edit_msg" value="true" class="button submit">{@form.submit}</button>
				<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
				<input type="hidden" name="token" value="{TOKEN}">
			</fieldset>
		</form>
	# END alert_form #

	# START alert_confirm #
		<fieldset>
			<legend>{@forum.report.topic.title}</legend>
			<div class="message-helper bgc success">
				{alert_confirm.L_CONFIRM_MESSAGE}
				<div class="align-center"><a href="{URL_TOPIC}" class="offload">{@forum.report.back}</a></div>
			</div>
		</fieldset>
	# END alert_confirm #
	<footer><a href="{U_CATEGORY}" class="offload">{CATEGORY_NAME}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="{U_TITLE_T}" class="offload">{TITLE_T}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="#">{@forum.report.topic}</a></footer>
</article>

# INCLUDE FORUM_BOTTOM #
