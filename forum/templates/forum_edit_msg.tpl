		# INCLUDE forum_top #

		<script>
		<!--
		function check_form_post(){
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
			}
		}
		-->
		</script>

		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-edit-message" class="forum-contents">
			<header>
				<h2>
					<a href="index.php">{L_FORUM_INDEX}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
					<a href="{U_FORUM_CAT}">{FORUM_CAT}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
					<a href="{U_TITLE_T}">{TITLE_T}</a> <span><em>{DESC}</em></span>
				</h2>
			</header>
			<div class="content">
				<form action="{U_ACTION}" method="post" onsubmit="return check_form_post();">
					# INCLUDE message_helper #

					<div class="fieldset-content">
						<p class="align-center">{L_REQUIRE}</p>
						<fieldset>
							<legend>{L_EDIT_MESSAGE}</legend>
							<div class="form-element form-element-textarea">
								<label for="contents">* {L_MESSAGE}</label>
								{KERNEL_EDITOR}
								<div class="form-field-textarea">
									<textarea rows="25" cols="66" id="contents" name="contents">{CONTENTS}</textarea>
								</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<legend>{L_SUBMIT}</legend>
							<input type="hidden" name="p_update" value="{P_UPDATE}">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" class="button submit" name="edit_msg" value="true">{L_SUBMIT}</button>
							<button type="button" class="button small" onclick="XMLHttpRequest_preview();">{L_PREVIEW}</button>
							<button type="reset" class="button reset" value="true">{L_RESET}</button>
						</fieldset>
					</div>
				</form>
			</div>
			<footer>
				<a href="index.php">{L_FORUM_INDEX}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
				<a href="{U_FORUM_CAT}">{FORUM_CAT}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
				<a href="{U_TITLE_T}">{TITLE_T}</a> <span><em>{DESC}</em></span>
			</footer>
		</article>

		# INCLUDE forum_bottom #
