# INCLUDE UPLOAD_FORM #

# IF C_MORE_THAN_ONE_LANG_AVAILABLE #
<script>
<!--
	function select_all(status)
	{
		var i;
		for(i = 1; i <= {LANGS_NUMBER}; i++)
		{
			if(document.getElementById('add-checkbox-' + i))
				document.getElementById('add-checkbox-' + i).checked = status;
		}
		document.getElementById('check-all-top').checked = status;
		document.getElementById('check-all-bottom').checked = status;
	}
-->
</script>
# ENDIF #

<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	# INCLUDE MSG #
	<fieldset>
		<legend>{@langs}</legend>
		<div class="fieldset-inset">
		# IF C_LANG_INSTALL #
			<table id="table">
				<caption>{@langs}</caption>
				<thead>
					<tr>
						# IF C_MORE_THAN_ONE_LANG_AVAILABLE #
						<th>
							<div class="form-field-checkbox">
								<input type="checkbox" id="check-all-top" onclick="select_all(this.checked);" title="${LangLoader::get_message('select_all', 'main')}" />
								<label for="check-all-top"></label>
							</div>
						</th>
						# ENDIF #
						<th>{@langs.name}</th>
						<th>{@langs.description}</th>
						<th>{@langs.authorizations}</th>
						<th>${LangLoader::get_message('enable', 'common')}</th>
						<th>${LangLoader::get_message('install', 'admin-common')}</th>
					</tr>
				</thead>
				# IF C_MORE_THAN_ONE_LANG_AVAILABLE #
				<tfoot>
					<tr>
						<td colspan="6">
							<div class="left">
								<div class="form-field-checkbox">
									<input type="checkbox" id="check-all-bottom" onclick="select_all(this.checked);" title="${LangLoader::get_message('select_all', 'main')}" />
									<label for="check-all-bottom"></label>
								</div>
								<button type="submit" name="add-selected-themes" value="true" class="submit">{@langs.install_all_selected_langs}</button>
							</div>
						</td>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START langs_not_installed #
					<tr>
						# IF C_MORE_THAN_ONE_LANG_AVAILABLE #
						<td>
							<div class="form-field-checkbox">
								<input type="checkbox" id="add-checkbox-{langs_not_installed.LANG_NUMBER}" name="add-checkbox-{langs_not_installed.LANG_NUMBER}" />
								<label for="add-checkbox-{langs_not_installed.LANG_NUMBER}"></label>
							</div>
						</td>
						# ENDIF #
						<td>
							<span id="lang-{langs_not_installed.ID}"></span>
							# IF langs_not_installed.C_HAS_PICTURE #
							<img src="{langs_not_installed.PICTURE_URL}" alt="{langs_not_installed.NAME}" class="valign-middle" />
							# ENDIF #
							<span class="text-strong">{langs_not_installed.NAME}</span> <em>({langs_not_installed.VERSION})</em>
						</td>
						<td class="left">
							<div id="desc_explain{langs_not_installed.ID}">
								<span class="text-strong">{@langs.author} :</span> # IF langs_not_installed.C_AUTHOR_EMAIL #<a href="mailto:{langs_not_installed.AUTHOR_EMAIL}">{langs_not_installed.AUTHOR}</a># ELSE #{langs_not_installed.AUTHOR}# ENDIF # # IF langs_not_installed.C_AUTHOR_WEBSITE #<a href="{langs_not_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
								<span class="text-strong">{@langs.compatibility} :</span> PHPBoost {langs_not_installed.COMPATIBILITY}<br />
							</div>
						</td>
						<td>
							<div id="authorizations_explain-{langs_not_installed.ID}">{langs_not_installed.AUTHORIZATIONS}</div>
						</td>
						<td class="input-radio">
							<div class="form-field-radio">
								<input id="activated-{langs_not_installed.ID}" type="radio" name="activated-{langs_not_installed.ID}" value="1" checked="checked" />
								<label for="activated-{langs_not_installed.ID}"></label>
							</div>
							<span class="form-field-radio-span">${LangLoader::get_message('yes', 'common')}</span>
							<br />
							<div class="form-field-radio">
								<input id="activated-{langs_not_installed.ID}2" type="radio" name="activated-{langs_not_installed.ID}" value="0" />
								<label for="activated-{langs_not_installed.ID}2"></label>
							</div>
							<span class="form-field-radio-span">${LangLoader::get_message('no', 'common')}</span>
						</td>
						<td>
							<button type="submit" class="submit" name="add-{langs_not_installed.ID}" value="true">${LangLoader::get_message('install', 'admin-common')}</button>
						</td>
					</tr>
				# END themes_not_installed #
				</tbody>
			</table>
		# ELSE #
			<div class="message-helper notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
		# ENDIF #
		</div>
	</fieldset>
</form>
