<script type="text/javascript">
<!--
function check_msg(){
    if(document.getElementById('name').value == "") {
        alert("{L_REQUIRE_TITLE}");
        return false;
    }
    if(document.getElementById('contents').value == "") {
        alert("{L_REQUIRE_TEXT}");
        return false;
    }
    return true;
}
-->
</script>
    
<div id="admin_contents">
    <form action="auth.php?token={TOKEN}" method="post" class="fieldset_content">
        <fieldset> 
            <legend>{L_ACTION_MENUS}</legend>
            <dl>
                <dt><label for="activ">{L_STATUS}</label></dt>
                <dd><label>
                    <select name="activ" id="activ">
                       # IF C_ENABLED #
                            <option value="1" selected="selected">{L_ENABLED}</option>
                            <option value="0">{L_DISABLED}</option>
                        # ELSE #
                            <option value="1">{L_ENABLED}</option>
                            <option value="0" selected="selected">{L_DISABLED}</option>
                        # ENDIF #                   
                    </select>
                </label></dd>
            </dl>
            <dl>
                <dt><label for="auth">{L_AUTHS}</label></dt>
                <dd><label>{AUTH_MENUS}</label></dd>
            </dl>
        </fieldset>     
    
        <fieldset class="fieldset_submit">
            <legend>{L_ACTION}</legend>
            <input type="hidden" name="action" value="{ACTION}" />
            <input type="hidden" name="id" value="{IDMENU}" />
            <input type="submit" name="valid" value="{L_ACTION}" class="submit" />
            <input type="reset" value="{L_RESET}" class="reset" />                  
        </fieldset> 
    </form>
</div>
