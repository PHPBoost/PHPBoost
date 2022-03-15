<div class="cell">
	<div class="cell-header">
		<div class="cell-name bigger">
			<a class="offload" href="{MODULE_URL}">{MODULE_NAME}</a>
		</div>
		# IF C_MODULE_ID #
			<a class="offload" href="{MODULE_URL}">
				# IF C_ICON_IS_PICTURE #
					<img src="{PATH_TO_ROOT}/{MODULE_ID}/{MODULE_ID}_mini.png" alt="{MODULE_NAME}" class="valign-middle" />
				# ELSE #
					<i class="{FA_ICON}"></i>
				# ENDIF #
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
