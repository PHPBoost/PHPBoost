<ul class="bb_ul">
	<h1>
	<a href="{MODULE_URL}" style="font-size:inherit;">
		# IF C_MODULE_ID #
		<img src="{PATH_TO_ROOT}/{MODULE_ID}/{MODULE_ID}.png" alt="" class="valign_middle" />
		# ENDIF #
		{MODULE_NAME}
	</a>
	</h1>
	<p>{MODULE_DESCRIPTION}</p>
	# START element #
	<li># INCLUDE element.ELEMENT #</li>
	# END element #
</ul>