<br />
<script language="text/javascript">
<!--
    var modulesResults = new Array("ResultsAll" # START results # , "Results{name}" # END results #);
    
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
    
    function ChangeResults(module)
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
                    <legend>{RESULTS}</legend>
                    <dl>
                        <dt>
                            <div class="choice">
                                <span onClick="ChangeResults('{All}');">{TITLE_ALL_RESULTS}</span>
                            </div>
                        </dt>
                        <dd>
                            <select id="ResultsChoice" name="ResultsSelection" onChange="ChangeResults();">
                                # START results #
                                    <option value="{results.MODULE_NAME}">{results.MODULE_NAME}
                                    </option>
                                # END results #
                            </select>
                        </dd>
                    </dl>
                </fieldset>
            </div>
        <div id="ResultsAll">
            <fieldset>
                <legend>{TITLE_ALL_RESULTS}</legend>
                {ALL_RESULTS}
            </fieldset>
        </div>
        # START results #
            <div id="Results{results.MODULE_NAME}">
                <fieldset>
                    <legend>{results.MODULE_NAME}</legend>
                    {results.RESULTS}
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
    ShowResults('');
-->
</script>
