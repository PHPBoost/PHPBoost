# IF C_GOT_ERROR #
	<div class="message-helper bgc warning">${i18nraw('generation_failed')}</div>
# ELSE #
	<div class="message-helper bgc success">${i18nraw('generation_succeeded')}</div>
# ENDIF #
<div class="align-center">
	<button type="button" class="button" onclick="window.location = '{GENERATE}'">{@try_again}</button>
</div>
