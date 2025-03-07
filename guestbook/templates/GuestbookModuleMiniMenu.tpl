# IF C_ANY_MESSAGE_GUESTBOOK #
    <div class="cell-body">
        <div class="cell-content">
            # IF C_HORIZONTAL #
                {CONTENT}
            # ELSE #
                {SUMMARY}
                # IF C_SUMMARY #... <a href="{U_MESSAGE}" class="small pinned offload">{@common.read.more}</a># ENDIF #
            # ENDIF #
            <p class="small">
                {@common.by}
                # IF C_VISITOR #
                    <span class="text-italic">{AUTHOR_DISPLAY_NAME}</span>
                # ELSE #
                    <a itemprop="author" href="{U_AUTHOR_PROFILE}" class="{AUTHOR_LEVEL_CLASS} offload" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR_DISPLAY_NAME}</a>
                # ENDIF #
            </p>
        </div>
        <div class="cell-content align-center">
            <a class="button small offload" href="{U_GUESTBOOK}">{@guestbook.module.title}</a>
        </div>
    </div>
# ELSE #
    <div class="cell-alert">
        <div class="message-helper bgc notice">{@common.no.item.now}</div>
    </div>
# ENDIF #
