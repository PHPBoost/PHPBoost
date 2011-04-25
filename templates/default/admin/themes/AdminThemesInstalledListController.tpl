<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/lightbox/lightbox.js"></script>
<form action="{REWRITED_SCRIPT}" method="post">
	<table class="module_table" style="width:99%;margin-bottom:30px;">
		<tr> 
			<th colspan="4">
				{@themes.installed}
			</th>
		</tr>
		<tr> 
			<td class="row2" colspan="4" style="text-align:center;">
				# INCLUDE MSG #	
				<strong>{@themes.default_theme_explain}</strong>
			</td>
		</tr>
		<tr>
			<td class="row2" style="width:100px;text-align:center;">
				{@themes.name}
			</td>
			<td class="row2" style="width:200px;text-align:center;">
				{@themes.description}
			</td>
			<td class="row2" style="width:200px;text-align:center;">
				{@themes.authorisations}
			</td>
			<td class="row2" style="width:80px;text-align:center;">
				{@themes.activated}
			</td>
		</tr>
		# START themes_installed #
			<tr> 	
				<td class="row2" style="text-align:center; # IF themes_installed.C_IS_DEFAULT_THEME # background-color: #E1E1E1 # ENDIF #">					
					<span id="theme-{themes_installed.ID}"></span>
					<strong>{themes_installed.NAME}</strong> <em>({themes_installed.VERSION})</em>
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
				<td class="row2" # IF themes_installed.C_IS_DEFAULT_THEME # style="background-color: #E1E1E1" # ENDIF #>
					<div id="desc_explain{themes_installed.ID}">
						<strong>{@themes.author}:</strong> 
						<a href="mailto:{themes_installed.AUTHOR_EMAIL}">
							{themes_installed.AUTHOR_NAME}
						</a>
						# IF themes_installed.C_WEBSITE # 
						<a href="{themes_installed.AUTHOR_WEBSITE}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/user_web.png" />
						</a>
						# ENDIF #
						<br />
						<strong>{@themes.description}:</strong> {themes_installed.DESCRIPTION}<br />
						<strong>{@themes.compatibility}:</strong> PHPBoost {themes_installed.COMPATIBILITY}<br />
						<strong>{@themes.html_version}:</strong> {themes_installed.HTML_VERSION}<br />
						<strong>{@themes.css_version}:</strong> {themes_installed.CSS_VERSION}<br />
						<strong>{@themes.main_color}:</strong> {themes_installed.MAIN_COLOR}<br />
						<strong>{@themes.width}:</strong> {themes_installed.WIDTH}<br />
					</div>
				</td>
				<td class="row2" # IF themes_installed.C_IS_DEFAULT_THEME # style="background-color: #E1E1E1" # ENDIF #>
					# IF NOT themes_installed.C_IS_DEFAULT_THEME #
						<div id="authorizations_explain-{themes_installed.ID}">
							{themes_installed.AUTHORIZATIONS}
						</div>
					# ELSE #
						<div style="text-align:center;">
							{@themes.visitor}
						</div>
					# ENDIF #
				</td>
				<td class="row2" style="text-align:center; # IF themes_installed.C_IS_DEFAULT_THEME # background-color: #E1E1E1 # ENDIF #">
					# IF NOT themes_installed.C_IS_DEFAULT_THEME #
						<label><input type="radio" name="activated-{themes_installed.ID}" value="1" # IF themes_installed.C_IS_ACTIVATED # checked="checked" # ENDIF # /> {@themes.yes}</label>
						<label><input type="radio" name="activated-{themes_installed.ID}" value="0" # IF NOT themes_installed.C_IS_ACTIVATED # checked="checked" # ENDIF # /> {@themes.no}</label>
						<br /><br />
						<a href="{PATH_TO_ROOT}/admin/themes/index.php?url=/{themes_installed.ID}/delete/">
							<input name="delete-{themes_installed.ID}" value="{L_DELETE}" class="submit" style="width:70px;text-align:center;"/>
						</a>
					# ELSE #
						{@themes.yes}
					# ENDIF #
				</td>
			</tr>						
		# END themes_installed #
	</table>
	
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		<input type="submit" name="update_themes_configuration" value="{L_UPDATE}" class="submit" />
		<input type="hidden" name="token" value="{TOKEN}" />
		<input type="hidden" name="update" value="true" />
		&nbsp;&nbsp; 
		<input type="reset" value="{L_RESET}" class="reset" />
	</fieldset>
</form>