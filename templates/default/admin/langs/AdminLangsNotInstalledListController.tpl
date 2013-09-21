# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post">
	<table class="module_table" style="width:99%;margin-bottom:30px;">
		<tr> 
			<th colspan="4">
				{@langs.not_installed}
			</th>
		</tr>
		<tr> 
			<td class="row2" colspan="4" style="text-align:center;">
				# INCLUDE MSG #	
			</td>
		</tr>
		# IF C_LANG_INSTALL #
		<tr>
			<td class="row2" style="width:100px;text-align:center;">
				{@langs.name}
			</td>
			<td class="row2" style="width:200px;text-align:center;">
				{@langs.description}
			</td>
			<td class="row2" style="width:200px;text-align:center;">
				{@langs.authorisations}
			</td>
			<td class="row2" style="width:80px;text-align:center;">
				{@langs.activated}
			</td>
		</tr>
		# ELSE #
		<tr>
			<td class="row2" colspan="4" style="text-align:center;">
				<strong>{@themes.add.not_lang}</strong>
			</td>
		</tr>
		# ENDIF #
		# START langs_not_installed #
			<tr> 	
				<td class="row2" style="text-align:center;">					
					<span id="lang-{langs_not_installed.ID}"></span>
					<strong>{langs_not_installed.NAME}</strong> <em>({langs_not_installed.VERSION})</em>
				</td>
				<td class="row2">
					<div id="desc_explain{langs_not_installed.ID}">
						<strong>{@langs.author}:</strong> 
						<a href="mailto:{langs_not_installed.AUTHOR_EMAIL}">
							{langs_not_installed.AUTHOR_NAME}
						</a>
						# IF langs_not_installed.C_WEBSITE # 
						<a href="{langs_not_installed.AUTHOR_WEBSITE}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/user_web.png" />
						</a>
						# ENDIF #
						<br />
						<strong>{@langs.compatibility}:</strong> PHPBoost {langs_not_installed.COMPATIBILITY}<br />
					</div>
				</td>
				<td class="row2">
					<div id="authorizations_explain-{langs_not_installed.ID}">
						{langs_not_installed.AUTHORIZATIONS}
					</div>
				</td>
				<td class="row2" style="text-align:center;">
					<label><input type="radio" name="activated-{langs_not_installed.ID}" value="1" checked="checked"> {@langs.yes}</label>
					<label><input type="radio" name="activated-{langs_not_installed.ID}" value="0"> {@langs.no}</label>
					<br /><br />
					<input type="submit" name="add-{langs_not_installed.ID}" value="{L_ADD}" class="submit"/>
				</td>
			</tr>						
		# END themes_not_installed #
	</table>
	
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		<input type="hidden" name="token" value="{TOKEN}">
	</fieldset>
</form>