# IF C_GOT_ERROR #
<div class="message-helper warning">${i18nraw('generation_failed')}</div>
# ELSE #
<div class="message-helper success">${i18nraw('generation_succeeded')}</div>
# ENDIF #
<div class="center">
	<button type="button" onclick="window.location = '{GENERATE}'">{@try_again}</button>
</div>
