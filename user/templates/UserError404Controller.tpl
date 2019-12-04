# IF HAS_TIME #
<meta http-equiv="refresh" content="{TIME};url=${U_LINK}">
# ENDIF #
<section id="module-user-error-404">
	<header><h1>${escape(TITLE)}</h1></header>
	<div class="content">
		<i class="fa fa-exclamation-triangle fa-4x"></i>

		<div class="type-error">
			{MESSAGE}
		</div>

		<div class="message-error">
			${LangLoader::get_message('error.404.message', 'status-messages-common')}
		</div>

		<div class="spacer"></div>
		# IF HAS_LINK #
		<div class="align-center">
			<strong><a href="{U_LINK}">${escape(LINK_NAME)}</a></strong>
		</div>
		# ENDIF #
	</div>
	<footer></footer>
</section>
