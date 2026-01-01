<section id="module-newsletter">
	<header class="section-header">
		# IF C_SUBTITLE #
			<div class="controls align-right">{@newsletter.module.title}</div>
		# ENDIF #
		<h1>
			# IF C_SUBTITLE #{L_SUBTITLE}# ELSE #{@newsletter.module.title}# ENDIF #
		</h1>
		# IF C_STREAM_TITLE #
			<div class="more">{STREAM_TITLE}</div>
		# ENDIF #
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				# INCLUDE TEMPLATE #
			</div>
		</div>
	</div>
	<footer></footer>
</section>
