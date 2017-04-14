<section id="module-user-home">
	<header>
		<h1>{@dashboard}</h1>
	</header>
	<div class="content">
		<p class="center text-strong">{@welcome} {PSEUDO}</p>
		
		<ul id="profile-container" class="elements-container columns-3">
			<li class="block">
				<a href="{U_VIEW_PROFILE}" title=""><i class="fa fa-user fa-2x"></i><span class="profile-element-title">${LangLoader::get_message('my_private_profile', 'main')}</span></a> 
			</li>
			<li class="block">
				<a href="{U_USER_PM}">
					# IF C_HAS_PM #
					<span class="fa fa-stack"><i class="fa fa-circle blink fa-circle-alert"><span>{NUMBER_PM}</span></i><i class="fa fa-envelope-o fa-stack-2x"></i></span><!--
				 --># ELSE #<!--
				 --><i class="fa fa-envelope-o fa-2x"></i><!--
				 --># END IF #<!--
				 --><span class="profile-element-title">${LangLoader::get_message('private_message', 'main')}</span>
				</a>
			</li>
			# IF C_USER_AUTH_FILES #
			<li class="block">
				<a href="{U_UPLOAD}">
					<i class="fa fa-cloud-upload fa-2x"></i><span class="profile-element-title">${LangLoader::get_message('files_management', 'main')}</span>
				</a>
			</li>
			# ENDIF #
			# IF IS_ADMIN #
			<li class="block">
				<a href="{PATH_TO_ROOT}/admin/">
					# IF C_UNREAD_ALERT #
					<span class="fa fa-stack"><i class="fa fa-circle blink fa-circle-alert"><span>{NUMBER_UNREAD_ALERTS}</span></i><i class="fa fa-wrench fa-stack-2x"></i></span><!--
				 --># ELSE #<!--
				 --><i class="fa fa-wrench fa-2x"></i><!--
				 --># ENDIF #<!--
				 --><span class="profile-element-title">${LangLoader::get_message('admin_panel', 'main')}</span>
				</a>
			</li>
			# ENDIF #
			# IF C_IS_MODERATOR #
			<li class="block">
				<a href="{U_MODERATION_PANEL}">
					<i class="fa fa-gavel fa-2x"></i><span class="profile-element-title">${LangLoader::get_message('moderation_panel', 'main')}</span>
				</a>
			</li>
			# ENDIF #
			<li class="block">
				<a href="{U_CONTRIBUTION_PANEL}">
					# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION #
					<span class="fa fa-stack"><i class="fa fa-circle blink fa-circle-alert"><span>{NUMBER_UNREAD_CONTRIBUTIONS}</span></i><i class="fa fa-file-text-o fa-stack-2x"></i></span><!--
				 --># ELSE #<!--
				 --><i class="fa fa-file-text-o fa-2x"></i><!--
				 --># ENDIF #<!--
				 --><span class="profile-element-title">${LangLoader::get_message('contribution_panel', 'main')}</span>
				</a>
			</li>
		</ul>
		<div class="spacer"></div>
		{MSG_MBR}
	</div>
	<footer></footer>
</section>