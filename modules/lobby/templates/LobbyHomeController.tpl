<section id="module-lobby" class="several-items flex-col">
	<header class="section-header" style="order: 0;">
		<h1>{MODULE_TITLE}</h1>
	</header>

	# INCLUDE ANCHORS_MENU #

	# INCLUDE CAROUSEL #

	# IF C_EDITO_ENABLED #
		<div class="sub-section" style="order: {EDITO_POSITION};">
			<div class="content-container">
				<article id="edito-panel">
					<div class="content">
						{EDITO}
						<div class="spacer"></div>
					</div>
				</article>
			</div>
		</div>
	# ENDIF #

	# INCLUDE LASTCOMS #

	# START modules #
		# INCLUDE modules.MODULE_CONTENT #
	# END modules #

	<footer style="order: 9999;"></footer>
</section>

<script>
	jQuery('document').ready(function() {
		listorder.init();
	});
</script>
