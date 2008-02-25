<link rel="stylesheet" href="../search/templates/search.css" type="text/css" media="screen, print, handheld" />
<script type="text/javascript">
<!--
    var modules = new Array();
    # START forms #
        modules.push("{forms.MODULE_NAME}");
    # END forms #
    
    function ShowForm(module)
    /*
     * Montre les résultats de ce module
     */
    {
        document.getElementById('Form'+module).style.display = 'block';
    }
    
    function HideForms()
    /*
     * Cache tous les résultats
     */
    {
        for ( var i = 0; i < modules.length; i++)
        {
            document.getElementById('Form'+modules[i]).style.display = 'none';
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
            <div class="choices">
                <fieldset>
                    <legend>{FORMS}</legend>
                    <ul>
                        <li><a class="choice" onClick="HideForms();">Cacher les formulaires</a></li>
                        # START forms #
                            <li><a class="choice" onClick="ChangeForm('{forms.MODULE_NAME}');">{forms.MODULE_NAME}</a></li>
                        # END forms #
                    </ul>
                </fieldset>
            </div>
            # START forms #
                <div id="Form{forms.MODULE_NAME}" class="module_position">
                    <fieldset>
                        <legend>{forms.MODULE_NAME}</legend>
                        {forms.SEARCH_FORM}
                    </fieldset>
                </div>
            # END forms #
            <fieldset class="fieldset_submit">
                <legend>{title_search}</legend>
                <input type="submit" name="search_submit" id="search_submit" value="{SEARCH}" class="submit" />
            </fieldset>
        </form>
        <script type="text/javascript">
        <!--
            HideForms();
        -->
        </script>
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom" style="text-align:center;">{HITS}</div>
</div>