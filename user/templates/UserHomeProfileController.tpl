<section>
	<header>
		<h1>{@profile}</h1>
	</header>
	<div class="content" >
		<p style="text-align:center;" class="text-strong">${LangLoader::get_message('welcome', 'main')} {PSEUDO}</p>
		
		<ul class="center" style="width: 99%;margin: 30px auto; display: flex; display: -webkit-flex; display: -ms-flex;">
			<li class="small-block" style="padding: 10px 0px;  margin-right: 2px;">
				<a href="{U_EDIT_PROFILE}" title="">
					<i class="fa fa-user fa-2x"></i><br/>
					{@profile.edit}
				</a> 
			</li>
			<li class="small-block" style="padding: 10px 2px; margin-right: 2px;">
				<a href="{U_USER_PM}">
					# IF C_HAS_PM #
					<span class="fa fa-stack">
						<i class="fa fa-circle blink fa-circle-alert">
							<span>{NUMBER_PM}</span>
						</i>
						<i class="fa fa-envelope-o fa-stack-2x"></i>
					</span><br/>
					# ELSE #
					<i class="fa fa-envelope-o fa-2x"></i><br/>
					# END IF #
					${LangLoader::get_message('private_message', 'main')}
				</a>
			</li>
			# IF C_USER_AUTH_FILES #
			<li class="small-block" style="padding: 10px 0px; margin-right: 2px;">
				<a href="{U_UPLOAD}">
					<i class="fa fa-cloud-upload fa-2x"></i><br />
					${LangLoader::get_message('files_management', 'main')}
				</a>
			</li>
			# ENDIF #
			# IF IS_ADMIN #
			<li class="small-block" style="padding: 10px 0px; width: 210px; margin-right: 2px;">
				<a href="{PATH_TO_ROOT}/admin/">
					# IF C_UNREAD_ALERT #
					<span class="fa fa-stack">
						<i class="fa fa-circle blink fa-circle-alert">
							<span>{NUMBER_UNREAD_ALERTS}</span>
						</i>
						<i class="fa fa-wrench fa-stack-2x"></i>
					</span><br/>
					# ELSE #
					<i class="fa fa-wrench fa-2x"></i><br />
					# ENDIF #
					${LangLoader::get_message('admin_panel', 'main')}
				</a>
			</li>
			# ENDIF #
			# IF C_IS_MODERATOR #
			<li class="small-block" style="padding: 10px 0px; margin-right: 2px;">
				<a href="{U_MODERATION_PANEL}">
					<i class="fa fa-gavel fa-2x"></i><br />
					${LangLoader::get_message('moderation_panel', 'main')}
				</a>
			</li>
			# ENDIF #
			<li class="small-block" style="padding: 10px 0px;">
				<a href="{U_CONTRIBUTION_PANEL}">
					# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION #
					<span class="fa fa-stack">
						<i class="fa fa-circle blink fa-circle-alert">
							<span>{NUMBER_UNREAD_CONTRIBUTIONS}</span>
						</i>
						<i class="fa fa-file-text-o fa-stack-2x"></i>
					</span><br/>
					# ELSE #
					<i class="fa fa-file-text-o fa-2x"></i><br />
					# ENDIF #
					${LangLoader::get_message('contribution_panel', 'main')}
				</a>
			</li>
		</ul>
		{MSG_MBR}
	</div>
	<footer></footer>
</section>