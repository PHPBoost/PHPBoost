<ul class="pagination # IF C_FULL #pagination-block# ENDIF #">
	# START page #
	<li>
		# IF page.C_PREVIOUS #
		<a href="{page.URL}" rel="prev" title="" class="prev-page">&laquo;</a>
		# ENDIF #
		
		# IF page.NAME #
		<a href="{page.URL}" title="" class="# IF page.C_CURRENT_PAGE #current-page# ENDIF #">{page.NAME}</a>
		# ENDIF #
		
		# IF page.C_NEXT #
		<a href="{page.URL}" rel="next" title="" class="next-page">&raquo;</a>
		# ENDIF #
	</li>
	# END page #
</ul>