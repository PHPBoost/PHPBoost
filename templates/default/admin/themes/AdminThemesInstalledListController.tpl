# IF C_MORE_THAN_ONE_THEME_INSTALLED #
<script>
<!--
	function select_all(status)
	{
		var i;
		for(i = 1; i <= {THEMES_NUMBER}; i++)
		{
			if(document.getElementById('delete-checkbox-' + i) && i != {SELECTED_THEME_NUMBER})
				document.getElementById('delete-checkbox-' + i).checked = status;
		}
		document.getElementById('check-all-top').checked = status;
		document.getElementById('check-all-bottom').checked = status;
	}
-->
</script>
# ENDIF #

<form action="{REWRITED_SCRIPT}" method="post">
	${LangLoader::get_message('themes.warning_before_install', 'admin-themes-common')}
	<table id="table">
		<caption>{@themes.installed_theme}</caption>
		<thead>
			<tr>
				# IF C_MORE_THAN_ONE_THEME_INSTALLED #
				<th>
					<div class="form-field-checkbox">
						<input type="checkbox" id="check-all-top" onclick="select_all(this.checked);" title="${LangLoader::get_message('select_all', 'main')}" />
						<label for="check-all-top"></label>
					</div>
				</th>
				# ENDIF #
				<th>{@themes.name}</th>
				<th>{@themes.description}</th>
				<th>{@themes.authorization}</th>
				<th>${LangLoader::get_message('enabled', 'common')}</th>
				<th>{@themes.uninstall_theme}</th>
			</tr>
		</thead>
		# IF C_MORE_THAN_ONE_THEME_INSTALLED #
		<tfoot>
			<tr>
				<td colspan="6">
					<div class="left">
						<div class="form-field-checkbox">
							<input type="checkbox" id="check-all-bottom" onclick="select_all(this.checked);" title="${LangLoader::get_message('select_all', 'main')}" />
							<label for="check-all-bottom"></label>
						</div>
						<button type="submit" name="add-selected-themes" value="true" class="submit">{@themes.uninstall_all_selected_themes}</button>
					</div>
				</td>
			</tr>
		</tfoot>
		# ENDIF #
		<tbody>
			<tr>
				<td colspan="# IF C_MORE_THAN_ONE_THEME_INSTALLED #6# ELSE #5# ENDIF #">
					# INCLUDE MSG #
					<span class="text-strong">{@themes.default_theme_explain}</span>
				</td>
			</tr>
			# START themes_installed #
				<tr>
					# IF C_MORE_THAN_ONE_THEME_INSTALLED #
					<td>
						<div class="form-field-checkbox">
							<input type="checkbox" id="delete-checkbox-{themes_installed.THEME_NUMBER}" name="delete-checkbox-{themes_installed.THEME_NUMBER}"# IF themes_installed.C_IS_DEFAULT_THEME # disabled="disabled"# ENDIF # />
							<label for="delete-checkbox-{themes_installed.THEME_NUMBER}"></label>
						</div>
					</td>
					# ENDIF #
					<td>
						<span id="theme-{themes_installed.ID}"></span>
						<span class="text-strong">{themes_installed.NAME}</span> <em>({themes_installed.VERSION})</em>
						<br /><br />
						# IF themes_installed.C_PICTURES #
							<a href="{themes_installed.MAIN_PICTURE}" data-lightbox="{themes_installed.ID}" data-rel="lightcase:collection" title="{themes_installed.NAME}">
								<img src="{themes_installed.MAIN_PICTURE}" alt="{themes_installed.NAME}" class="picture-table" />
								<br/>
								{@themes.view_real_preview}
							</a>
							# START themes_installed.pictures #
								<a href="{themes_installed.pictures.URL}" data-lightbox="{themes_installed.ID}" data-rel="lightcase:collection" title="{themes_installed.NAME}"></a>
							# END themes_installed.pictures #
						# ENDIF #

					</td>
					<td class="left">
						<div id="desc_explain{themes_installed.ID}">
							<span class="text-strong">{@themes.author} :</span> # IF themes_installed.C_AUTHOR_EMAIL #<a href="mailto:{themes_installed.AUTHOR_EMAIL}">{themes_installed.AUTHOR}</a># ELSE #{themes_installed.AUTHOR}# ENDIF # # IF themes_installed.C_AUTHOR_WEBSITE #<a href="{themes_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
							<span class="text-strong">{@themes.description} :</span> {themes_installed.DESCRIPTION}<br />
							<span class="text-strong">{@themes.compatibility} :</span> PHPBoost {themes_installed.COMPATIBILITY}<br />
							<span class="text-strong">{@themes.html_version} :</span> {themes_installed.HTML_VERSION}<br />
							<span class="text-strong">{@themes.css_version} :</span> {themes_installed.CSS_VERSION}<br />
							<span class="text-strong">{@themes.main_color} :</span> {themes_installed.MAIN_COLOR}<br />
							<span class="text-strong">{@themes.width} :</span> {themes_installed.WIDTH}<br />
						</div>
					</td>
					<td>
						# IF NOT themes_installed.C_IS_DEFAULT_THEME #
							<div id="authorizations_explain-{themes_installed.ID}">{themes_installed.AUTHORIZATIONS}</div>
						# ELSE #
							${LangLoader::get_message('visitor', 'user-common')}
						# ENDIF #
					</td>

					# IF NOT themes_installed.C_IS_DEFAULT_THEME #
					<td class="input-radio">
						<div class="form-field-radio">
							<input id="activated-{themes_installed.ID}" type="radio" name="activated-{themes_installed.ID}" value="1" # IF themes_installed.C_IS_ACTIVATED # checked="checked" # ENDIF #>
							<label for="activated-{themes_installed.ID}"></label>
						</div>
						<span class="form-field-radio-span">${LangLoader::get_message('yes', 'common')}</span>
						<br />
						<div class="form-field-radio">
							<input id="activated-{themes_installed.ID}2" type="radio" name="activated-{themes_installed.ID}" value="0" # IF NOT themes_installed.C_IS_ACTIVATED # checked="checked" # ENDIF #>
							<label for="activated-{themes_installed.ID}2"></label>
						</div>
						<span class="form-field-radio-span">${LangLoader::get_message('no', 'common')}</span>
						<label> </label>
						<label> </label>
					</td>
					<td>
						<button type="submit" class="submit" name="delete-{themes_installed.ID}" value="true">{@themes.uninstall_theme}</button>
					</td>
					# ELSE #
					<td>
						${LangLoader::get_message('yes', 'common')}
					</td>
					<td>
					</td>
					# ENDIF #

				</tr>
			# END themes_installed #
		</tbody>
	</table>

	<fieldset class="fieldset-submit">
		<legend>{L_SUBMIT}</legend>
		<button type="submit" class="submit" name="update_themes_configuration" value="true">${LangLoader::get_message('update', 'main')}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="update" value="true">
		<button type="reset" value="true">${LangLoader::get_message('reset', 'main')}</button>
	</fieldset>
</form>
