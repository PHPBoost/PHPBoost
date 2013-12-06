<section>
	<header>
		<h1>{@profile}</h1>
	</header>
	<div class="content">
		<p style="text-align:center;" class="text_strong">${LangLoader::get_message('welcome', 'main')} {PSEUDO}</p>
		
		<table class="module_table" style="width:99%;margin-top:15px;">
			<tr>
				<td class="row2" style="text-align:center;">
					<a href="{U_EDIT_PROFILE}" title="">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/member.png" alt="{profile.edit}" title="{profile.edit}" />
					</a>
					<br />
					<a href="{U_EDIT_PROFILE}">{profile.edit}</a> <br /><br />
				</td>
				<td class="row2" style="text-align:center;">
					<a href="{U_USER_PM}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/# IF C_HAS_PM #new_pm.gif# ELSE #pm.png# ENDIF #" alt="${LangLoader::get_message('private_message', 'main')}" title="${LangLoader::get_message('private_message', 'main')}" />
					</a>
					<br />
					<a href="{U_USER_PM}">{NUMBER_PM} ${LangLoader::get_message('private_message', 'main')}</a> <br /><br />
				</td>
				# IF C_USER_AUTH_FILES #
				<td class="row2" style="text-align:center;">
					<a href="{U_UPLOAD}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="${LangLoader::get_message('files_management', 'main')}" title="${LangLoader::get_message('files_management', 'main')}" />
					</a>
					<br />
					<a href="{U_UPLOAD}">${LangLoader::get_message('files_management', 'main')}</a> <br /><br />
				</td>
				# ENDIF #
				<td class="row2" style="text-align:center;">
					<a href="{U_CONTRIBUTION_PANEL}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution.png" alt="${LangLoader::get_message('contribution_panel', 'main')}" title="${LangLoader::get_message('contribution_panel', 'main')}" />
					</a>
					<br />
					<a href="{U_CONTRIBUTION_PANEL}">${LangLoader::get_message('contribution_panel', 'main')}</a> <br /><br />
				</td>
				# IF C_IS_MODERATOR #
				<td class="row2" style="text-align:center;">
					<a href="{U_MODERATION_PANEL}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/moderation_panel.png" alt="${LangLoader::get_message('moderation_panel', 'main')}" title="${LangLoader::get_message('moderation_panel', 'main')}" />
					</a>
					<br />
					<a href="{U_MODERATION_PANEL}">${LangLoader::get_message('moderation_panel', 'main')}</a> <br /><br />
				</td>
				# ENDIF #
			</tr>
		</table>
		<br /><br />
		{MSG_MBR}
	</div>
	<footer></footer>
</section>