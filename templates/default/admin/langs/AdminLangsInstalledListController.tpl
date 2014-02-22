<form action="{REWRITED_SCRIPT}" method="post">
	<table class="module_table" style="width:99%;margin-bottom:30px;">
		<tr> 
			<th colspan="4">
				{@langs.installed}
			</th>
		</tr>
		<tr> 
			<td class="row2" colspan="4" style="text-align:center;">
				# INCLUDE MSG #	
				<strong>{@langs.default_lang_explain}</strong>
			</td>
		</tr>
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
		# START langs_installed #
			<tr> 	
				<td class="row2 # IF langs_installed.C_IS_DEFAULT_LANG # row_disabled # ENDIF #" style="text-align:center;">					
					<span id="lang-{langs_installed.ID}"></span>
					<strong>{langs_installed.NAME}</strong> <em>({langs_installed.VERSION})</em>
				</td>
				<td class="row2 # IF langs_installed.C_IS_DEFAULT_LANG # row_disabled # ENDIF #">
					<div id="desc_explain{langs_installed.ID}">
						<strong>{@langs.author}:</strong> 
						<a href="mailto:{langs_installed.AUTHOR_EMAIL}">
							{langs_installed.AUTHOR_NAME}
						</a>
						# IF langs_installed.C_WEBSITE # 
						<a href="{langs_installed.AUTHOR_WEBSITE}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/user_web.png" />
						</a>
						# ENDIF #
						<br />
						<strong>{@langs.compatibility}:</strong> PHPBoost {langs_installed.COMPATIBILITY}<br />
					</div>
				</td>
				<td class="row2 # IF langs_installed.C_IS_DEFAULT_LANG # row_disabled # ENDIF #">
					# IF NOT langs_installed.C_IS_DEFAULT_LANG #
						<div id="authorizations_explain-{langs_installed.ID}">
							{langs_installed.AUTHORIZATIONS}
						</div>
					# ELSE #
						<div style="text-align:center;">
							{@langs.visitor}
						</div>
					# ENDIF #
				</td>
				<td class="row2 # IF langs_installed.C_IS_DEFAULT_LANG # row_disabled # ENDIF #" style="text-align:center;">
					# IF NOT langs_installed.C_IS_DEFAULT_LANG #
						<label><input type="radio" name="activated-{langs_installed.ID}" value="1" # IF langs_installed.C_IS_ACTIVATED # checked="checked" # ENDIF # /> {@langs.yes}</label>
						<label><input type="radio" name="activated-{langs_installed.ID}" value="0" # IF NOT langs_installed.C_IS_ACTIVATED # checked="checked" # ENDIF # /> {@langs.no}</label>
						<br /><br />
						<a href="{langs_installed.DELETE_LINK}">
							<input name="delete-{langs_installed.ID}" value="{L_DELETE}" class="submit" style="width:70px;text-align:center;"/>
						</a>
					# ELSE #
						{@langs.yes}
					# ENDIF #
				</td>
			</tr>						
		# END langs_installed #
	</table>
	
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		<input type="submit" name="update_langs_configuration" value="{L_UPDATE}" class="submit" />
		<input type="hidden" name="token" value="{TOKEN}" />
		<input type="hidden" name="update" value="true" />
		&nbsp;&nbsp; 
		<input type="reset" value="{L_RESET}" class="reset" />
	</fieldset>
</form>