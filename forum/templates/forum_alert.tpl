		# INCLUDE forum_top #

		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-moderation" class="forum-contents">
			<header>
				<h2><a href="{U_FORUM_CAT}">{FORUM_CAT}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="{U_TITLE_T}">{TITLE_T}</a> <span><em>{DESC}</em></span> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="#">{L_ALERT}</a></h2>
			</header>

			# START alert_form #
				<script>
					function check_form_alert(){
						if(document.getElementById('contents').value == "") {
							alert("{L_REQUIRE_TEXT}");
							return false;
						}
						if(document.getElementById('title').value == "") {
							alert("{L_REQUIRE_TITLE}");
							return false;
						}
						return true;
					}
				</script>

				<form method="post" action="alert.php" onsubmit="javascript:return check_form_alert();">
					<fieldset>
						<legend><h1>{L_ALERT}</h1></legend>

						<div class="message-helper bgc notice">{L_ALERT_EXPLAIN}: <a href="{alert_form.U_TOPIC}">{alert_form.TITLE}</a></div>
						<div class="form-element">
							<label for="title">* {L_ALERT_TITLE}</label>
							<div class="form-field">
								<input type="text" name="title" id="title">
							</div>
						</div>
						<div class="form-element form-element-textarea">
							<label for="contents">* {L_ALERT_CONTENTS}</label>
							{KERNEL_EDITOR}
							<textarea rows="15" cols="40" id="contents" name="contents"></textarea>
							<input type="hidden" name="id" value="{alert_form.ID_ALERT}">
						</div>
					</fieldset>

					<fieldset class="fieldset-submit">
							<button type="submit" name="edit_msg" value="true" class="button submit">{L_SUBMIT}</button>
							<button onclick="XMLHttpRequest_preview();" class="button preview-button" type="button">{L_PREVIEW}</button>
							<button type="reset" class="button reset-button" value="true">{L_RESET}</button>
							<input type="hidden" name="token" value="{TOKEN}">
					</fieldset>
				</form>
				<div class="spacer"></div>
				# END alert_form #


				# START alert_confirm #
				<fieldset>
					<legend>{L_ALERT}</legend>
					<div class="message-helper bgc success">
						{alert_confirm.MSG}
						<div class="align-center"><a href="{URL_TOPIC}">{L_BACK_TOPIC}</a></div>
					</div>
				</fieldset>
				# END alert_confirm #
			<footer><a href="{U_FORUM_CAT}">{FORUM_CAT}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="{U_TITLE_T}">{TITLE_T}</a> <span><em>{DESC}</em></span> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="#">{L_ALERT}</a></footer>
		</article>

		# INCLUDE forum_bottom #
