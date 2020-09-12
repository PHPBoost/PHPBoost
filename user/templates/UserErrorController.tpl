# IF HAS_TIME #
	<meta http-equiv="refresh" content="{TIME};url=${U_LINK}">
# ENDIF #
<section id="module-user-error">
	<header><h1>${escape(TITLE)}</h1></header>
	<article class="content">
		<div class="message-helper bgc {ERROR_TYPE}">{MESSAGE}</div>
		# IF HAS_LINK #
			<div class="align-center">
				<strong><a href="{U_LINK}">${escape(LINK_NAME)}</a></strong>
			</div>
		# ENDIF #
	</article>
	<footer></footer>
</section>
