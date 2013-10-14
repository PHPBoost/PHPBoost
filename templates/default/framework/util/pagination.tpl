<nav class="pagination">
	# START page #
	<a href="{page.URL}" # IF page.C_PREVIOUS # rel="prev" # ENDIF # # IF page.C_NEXT # rel="next" # ENDIF # title="">
		<span # IF page.C_CURRENT_PAGE # class="current_page" # ENDIF #>{page.NAME}</span>
	</a>
	# END page #
</nav>