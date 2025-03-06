<article id="Id" class="message-container message-small" itemscope="itemscope" itemtype="https://schema.org/Comment">
    <header class="message-header-container">
        # IF C_USER_AVATAR #<img class="message-user-avatar" src="# IF C_USER_HAS_AVATAR #{U_USER_AVATAR}# ELSE #{U_DEFAULT_AVATAR}# ENDIF #" alt="{@common.avatar}"># ENDIF #
        <div class="message-header-infos">
            <div class="message-user-infos hidden-small-screens">
                <div></div>
                <div class="message-user-links"></div>
            </div>
            <div class="message-user">
                <h3 class="message-user-pseudo">
					<i class="fa fa-# IF C_USER_ONLINE #fa-user-check success# ELSE #fa-user-times error# ENDIF #" aria-hidden="true"></i>
                    # IF C_USER_PSEUDO #
                        <a class="offload" href="{U_USER_PROFILE}">{USER_PSEUDO}</a>
                        <span class="sr-only"># IF C_USER_ONLINE #{@forum.connected.member)}# ELSE #{@forum.not.connected.member)}# ENDIF #</span>
                    # ELSE #
                        <span>{@user.guest}</span>
                    # ENDIF #
				</h3>
                <div class="message-actions">
                </div>
            </div>
            <div class="message-infos">
                <time datetime="{DATE}" itemprop="datePublished">{@common.on.date} : {DATE}</time>
                <a class="offload" href="{U_TOPIC}">{@forum.topic} : {TITLE}</a>
            </div>
        </div>
    </header>
    <div class="message-content">
        {CONTENT}
    </div>
</article>
