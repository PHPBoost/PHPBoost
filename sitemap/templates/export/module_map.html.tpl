<div class="cell">
	<div class="cell-header">
		<div class="cell-name biggest"><a href="{MODULE_URL}">{MODULE_NAME}</a></div>
		# IF C_MODULE_ID #
			<a href="{MODULE_URL}">
				<img src="{PATH_TO_ROOT}/{MODULE_ID}/{MODULE_ID}.png" alt="{MODULE_NAME}" class="valign-middle" />
			</a>
		# ENDIF #
	</div>
	<!-- <div class="cell-body">
		<div class="cell-content">{MODULE_DESCRIPTION}</div>
	</div> -->
	<div class="cell-list">
		<ul>
			# START element #
				<li># INCLUDE element.ELEMENT #</li>
			# END element #
		</ul>
	</div>
</div>
