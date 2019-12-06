		<script>
		<!--
			var path = '{PICTURES_DATA_PATH}';
			var selected_cat = {SELECTED_CAT};
			function check_form_post(){
				if(document.getElementById('title') && document.getElementById('title').value == "") {
					alert("{L_ALERT_TITLE}");
					return false;
				}
				if(document.getElementById('contents').value == "") {
					alert("{L_ALERT_CONTENTS}");
					return false;
				}
				return true;
			}
			var disabled = {OWN_AUTH_DISABLED};
			function disable_own_auth()
			{
				if( disabled )
				{
					disabled = false;
					jQuery("#own_auth_display").show();
				}
				else
				{
					jQuery("#own_auth_display").hide();
					disabled = true;
				}
			}
		-->
		</script>

		<script src="{PATH_TO_ROOT}/pages/templates/js/pages.js"></script>

		# INCLUDE message_helper #

		# START previewing #
		<article>
			<header>
				<h1>{L_PREVIEWING} {previewing.TITLE}</h1>
			</header>
			<div class="content">{previewing.PREVIEWING}</div>
			<footer></footer>
		</article>
		# END previewing #

		<form action="post.php" method="post" onsubmit="return check_form_post();" class="fieldset-content">
			<fieldset>
				<legend>{L_TITLE_POST}</legend>
				# START create #
				<div class="form-element">
					<label for="title">* {L_TITLE_FIELD}</label>
					<div class="form-field"><label><input type="text" id="title" name="title" maxlength="250" class="field-large" value="{PAGE_TITLE}"></label></div>
				</div>
				# END create #
				<div class="form-element form-element-textarea">
					<label for="contents">* {L_CONTENTS}</label>
					{KERNEL_EDITOR}
					<div class="form-field-textarea">
						<textarea rows="25" id="contents" name="contents">{CONTENTS}</textarea>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>{L_PATH}</legend>
				<div class="form-element">
					<label for="is_cat">{L_IS_CAT}</label>
					<div class="form-field">
						<label class="checkbox">
							<input type="checkbox" name="is_cat" id="is_cat" {CHECK_IS_CAT}>
							<span></span>
						</label>
					</div>
				</div>
				<div class="form-element">
					<label>{L_CAT}</label>
					<div class="explorer d-inline">
						<div class="cats">
							<div class="content">
								<input type="hidden" name="id_cat" id="id_cat" value="{ID_CAT}"/>
								<ul>
									<li><a id="class-0" class="{CAT_0}" href="javascript:select_cat(0);"><i class="fa fa-folder" aria-hidden="true"></i>{L_ROOT}</a></li>
									{CAT_LIST}
								</ul>
							</div>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>{L_PROPERTIES}</legend>
				<div class="form-element">
					<label for="count_hits">{L_COUNT_HITS}</label>
					<div class="form-field">
						<label class="checkbox">
							<input type="checkbox" id="count_hits" name="count_hits" {COUNT_HITS_CHECKED}>
							<span>&nbsp;</span>
						</label>
					</div>
				</div>
				<div class="form-element">
					<label for="comments_activated">{L_COMMENTS_ACTIVATED}</label>
					<div class="form-field">
						<label class="checkbox">
							<input type="checkbox" id="comments_activated" name="comments_activated" {COMMENTS_ACTIVATED_CHECKED}>
							<span></span>
						</label>
					</div>
				</div>
				<div class="form-element">
					<label for="display_print_link">{L_DISPLAY_PRINT_LINK}</label>
					<div class="form-field">
						<label class="checkbox">
							<input type="checkbox" id="display_print_link" name="display_print_link" {DISPLAY_PRINT_LINK_CHECKED}>
							<span></span>
						</label>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>{L_AUTH}</legend>
				<div class="form-element">
					<label for="own_auth">{L_OWN_AUTH}</label>
					<div class="form-field">
						<label class="checkbox">
							<input type="checkbox" name="own_auth" id="own_auth" onclick="disable_own_auth();" {OWN_AUTH_CHECKED}>
							<span>&nbsp;</span>
						</label>
					</div>
				</div>
				<span id="own_auth_display" style="{DISPLAY}">
					<div class="form-element">
						<label>{L_READ_PAGE}</label>
						<div class="form-field">{SELECT_READ_PAGE}</div>
					</div>
					<div class="form-element">
						<label>{L_EDIT_PAGE}</label>
						<div class="form-field">{SELECT_EDIT_PAGE}</div>
					</div>
					<div class="form-element">
						<label>{L_READ_COM}</label>
						<div class="form-field">{SELECT_READ_COM}</div>
					</div>
				</span>
			</fieldset>

			<fieldset class="fieldset-submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="id_edit" value="{ID_EDIT}">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" class="button submit">{L_SUBMIT}</button>
				<button type="submit" class="button small" name="preview" value="preview">{L_PREVIEW}</button>
				<button type="reset" class="button reset">{L_RESET}</button>
			</fieldset>
		</form>
