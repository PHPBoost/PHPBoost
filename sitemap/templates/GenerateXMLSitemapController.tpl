# IF C_GOT_ERROR #
<div class="warning">${i18nraw('generation_failed')}</div>
# ELSE #
<div class="success">${i18nraw('generation_succeeded')}</div>
# ENDIF #
<br />
<div class="center">
	<button type="button" onclick="window.location = '{GENERATE}'">{@try_again}</button>
</div>