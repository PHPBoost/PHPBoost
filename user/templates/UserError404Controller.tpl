# IF HAS_TIME #
	<meta http-equiv="refresh" content="{TIME};url=${U_LINK}">
# ENDIF #
<section id="module-user-error-404">
	<header class="section-header"><h1>${escape(TITLE)}</h1></header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				<img src="{U_ERROR_IMG}" alt="404">

				<div class="type-error">
					{MESSAGE}
				</div>

				<div class="message-error">
					{@H|warning.404.message}
				</div>

				<div class="spacer"></div>
				# IF HAS_LINK #
					<div class="align-center">
						<strong><a class="offload" href="{U_LINK}">${escape(LINK_NAME)}</a></strong>
					</div>
				# ENDIF #
			</div>
		</div>
	</div>
	<footer></footer>
</section>
