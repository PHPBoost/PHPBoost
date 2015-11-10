<form action="{REWRITED_SCRIPT}" method="post">
	<table id="table">
		<caption>{@langs.installed_langs}</caption>
		<thead>
			<tr> 
				<th>{@langs.name}</th>
				<th>{@langs.description}</th>
				<th>{@langs.authorizations}</th>
				<th>${LangLoader::get_message('enabled', 'common')}</th>
				<th>${LangLoader::get_message('delete', 'common')}</th>
			</tr>
		</thead>
		<tbody>
			<tr> 
				<td colspan="5">
					# INCLUDE MSG #	
					<span class="text-strong">{@langs.default_lang_explain}</span>
				</td>
			</tr>
			# START langs_installed #
				<tr> 	
					<td>					
						<span id="lang-{langs_installed.ID}"></span>
						<span class="text-strong">{langs_installed.NAME}</span> <span class="text-italic">({langs_installed.VERSION})</span>
					</td>
					<td class="left">
						<div id="desc_explain{langs_installed.ID}">
							<span class="text-strong">{@langs.author} :</span> 
							<a href="mailto:{langs_installed.AUTHOR_EMAIL}">{langs_installed.AUTHOR_NAME}</a>
							# IF langs_installed.C_WEBSITE # 
							<a href="{langs_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a>
							# ENDIF #
							<br />
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
						<button type="submit" class="submit" name="delete-{langs_installed.ID}" value="true">${LangLoader::get_message('delete', 'common')}</button>
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
			<button type="submit" class="submit" name="update_langs_configuration" value="true">{L_UPDATE}</button>
			<input type="hidden" name="token" value="{TOKEN}">
			<input type="hidden" name="update" value="true">
			<button type="reset" value="true">{L_RESET}</button>
		</div>
	</fieldset>
</form>