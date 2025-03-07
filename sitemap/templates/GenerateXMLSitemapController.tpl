# IF C_GOT_ERROR #
	<div class="message-helper bgc warning">${i18nraw('sitemap.generation.error')}</div>
# ELSE #
	<div class="message-helper bgc success">${i18nraw('sitemap.generation.success')}</div>
# ENDIF #
<div class="align-center">
	<button type="button" class="button" onclick="window.location = '{GENERATE}'">{@sitemap.try.again}</button>
</div>
