# START results #
<div id="results_{RESULTS_NAME}_{results.NUM_PAGE}" style="display:{results.BLOCK_DISPLAY}">
    <ul class="search_results">
        # START results.page #
        <li>
            # IF C_ALL_RESULTS # <span><b>{results.page.L_MODULE_NAME}</b></span> - # ENDIF #
            <a href="{results.page.U_LINK}">TITRE{results.page.TITLE}</a>
        </li>
        # END results.page #
    </ul>
</div>
# END results #