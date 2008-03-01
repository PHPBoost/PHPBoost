<br />
<script type="text/javascript">
<!--
    var modulesResults = new Array('All');
    # START mResults #
        modulesResults.push('{mResults.MODULE_NAME}');
    # END mResults #
    
    function ShowResults(module)
    /*
     * Montre les résultats de ce module
     */
    {
        if ( module != '' )
            document.getElementById('Results'+module).style.display = 'block';
        else
        {
            if ( modulesResults.length > 0 )
                document.getElementById('Results'+modulesResults[0]).style.display = 'block';
        }
    }
    
    function HideResults()
    /*
     * Cache tous les résultats
     */
    {
        for ( var i = 0; i < modulesResults.length; i++)
        {
            document.getElementById('Results'+modulesResults[i]).style.display = 'none';
        }
    }
    
    function ChangeResults()
    /*
     * Change le cadre des résultats
     */
    {
        HideResults();
        ShowResults(document.getElementById('ResultsChoice').value);
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
        <div id="ResultsAll">
            <fieldset>
                <legend>{TITLE_ALL_RESULTS}</legend>
                <ul class="search_results">
                    # START allResults #
                        <li>{allResults.RESULT}</li>
                    # END allResults #
                </ul>
            </fieldset>
        </div>
        # START results #
            <div id="Results{results.MODULE_NAME}">
                <fieldset>
                    <legend>{results.MODULE_NAME}</legend>
                    <ul class="search_results">
                        # START results.module #
                            <li>{results.module.RESULT}</li>
                        # END results.module #
                    </ul>
                </fieldset>
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
