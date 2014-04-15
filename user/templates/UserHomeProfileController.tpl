<section>
	<header>
		<h1>{@profile}</h1>
	</header>
	<div class="content">
		<p style="text-align:center;" class="text-strong">${LangLoader::get_message('welcome', 'main')} {PSEUDO}</p>
		
		<ul class="center" style="width:99%;margin:30px auto;">
			<li class="small-block" style="max-width:93px;">
				<a href="{U_EDIT_PROFILE}" title="">
					<i class="fa fa-user fa-2x"></i><br/>
					{@profile.edit}
				</a> 
			</li>
			<li class="small-block" style="max-width:93px;">
				<a href="{U_USER_PM}">
					# IF C_HAS_PM #
					<span class="fa fa-stack">
						<i class="fa fa-circle blink fa-circle-alert">
							<span>{NUMBER_PM}</span>
						</i>
						<i class="fa fa-envelope-o fa-stack-2x"></i>
					</span><br/>
					# ELSE #
					<span class="fa fa-stack">
						<i class="fa fa-circle blink fa-circle-alert">
							<span>1</span>
						</i>
						<i class="fa fa-envelope-o fa-stack-2x"></i>
					</span><br/>
					# END IF #
					${LangLoader::get_message('private_message', 'main')}
				</a>
			</li>
			# IF C_USER_AUTH_FILES #
			<li class="small-block" style="max-width:93px;">
				<a href="{U_UPLOAD}">
					<i class="fa fa-cloud-upload fa-2x"></i><br />
					${LangLoader::get_message('files_management', 'main')}
				</a>
			</li>
			# ENDIF #
			<li class="small-block" style="max-width:93px;">
				<a href="{U_CONTRIBUTION_PANEL}">
					# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION #
					<span class="fa fa-stack">
						<i class="fa fa-circle blink fa-circle-alert">
							<span>{NUMBER_UNREAD_CONTRIBUTIONS}</span>
						</i>
						<i class="fa fa-file-text-o fa-stack-2x"></i>
					</span><br/>
					# ELSE #
					<span class="fa fa-stack">
						<i class="fa fa-circle blink fa-circle-alert">
							<span>2</span>
						</i>
						<i class="fa fa-envelope-o fa-stack-2x"></i>
					</span><br/>
					# ENDIF #
					${LangLoader::get_message('contribution_panel', 'main')}
				</a>
			</li>
			# IF C_IS_MODERATOR #
			<li class="small-block" style="max-width:93px;">
				<a href="{U_MODERATION_PANEL}">
					<i class="fa fa-gavel fa-2x"></i><br />
					${LangLoader::get_message('moderation_panel', 'main')}
				</a>
			</li>
			# ENDIF #
		</ul>
		{MSG_MBR}
	</div>
	<footer></footer>
</section>