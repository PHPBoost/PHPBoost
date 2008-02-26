<link rel="stylesheet" href="../search/templates/search.css" type="text/css" media="screen, print, handheld" />
<script type="text/javascript">
<!--
    var modules = new Array();
    # START forms #
        modules.push("{forms.MODULE_NAME}");
    # END forms #
    
    function ShowAdvancedSearchForms()
    /*
     *  Montre les champs de recherche avancée
     */
    {
        HideAdvancedSearchForms();
        document.getElementById('FormsChoice').style.display = 'block';
        ShowForm('');
        document.getElementById('AdvancedSearch').style.display = 'none';
        document.getElementById('SimpleSearch').style.display = 'block';
    }
    
    function HideAdvancedSearchForms()
    /*
     *  Cache les champs de recherche avancée
     */
    {
        HideForms();
        document.getElementById('FormsChoice').style.display = 'none';
        document.getElementById('SimpleSearch').style.display = 'none';
        document.getElementById('AdvancedSearch').style.display = 'block';
    }
    
    function ShowForm(module)
    /*
     * Montre les résultats de ce module
     */
    {
        if ( module != '' )
            document.getElementById('Form'+module).style.display = 'block';
        else
        {
            if ( modules.length > 0 )
                document.getElementById('Form'+modules[0]).style.display = 'block';
        }
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
        ShowForm(document.getElementById('FormsChoice').value);
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
                <dl>
                    <dt>
                        <label id="AdvancedSearch"><a onClick="ShowAdvancedSearchForms();">{ADVANCED_SEARCH}</a></label>
                        <label id="SimpleSearch"><a onClick="HideAdvancedSearchForms();">{SIMPLE_SEARCH}</a></label>
                    </dt>
                    <dd>
                        <select id="FormsChoice" name="FormsSelection" onChange="ChangeForm();">
                            # START forms #
                                <option value="{forms.MODULE_NAME}">{forms.MODULE_NAME}</option>
                            # END forms #
                        </select>
                    </dd>
                </dl>
            </fieldset>
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
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom" style="text-align:center;">{HITS}</div>
</div>

<script type="text/javascript">
<!--
    // On cache les éléments ne devant pas s'afficher au début
    document.getElementById('SimpleSearch').style.display = 'none';
    HideAdvancedSearchForms();
-->
</script>