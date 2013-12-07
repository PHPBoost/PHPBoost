<section>
	<header>
		<h1>{@profile}</h1>
	</header>
	<div class="content">
		<p style="text-align:center;" class="text_strong">${LangLoader::get_message('welcome', 'main')} {PSEUDO}</p>
		
		<ul style="width:99%;margin:30px auto;">
			<li class="small_block">
				<a href="{U_EDIT_PROFILE}" title="">
					<i class="icon-user icon-2x"></i><br/>
					{@profile.edit}
				</a> 
			</li>
			<li class="small_block">
				<a href="{U_USER_PM}">
					# IF C_HAS_PM #
					<span class="icon-stack">
						<i class="icon-circle blink icon-circle-alerte">
							<span>{NUMBER_PM}</span>
						</i>
						<i class="icon-envelope-o icon-stack-2x"></i>
					</span><br/>
					# ELSE #
					<i class="icon-envelope-o icon-2x"></i><br/>
					# END IF #
					${LangLoader::get_message('private_message', 'main')}
				</a>
			</li>
			# IF C_USER_AUTH_FILES #
			<li class="small_block">
				<a href="{U_UPLOAD}">
					<i class="icon-cloud-upload icon-2x"></i><br />
					${LangLoader::get_message('files_management', 'main')}
				</a>
			</li>
			# ENDIF #
			<li class="small_block">
				<a href="{U_CONTRIBUTION_PANEL}">
					# IF C_UNREAD_CONTRIBUTION #
					<span class="icon-stack">
						<i class="icon-circle blink icon-circle-alerte">
							<span>{NUMBER_UNREAD_CONTRIBUTIONS}</span>
						</i>
						<i class="icon-file-text-o icon-stack-2x"></i>
					</span><br/>
					# ELSE #
					<i class="icon-file-text-o icon-2x"></i><br />
					# ENDIF #
					${LangLoader::get_message('contribution_panel', 'main')}
				</a>
			</li>
			# IF C_IS_MODERATOR #
			<li class="small_block">
				<a href="{U_MODERATION_PANEL}">
					<i class="icon-gavel icon-2x"></i><br />
					${LangLoader::get_message('moderation_panel', 'main')}
				</a>
			</li>
			# ENDIF #
		</ul>
		{MSG_MBR}
	</div>
	<footer></footer>
</section>