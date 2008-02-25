<script type="text/javascript">
<!--
    var modules = new Array("ResultsAll" # START forms # , "Form{name}" # END forms #);
    
    function ShowForm(module)
    /*
     * Montre les résultats de ce module
     */
    {
        document.getElementById('Form'.module).style.display = 'block';
    }
    
    function HideForms()
    /*
     * Cache tous les résultats
     */
    {
        for ( var i = 0; i < modules.length; i++)
        {
            document.getElementById('Form'.modules[i]).style.display = 'none';
        }
    }
    
    function ChangeForm(module)
    /*
     * Change le cadre des résultats
     */
    {
        HideForms();
        ShowForm(module);
    }
    
    function check_form_post()
    {
        var textSearched = document.getElementById("search").value;
        
        if ( textSearched.length > 3 )
        {
            textSearched = escape_xmlhttprequest(textSearched);
            return true;
        }
        else
        {
            alert('{WARNING_LENGTH_STRING_SEARCH}');
            return false;
        }
    }
-->
</script>

<div class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r"></div>
    <div class="module_top">{TITLE}</div>
    <div class="module_contents">
        <div class="spacer">&nbsp;</div>
        <form action="../search/search.php#results" onsubmit="return check_form_post();" method="post">
            <fieldset>
                <legend>{TITLE_SEARCH}</legend>
                <dl>
                    <dt><label for="search">{SEARCH_MIN_LENGTH}</label></dt>
                    <dd><label><input type="text" size="35" id="search" name="search" value="{TEXT_SEARCHED}"  class="text" /></label></dd>
                </dl>
            </fieldset>
            # START forms #
                <div class="module_position">
                    <fieldset>
                        <legend><a href="" onClick="ChangeForm('Form{forms.MODULE_NAME}')">{forms.MODULE_NAME}</a></legend>
                        {forms.SEARCH_FORM}
                    </fieldset>
                </div>
            # END forms #
            <fieldset class="fieldset_submit">
                <legend>{title_search}</legend>
                <input type="submit" name="search_submit" id="search_submit" value="{SEARCH}" class="submit" />
            </fieldset>
        </form>
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom" style="text-align:center;">{HITS}</div>
</div>