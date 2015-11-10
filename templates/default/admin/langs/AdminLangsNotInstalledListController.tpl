# INCLUDE UPLOAD_FORM #
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
						<th>{@langs.name}</th>
						<th>{@langs.description}</th>
						<th>{@langs.authorizations}</th>
						<th>${LangLoader::get_message('enable', 'common')}</th>
						<th>{@langs.install_lang}</th>
					</tr>
				</thead>
				<tbody>
					# START langs_not_installed #
					<tr>
						<td>
							<span id="lang-{langs_not_installed.ID}"></span>
							<span class="text-strong">{langs_not_installed.NAME}</span> <em>({langs_not_installed.VERSION})</em>
						</td>
						<td class="left">
							<div id="desc_explain{langs_not_installed.ID}">
								<span class="text-strong">{@langs.author} :</span> 
								<a href="mailto:{langs_not_installed.AUTHOR_EMAIL}">{langs_not_installed.AUTHOR_NAME}</a>
								# IF langs_not_installed.C_WEBSITE # 
								<a href="{langs_not_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a>
								# ENDIF #
								<br />
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
							<button type="submit" class="submit" name="add-{langs_not_installed.ID}" value="true">{@langs.install_lang}</button>
						</td>
					</tr>
				# END themes_not_installed #
				</tbody>
			</table>
		# ELSE #
			<div class="notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
		# ENDIF #
		</div>
	</fieldset>
</form>