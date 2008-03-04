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
    
    function HideResults()
    // Cache tous les résultats
    {
        for ( var i = 0; i < modulesResults.length; i++ )
        {
            hide_div(RESULTS + modulesResults[i]);
        }
    }
    
    function ChangeResults()
    // Change le cadre des résultats
    {
        var module = document.getElementById('ResultsChoice').value;
        HideResults();
        show_div(RESULTS + module);
        if ( !inArray(module, calculatedResults) )
        {
            document.getElementById(INFOS_RESULTS + module).innerHTML = 'Calcul des résultats en cours...';
            XMLHttpRequest_search_module(module);
        }
    }
    
    function GetFormData()
    // Reconstitution d'une chaine "POSTABLE" à partir des formulaires
    {
        var dataString = "";
        var form = document.getElementById('SearchForm');
        var elements = form.elements;
        
        for( var i = 0; i < form.length; i++ )
        {
            if ( elements[i].name )
            {
                dataString += elements[i].name + "=" + escape(elements[i].value);
                if ( (i + 1) < form.length )
                    dataString += "&";
            }
        }
        
        return data1;
    }
    
    function XMLHttpRequest_regenerate_search()
    // Affiche les résultats de la recherche pour le module particulier <module>
    {
        var xhr = xmlhttprequest_init('../search/searchXMLHTTPRequest.php');
        xhr.onreadystatechange = function()
        {
        }
//         xmlhttprequest_sender(xhr, document.getElementById('SearchForm'));
        xmlhttprequest_sender(xhr, GetFormData());
    }
    
    function XMLHttpRequest_search_module(module)
    // Affiche les résultats de la recherche pour le module particulier <module>
    {
        var xhr_object = xmlhttprequest_init('../search/searchModuleXMLHTTPRequest.php?idSearch=' + idSearch[module] + '&pageNum={PAGE_NUM}');
        xhr_object.onreadystatechange = function()
        {
            if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '')
            {
                // Si les résultats sont toujours en cache, on les récupère.
                if ( xhr_object.responseText != 'NO RESULTS IN CACHE' )
                {
                    eval(xhr_object.responseText);
                    document.getElementById(INFOS_RESULTS + module).innerHTML = resultsAJAX['nbResults'];
                    document.getElementById(RESULTS_LIST + module).innerHTML = resultsAJAX['results'];
                    
                    // Met à jour la liste des résultats affiché, pour ne pas les rechercher
                    // dans la base de donnée si ils sont déjà dans le html.
                    calculatedResults.push(module);
                }
                else    // Sinon, on les recalcule, et on les récupère.
                {
                    XMLHttpRequest_regenerate_search();
                    XMLHttpRequest_search_module(module);
                }
            }
        }
        xmlhttprequest_sender(xhr_object, null);
    }
-->
</script>

<div id="results" class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r"></div>
    <div class="module_top">{SEARCH_RESULTS}
        <div id="resultsChoices" class="resultsChoices" style="display:none">
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
            <div id="Results{results.MODULE_NAME}" class="results" style="display:none">
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
    if ( browserAJAXFriendly() )
        show_div('resultsChoices');
-->
</script>
