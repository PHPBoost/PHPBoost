<div class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r">
        <span style="float: right;"><a href="{U_CREATE}" title="{EL_CREATE_NEW_BLOG}">{L_CREATE_NEW_BLOG}</a></span>
    </div>
    <div class="module_top">{L_BLOGS_LIST}</div>
    <div class="module_contents">
        # START blogs #
        <div class="module_position">
            <div class="module_top_l"></div>
            <div class="module_top_r"></div>
            <div class="module_top">
                <span style="float: left; padding-left: 5px;"><a href="{blogs.U_DETAILS}" title="{blogs.E_TITLE}">{blogs.TITLE}</a></span>
                <span style="float: right;">
                    <a href="{blogs.U_EDIT}" title="{EL_EDIT}">
                        <img class="valign_middle" src="{PATH_TO_ROOT}/templates/base/images/{LANG}/edit.png" alt="{EL_EDIT}">
                    </a>
                    <a href="{blogs.U_DELETE}" title="{EL_DELETE}" onclick="return confirm({JL_CONFIRM_DELETE_BLOG});">
                        <img class="valign_middle" src="{PATH_TO_ROOT}/templates/base/images/{LANG}/delete.png" alt="{EL_DELETE}">
                    </a>
                </span>
            </div>
            <div class="module_contents">
                {blogs.DESCRIPTION}
                <div class="spacer"></div>
            </div>
            <div class="module_bottom_l"></div>
            <div class="module_bottom_r"><span style="float:right">{blogs.USER}</span></div>
            <div class="module_bottom"></div>
        </div>
        <br />
        # END blogs #
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom"></div>
</div>