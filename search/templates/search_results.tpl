<br />
<script type="text/javascript">
<!--
    const RESULTS = 'Results';
    const RESULTS_TITLE = 'ResultsTitle';
    const INFOS_RESULTS = 'infosResults';
    const RESULTS_LIST = 'ResultsList';
    var modulesResults = new Array('All');
    # START results #
        modulesResults.push('{results.MODULE_NAME}');
    # END results #
    
    var idSearch = new Array();
    # START results #
        idSearch['{results.MODULE_NAME}'] = '{results.ID_SEARCH}';
    # END results #
    
    var calculatedResults = new Array('All');
    
    function ShowResults(module)
    /*
     * Montre les résultats de ce module
     */
    {
        if ( module != '' )
            document.getElementById(RESULTS + module).style.display = 'block';
        else if ( modulesResults.length > 0 )
            document.getElementById(RESULTS + modulesResults[0]).style.display = 'block';
    }
    
    function HideResults()
    /*
     * Cache tous les résultats
     */
    {
        for ( var i = 0; i < modulesResults.length; i++)
        {
            document.getElementById(RESULTS + modulesResults[i]).style.display = 'none';
        }
    }
    
    function inArray(aValue, anArray)
    /*
     * Teste la présence d'une valeur dans un tableau
     */
    {
        for ( var i = 0; i < anArray.length; i++)
        {
            if ( anArray[i] == aValue )
                return true;
        }
        return false;
    }
    
    function ChangeResults()
    /*
     * Change le cadre des résultats
     */
    {
        var module = document.getElementById('ResultsChoice').value;
        HideResults();
        ShowResults(module);
        if ( !inArray(module, calculatedResults) )
        {
            document.getElementById(INFOS_RESULTS + module).innerHTML = 'Calcul des résultats en cours...';
            XMLHttpRequest_search_module(module);
        }
    }
    
    function XMLHttpRequest_search_module(module)
    /*
     * Affiche les résultats de la recherche pour le module particulier <module>
     */
    {
        var results = 'RESULTATS : ...';
        var xhr_object = xmlhttprequest_init('../search/searchXMLHTTPRequest.php?idSearch=' + idSearch[module] + '&pageNum={PAGE_NUM}');
        xhr_object.onreadystatechange = function()
        {
            if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
            {
                eval(xhr_object.responseText);
                document.getElementById(INFOS_RESULTS + module).innerHTML = resultsAJAX['nbResults'];
                document.getElementById(RESULTS_LIST + module).innerHTML = resultsAJAX['results'];
            }
        }
        xmlhttprequest_sender(xhr_object, null);
        calculatedResults.push(module);
    }
-->
</script>

<div id="results" class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r"></div>
    <div class="module_top">{SEARCH_RESULTS}
        <div class="resultsChoices">
            <span>{PRINT}</span>
            <select id="ResultsChoice" name="ResultsSelection" onChange="ChangeResults();">
                <option value="All">{TITLE_ALL_RESULTS}</option>
                # START results #
                    <option value="{results.MODULE_NAME}">---> {results.MODULE_NAME}</option>
                # END results #
            </select>
        </div>
    </div>
    <div class="module_contents">
<!--         <div class="spacer">&nbsp;</div> -->
        <div id="ResultsAll" class="results">
            <span id="ResultsTitleAll" class="title">{TITLE_ALL_RESULTS}</span><br />
            <span id="infosResultsAll" class="infosResults">{NB_RESULTS} {NB_RESULTS_FOUND}</span>
            <div id="ResultsListAll" class="ResultsList">
                <ul class="search_results">
                    # START allResults #
                        <li>{allResults.RESULT}</li>
                    # END allResults #
                </ul>
            </div>
        </div>
        # START results #
            <div id="Results{results.MODULE_NAME}" class="results">
                <span id="ResultsTitle{results.MODULE_NAME}" class="title">{results.MODULE_NAME}</span><br />
                <span id="infosResults{results.MODULE_NAME}" class="infosResults"></span>
                <div id="ResultsList{results.MODULE_NAME}"></div>
            </div>
        # END results #
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom" style="text-align:center;">{HITS}</div>
</div>
<script type="text/javascript">
<!--
    // On cache les éléments ne devant pas s'afficher au début
    HideResults();
    ShowResults('All');
-->
</script>
