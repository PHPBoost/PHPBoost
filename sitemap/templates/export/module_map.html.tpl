<h1>
	# IF C_MODULE_ID #
	<a href="{MODULE_URL}"><img src="{PATH_TO_ROOT}/{MODULE_ID}/{MODULE_ID}.png" alt="" class="valign-middle" /></a>
	# ENDIF #
	<a href="{MODULE_URL}" style="font-size:inherit;">
	{MODULE_NAME}
	</a>
</h1>
<p>{MODULE_DESCRIPTION}</p>
<ul>
	# START element #
	<li># INCLUDE element.ELEMENT #</li>
	# END element #
</ul>