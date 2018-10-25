<h2>
	# IF C_MODULE_ID #
	<a href="{MODULE_URL}" title="{MODULE_NAME}"><img src="{PATH_TO_ROOT}/{MODULE_ID}/{MODULE_ID}.png" alt="{MODULE_NAME}" class="valign-middle" /></a>
	# ENDIF #
	<a href="{MODULE_URL}" title="{MODULE_NAME}">
	{MODULE_NAME}
	</a>
</h2>
<p>{MODULE_DESCRIPTION}</p>
<ul>
	# START element #
	<li># INCLUDE element.ELEMENT #</li>
	# END element #
</ul>
