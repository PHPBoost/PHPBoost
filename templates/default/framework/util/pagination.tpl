# START page #
<a href="{page.URL}" # IF page.C_PREVIOUS # rel="prev" # ENDIF # # IF page.C_NEXT # rel="next" # ENDIF # title="" class="pagination_link">
	<span # IF page.C_CURRENT_PAGE # style="text-decoration: underline;" class="text_strong"# ENDIF #>{page.NAME}</span>
</a>
# END page #