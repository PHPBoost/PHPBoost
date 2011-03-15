<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><strong>{L_PROFIL}</strong></div>
			<div class="module_contents">
				<p style="text-align:center;" class="text_strong">{L_WELCOME} {USER_NAME}</p>
				
				<table class="module_table" style="width:99%;margin-top:15px;">
					<tr>
						<td class="row2" style="text-align:center;">
							<a href="{PATH_TO_ROOT}/member/index.php?url=/profile/edit" title="">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/member.png" alt="{L_PROFIL_EDIT}" title="{L_PROFIL_EDIT}" />
							</a>
							<br />
							<a href="{PATH_TO_ROOT}/member/index.php?url=/profile/edit">{L_PROFIL_EDIT}</a> <br /><br />
						</td>
						<td class="row2" style="text-align:center;">
							<a href="pm{U_USER_PM}">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{IMG_PM}" alt="{L_PRIVATE_MESSAGE}" title="{L_PRIVATE_MESSAGE}" />
							</a>
							<br />
							<a href="pm{U_USER_PM}">{PM} {L_PRIVATE_MESSAGE}</a> <br /><br />
						</td>
						# IF C_USER_AUTH_FILES #
						<td class="row2" style="text-align:center;">
							<a href="upload.php{SID}">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="{L_FILES_MANAGEMENT}" title="{L_FILES_MANAGEMENT}" />
							</a>
							<br />
							<a href="upload.php{SID}">{L_FILES_MANAGEMENT}</a> <br /><br />
						</td>				
						# ENDIF #
						<td class="row2" style="text-align:center;">
							<a href="{U_CONTRIBUTION_PANEL}">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution.png" alt="{L_CONTRIBUTION_PANEL}" title="{L_CONTRIBUTION_PANEL}" />
							</a>
							<br />
							<a href="{U_CONTRIBUTION_PANEL}">{L_CONTRIBUTION_PANEL}</a> <br /><br />
						</td>
						# IF C_IS_MODERATOR #
						<td class="row2" style="text-align:center;">
							<a href="{U_MODERATION_PANEL}">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/moderation_panel.png" alt="{L_MODERATION_PANEL}" title="{L_MODERATION_PANEL}" />
							</a>
							<br />
							<a href="{U_MODERATION_PANEL}">{L_MODERATION_PANEL}</a> <br /><br />
						</td>				
						# ENDIF #
					</tr>
				</table>
				<br /><br />
				{MSG_MBR}
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>