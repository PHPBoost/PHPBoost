		<script type="text/javascript">
		<!--
		function check_form()
		{
			if(document.getElementById('news_pagination').value == "") {
				new Effect.ScrollTo('news_pagination',{duration:1.2});
				alert("{L_REQUIRE_NEWS_PAGINATION}");
				return false;
			}

			if(document.getElementById('archives_pagination').value == "") {
				new Effect.ScrollTo('archives_pagination',{duration:1.2});
				alert("{L_REQUIRE_ARCHIVES_PAGINATION}");
				return false;
			}

			if(document.getElementById('nbr_columns').value == "") {
				new Effect.ScrollTo('nbr_columns',{duration:1.2});
				alert("{L_REQUIRE_NBR_COL}");
				return false;
			}

			return true;
		}
		-->
		</script>

		{ADMIN_MENU}

		<div id="admin_contents">

			<form action="admin_news_config.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_CONFIG_NEWS}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="news_pagination">* {L_NBR_NEWS_P}</label></dt>
						<dd><label><input type="text" maxlength="3" size="6" name="news_pagination" id="news_pagination" value="{NEWS_PAGINATION}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="archives_pagination">* {L_NBR_ARCH_P}</label></dt>
						<dd><label><input type="text" maxlength="3" size="6" name="archives_pagination" id="archives_pagination" value="{ARCHIVES_PAGINATION}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_columns">* {L_NBR_COLUMN_MAX}</label></dt>
						<dd><label><input type="text" size="6" maxlength="1" id="nbr_columns" name="nbr_columns" value="{NBR_COLUMNS}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="pagination_activated">{L_ACTIV_PAGINATION}</label></dt>
						<dd>
							<label><input type="radio" name="pagination_activated" id="pagination_activated" value="1" # IF PAGIN_ENABLED #checked="checked" # ENDIF #/> {L_YES}</label>
							&nbsp;&nbsp;
							<label><input type="radio" name="pagination_activated" value="0" # IF PAGIN_DISABLED #checked="checked" # ENDIF #/> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="type">{L_ACTIV_NEWS_BLOCK}</label></dt>
						<dd>
							<label><input type="radio" name="type" id="news_block_activated" value="1" # IF BLOCK_ENABLED #checked="checked" # ENDIF #/> {L_YES}</label>
							&nbsp;&nbsp;
							<label><input type="radio" name="type" value="0" # IF BLOCK_DISABLED #checked="checked" # ENDIF #/> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="comments_activated">{L_ACTIV_COM_NEWS}</label></dt>
						<dd>
							<label><input type="radio" name="comments_activated" id="comments_activated" value="1" # IF COM_ENABLED #checked="checked" # ENDIF #/> {L_YES}</label>
							&nbsp;&nbsp;
							<label><input type="radio" name="comments_activated" value="0" # IF COM_DISABLED #checked="checked" # ENDIF #/> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="icon_activated">{L_ACTIV_ICON_NEWS}</label></dt>
						<dd>
							<label><input type="radio" name="icon_activated" id="icon_activated" value="1" # IF ICON_ENABLED #checked="checked" # ENDIF #/> {L_YES}</label>
							&nbsp;&nbsp;
							<label><input type="radio" name="icon_activated" value="0" # IF ICON_DISABLED #checked="checked" # ENDIF #/> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="display_author">{L_DISPLAY_NEWS_AUTHOR}</label></dt>
						<dd>
							<label><input type="radio" name="display_author" id="display_author" value="1" # IF AUTHOR_ENABLED #checked="checked" # ENDIF #/> {L_YES}</label>
							&nbsp;&nbsp;
							<label><input type="radio" name="display_author" value="0" # IF AUTHOR_DISABLED #checked="checked" # ENDIF #/> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="display_date">{L_DISPLAY_NEWS_DATE}</label></dt>
						<dd>
							<label><input type="radio" name="display_date" id="display_date" value="1" # IF DATE_ENABLED #checked="checked" # ENDIF #/>	{L_YES}</label>
							&nbsp;&nbsp;
							<label><input type="radio" name="display_date" value="0" # IF DATE_DISABLED #checked="checked" # ENDIF #/> {L_NO}</label>
						</dd>
					</dl>
				</fieldset>

				<fieldset>
					<legend>{L_GLOBAL_AUTH}</legend>
					<p>{L_GLOBAL_AUTH_EXPLAIN}</p>
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
							<label for="auth_contribution">{L_AUTH_CONTRIBUTION}</label>
						</dt>
						<dd>
							{AUTH_CONTRIBUTION}
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="auth_write">{L_AUTH_WRITE}</label>
						</dt>
						<dd>
							{AUTH_WRITE}
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="auth_moderation">{L_AUTH_MODERATION}</label>
						</dt>
						<dd>
							{AUTH_MODERATION}
						</dd>
					</dl>
				</fieldset>

				<fieldset id="preview_description">
					<legend>{L_EDITO_WHERE}</legend>
					<dl>
						<dt><label for="edito_activated">{L_ACTIV_EDITO}</label></dt>
						<dd>
							<label><input type="radio" name="edito_activated" id="edito_activated" value="1" # IF EDITO_ENABLED #checked="checked" # ENDIF #/> {L_YES}</label>
							&nbsp;&nbsp;
							<label><input type="radio" name="edito_activated" value="0" # IF EDITO_DISABLED #checked="checked" # ENDIF #/> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="edito_title">{L_TITLE}</label></dt>
						<dd><label><input type="text" maxlength="100" size="60" name="edito_title" id="edito_title" value="{TITLE}" class="text" /></label></dd>
					</dl>
					<br />
					<label for="contents">{L_TEXT}</label>
					<label>
						{KERNEL_EDITOR}
						<textarea rows="20" cols="90" id="contents" name="edito_content">{CONTENTS}</textarea>
					</label>
					<br />
				</fieldset>

				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="submit" value="{L_UPDATE}" class="submit" />
					<script type="text/javascript">
					<!--
					document.write('&nbsp;&nbsp; <input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(); new Effect.ScrollTo(\'preview_description\',{duration:1.2}); return false;" type="button" class="submit" />&nbsp;&nbsp;');
					-->
					</script>
					&nbsp;&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>
		</div>
