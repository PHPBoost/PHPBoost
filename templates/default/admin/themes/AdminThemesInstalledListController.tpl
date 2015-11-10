<form action="{REWRITED_SCRIPT}" method="post">
	<table id="table">
		<caption>{@themes.installed_theme}</caption>
		<thead>
			<tr> 
				<th>{@themes.name}</th>
				<th>{@themes.description}</th>
				<th>{@themes.authorization}</th>
				<th>${LangLoader::get_message('enabled', 'common')}</th>
				<th>${LangLoader::get_message('delete', 'common')}</th>
			</tr>
		</thead>
		<tbody>
			<tr> 
				<td colspan="5">
					# INCLUDE MSG #	
					<span class="text-strong">{@themes.default_theme_explain}</span>
				</td>
			</tr>
			# START themes_installed #
				<tr> 	
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
							<span class="text-strong">{@themes.author} :</span> 
							<a href="mailto:{themes_installed.AUTHOR_EMAIL}">{themes_installed.AUTHOR_NAME}</a>
							# IF themes_installed.C_WEBSITE # 
							<a href="{themes_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a>
							# ENDIF #
							<br />
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
						<button type="submit" class="submit" name="delete-{themes_installed.ID}" value="true">${LangLoader::get_message('delete', 'common')}</button>
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
		<button type="submit" class="submit" name="update_themes_configuration" value="true">{L_UPDATE}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="update" value="true">
		<button type="reset" value="true">{L_RESET}</button>
	</fieldset>
</form>