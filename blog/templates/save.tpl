<script type="text/javascript">
<!--
function check_create_blog_form()
{
    return true;
}
-->
</script>
<div class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r"></div>
    <div class="module_top">{L_SAVE_BLOG}</div>
    <div class="module_contents">
        # IF L_ERROR_MESSAGE #
            <div class="warning">{L_ERROR_MESSAGE}</div>
        # ENDIF #
        <form action="{U_FORM_VALID}" onsubmit="return check_create_blog_form();" method="post">
            <fieldset>
                <legend>{L_SAVE_BLOG}</legend>
                <dl>
                    <dt><label for="title">* {L_TITLE}</label></dt>
                    <dd><input type="text" id="title" name="title" maxlength="{TITLE_MAX_LENGTH}" value="{E_TITLE}" class="text" /></dd>
                </dl>
                <br />
                <label for="description">* {L_DESCRIPTION}</label>
                {KERNEL_EDITOR} <textarea rows="20" cols="90" id="description" name="description">{DESCRIPTION}</textarea><br />
            </fieldset>
            <fieldset class="fieldset_submit">
                <input type="submit" name="submit" value="{EL_SAVE}" class="submit" />
            </fieldset>
        </form>
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom"></div>
</div>