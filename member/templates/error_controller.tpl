<!-- Move the styles in CSS -->
<div>
	<div style="margin:10px;margin-bottom:20px;padding-bottom:5px;border-bottom:1px solid #aaaaaa;">
		<strong>{E_TITLE} # IF CODE #(#{E_CODE})# END IF #</strong>
	</div>
	<div>
		<img src="{PATH_TO_ROOT}/templates/base/images/{LEVEL}.png" alt="{E_TITLE}"
		style="float: left; padding-right: 6px;" />
		{MESSAGE}
	</div>
	<div style="padding:30px;text-align:center;">
		<strong><a href="{U_LINK}" title="{E_LINK_NAME}">{E_LINK_NAME}</a></strong>
	</div>
</div>
