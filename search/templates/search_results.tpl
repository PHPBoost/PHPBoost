<br />
<script type="text/javascript">
<!--
    const RESULTS = 'Results';
    const RESULTS_TITLE = 'ResultsTitle';
    const INFOS_RESULTS = 'infosResults';
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
            var results = XMLHttpRequest_search_module(module);
//             document.getElementById(INFOS_RESULTS + module).innerHTML = results;
        }
    }
    
    function XMLHttpRequest_search_module(module)
    /*
     * Calcul de nouveaux résultats de recherche
     */
    {
        var results = 'RESULTATS : ...';
        
        var xhr_object = xmlhttprequest_init('../search/searchXMLHTTPRequest.php?idSearch=' + idSearch[module] + '&amp;pageNum={PAGE_NUM}');
        xhr_object.onreadystatechange = function()
        {
            if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
            { 
//                 var array_unread_topics = new Array('', '');
//                 eval(xhr_object.responseText);
//                 
//                 if( array_unread_topics[0] > 0 )
//                     forum_display_block('forum_unread' + divID);
//                     
                document.getElementById(INFOS_RESULTS + module).innerHTML = xhr_object.responseText;
//                 document.getElementById('nbr_unread_topics2').innerHTML = array_unread_topics[1];
//                 document.getElementById('forum_blockforum_unread').innerHTML = array_unread_topics[2];
//                 document.getElementById('forum_blockforum_unread2').innerHTML = array_unread_topics[2];
            }
//             else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
//             {   
//                 alert("{L_AUTH_ERROR}");
//                 if( document.getElementById('refresh_unread' + divID) )
//                     document.getElementById('refresh_unread' + divID).src = '../templates/{THEME}/images/refresh_mini.png';
//             }
        }
        xmlhttprequest_sender(xhr_object, null);
        calculatedResults.push(module);
        return results;
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
            <span id="ResultsTitleAll" class="title">{TITLE_ALL_RESULTS}</span>
            <div id="ResultsListAll">
                <span id="infosResultsAll" class="infosResults">{NB_RESULTS} {RESULTS} ont été trouvés.</span>
                <ul class="search_results">
                    # START allResults #
                        <li>{allResults.RESULT}</li>
                    # END allResults #
                </ul>
            </div>
        </div>
        # START results #
            <div id="Results{results.MODULE_NAME}" class="results">
                <span id="ResultsTitle{results.MODULE_NAME}" class="title">{results.MODULE_NAME}</span>
                <div id="ResultsList{results.MODULE_NAME}">
                    <span id="infosResults{results.MODULE_NAME}" class="infosResults"></span>
<!--                     <ul class="search_results"> -->
<!--                         # START results.module # -->
<!--                             <li>{results.module.RESULT}</li> -->
<!--                         # END results.module # -->
<!--                     </ul> -->
                </div>
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
