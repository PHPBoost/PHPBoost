<br />
<script type="text/javascript">
<!--
    const RESULTS = 'Results';
    const RESULTS_TITLE = 'ResultsTitle';
    var modulesResults = new Array('All');
    # START results #
        modulesResults.push('{results.MODULE_NAME}');
    # END results #
    
    var calculatedResults = new Array('All');
    
    function ShowResults(module)
    /*
     * Montre les résultats de ce module
     */
    {
        if ( module != '' )
            document.getElementById(RESULTS + module).style.display = 'block';
        else
        {
            if ( modulesResults.length > 0 )
                document.getElementById(RESULTS + modulesResults[0]).style.display = 'block';
        }
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
        ShowResults(document.getElementById('ResultsChoice').value);
        if ( !inArray(module, calculatedResults) )
        {
            document.getElementById(RESULTS_TITLE + module).innerHTML = 'Calcul des résultats en cours';
            XMLHttpRequest_search_module(module);
        }
    }
    
    function XMLHttpRequest_search_module(module)
    /*
     * Calcul de nouveaux résultats de recherche
     */
    {
//         if( document.getElementById('refresh_unread' + divID) )
//             document.getElementById('refresh_unread' + divID).src = '../templates/{THEME}/images/loading_mini.gif';
//             
//         var xhr_object = xmlhttprequest_init('../forum/xmlhttprequest.php?refresh_unread=1');
//         xhr_object.onreadystatechange = function() 
//         {
//             if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
//             {   
//                 if( document.getElementById('refresh_unread' + divID) )
//                     document.getElementById('refresh_unread' + divID).src = '../templates/{THEME}/images/refresh_mini.png';
//                 
//                 var array_unread_topics = new Array('', '');
//                 eval(xhr_object.responseText);
//                 
//                 if( array_unread_topics[0] > 0 )
//                     forum_display_block('forum_unread' + divID);
//                     
//                 document.getElementById('nbr_unread_topics').innerHTML = array_unread_topics[1];
//                 document.getElementById('nbr_unread_topics2').innerHTML = array_unread_topics[1];
//                 document.getElementById('forum_blockforum_unread').innerHTML = array_unread_topics[2];
//                 document.getElementById('forum_blockforum_unread2').innerHTML = array_unread_topics[2];
//             }
//             else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
//             {   
//                 alert("{L_AUTH_ERROR}");
//                 if( document.getElementById('refresh_unread' + divID) )
//                     document.getElementById('refresh_unread' + divID).src = '../templates/{THEME}/images/refresh_mini.png';
//             }
//         }
//         xmlhttprequest_sender(xhr_object, null);
        document.getElementById(RESULTS_TITLE + module).innerHTML = 'RESULTATS : ...';
        calculatedResults.push(module);
    }
-->
</script>

<div id="results" class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r"></div>
    <div class="module_top">{SEARCH_RESULTS}</div>
    <div class="module_contents">
        <div class="spacer">&nbsp;</div>
        <div class="choices">
                <fieldset>
                    <legend>{RESULTS_CHOICE}</legend>
                    <dl>
                        <dt>
                            <div class="choice">
                                <span>{PRINT}</span>
                            </div>
                        </dt>
                        <dd>
                            <select id="ResultsChoice" name="ResultsSelection" onChange="ChangeResults();">
                                <option value="All">{TITLE_ALL_RESULTS}</option>
                                # START results #
                                    <option value="{results.MODULE_NAME}">---> {results.MODULE_NAME}</option>
                                # END results #
                            </select>
                        </dd>
                    </dl>
                </fieldset>
            </div>
        <div id="ResultsAll" class="results">
            <div id="ResultsTitleAll" class="legend">{TITLE_ALL_RESULTS}</div>
            <div id="nbResultsAll" class="nbResults"></div>
            <div id="ResultsListAll">
                <ul class="search_results">
                    # START allResults #
                        <li>{allResults.RESULT}</li>
                    # END allResults #
                </ul>
            </div>
        </div>
        # START results #
            <div id="Results{results.MODULE_NAME}" class="results">
                <div id="ResultsTitle{results.MODULE_NAME}" class="legend">{results.MODULE_NAME}</div>
                <div id="nbResults{results.MODULE_NAME}" class="nbResults"></div>
                <div id="ResultsList{results.MODULE_NAME}">
                    <ul class="search_results">
                        # START results.module #
                            <li>{results.module.RESULT}</li>
                        # END results.module #
                    </ul>
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
