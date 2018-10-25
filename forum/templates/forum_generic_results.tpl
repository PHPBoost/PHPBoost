<div class="message">
	<div class="message-container">
	
		<div class="message-user-infos">
			<div class="message-pseudo">
				<i class="fa fa-# IF C_USER_ONLINE #fa-online# ELSE #fa-offline# ENDIF #"></i> # IF C_USER_PSEUDO #<a href="{U_USER_PROFILE}">{USER_PSEUDO}</a># ELSE #<span>${LangLoader::get_message('guest', 'main')}</span># ENDIF #
			</div>
			<div class="message-level"></div>
			<img src="# IF C_USER_AVATAR #{U_USER_AVATAR}# ELSE #{U_DEFAULT_AVATAR}# ENDIF #" alt="${LangLoader::get_message('avatar', 'user-common')}" title="${LangLoader::get_message('avatar', 'user-common')}" class="message-avatar"/>
		</div>

		<div class="message-date">
			<span class="actions">
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
</div>