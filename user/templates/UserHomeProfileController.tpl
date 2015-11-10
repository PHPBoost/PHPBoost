<section id="module-user-home">
	<header>
		<h1>{@profile}</h1>
	</header>
	<div class="content">
		<p class="center text-strong">{@welcome} {PSEUDO}</p>
		
		<ul id="profile-container">
			<li class="small-block">
				<a href="{U_VIEW_PROFILE}" title="">
					<i class="fa fa-user fa-2x"></i><br/>
					${LangLoader::get_message('my_private_profile', 'main')}
				</a> 
			</li>
			<li class="small-block">
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
			<li class="small-block">
				<a href="{U_UPLOAD}">
					<i class="fa fa-cloud-upload fa-2x"></i><br />
					${LangLoader::get_message('files_management', 'main')}
				</a>
			</li>
			# ENDIF #
			# IF IS_ADMIN #
			<li class="small-block">
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
			<li class="small-block">
				<a href="{U_MODERATION_PANEL}">
					<i class="fa fa-gavel fa-2x"></i><br />
					${LangLoader::get_message('moderation_panel', 'main')}
				</a>
			</li>
			# ENDIF #
			<li class="small-block">
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
		<div class="spacer"></div>
		{MSG_MBR}
	</div>
	<footer></footer>
</section>