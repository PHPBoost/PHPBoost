<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/lightbox/lightbox.js"></script>
# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post">
	<table class="module_table" style="width:99%;margin-bottom:30px;">
		<tr> 
			<th colspan="4">
				{@themes.not_installed}
			</th>
		</tr>
		<tr> 
			<td class="row2" colspan="4" style="text-align:center;">
				# INCLUDE MSG #	
			</td>
		</tr>
		# IF C_THEME_INSTALL #
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
		# ELSE #
		<tr>
			<td class="row2" colspan="4" style="text-align:center;">
				<strong>{@themes.add.not_theme}</strong>
			</td>
		</tr>
		# ENDIF #
		# START themes_not_installed #
			<tr> 	
				<td class="row2" style="text-align:center;">					
					<span id="theme-{themes_not_installed.ID}"></span>
					<strong>{themes_not_installed.NAME}</strong> <em>({themes_not_installed.VERSION})</em>
					<br /><br />
					# IF themes_not_installed.C_PICTURES #
						<a href="{themes_not_installed.MAIN_PICTURE}" rel="lightbox[{themes_not_installed.ID}]" title="{themes_not_installed.NAME}">
							<img src="{themes_not_installed.MAIN_PICTURE}" alt="{themes_not_installed.NAME}" style="vertical-align:top; max-height:180px;" />
							<br/>
							{@themes.view_real_preview}
						</a>
						# START themes_not_installed.pictures #
							<a href="{themes_not_installed.pictures.URL}" rel="lightbox[{themes_not_installed.ID}]" title="{themes_not_installed.NAME}"></a>
						# END themes_not_installed.pictures #
					# ENDIF #
					
				</td>
				<td class="row2">
					<div id="desc_explain{themes_not_installed.ID}">
						<strong>{@themes.author}:</strong> 
						<a href="mailto:{themes_not_installed.AUTHOR_EMAIL}">
							{themes_not_installed.AUTHOR_NAME}
						</a>
						# IF themes_not_installed.C_WEBSITE # 
						<a href="{themes_not_installed.AUTHOR_WEBSITE}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/user_web.png" />
						</a>
						# ENDIF #
						<br />
						<strong>{@themes.description}:</strong> {themes_not_installed.DESCRIPTION}<br />
						<strong>{@themes.compatibility}:</strong> PHPBoost {themes_not_installed.COMPATIBILITY}<br />
						<strong>{@themes.html_version}:</strong> {themes_not_installed.HTML_VERSION}<br />
						<strong>{@themes.css_version}:</strong> {themes_not_installed.CSS_VERSION}<br />
						<strong>{@themes.main_color}:</strong> {themes_not_installed.MAIN_COLOR}<br />
						<strong>{@themes.width}:</strong> {themes_not_installed.WIDTH}<br />
					</div>
				</td>
				<td class="row2">
					<div id="authorizations_explain-{themes_not_installed.ID}">
						{themes_not_installed.AUTHORIZATIONS}
					</div>
				</td>
				<td class="row2" style="text-align:center;">
					<label><input type="radio" name="activated-{themes_not_installed.ID}" value="1" checked="checked" /> {@themes.yes}</label>
					<label><input type="radio" name="activated-{themes_not_installed.ID}" value="0" /> {@themes.no}</label>
					<br /><br />
					<input type="submit" name="add-{themes_not_installed.ID}" value="{L_ADD}" class="submit"/>
					<input type="hidden" name="id_theme" value="{themes_not_installed.ID}" />
				</td>
			</tr>						
		# END themes_not_installed #
	</table>
	
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		<input type="hidden" name="token" value="{TOKEN}" />
	</fieldset>
</form>