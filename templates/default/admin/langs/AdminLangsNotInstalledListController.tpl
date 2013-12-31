# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post">
	<table>
		<caption>{@langs.not_installed}</caption>
		# IF C_LANG_INSTALL #
		<thead>
			<tr> 
				<th>{@langs.name}</th>
				<th>{@langs.description}</th>
				<th>{@langs.authorisations}</th>
				<th>{@langs.activated}</th>
				<th>{L_ADD}</th>
			</tr>
		</thead>
		<tbody>
			<tr> 
				<td colspan="5">
					# INCLUDE MSG #	
				</td>
			</tr>
			# ELSE #
			<tbody>
				<tr>
					<td colspan="5">
						<span class="text-strong">{@themes.add.not_lang}</span>
					</td>
				</tr>
			# ENDIF #
			# START langs_not_installed #
				<tr> 	
					<td>					
						<span id="lang-{langs_not_installed.ID}"></span>
						<span class="text-strong">{langs_not_installed.NAME}</span> <em>({langs_not_installed.VERSION})</em>
					</td>
					<td>
						<div id="desc_explain{langs_not_installed.ID}" style="text-align:left;">
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
						<label><input type="radio" name="activated-{langs_not_installed.ID}" value="1" checked="checked"> {@langs.yes}</label>
						<label><input type="radio" name="activated-{langs_not_installed.ID}" value="0"> {@langs.no}</label>
					</td>
					<td>
						<button type="submit" name="add-{langs_not_installed.ID}" value="true">{L_ADD}</button>
					</td>
				</tr>
			# END themes_not_installed #
		</tbody>
	</table>
	
	<fieldset class="fieldset-submit">
		<legend>{L_SUBMIT}</legend>
		<input type="hidden" name="token" value="{TOKEN}">
	</fieldset>
</form>