<br />
<script language="text/javascript">
<!--
    var modules = new Array("ResultsAll" # START results # , "Results{name}" # END results #);
    
    function ShowResults(module)
    /*
     * Montre les résultats de ce module
     */
    {
        document.getElementById('Results'.module).style.display = 'block';
    }
    
    function HideResults()
    /*
     * Cache tous les résultats
     */
    {
        for ( var i = 0; i < modules.length; i++)
        {
            document.getElementById('Results'.modules[i]).style.display = 'none';
        }
    }
    
    function ChangeResults(module)
    /*
     * Change le cadre des résultats
     */
    {
        HideResults();
        ShowResults(module);
    }
-->
</script>

<div id="results" class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r"></div>
    <div class="module_top">{SEARCH_RESULTS}</div>
    <div class="module_contents">
        <div class="spacer">&nbsp;</div>
        <div id="ResultsAll">
            <fieldset>
                <legend>{TITLE_ALL_RESULTS}</legend>
                {ALL_RESULTS}
            </fieldset>
        </div>
        # START modules #
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
