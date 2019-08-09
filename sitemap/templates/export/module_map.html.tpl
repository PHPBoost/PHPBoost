<h2>
	<a href="{MODULE_URL}">
		# IF C_MODULE_ID #
		<img src="{PATH_TO_ROOT}/{MODULE_ID}/{MODULE_ID}.png" alt="{MODULE_NAME}" class="valign-middle" />
		# ENDIF #
		{MODULE_NAME}
	</a>
</h2>
<p>{MODULE_DESCRIPTION}</p>
<ul>
	# START element #
		<li># INCLUDE element.ELEMENT #</li>
	# END element #
</ul>
