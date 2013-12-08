<form action="{REWRITED_SCRIPT}" method="post">
	{@langs.installed}
	<table>
		<thead>
			<tr> 
				<th>
					{@langs.name}
				</th>
				<th>
					{@langs.description}
				</th>
				<th>
					{@langs.authorisations}
				</th>
				<th>
					{@langs.activated}
				</th>
			</tr>
		</thead>
		<tbody>
			<tr> 
				<td colspan="4">
					# INCLUDE MSG #	
					<span class="text-strong">{@langs.default_lang_explain}</span>
				</td>
			</tr>
			# START langs_installed #
				<tr> 	
					<td class="# IF langs_installed.C_IS_DEFAULT_LANG # row_disabled # ENDIF #" style="text-align:center;">					
						<span id="lang-{langs_installed.ID}"></span>
						<span class="text-strong">{langs_installed.NAME}</span> <span class="text-italic">({langs_installed.VERSION})</span>
					</td>
					<td class="# IF langs_installed.C_IS_DEFAULT_LANG # row_disabled # ENDIF #">
						<div id="desc_explain{langs_installed.ID}">
							<span class="text-strong">{@langs.author}:</span> 
							<a href="mailto:{langs_installed.AUTHOR_EMAIL}">
								{langs_installed.AUTHOR_NAME}
							</a>
							# IF langs_installed.C_WEBSITE # 
							<a href="{langs_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a>
							# ENDIF #
							<br />
							<span class="text-strong">{@langs.compatibility}:</span> PHPBoost {langs_installed.COMPATIBILITY}<br />
						</div>
					</td>
					<td class="# IF langs_installed.C_IS_DEFAULT_LANG # row_disabled # ENDIF #">
						# IF NOT langs_installed.C_IS_DEFAULT_LANG #
							<div id="authorizations_explain-{langs_installed.ID}">
								{langs_installed.AUTHORIZATIONS}
							</div>
						# ELSE #
								{@langs.visitor}
						# ENDIF #
					</td>
					<td class="# IF langs_installed.C_IS_DEFAULT_LANG # row_disabled # ENDIF #" style="text-align:center;">
						# IF NOT langs_installed.C_IS_DEFAULT_LANG #
							<label><input type="radio" name="activated-{langs_installed.ID}" value="1" # IF langs_installed.C_IS_ACTIVATED # checked="checked" # ENDIF #> {@langs.yes}</label>
							<label><input type="radio" name="activated-{langs_installed.ID}" value="0" # IF NOT langs_installed.C_IS_ACTIVATED # checked="checked" # ENDIF #> {@langs.no}</label>
							<br /><br />
							<button type="submit" name="delete-{langs_installed.ID}" value="true">{L_DELETE}</button>
						# ELSE #
							{@langs.yes}
						# ENDIF #
					</td>
				</tr>
			# END langs_installed #
		</tbody>
	</table>
	
	<fieldset class="fieldset-submit">
		<legend>{L_SUBMIT}</legend>
		<button type="submit" name="update_langs_configuration" value="true">{L_UPDATE}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="update" value="true">
		&nbsp;&nbsp; 
		<button type="reset" value="true">{L_RESET}</button>
	</fieldset>
</form>