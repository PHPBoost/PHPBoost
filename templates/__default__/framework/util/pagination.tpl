<nav class="pagination">
	<ul# IF C_LIGHT_PAGINATION # class="light-pagination"# ENDIF #>
		# START page #
			# IF page.C_PREVIOUS_PAGE #
				<li class="pagination-item">
					<a class="offload" href="{page.U_PAGE}" rel="prev" aria-label="{@common.pagination.first}" class="prev-page"><i class="fa fa-angle-left"></i></a>
				</li>
			# ENDIF #

			# IF page.PAGE_NAME #
				<li class="pagination-item">
					<a href="{page.U_PAGE}" class="offload# IF page.C_CURRENT_PAGE # current-page# ENDIF #" aria-label="{page.L_PAGE}">{page.PAGE_NAME}</a>
				</li>
			# ENDIF #

			# IF page.C_NEXT_PAGE #
				<li class="pagination-item">
					<a class="offload" href="{page.U_PAGE}" rel="next" aria-label="{@common.pagination.last}" class="next-page"><i class="fa fa-angle-right"></i></a>
				</li>
			# ENDIF #
		# END page #
	</ul>
</nav>
