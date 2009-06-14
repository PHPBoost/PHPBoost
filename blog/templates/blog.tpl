<div class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r">
        <span style="float: right;">
            <a href="{U_EDIT}" title="{EL_EDIT}">
                <img class="valign_middle" src="{PATH_TO_ROOT}/templates/base/images/{LANG}/edit.png" alt="{EL_EDIT}">
            </a>
            <a href="{U_DELETE}" title="{EL_DELETE}" onclick="return confirm({JL_CONFIRM_DELETE});">
                <img class="valign_middle" src="{PATH_TO_ROOT}/templates/base/images/{LANG}/delete.png" alt="{EL_DELETE}">
            </a>
        </span>
    </div>
    <div class="module_top">{TITLE}</div>
    <div class="module_contents">
        <div class="module_position">
            <div class="module_contents">{DESCRIPTION}<div class="spacer"></div></div>
            <span style="float:right">{USER}</span>
        </div>
        # START posts #
        <div class="module_position">
            <div class="module_top_l"></div>
            <div class="module_top_r"></div>
            <div class="module_top">
                <span style="float: left; padding-left: 5px;"><a href="{blogs.U_DETAILS}" title="{blogs.E_TITLE}">{posts.TITLE}</a></span>
                <span style="float: right;">
                    <!--  <a href="{blogs.U_EDIT}" title="{EL_EDIT}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/base/images/{LANG}/edit.png" alt="{EL_EDIT}"></a>
                    <a href="{blogs.U_DELETE}" title="{EL_DELETE}" onclick="return confirm({JL_CONFIRM_DELETE});">
                        <img class="valign_middle" src="{PATH_TO_ROOT}/templates/base/images/{LANG}/delete.png" alt="{EL_DELETE}">
                    </a>-->
                </span>
            </div>
            <div class="module_contents">
                {posts.CONTENT}
                <div class="spacer"></div>
            </div>
            <div class="module_bottom_l"></div>
            <div class="module_bottom_r"><span style="float:right">{posts.DATE}</span></div>
            <div class="module_bottom"></div>
        </div>
        # END posts #
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom"></div>
</div>