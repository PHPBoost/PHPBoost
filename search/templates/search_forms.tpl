<script type="text/javascript">
<!--
    const FORM = 'Form';
    var modulesForms = new Array();
    # START forms #
        modulesForms.push("{forms.MODULE_NAME}");
    # END forms #
    
    function ShowAdvancedSearchForms()
    /*
     *  Montre les champs de recherche avancée
     */
    {
        HideAdvancedSearchForms();
        show_div('FormsChoice');
        show_div(FORM + document.getElementById('FormsChoice').value);
        hide_div('AdvancedSearch');
        show_div('SimpleSearch');
    }
    
    function HideAdvancedSearchForms()
    /*
     *  Cache les champs de recherche avancée
     */
    {
        HideForms();
        hide_div('FormsChoice');
        hide_div('SimpleSearch');
        show_div('AdvancedSearch');
    }
    
    function HideForms()
    /*
     * Cache tous les résultats
     */
    {
        for ( var i = 0; i < modulesForms.length; i++)
        {
            hide_div(FORM + modulesForms[i]);
        }
    }
    
    function ChangeForm()
    /*
     * Change le cadre des résultats
     */
    {
        HideForms();
        show_div(FORM + document.getElementById('FormsChoice').value);
    }
    
    function check_search_form_post()
    {
        var textSearched = document.getElementById("TxTsearched").value;
        
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
        <form id="SearchForm" action="../search/search.php#results" onsubmit="return check_search_form_post();" method="post">
            <fieldset>
                <legend>{TITLE_SEARCH}</legend>
                <dl>
                    <dt><label for="search">{SEARCH_MIN_LENGTH}</label></dt>
                    <dd><label><input type="text" size="35" id="TxTsearched" name="search" value="{TEXT_SEARCHED}"  class="text" /></label></dd>
                </dl>
                <dl>
                    <dt>
                        <label id="AdvancedSearch"><a onClick="ShowAdvancedSearchForms();">{ADVANCED_SEARCH}</a></label>
                        <label id="SimpleSearch"><a onClick="HideAdvancedSearchForms();">{SIMPLE_SEARCH}</a></label>
                    </dt>
                    <dd>
                        <select id="FormsChoice" name="FormsSelection" onChange="ChangeForm();">
                            # START forms #
                                <option value="{forms.MODULE_NAME}">{forms.MODULE_NAME}
                                </option>
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
    hide_div('SimpleSearch');
    HideAdvancedSearchForms();
-->
</script>