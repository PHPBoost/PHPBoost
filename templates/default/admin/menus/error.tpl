<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top">
		<strong>
			${escape(TITLE)}
			# IF CODE #
			(#${escape(CODE)})
			# END IF #
		</strong>
	</div>
	<div class="module_contents">
		<span id="errorh"></span>
		<div class="error_warning"
			style="width: 500px; margin: auto; padding: 15px;"><img
			src="../templates/base/images/important.png" alt=""
			style="float: left; padding-right: 6px;" /> <strong>${escape(TITLE)}
		# IF CODE #(#${escape(CODE)})# END IF #</strong><br />
		<br />
		${escape(MESSAGE)}
		</div>
	</div>
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>

	<div class="module_bottom">
		<strong>
			<a href="{U_LINK}" title="${escape(LINK_NAME)}">${escape(LINK_NAME)}</a>
		</strong>
	</div>
</div>
