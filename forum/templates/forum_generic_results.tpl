<div class="message">

		<div class="message-user-infos">
			<div class="message-pseudo">
				<i class="fa fa-# IF C_USER_ONLINE #fa-user-check success# ELSE #fa-user-times error# ENDIF #" aria-hidden="true"></i> # IF C_USER_PSEUDO #<a href="{U_USER_PROFILE}">{USER_PSEUDO}</a><span class="sr-only"># IF C_USER_ONLINE #${LangLoader::get_message('forum.connected.mbr.yes', 'common', 'forum')}# ELSE #${LangLoader::get_message('forum.connected.mbr.no', 'common', 'forum')}# ENDIF #</span># ELSE #<span>${LangLoader::get_message('guest', 'main')}</span># ENDIF #
			</div>
			<div class="message-level"></div>
			<img src="# IF C_USER_AVATAR #{U_USER_AVATAR}# ELSE #{U_DEFAULT_AVATAR}# ENDIF #" alt="${LangLoader::get_message('avatar', 'user-common')}" class="message-avatar"/>
		</div>

		<div class="message-date">
			<span class="controls">
				{L_TOPIC} : <a class="small" href="{U_TOPIC}">{TITLE}</a>
			</span>
			<span>{L_ON}: {DATE}</span>
		</div>

		<div class="message-message">
			<div class="message-content">
				{CONTENTS}
			</div>
		</div>

</div>
