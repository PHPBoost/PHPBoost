# IF C_SUGGESTED_NEWS #
	<aside class="suggested-links">
		<span class="text-strong"><i class="fa fa-lightbulb"></i> ${LangLoader::get_message('suggestions', 'common')} :</span>
		<div class="cell-row">
			# START suggested #
				<div class="cell">
					<div class="cell-body">
						<div class="cell-thumbnail cell-landscape cell-center">
							# IF suggested.C_HAS_THUMBNAIL #<img src="{suggested.U_THUMBNAIL}" alt="{suggested.TITLE}"># ENDIF #
						</div>
						<div class="cell-content">
							<a href="{suggested.U_CATEGORY}" class="small">{suggested.CATEGORY_NAME}</a>
							<a href="{suggested.U_ITEM}" class="suggested-item">
								<h6>{suggested.TITLE}</h6>
							</a>
						</div>
					</div>
				</div>
			# END suggested #
		</div>
	</aside>
# ENDIF #

# IF C_RELATED_LINKS #
	<aside class="related-links">
		# IF C_PREVIOUS_ITEM #
			<a class="related-item previous-item" href="{U_PREVIOUS_ITEM}">
				<i class="fa fa-chevron-left"></i>
				# IF C_PREVIOUS_HAS_THUMBNAIL #<img src="{U_PREVIOUS_THUMBNAIL}" alt="{PREVIOUS_ITEM}"># ENDIF #
				{PREVIOUS_ITEM}
			</a>
		# ELSE #
			<span></span>
		# ENDIF #
		# IF C_NEXT_ITEM #
			<a class="related-item next-item" href="{U_NEXT_ITEM}">
				{NEXT_ITEM}
				# IF C_NEXT_HAS_THUMBNAIL #<img src="{U_NEXT_THUMBNAIL}" alt="{NEXT_ITEM}"># ENDIF #
				<i class="fa fa-chevron-right"></i>
			</a>
		# ENDIF #
	</aside>
# ENDIF #
