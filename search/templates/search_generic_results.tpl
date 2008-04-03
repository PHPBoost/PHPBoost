# START page #
<div id="results_{RESULTS_NAME}_{page.NUM_PAGE}" style="display:{page.BLOCK_DISPLAY}">
    <ul class="search_results">
        # START page.results #
        <li>
            # IF C_ALL_RESULTS # <span><b>{page.results.L_MODULE_NAME}</b></span> - # ENDIF #
            <a href="{page.results.U_LINK}">{page.results.TITLE}</a>
        </li>
        # END page.results #
    </ul>
</div>
# END page #