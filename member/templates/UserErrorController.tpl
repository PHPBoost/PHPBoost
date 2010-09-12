<!-- Move the styles in CSS -->
<div>
	<div style="margin:10px;margin-bottom:20px;padding-bottom:5px;border-bottom:1px solid #aaaaaa;">
		<strong>${escape(TITLE)}</strong>
	</div>
	<div>
		<img src="{PATH_TO_ROOT}/templates/base/images/{ERROR_TYPE}.png" alt="${escape(TITLE)}"
		style="float: left; padding-right: 6px;" />
		{MESSAGE}
	</div>
    # IF HAS_LINK #
	<div style="padding:30px;text-align:center;">
		<strong><a href="{U_LINK}" title="${escape(LINK_NAME)}">${escape(LINK_NAME)}</a></strong>
	</div>
    # ENDIF #
</div>
