# IF C_MORE_THAN_ONE_LANG_INSTALLED #
<script>
<!--
	function select_all(status)
	{
		var i;
		for(i = 1; i <= {LANGS_NUMBER}; i++)
		{
			if(document.getElementById('delete-checkbox-' + i) && i != {SELECTED_LANG_NUMBER})
				document.getElementById('delete-checkbox-' + i).checked = status;
		}
		document.getElementById('check-all-top').checked = status;
		document.getElementById('check-all-bottom').checked = status;
	}
-->
</script>
# ENDIF #

<form action="{REWRITED_SCRIPT}" method="post">
	${LangLoader::get_message('langs.warning_before_install', 'admin-langs-common')}
	<table id="table">
		<caption>{@langs.installed_langs}</caption>
		<thead>
			<tr>
				# IF C_MORE_THAN_ONE_LANG_INSTALLED #
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
				<th>${LangLoader::get_message('enabled', 'common')}</th>
				<th>{@langs.uninstall_lang}</th>
			</tr>
		</thead>
		# IF C_MORE_THAN_ONE_LANG_INSTALLED #
		<tfoot>
			<tr>
				<td colspan="6">
					<div class="left">
						<div class="form-field-checkbox">
							<input type="checkbox" id="check-all-bottom" onclick="select_all(this.checked);" title="${LangLoader::get_message('select_all', 'main')}" />
							<label for="check-all-bottom"></label>
						</div>
						<button type="submit" name="delete-selected-langs" value="true" class="submit">{@langs.uninstall_all_selected_langs}</button>
					</div>
				</td>
			</tr>
		</tfoot>
		# ENDIF #
		<tbody>
			<tr>
				<td colspan="# IF C_MORE_THAN_ONE_LANG_INSTALLED #6# ELSE #5# ENDIF #">
					# INCLUDE MSG #
					<span class="text-strong">{@langs.default_lang_explain}</span>
				</td>
			</tr>
			# START langs_installed #
				<tr>
					# IF C_MORE_THAN_ONE_LANG_INSTALLED #
					<td>
						<div class="form-field-checkbox">
							<input type="checkbox" id="delete-checkbox-{langs_installed.LANG_NUMBER}" name="delete-checkbox-{langs_installed.LANG_NUMBER}"# IF langs_installed.C_IS_DEFAULT_LANG # disabled="disabled"# ENDIF # />
							<label for="delete-checkbox-{langs_installed.LANG_NUMBER}"></label>
						</div>
					</td>
					# ENDIF #
					<td>
						<span id="lang-{langs_installed.ID}"></span>
						<span class="text-strong">{langs_installed.NAME}</span> <span class="text-italic">({langs_installed.VERSION})</span>
					</td>
					<td class="left">
						<div id="desc_explain{langs_installed.ID}">
							<span class="text-strong">{@langs.author} :</span> # IF langs_installed.C_AUTHOR_EMAIL #<a href="mailto:{langs_installed.AUTHOR_EMAIL}">{langs_installed.AUTHOR}</a># ELSE #{langs_installed.AUTHOR}# ENDIF # # IF langs_installed.C_AUTHOR_WEBSITE #<a href="{langs_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
							<span class="text-strong">{@langs.compatibility} :</span> PHPBoost {langs_installed.COMPATIBILITY}<br />
						</div>
					</td>
					<td>
						# IF NOT langs_installed.C_IS_DEFAULT_LANG #
							<div id="authorizations_explain-{langs_installed.ID}">{langs_installed.AUTHORIZATIONS}</div>
						# ELSE #
							${LangLoader::get_message('visitor', 'user-common')}
						# ENDIF #
					</td>
					# IF NOT langs_installed.C_IS_DEFAULT_LANG #
					<td class="input-radio">
						<div class="form-field-radio">
							<input id="activated-{langs_installed.ID}" type="radio" name="activated-{langs_installed.ID}" value="1" # IF langs_installed.C_IS_ACTIVATED # checked="checked" # ENDIF # />
							<label for="activated-{langs_installed.ID}"></label>
						</div>
						<span class="form-field-radio-span">${LangLoader::get_message('yes', 'common')}</span>
						<br />
						<div class="form-field-radio">
							<input id="activated-{langs_installed.ID}2" type="radio" name="activated-{langs_installed.ID}" value="0" # IF NOT langs_installed.C_IS_ACTIVATED # checked="checked" # ENDIF # />
							<label for="activated-{langs_installed.ID}2"></label>
						</div>
						<span class="form-field-radio-span">${LangLoader::get_message('no', 'common')}</span>
					</td>
					<td>
						<button type="submit" class="submit" name="delete-{langs_installed.ID}" value="true">{@langs.uninstall_lang}</button>
					</td>
					# ELSE #
					<td>
						${LangLoader::get_message('yes', 'common')}
					</td>
					<td>
					</td>
					# ENDIF #
				</tr>
			# END langs_installed #
		</tbody>
	</table>

	<fieldset class="fieldset-submit">
		<legend>{L_SUBMIT}</legend>
		<div class="fieldset-inset">
			<button type="submit" class="submit" name="update_langs_configuration" value="true">${LangLoader::get_message('update', 'main')}</button>
			<input type="hidden" name="token" value="{TOKEN}">
			<input type="hidden" name="update" value="true">
			<button type="reset" value="true">${LangLoader::get_message('reset', 'main')}</button>
		</div>
	</fieldset>
</form>
