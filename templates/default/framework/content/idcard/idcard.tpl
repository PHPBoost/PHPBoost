<div class="id-card">
    <h5>{@about.author}</h5>
    <img class="item-thumbnail" src="{U_AVATAR}" alt="{AUTHOR_NAME}" />
    <h6>
        # IF C_AUTHOR_IS_MEMBER #
            <a itemprop="author" rel="author" class="{USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{AUTHOR_NAME}</a>
        # ELSE #
            {AUTHOR_NAME}
        # ENDIF #
    </h6>
    <p class="biography">{BIOGRAPHY}</p>
</div>
