# IF HAS_TIME #
	<meta http-equiv="refresh" content="{TIME};url=${U_LINK}">
# ENDIF #
<section id="module-user-error-403">
	<header class="section-header"><h1>${escape(TITLE)}</h1></header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				<i class="fa fa-minus-circle fa-4x error"></i>

				<div class="type-error">
					{MESSAGE}
				</div>

				<div class="message-error">
					{@H|warning.403.message}
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
