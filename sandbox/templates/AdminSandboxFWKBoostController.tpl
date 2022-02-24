<section id="admin-sandbox-component">
	<header>
		<h1>
			{@sandbox.module.title} - {@sandbox.components}
		</h1>
	</header>

	# INCLUDE MODAL #

	# INCLUDE LIST #
	# INCLUDE PAGINATION #

	# INCLUDE TABLE #

	# INCLUDE MESSAGE_HELPER #

	# INCLUDE BASIC #

	# INCLUDE ACCORDION #

	# INCLUDE TABS #

	# INCLUDE WIZARD #

	<footer></footer>
</section>
<script>jQuery('#admin-sandbox-component article a').on('click', function(){return false;});</script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/form/validator# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/form/form# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
