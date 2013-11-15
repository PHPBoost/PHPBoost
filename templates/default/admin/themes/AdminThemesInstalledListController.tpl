<form action="{REWRITED_SCRIPT}" method="post">
	{@themes.installed}
	<table>
		<thead>
			<tr> 
				<th>
					{@themes.name}
				</th>
				<th>
					{@themes.description}
				</th>
				<th>
					{@themes.authorisations}
				</th>
				<th>
					{@themes.activated}
				</th>
			</tr>
		</thead>
		<tbody>
			<tr> 
				<td colspan="4">
					# INCLUDE MSG #	
					<span class="text_strong">{@themes.default_theme_explain}</span>
				</td>
			</tr>
			# START themes_installed #
				<tr> 	
					<td class="# IF themes_installed.C_IS_DEFAULT_THEME # row_disabled # ENDIF #">					
						<span id="theme-{themes_installed.ID}"></span>
						<span class="text_strong">{themes_installed.NAME}</span> <em>({themes_installed.VERSION})</em>
						<br /><br />
						# IF themes_installed.C_PICTURES #
							<a href="{themes_installed.MAIN_PICTURE}" rel="lightbox[{themes_installed.ID}]" title="{themes_installed.NAME}">
								<img src="{themes_installed.MAIN_PICTURE}" alt="{themes_installed.NAME}" style="vertical-align:top; max-height:180px;" />
								<br/>
								{@themes.view_real_preview}
							</a>
							# START themes_installed.pictures #
								<a href="{themes_installed.pictures.URL}" rel="lightbox[{themes_installed.ID}]" title="{themes_installed.NAME}"></a>
							# END themes_installed.pictures #
						# ENDIF #
						
					</td>
					<td class="# IF themes_installed.C_IS_DEFAULT_THEME # row_disabled # ENDIF #">
						<div id="desc_explain{themes_installed.ID}">
							<span class="text_strong">{@themes.author}:</span> 
							<a href="mailto:{themes_installed.AUTHOR_EMAIL}">
								{themes_installed.AUTHOR_NAME}
							</a>
							# IF themes_installed.C_WEBSITE # 
							<a href="{themes_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a>
							# ENDIF #
							<br />
							<span class="text_strong">{@themes.description}:</span> {themes_installed.DESCRIPTION}<br />
							<span class="text_strong">{@themes.compatibility}:</span> PHPBoost {themes_installed.COMPATIBILITY}<br />
							<span class="text_strong">{@themes.html_version}:</span> {themes_installed.HTML_VERSION}<br />
							<span class="text_strong">{@themes.css_version}:</span> {themes_installed.CSS_VERSION}<br />
							<span class="text_strong">{@themes.main_color}:</span> {themes_installed.MAIN_COLOR}<br />
							<span class="text_strong">{@themes.width}:</span> {themes_installed.WIDTH}<br />
						</div>
					</td>
					<td class="# IF themes_installed.C_IS_DEFAULT_THEME # row_disabled # ENDIF #">
						# IF NOT themes_installed.C_IS_DEFAULT_THEME #
							<div id="authorizations_explain-{themes_installed.ID}">
								{themes_installed.AUTHORIZATIONS}
							</div>
						# ELSE #
							<div>
								{@themes.visitor}
							</div>
						# ENDIF #
					</td>
					<td class="# IF themes_installed.C_IS_DEFAULT_THEME # row_disabled # ENDIF #">
						# IF NOT themes_installed.C_IS_DEFAULT_THEME #
							<label><input type="radio" name="activated-{themes_installed.ID}" value="1" # IF themes_installed.C_IS_ACTIVATED # checked="checked" # ENDIF #> {@themes.yes}</label>
							<label><input type="radio" name="activated-{themes_installed.ID}" value="0" # IF NOT themes_installed.C_IS_ACTIVATED # checked="checked" # ENDIF #> {@themes.no}</label>
							<br /><br />
							<a href="{themes_installed.DELETE_LINK}">
								<input name="delete-{themes_installed.ID}" value="{L_DELETE}" style="width:70px;text-align:center;"/>
							</a>
						# ELSE #
							{@themes.yes}
						# ENDIF #
					</td>
				</tr>						
			# END themes_installed #
		</tbody>
	</table>
	
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		<button type="submit" name="update_themes_configuration" value="true">{L_UPDATE}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="update" value="true">
		&nbsp;&nbsp; 
		<button type="reset" value="true">{L_RESET}</button>
	</fieldset>
</form>