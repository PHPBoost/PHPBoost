# INCLUDE UPLOAD_FORM #

# IF C_MORE_THAN_ONE_THEME_AVAILABLE #
<script>
<!--
	function select_all(status)
	{
		var i;
		for(i = 1; i <= {THEMES_NUMBER}; i++)
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
		<legend>{@themes.themes_available}</legend>
		<div class="fieldset-inset">
		# IF C_THEME_INSTALL #
			<table id="table">
				<caption>{@themes.themes_available}</caption>
				<thead>
					<tr>
						# IF C_MORE_THAN_ONE_THEME_AVAILABLE #
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
						<th>${LangLoader::get_message('enable', 'common')}</th>
						<th>{@themes.install_theme}</th>
					</tr>
				</thead>
				# IF C_MORE_THAN_ONE_THEME_AVAILABLE #
				<tfoot>
					<tr>
						<td colspan="6">
							<div class="left">
								<div class="form-field-checkbox">
									<input type="checkbox" id="check-all-bottom" onclick="select_all(this.checked);" title="${LangLoader::get_message('select_all', 'main')}" />
									<label for="check-all-bottom"></label>
								</div>
								<button type="submit" name="add-selected-themes" value="true" class="submit">{@themes.install_all_selected_themes}</button>
							</div>
						</td>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START themes_not_installed #
					<tr>
						# IF C_MORE_THAN_ONE_THEME_AVAILABLE #
						<td>
							<div class="form-field-checkbox">
								<input type="checkbox" id="add-checkbox-{themes_not_installed.THEME_NUMBER}" name="add-checkbox-{themes_not_installed.THEME_NUMBER}" />
								<label for="add-checkbox-{themes_not_installed.THEME_NUMBER}"></label>
							</div>
						</td>
						# ENDIF #
						<td>
							<span id="theme-{themes_not_installed.ID}"></span>
							<span class="text-strong">{themes_not_installed.NAME}</span> <span class="text-italic">({themes_not_installed.VERSION})</span>
							<br /><br />
							# IF themes_not_installed.C_PICTURES #
								<a href="{themes_not_installed.MAIN_PICTURE}" data-lightbox="{themes_not_installed.ID}" data-rel="lightcase:collection" title="{themes_not_installed.NAME}">
									<img src="{themes_not_installed.MAIN_PICTURE}" alt="{themes_not_installed.NAME}" class="picture-table" />
									<br/>
									{@themes.view_real_preview}
								</a>
								# START themes_not_installed.pictures #
									<a href="{themes_not_installed.pictures.URL}" data-lightbox="{themes_not_installed.ID}" data-rel="lightcase:collection" title="{themes_not_installed.NAME}"></a>
								# END themes_not_installed.pictures #
							# ENDIF #
						</td>
						<td>
							<div id="desc_explain{themes_not_installed.ID}" class="left">
								<span class="text-strong">{@themes.author} :</span> # IF themes_not_installed.C_AUTHOR_EMAIL #<a href="mailto:{themes_not_installed.AUTHOR_EMAIL}">{themes_not_installed.AUTHOR}</a># ELSE #{themes_not_installed.AUTHOR}# ENDIF # # IF themes_not_installed.C_AUTHOR_WEBSITE #<a href="{themes_not_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
								<span class="text-strong">{@themes.description} :</span> {themes_not_installed.DESCRIPTION}<br />
								<span class="text-strong">{@themes.compatibility} :</span> PHPBoost {themes_not_installed.COMPATIBILITY}<br />
								<span class="text-strong">{@themes.html_version} :</span> {themes_not_installed.HTML_VERSION}<br />
								<span class="text-strong">{@themes.css_version} :</span> {themes_not_installed.CSS_VERSION}<br />
								<span class="text-strong">{@themes.main_color} :</span> {themes_not_installed.MAIN_COLOR}<br />
								<span class="text-strong">{@themes.width} :</span> {themes_not_installed.WIDTH}<br />
							</div>
						</td>
						<td>
							<div id="authorizations_explain-{themes_not_installed.ID}">
								{themes_not_installed.AUTHORIZATIONS}
							</div>
						</td>
						<td class="input-radio">
							<div class="form-field-radio">
								<input id="activated-{themes_not_installed.ID}" type="radio" name="activated-{themes_not_installed.ID}" value="1" checked="checked">
								<label for="activated-{themes_not_installed.ID}"></label>
							</div>
							<span class="form-field-radio-span">${LangLoader::get_message('yes', 'common')}</span>
							<br />
							<div class="form-field-radio">
								<input id="activated-{themes_not_installed.ID}2" type="radio" name="activated-{themes_not_installed.ID}" value="0">
								<label for="activated-{themes_not_installed.ID}2"></label>
							</div>
							<span class="form-field-radio-span">${LangLoader::get_message('no', 'common')}</span>
						</td>
						<td>
							<button type="submit" class="submit" name="add-{themes_not_installed.ID}" value="true">{@themes.install_theme}</button>
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
